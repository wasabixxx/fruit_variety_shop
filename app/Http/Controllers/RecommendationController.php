<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecommendationController extends Controller
{
    protected $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Get personalized recommendations for user
     */
    public function getUserRecommendations(Request $request)
    {
        $limit = min($request->get('limit', 12), 24); // Max 24 items
        
        if (Auth::check()) {
            $recommendations = $this->recommendationService->getRecommendationsForUser(Auth::user(), $limit);
        } else {
            $recommendations = $this->recommendationService->getGuestRecommendations($limit);
        }
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $recommendations->map(function($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'image_url' => $product->image_url,
                        'category' => $product->category->name ?? 'N/A',
                        'average_rating' => $product->average_rating,
                        'total_reviews' => $product->total_reviews,
                        'url' => route('products.show', $product),
                    ];
                }),
                'count' => $recommendations->count()
            ]);
        }
        
        return view('recommendations.user', compact('recommendations'));
    }

    /**
     * Get similar products for a specific product
     */
    public function getSimilarProducts(Product $product, Request $request)
    {
        $limit = min($request->get('limit', 8), 16); // Max 16 items
        
        $similarProducts = $this->recommendationService->getSimilarProducts($product, $limit);
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $similarProducts->map(function($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'image_url' => $product->image_url,
                        'category' => $product->category->name ?? 'N/A',
                        'average_rating' => $product->average_rating,
                        'total_reviews' => $product->total_reviews,
                        'url' => route('products.show', $product),
                    ];
                }),
                'count' => $similarProducts->count()
            ]);
        }
        
        return view('recommendations.similar', compact('product', 'similarProducts'));
    }

    /**
     * Get popular products
     */
    public function getPopularProducts(Request $request)
    {
        $limit = min($request->get('limit', 12), 24); // Max 24 items
        
        $popularProducts = $this->recommendationService->getPopularProducts($limit);
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $popularProducts->map(function($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'image_url' => $product->image_url,
                        'category' => $product->category->name ?? 'N/A',
                        'average_rating' => $product->average_rating,
                        'total_reviews' => $product->total_reviews,
                        'url' => route('products.show', $product),
                    ];
                }),
                'count' => $popularProducts->count()
            ]);
        }
        
        return view('recommendations.popular', compact('popularProducts'));
    }

    /**
     * Get trending products
     */
    public function getTrendingProducts(Request $request)
    {
        $limit = min($request->get('limit', 12), 24); // Max 24 items
        
        $trendingProducts = $this->recommendationService->getTrendingProducts($limit);
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $trendingProducts->map(function($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'image_url' => $product->image_url,
                        'category' => $product->category->name ?? 'N/A',
                        'average_rating' => $product->average_rating,
                        'total_reviews' => $product->total_reviews,
                        'url' => route('products.show', $product),
                    ];
                }),
                'count' => $trendingProducts->count()
            ]);
        }
        
        return view('recommendations.trending', compact('trendingProducts'));
    }

    /**
     * Clear user's recommendation cache
     */
    public function clearCache(Request $request)
    {
        if (Auth::check()) {
            $this->recommendationService->clearUserCache(Auth::user());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cache đã được xóa thành công'
                ]);
            }
            
            return redirect()->back()->with('success', 'Cache gợi ý đã được làm mới');
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Bạn cần đăng nhập để thực hiện thao tác này'
        ], 401);
    }

    /**
     * Load more recommendations via AJAX
     */
    public function loadMore(Request $request)
    {
        $type = $request->get('type', 'user'); // user, popular, trending
        $offset = $request->get('offset', 0);
        $limit = min($request->get('limit', 8), 16);
        
        $recommendations = collect();
        
        switch ($type) {
            case 'popular':
                $recommendations = $this->recommendationService->getPopularProducts($limit + $offset)->skip($offset);
                break;
                
            case 'trending':
                $recommendations = $this->recommendationService->getTrendingProducts($limit + $offset)->skip($offset);
                break;
                
            case 'user':
            default:
                if (Auth::check()) {
                    $recommendations = $this->recommendationService->getRecommendationsForUser(Auth::user(), $limit + $offset)->skip($offset);
                } else {
                    $recommendations = $this->recommendationService->getGuestRecommendations($limit + $offset)->skip($offset);
                }
                break;
        }
        
        return response()->json([
            'success' => true,
            'data' => $recommendations->take($limit)->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image_url' => $product->image_url,
                    'category' => $product->category->name ?? 'N/A',
                    'average_rating' => $product->average_rating,
                    'total_reviews' => $product->total_reviews,
                    'url' => route('products.show', $product),
                ];
            }),
            'has_more' => $recommendations->count() > $limit,
            'next_offset' => $offset + $limit
        ]);
    }
}