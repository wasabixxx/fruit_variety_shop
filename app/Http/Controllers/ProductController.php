<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    public function index()
    {
        $products = Product::with('category')->paginate(12);
        $categories = Category::withCount('products')->get();
        
        // Get trending products for homepage
        $trendingProducts = $this->recommendationService->getTrendingProducts(8);
        
        return view('products.index', compact('products', 'categories', 'trendingProducts'));
    }

    public function show(Product $product)
    {
        $product->load('category');
        // Eager load reviews count and average rating for view
        $product->reviews_count = $product->approvedReviews()->count();
        $product->average_rating = round($product->approvedReviews()->avg('rating'), 1);

        // Get similar products using recommendation service
        $relatedProducts = $this->recommendationService->getSimilarProducts($product, 4);
        
        // Get personalized recommendations for logged-in users
        $recommendedProducts = collect();
        if (Auth::check()) {
            $recommendedProducts = $this->recommendationService->getRecommendationsForUser(Auth::user(), 6);
        } else {
            $recommendedProducts = $this->recommendationService->getGuestRecommendations(6);
        }
        
        return view('products.show', compact('product', 'relatedProducts', 'recommendedProducts'));
    }
}