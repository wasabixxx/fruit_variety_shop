<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    protected $voucherService;

    public function __construct(VoucherService $voucherService)
    {
        $this->voucherService = $voucherService;
    }

    /**
     * Display available public vouchers
     */
    public function index()
    {
        $vouchers = $this->voucherService->getPublicVouchers(12);
        
        return view('vouchers.index', compact('vouchers'));
    }

    /**
     * Get available vouchers for current user (AJAX)
     */
    public function getAvailableVouchers(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để xem voucher'
            ], 401);
        }

        $orderAmount = $request->get('order_amount', 0);
        $categoryIds = $request->get('category_ids', []);

        $vouchers = $this->voucherService->getAvailableVouchersForUser(
            Auth::user(),
            $orderAmount,
            $categoryIds
        );

        return response()->json([
            'success' => true,
            'vouchers' => $vouchers->map(function ($voucher) {
                return [
                    'id' => $voucher->id,
                    'code' => $voucher->code,
                    'name' => $voucher->name,
                    'description' => $voucher->description,
                    'type' => $voucher->type,
                    'amount' => $voucher->amount,
                    'discount_text' => $voucher->getDiscountText(),
                    'minimum_order_amount' => $voucher->minimum_order_amount,
                    'expires_at' => $voucher->expires_at?->format('d/m/Y H:i'),
                    'usage_limit' => $voucher->usage_limit,
                    'used_count' => $voucher->used_count,
                    'remaining_uses' => $voucher->usage_limit ? max(0, $voucher->usage_limit - $voucher->used_count) : null
                ];
            })
        ]);
    }

    /**
     * Validate voucher code (AJAX)
     */
    public function validateCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'order_amount' => 'required|numeric|min:0',
            'category_ids' => 'array'
        ]);

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để sử dụng voucher'
            ], 401);
        }

        $validation = $this->voucherService->validateVoucher(
            $request->code,
            Auth::user(),
            $request->order_amount,
            $request->get('category_ids', [])
        );

        if ($validation['valid']) {
            return response()->json([
                'success' => true,
                'message' => $validation['message'],
                'voucher' => [
                    'id' => $validation['voucher']->id,
                    'code' => $validation['voucher']->code,
                    'name' => $validation['voucher']->name,
                    'type' => $validation['voucher']->type,
                    'amount' => $validation['voucher']->amount,
                    'discount_text' => $validation['voucher']->getDiscountText()
                ],
                'discount_amount' => $validation['discount'],
                'formatted_discount' => number_format($validation['discount'], 0, ',', '.') . 'đ'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $validation['message'],
                'voucher' => $validation['voucher'] ? [
                    'id' => $validation['voucher']->id,
                    'code' => $validation['voucher']->code,
                    'name' => $validation['voucher']->name,
                    'status' => $validation['voucher']->getStatusText()
                ] : null
            ], 422);
        }
    }

    /**
     * Show voucher details
     */
    public function show(Voucher $voucher)
    {
        // Only show public vouchers or vouchers user can access
        if (!$voucher->is_public && !$voucher->is_active) {
            abort(404);
        }

        // Check if user can access this voucher
        if (Auth::check() && $voucher->applicable_users) {
            $canAccess = in_array(Auth::id(), $voucher->applicable_users);
            if (!$canAccess) {
                abort(403, 'Bạn không có quyền truy cập voucher này');
            }
        }

        return view('vouchers.show', compact('voucher'));
    }

    /**
     * Apply voucher to session cart (for guest checkout preparation)
     */
    public function applyToCart(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        // Store voucher code in session for checkout
        session(['voucher_code' => strtoupper(trim($request->code))]);

        return response()->json([
            'success' => true,
            'message' => 'Mã voucher đã được lưu để áp dụng khi thanh toán'
        ]);
    }

    /**
     * Remove voucher from session cart
     */
    public function removeFromCart()
    {
        session()->forget('voucher_code');

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa mã voucher khỏi giỏ hàng'
        ]);
    }

    /**
     * Get user's voucher usage history
     */
    public function myVouchers()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem lịch sử voucher');
        }

        $user = Auth::user();
        
        // Get user's voucher usages
        $usedVouchers = $user->voucherUsages()
            ->with(['voucher', 'order'])
            ->orderBy('used_at', 'desc')
            ->paginate(10);

        // Get available vouchers for user
        $availableVouchers = $this->voucherService->getAvailableVouchersForUser($user);

        return view('vouchers.my-vouchers', compact('usedVouchers', 'availableVouchers'));
    }

    /**
     * Calculate potential savings for user
     */
    public function calculateSavings(Request $request)
    {
        $request->validate([
            'order_amount' => 'required|numeric|min:0',
            'category_ids' => 'array'
        ]);

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập'
            ], 401);
        }

        $vouchers = $this->voucherService->getAvailableVouchersForUser(
            Auth::user(),
            $request->order_amount,
            $request->get('category_ids', [])
        );

        $bestSavings = 0;
        $bestVoucher = null;

        foreach ($vouchers as $voucher) {
            $discount = $voucher->calculateDiscount(
                $request->order_amount,
                $request->get('category_ids', [])
            );

            if ($discount > $bestSavings) {
                $bestSavings = $discount;
                $bestVoucher = $voucher;
            }
        }

        return response()->json([
            'success' => true,
            'best_savings' => $bestSavings,
            'formatted_savings' => number_format($bestSavings, 0, ',', '.') . 'đ',
            'best_voucher' => $bestVoucher ? [
                'code' => $bestVoucher->code,
                'name' => $bestVoucher->name,
                'discount_text' => $bestVoucher->getDiscountText()
            ] : null,
            'total_vouchers' => $vouchers->count()
        ]);
    }
}
