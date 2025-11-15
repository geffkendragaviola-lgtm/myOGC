<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'icon',
        'button_text',
        'link',
        'category',
        'image_path',
        'use_yt_thumbnail',
        'is_active',
        'show_disclaimer',
        'disclaimer_text'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'use_yt_thumbnail' => 'boolean',
        'show_disclaimer' => 'boolean',
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
        return $query->orderBy('created_at', 'desc');
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

    /**
     * Get the image URL
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->use_yt_thumbnail && $this->link) {
            return $this->getYoutubeThumbnail($this->link);
        }

        if ($this->image_path) {
            return Storage::url($this->image_path);
        }

        // Default placeholder image
        return asset('images/default-resource.jpg');
    }

    /**
     * Extract YouTube thumbnail from URL
     */
    private function getYoutubeThumbnail($url): string
    {
        // Extract video ID from various YouTube URL formats
        $videoId = $this->extractYoutubeVideoId($url);

        if ($videoId) {
            return "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg";
        }

        return asset('images/default-resource.jpg');
    }

    /**
     * Extract YouTube video ID from URL
     */
    private function extractYoutubeVideoId($url): ?string
    {
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
        preg_match($pattern, $url, $matches);

        return $matches[1] ?? null;
    }

    /**
     * Check if resource is a YouTube video
     */
    public function getIsYoutubeAttribute(): bool
    {
        return $this->category === 'youtube' && $this->link && str_contains($this->link, 'youtube.com');
    }

    /**
     * Get default disclaimer text
     */
    public function getDefaultDisclaimerAttribute(): string
    {
        return 'We We do not claim ownership of this content. All rights, credits, and copyrights belong to the original owners. These resources are shared for educational and informational purposes only.';
    }

    /**
     * Get the display disclaimer text
     */
    public function getDisplayDisclaimerAttribute(): string
    {
        if ($this->show_disclaimer) {
            return $this->disclaimer_text ?: $this->default_disclaimer;
        }
        return '';
    }
}
