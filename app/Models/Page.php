<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import BelongsTo
use Cviebrock\EloquentSluggable\Sluggable; // Import Sluggable
// use Illuminate\Support\Str; // Str may not be needed anymore

class Page extends Model
{
    use HasFactory, Sluggable; // Use Sluggable trait

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'status',
        'user_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
    ];

    /**
     * Get the user that owns the page.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
