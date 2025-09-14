<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Wishlist;
use App\Models\Review;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RecommendationService
{
    protected $cacheTime = 3600; // 1 hour cache

    /**
     * Get recommended products for a user based on multiple algorithms
     */
    public function getRecommendationsForUser(User $user, int $limit = 12): Collection
    {
        $cacheKey = "user_recommendations_{$user->id}_{$limit}";
        
        return Cache::remember($cacheKey, $this->cacheTime, function() use ($user, $limit) {
            $recommendations = collect();
            
            // 1. Based on purchase history (40% weight)
            $purchaseRecommendations = $this->getRecommendationsFromPurchaseHistory($user, ceil($limit * 0.4));
            $recommendations = $recommendations->merge($purchaseRecommendations);
        
            // 2. Based on wishlist (30% weight)
            $wishlistRecommendations = $this->getRecommendationsFromWishlist($user, ceil($limit * 0.3));
            $recommendations = $recommendations->merge($wishlistRecommendations);
            
            // 3. Collaborative filtering (20% weight)
            $collaborativeRecommendations = $this->getCollaborativeRecommendations($user, ceil($limit * 0.2));
            $recommendations = $recommendations->merge($collaborativeRecommendations);
            
            // 4. Popular items (10% weight)
            $popularRecommendations = $this->getPopularProducts(ceil($limit * 0.1));
            $recommendations = $recommendations->merge($popularRecommendations);
            
            // Remove duplicates and shuffle
            return $recommendations->unique('id')->shuffle()->take($limit);
        });
    }

    /**
     * Get recommendations for a specific product (similar products)
     */
    public function getSimilarProducts(Product $product, int $limit = 8): Collection
    {
        $cacheKey = "similar_products_{$product->id}_{$limit}";
        
        return Cache::remember($cacheKey, $this->cacheTime, function() use ($product, $limit) {
            $recommendations = collect();
            
            // 1. Same category products (60% weight)
            $categoryProducts = $this->getProductsByCategory($product, ceil($limit * 0.6));
            $recommendations = $recommendations->merge($categoryProducts);
            
            // 2. Similar price range (30% weight)
            $priceRangeProducts = $this->getProductsBySimilarPrice($product, ceil($limit * 0.3));
            $recommendations = $recommendations->merge($priceRangeProducts);
            
            // 3. Frequently bought together (10% weight)
            $frequentlyBoughtTogether = $this->getFrequentlyBoughtTogether($product, ceil($limit * 0.1));
            $recommendations = $recommendations->merge($frequentlyBoughtTogether);
            
            return $recommendations->unique('id')->take($limit);
        });
    }

    /**
     * Get recommendations based on user's purchase history
     */
    protected function getRecommendationsFromPurchaseHistory(User $user, int $limit): Collection
    {
        // Get categories user has purchased from
        $purchasedCategories = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.user_id', $user->id)
            ->where('orders.order_status', 'completed')
            ->distinct()
            ->pluck('products.category_id');

        if ($purchasedCategories->isEmpty()) {
            return collect();
        }

        // Get products from these categories that user hasn't bought
        $purchasedProductIds = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.user_id', $user->id)
            ->pluck('order_items.product_id');

        return Product::whereIn('category_id', $purchasedCategories)
            ->whereNotIn('id', $purchasedProductIds)
            ->where('stock', '>', 0)
            ->with(['category', 'reviews'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recommendations based on user's wishlist
     */
    protected function getRecommendationsFromWishlist(User $user, int $limit): Collection
    {
        // Get categories from wishlist
        $wishlistCategories = Wishlist::join('products', 'wishlists.product_id', '=', 'products.id')
            ->where('wishlists.user_id', $user->id)
            ->distinct()
            ->pluck('products.category_id');

        if ($wishlistCategories->isEmpty()) {
            return collect();
        }

        // Get products from these categories not in wishlist
        $wishlistProductIds = Wishlist::where('user_id', $user->id)->pluck('product_id');

        return Product::whereIn('category_id', $wishlistCategories)
            ->whereNotIn('id', $wishlistProductIds)
            ->where('stock', '>', 0)
            ->with(['category', 'reviews'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Collaborative filtering - users who bought similar products
     */
    protected function getCollaborativeRecommendations(User $user, int $limit): Collection
    {
        // Find users who bought similar products
        $userProducts = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.user_id', $user->id)
            ->where('orders.order_status', 'completed')
            ->pluck('order_items.product_id');

        if ($userProducts->isEmpty()) {
            return collect();
        }

        // Find other users who bought these products
        $similarUsers = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('order_items.product_id', $userProducts)
            ->where('orders.user_id', '!=', $user->id)
            ->where('orders.order_status', 'completed')
            ->select('orders.user_id')
            ->groupBy('orders.user_id')
            ->havingRaw('COUNT(DISTINCT order_items.product_id) >= 2')
            ->pluck('orders.user_id');

        if ($similarUsers->isEmpty()) {
            return collect();
        }

        // Get products these similar users bought (but our user hasn't)
        return Product::whereIn('id', function($query) use ($similarUsers, $userProducts) {
                $query->select('order_items.product_id')
                    ->from('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->whereIn('orders.user_id', $similarUsers)
                    ->whereNotIn('order_items.product_id', $userProducts)
                    ->where('orders.order_status', 'completed')
                    ->groupBy('order_items.product_id')
                    ->havingRaw('COUNT(*) >= 2');
            })
            ->where('stock', '>', 0)
            ->with(['category', 'reviews'])
            ->limit($limit)
            ->get();
    }

    /**
     * Get popular products based on sales and ratings
     */
    public function getPopularProducts(int $limit = 12): Collection
    {
        $cacheKey = "popular_products_{$limit}";
        
        return Cache::remember($cacheKey, $this->cacheTime, function() use ($limit) {
            return Product::select('products.*')
                ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
                ->leftJoin('reviews', 'products.id', '=', 'reviews.product_id')
                ->where('products.stock', '>', 0)
                ->groupBy('products.id')
                ->orderByRaw('(COUNT(DISTINCT order_items.id) * 0.7 + AVG(COALESCE(reviews.rating, 0)) * 0.3) DESC')
                ->with(['category', 'reviews'])
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get trending products (high recent activity)
     */
    public function getTrendingProducts(int $limit = 12): Collection
    {
        $cacheKey = "trending_products_{$limit}";
        
        return Cache::remember($cacheKey, $this->cacheTime, function() use ($limit) {
            $thirtyDaysAgo = now()->subDays(30);
            
            return Product::select('products.*')
                ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
                ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
                ->leftJoin('wishlists', 'products.id', '=', 'wishlists.product_id')
                ->where('products.stock', '>', 0)
                ->where(function($query) use ($thirtyDaysAgo) {
                    $query->where('orders.created_at', '>=', $thirtyDaysAgo)
                          ->orWhere('wishlists.created_at', '>=', $thirtyDaysAgo);
                })
                ->groupBy('products.id')
                ->orderByRaw('(COUNT(DISTINCT order_items.id) + COUNT(DISTINCT wishlists.id)) DESC')
                ->with(['category', 'reviews'])
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get products by same category
     */
    protected function getProductsByCategory(Product $product, int $limit): Collection
    {
        return Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->with(['category', 'reviews'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get products with similar price range
     */
    protected function getProductsBySimilarPrice(Product $product, int $limit): Collection
    {
        $priceRange = $product->price * 0.3; // 30% price range
        
        return Product::whereBetween('price', [
                $product->price - $priceRange,
                $product->price + $priceRange
            ])
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->with(['category', 'reviews'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get frequently bought together products
     */
    protected function getFrequentlyBoughtTogether(Product $product, int $limit): Collection
    {
        // Find products that appear in same orders as this product
        $relatedProductIds = OrderItem::whereIn('order_id', function($query) use ($product) {
                $query->select('order_id')
                    ->from('order_items')
                    ->where('product_id', $product->id);
            })
            ->where('product_id', '!=', $product->id)
            ->groupBy('product_id')
            ->havingRaw('COUNT(*) >= 2') // Appeared together at least 2 times
            ->pluck('product_id');

        return Product::whereIn('id', $relatedProductIds)
            ->where('stock', '>', 0)
            ->with(['category', 'reviews'])
            ->limit($limit)
            ->get();
    }

    /**
     * Get recommendations for guest users (not logged in)
     */
    public function getGuestRecommendations(int $limit = 12): Collection
    {
        $cacheKey = "guest_recommendations_{$limit}";
        
        return Cache::remember($cacheKey, $this->cacheTime, function() use ($limit) {
            // Mix of popular and trending products
            $popular = $this->getPopularProducts(ceil($limit * 0.6));
            $trending = $this->getTrendingProducts(ceil($limit * 0.4));
            
            return $popular->merge($trending)->unique('id')->shuffle()->take($limit);
        });
    }

    /**
     * Clear recommendation cache for a user
     */
    public function clearUserCache(User $user): void
    {
        Cache::forget("user_recommendations_{$user->id}_12");
        Cache::forget("user_recommendations_{$user->id}_8");
        Cache::forget("user_recommendations_{$user->id}_6");
    }

    /**
     * Clear product recommendation cache
     */
    public function clearProductCache(Product $product): void
    {
        Cache::forget("similar_products_{$product->id}_8");
        Cache::forget("similar_products_{$product->id}_6");
        Cache::forget("similar_products_{$product->id}_4");
    }

    /**
     * Clear all recommendation caches
     */
    public function clearAllCaches(): void
    {
        Cache::tags(['recommendations'])->flush();
    }
}