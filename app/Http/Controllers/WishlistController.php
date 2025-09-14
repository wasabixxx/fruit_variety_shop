<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the user's wishlist.
     */
    public function index()
    {
        $wishlists = Wishlist::with(['product.category'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(12);

        return view('wishlist.index', compact('wishlists'));
    }

    /**
     * Add product to wishlist.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $productId = $request->product_id;
        $userId = Auth::id();

        // Check if product already in wishlist
        if (Wishlist::isInWishlist($userId, $productId)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm đã có trong danh sách yêu thích!'
                ], 400);
            }
            
            return redirect()->back()->with('error', 'Sản phẩm đã có trong danh sách yêu thích!');
        }

        // Add to wishlist
        $wishlist = Wishlist::addToWishlist($userId, $productId);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm vào danh sách yêu thích!',
                'wishlist_count' => Wishlist::where('user_id', $userId)->count()
            ]);
        }

        return redirect()->back()->with('success', 'Đã thêm vào danh sách yêu thích!');
    }

    /**
     * Remove product from wishlist.
     */
    public function destroy(Request $request, $productId = null)
    {
        $userId = Auth::id();
        
        // If productId is not in URL, try to get from request
        if (!$productId) {
            $productId = $request->product_id;
        }

        if (!$productId) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy sản phẩm!'
                ], 400);
            }
            
            return redirect()->back()->with('error', 'Không tìm thấy sản phẩm!');
        }

        // Remove from wishlist
        $deleted = Wishlist::removeFromWishlist($userId, $productId);

        if ($deleted) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đã xóa khỏi danh sách yêu thích!',
                    'wishlist_count' => Wishlist::where('user_id', $userId)->count()
                ]);
            }

            return redirect()->back()->with('success', 'Đã xóa khỏi danh sách yêu thích!');
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm trong danh sách yêu thích!'
            ], 404);
        }

        return redirect()->back()->with('error', 'Không tìm thấy sản phẩm trong danh sách yêu thích!');
    }

    /**
     * Toggle product in wishlist (AJAX endpoint).
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $productId = $request->product_id;
        $userId = Auth::id();

        // Check if product is in wishlist
        $inWishlist = Wishlist::isInWishlist($userId, $productId);

        if ($inWishlist) {
            // Remove from wishlist
            Wishlist::removeFromWishlist($userId, $productId);
            $message = 'Đã xóa khỏi danh sách yêu thích!';
            $action = 'removed';
        } else {
            // Add to wishlist
            Wishlist::addToWishlist($userId, $productId);
            $message = 'Đã thêm vào danh sách yêu thích!';
            $action = 'added';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'action' => $action,
            'in_wishlist' => !$inWishlist,
            'wishlist_count' => Wishlist::where('user_id', $userId)->count()
        ]);
    }

    /**
     * Get wishlist count for user.
     */
    public function count()
    {
        $count = Wishlist::where('user_id', Auth::id())->count();
        
        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * Check if product is in user's wishlist.
     */
    public function check(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $inWishlist = Wishlist::isInWishlist(Auth::id(), $request->product_id);

        return response()->json([
            'success' => true,
            'in_wishlist' => $inWishlist
        ]);
    }
}
