<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable; // Add Sluggable import
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import BelongsTo
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // Import BelongsToMany

class Post extends Model
{
    use HasFactory, Sluggable; // Add Sluggable trait

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        // 'slug', // Remove slug from fillable, Sluggable handles it
        'content',
        'status',
        'published_at',
        'user_id',
        'category_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'featured_image_path', // Restore featured image path
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Get the user that owns the post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that owns the post.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * The tags that belong to the post.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class); // Assumes pivot table name 'post_tag'
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                // Custom method to handle Korean characters correctly
                'method' => function ($string, $separator = '-') {
                    // 1. Remove characters that are not Korean, letters, numbers, or separator
                    $string = preg_replace('/[^\\p{L}\\p{N}\\'.preg_quote($separator).']+/u', $separator, $string);
                    // 2. Replace multiple separators with a single one
                    $string = preg_replace('/\\'.preg_quote($separator).'{2,}/', $separator, $string);
                    // 3. Trim separators from the beginning and end
                    $string = trim($string, $separator);
                    // 4. Convert to lowercase
                    $string = mb_strtolower($string, 'UTF-8'); // Use mb_strtolower for Unicode
                    return $string;
                },
                'separator' => '-', // Keep separator as hyphen
                'onUpdate' => true // Update slug when title is updated
            ]
        ];
    }
}
