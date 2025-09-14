<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_published',
        'show_in_menu',
        'menu_order',
        'template',
        'featured_image',
        'created_by',
        'updated_by',
        'published_at'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'show_in_menu' => 'boolean',
        'menu_order' => 'integer',
        'published_at' => 'datetime'
    ];

    protected $dates = [
        'published_at'
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Auto generate slug from title
        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
            
            // Set published_at if publishing for first time
            if ($page->is_published && !$page->published_at) {
                $page->published_at = now();
            }
        });

        static::updating(function ($page) {
            // Update published_at when publishing
            if ($page->is_published && !$page->getOriginal('is_published')) {
                $page->published_at = now();
            }
        });
    }

    /**
     * Relationships
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scopes
     */
    public function scopePublished(Builder $query)
    {
        return $query->where('is_published', true)
                    ->where(function ($q) {
                        $q->whereNull('published_at')
                          ->orWhere('published_at', '<=', now());
                    });
    }

    public function scopeInMenu(Builder $query)
    {
        return $query->where('show_in_menu', true)
                    ->orderBy('menu_order');
    }

    public function scopeDraft(Builder $query)
    {
        return $query->where('is_published', false);
    }

    /**
     * Accessors & Mutators
     */
    public function getMetaTitleAttribute($value)
    {
        return $value ?: $this->title;
    }

    public function getExcerptAttribute($value)
    {
        return $value ?: Str::limit(strip_tags($this->content), 160);
    }

    /**
     * Helper methods
     */
    public function getStatusText()
    {
        if ($this->is_published) {
            return 'Published';
        }
        return 'Draft';
    }

    public function getStatusBadgeClass()
    {
        if ($this->is_published) {
            return 'bg-success';
        }
        return 'bg-secondary';
    }

    public function getPublicUrl()
    {
        return route('pages.show', $this->slug);
    }

    public function isPublished()
    {
        return $this->is_published && 
               ($this->published_at === null || $this->published_at <= now());
    }

    /**
     * Get pages for navigation menu
     */
    public static function getMenuPages()
    {
        return static::published()
                    ->inMenu()
                    ->get();
    }

    /**
     * Find by slug
     */
    public static function findBySlug($slug)
    {
        return static::where('slug', $slug)->first();
    }

    /**
     * Get available templates
     */
    public static function getTemplates()
    {
        return [
            'default' => 'Default Template',
            'about' => 'About Us Template',
            'contact' => 'Contact Template',
            'policy' => 'Policy Template',
            'full-width' => 'Full Width Template'
        ];
    }
}
