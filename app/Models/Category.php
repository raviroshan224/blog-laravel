<?php
// app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        // 'description',
        'slug'
    ];

    /**
     * Get all posts for this category
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Boot method to add model events
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug when creating a category
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
                
                // Ensure slug is unique
                $originalSlug = $category->slug;
                $counter = 1;
                
                while (static::where('slug', $category->slug)->exists()) {
                    $category->slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }
        });

        // Auto-update slug when updating category name
        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->name);
                
                // Ensure slug is unique
                $originalSlug = $category->slug;
                $counter = 1;
                
                while (static::where('slug', $category->slug)->where('id', '!=', $category->id)->exists()) {
                    $category->slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }
        });

        // Prevent deletion if category has posts
        static::deleting(function ($category) {
            if ($category->posts()->count() > 0) {
                throw new \Exception(
                    "Cannot delete category '{$category->name}' because it has {$category->posts()->count()} posts associated with it."
                );
            }
        });
    }

    /**
     * Check if category can be safely deleted
     */
    public function canBeDeleted(): bool
    {
        return $this->posts()->count() === 0;
    }

    /**
     * Get posts count attribute
     */
    public function getPostsCountAttribute(): int
    {
        return $this->posts()->count();
    }

    /**
     * Scope to get categories that can be deleted (no posts)
     */
    public function scopeDeletable($query)
    {
        return $query->doesntHave('posts');
    }

    /**
     * Scope to get categories that cannot be deleted (have posts)
     */
    public function scopeNotDeletable($query)
    {
        return $query->has('posts');
    }
}