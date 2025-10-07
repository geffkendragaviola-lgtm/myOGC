<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resource extends Model
{
    use HasFactory; // Remove SoftDeletes from here

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'icon',
        'button_text',
        'link',
        'category',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that created the resource.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include active resources.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order resources.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('title');
    }

    /**
     * Scope a query by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get available categories
     */
    public static function getCategories(): array
    {
        return [
            'youtube' => 'YouTube Videos',
            'ebooks' => 'eBooks',
            'private' => 'Private Videos',
            'ogc' => 'OGC Resources',
        ];
    }

    /**
     * Get category label
     */
    public function getCategoryLabelAttribute(): string
    {
        return self::getCategories()[$this->category] ?? ucfirst($this->category);
    }
}
