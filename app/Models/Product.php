<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category_id', 'price', 'description', 'image', 'stock'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Reviews relationship
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->approved();
    }

    // Wishlist relationship
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishlistedByUsers()
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }

    // Rating calculations
    public function getAverageRatingAttribute()
    {
        return $this->approvedReviews()->avg('rating') ?: 0;
    }

    public function getTotalReviewsAttribute()
    {
        return $this->approvedReviews()->count();
    }

    public function getRatingStarsAttribute()
    {
        $rating = round($this->average_rating);
        return str_repeat('⭐', $rating) . str_repeat('☆', 5 - $rating);
    }

    // Image URL helper
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        // Check if it's already a full URL (starts with http:// or https://)
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        // If it's a relative path, prepend storage URL
        return asset('storage/' . $this->image);
    }

    public function hasImage()
    {
        return !empty($this->image);
    }
}