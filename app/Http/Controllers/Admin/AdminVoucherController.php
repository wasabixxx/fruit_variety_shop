<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Models\Category;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminVoucherController extends Controller
{
    protected $voucherService;

    public function __construct(VoucherService $voucherService)
    {
        $this->voucherService = $voucherService;
    }

    /**
     * Display vouchers list
     */
    public function index(Request $request)
    {
        $query = Voucher::with(['creator']);

        // Search filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', true)
                          ->where(function ($q) {
                              $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                          });
                    break;
                case 'expired':
                    $query->where('expires_at', '<', now());
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
                case 'used_up':
                    $query->whereRaw('used_count >= usage_limit')->whereNotNull('usage_limit');
                    break;
            }
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $vouchers = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.vouchers.index', compact('vouchers'));
    }

    /**
     * Show voucher creation form
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.vouchers.create', compact('categories'));
    }

    /**
     * Store new voucher
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|max:50|unique:vouchers,code',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'maximum_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'is_active' => 'boolean',
            'is_public' => 'boolean',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        try {
            $data = $request->except(['action', 'category_filter']);
            
            // Process form data
            $data['is_active'] = $request->has('is_active');
            $data['is_public'] = $request->has('is_public');
            
            // Handle percentage validation
            if ($data['type'] === 'percentage' && $data['value'] > 100) {
                return redirect()->back()
                    ->withErrors(['value' => 'Phần trăm giảm giá không được vượt quá 100%'])
                    ->withInput();
            }

            // Create voucher
            $voucher = $this->voucherService->createVoucher($data, Auth::user());
            
            // Attach categories if specified
            if ($request->filled('category_ids')) {
                $voucher->categories()->sync($request->category_ids);
            }
            
            // Check for action
            if ($request->action === 'save_and_continue') {
                return redirect()->route('admin.vouchers.create')
                    ->with('success', 'Voucher đã được tạo thành công. Tiếp tục tạo voucher mới.');
            }
                
            return redirect()->route('admin.vouchers.show', $voucher)
                ->with('success', 'Voucher đã được tạo thành công');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show voucher details
     */
    public function show(Voucher $voucher)
    {
        $voucher->load(['creator', 'usages.user', 'usages.order']);
        $stats = $this->voucherService->getVoucherStats($voucher);
        
        return view('admin.vouchers.show', compact('voucher', 'stats'));
    }

    /**
     * Show voucher edit form
     */
    public function edit(Voucher $voucher)
    {
        $categories = Category::all();
        return view('admin.vouchers.edit', compact('voucher', 'categories'));
    }

    /**
     * Update voucher
     */
    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('vouchers', 'code')->ignore($voucher->id)
            ],
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'maximum_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:' . $voucher->used_count,
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'is_active' => 'boolean',
            'is_public' => 'boolean',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        try {
            $data = $request->except(['category_filter']);
            
            // Process form data
            $data['is_active'] = $request->has('is_active');
            $data['is_public'] = $request->has('is_public');

            // Handle percentage validation
            if ($data['type'] === 'percentage' && $data['value'] > 100) {
                return redirect()->back()
                    ->withErrors(['value' => 'Phần trăm giảm giá không được vượt quá 100%'])
                    ->withInput();
            }

            // Validate restrictions for used vouchers
            if ($voucher->used_count > 0) {
                // Can't change type or increase minimum order amount
                if ($voucher->type !== $data['type']) {
                    return redirect()->back()
                        ->withErrors(['type' => 'Không thể thay đổi loại voucher đã được sử dụng'])
                        ->withInput();
                }
                
                if ($voucher->minimum_order_amount && $data['minimum_order_amount'] > $voucher->minimum_order_amount) {
                    return redirect()->back()
                        ->withErrors(['minimum_order_amount' => 'Không thể tăng yêu cầu đơn hàng tối thiểu'])
                        ->withInput();
                }
                
                // Can only decrease discount value
                if ($data['value'] > $voucher->value) {
                    return redirect()->back()
                        ->withErrors(['value' => 'Chỉ có thể giảm giá trị voucher, không thể tăng'])
                        ->withInput();
                }
            }

            $voucher->update($data);
            
            // Update categories
            if ($request->filled('category_ids')) {
                $voucher->categories()->sync($request->category_ids);
            } else {
                $voucher->categories()->detach();
            }
                
            return redirect()->route('admin.vouchers.show', $voucher)
                ->with('success', 'Voucher đã được cập nhật thành công');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Delete voucher
     */
    public function destroy(Voucher $voucher)
    {
        if ($voucher->used_count > 0) {
            return redirect()->back()
                ->withErrors(['error' => 'Không thể xóa voucher đã được sử dụng']);
        }

        $voucher->delete();

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher đã được xóa thành công');
    }

    /**
     * Toggle voucher status
     */
    public function toggleStatus(Voucher $voucher)
    {
        $voucher->update(['is_active' => !$voucher->is_active]);

        $status = $voucher->is_active ? 'kích hoạt' : 'tạm dừng';
        
        return redirect()->back()
            ->with('success', "Voucher đã được {$status} thành công");
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'voucher_ids' => 'required|array',
            'voucher_ids.*' => 'exists:vouchers,id'
        ]);

        $vouchers = Voucher::whereIn('id', $request->voucher_ids);
        $count = $vouchers->count();

        switch ($request->action) {
            case 'activate':
                $vouchers->update(['is_active' => true]);
                $message = "Đã kích hoạt {$count} voucher";
                break;
                
            case 'deactivate':
                $vouchers->update(['is_active' => false]);
                $message = "Đã tạm dừng {$count} voucher";
                break;
                
            case 'delete':
                // Only delete unused vouchers
                $used = $vouchers->where('used_count', '>', 0)->count();
                $unused = $vouchers->where('used_count', 0);
                $deletedCount = $unused->count();
                $unused->delete();
                
                if ($used > 0) {
                    $message = "Đã xóa {$deletedCount} voucher. Không thể xóa {$used} voucher đã được sử dụng";
                } else {
                    $message = "Đã xóa {$deletedCount} voucher";
                }
                break;
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Export vouchers
     */
    public function export(Request $request)
    {
        $vouchers = Voucher::with(['creator'])
            ->when($request->filled('status'), function ($query) use ($request) {
                // Apply same filters as index
                switch ($request->status) {
                    case 'active':
                        $query->where('is_active', true)
                              ->where(function ($q) {
                                  $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                              });
                        break;
                    case 'expired':
                        $query->where('expires_at', '<', now());
                        break;
                    case 'inactive':
                        $query->where('is_active', false);
                        break;
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'vouchers_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($vouchers) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Mã voucher',
                'Tên voucher',
                'Loại',
                'Giá trị',
                'Đã sử dụng',
                'Giới hạn',
                'Trạng thái',
                'Ngày tạo',
                'Ngày hết hạn',
                'Người tạo'
            ]);

            foreach ($vouchers as $voucher) {
                fputcsv($file, [
                    $voucher->code,
                    $voucher->name,
                    $voucher->type === 'percentage' ? 'Phần trăm' : 'Cố định',
                    $voucher->getDiscountText(),
                    $voucher->used_count,
                    $voucher->usage_limit ?: 'Không giới hạn',
                    $voucher->getStatusText(),
                    $voucher->created_at->format('d/m/Y H:i'),
                    $voucher->expires_at ? $voucher->expires_at->format('d/m/Y H:i') : 'Không giới hạn',
                    $voucher->creator->name ?? 'N/A'
                ]);
            }

            fclose($file);
        }, 200, $headers);
    }

    /**
     * Get voucher statistics for dashboard
     */
    public function statistics()
    {
        $stats = [
            'total_vouchers' => Voucher::count(),
            'active_vouchers' => Voucher::active()->count(),
            'expired_vouchers' => Voucher::where('expires_at', '<', now())->count(),
            'used_vouchers' => Voucher::where('used_count', '>', 0)->count(),
            'total_usage' => Voucher::sum('used_count'),
            'total_discount_given' => \App\Models\VoucherUsage::sum('discount_amount'),
            'top_vouchers' => Voucher::orderBy('used_count', 'desc')->limit(5)->get(),
            'recent_vouchers' => Voucher::latest()->limit(5)->get()
        ];

        return response()->json($stats);
    }
}