<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created review
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        // Check if user can review this product
        if (!self::userCanReviewProduct($request->product_id, Auth::id())) {
            return back()->with('error', 'Bạn chỉ có thể đánh giá sản phẩm từ đơn hàng đã giao thành công và chưa được đánh giá.');
        }

        // Find a delivered order that contains this product
        $order = Order::where('user_id', Auth::id())
            ->where('order_status', 'delivered')
            ->whereHas('orderItems', function($query) use ($request) {
                $query->where('product_id', $request->product_id);
            })
            ->first();

        if (!$order) {
            return back()->with('error', 'Không tìm thấy đơn hàng đã giao cho sản phẩm này.');
        }

        // Create review
        $review = Review::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'order_id' => $order->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_verified' => true, // Verified purchase
            'is_approved' => true // Auto approve for now
        ]);

        return back()->with('success', 'Đánh giá đã được gửi thành công!');
    }

    /**
     * Get reviews for a product
     */
    public function getProductReviews(Product $product, Request $request)
    {
        $rating = $request->get('rating');
        $perPage = $request->get('per_page', 10);

        $query = $product->approvedReviews()
            ->with(['user:id,name'])
            ->latest();

        if ($rating) {
            $query->byRating($rating);
        }

        $reviews = $query->paginate($perPage);

        // Calculate rating distribution
        $ratingDistribution = $product->approvedReviews()
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        return response()->json([
            'reviews' => $reviews,
            'average_rating' => round($product->average_rating, 1),
            'total_reviews' => $product->total_reviews,
            'rating_distribution' => $ratingDistribution
        ]);
    }

    /**
     * Update a review
     */
    public function update(Request $request, Review $review)
    {
        // Debug log
        \Log::info('Review update called', [
            'review_id' => $review->id,
            'method' => $request->method(),
            'user_id' => Auth::id(),
            'data' => $request->all()
        ]);

        // Verify ownership
        if ($review->user_id !== Auth::id()) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Không có quyền chỉnh sửa đánh giá này.'], 403);
            }
            return back()->with('error', 'Không có quyền chỉnh sửa đánh giá này.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Đánh giá đã được cập nhật!',
                'review' => $review
            ]);
        }

        return back()->with('success', 'Đánh giá đã được cập nhật!');
    }

    /**
     * Delete a review
     */
    public function destroy(Request $request, Review $review)
    {
        // Verify ownership or admin
        if ($review->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            if ($request->ajax()) {
                return response()->json(['error' => 'Không có quyền xóa đánh giá này.'], 403);
            }
            return back()->with('error', 'Không có quyền xóa đánh giá này.');
        }

        $review->delete();

        if ($request->ajax()) {
            return response()->json(['message' => 'Đánh giá đã được xóa.']);
        }

        return back()->with('success', 'Đánh giá đã được xóa.');
    }

    /**
     * Get user's reviews
     */
    public function getUserReviews(Request $request)
    {
        $reviews = Auth::user()->reviews()
            ->with(['product:id,name,price', 'order:id,created_at'])
            ->latest()
            ->paginate(10);

        return response()->json($reviews);
    }

    /**
     * Check if user can review a product from an order (API endpoint)
     */
    public function canReview(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id'
        ]);

        // Check if order belongs to user and is delivered
        $order = Order::where('id', $request->order_id)
            ->where('user_id', Auth::id())
            ->where('order_status', 'delivered')
            ->first();

        if (!$order) {
            return response()->json(['can_review' => false, 'reason' => 'Order not delivered']);
        }

        // Check if product is in order
        $orderItem = $order->orderItems()->where('product_id', $request->product_id)->first();
        if (!$orderItem) {
            return response()->json(['can_review' => false, 'reason' => 'Product not in order']);
        }

        // Check if already reviewed
        $existingReview = Review::where([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'order_id' => $request->order_id
        ])->first();

        return response()->json([
            'can_review' => !$existingReview,
            'existing_review' => $existingReview,
            'reason' => $existingReview ? 'Already reviewed' : null
        ]);
    }

    /**
     * Check if user can review a product (helper method for views)
     */
    public static function userCanReviewProduct($productId, $userId)
    {
        if (!$userId) {
            return false;
        }

        // Check if user has any delivered orders containing this product
        $hasDeliveredOrder = Order::where('user_id', $userId)
            ->where('order_status', 'delivered')
            ->whereHas('orderItems', function($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->exists();

        if (!$hasDeliveredOrder) {
            return false;
        }

        // Check if user already reviewed this product
        $hasReviewed = Review::where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();

        return !$hasReviewed;
    }
}
