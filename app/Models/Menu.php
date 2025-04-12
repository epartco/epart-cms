<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug', // Add slug field
        'description',
        'order', // Added order field
    ];

    /**
     * Get the menu items associated with the menu.
     */
    public function items(): HasMany
    {
        // Get top-level items first, ordered by 'order'
        // Eager load children recursively, also ordered by 'order'
        return $this->hasMany(MenuItem::class)->whereNull('parent_id')->with('children')->orderBy('order');
    }

    /**
     * Get all menu items associated with the menu, regardless of hierarchy.
     * Useful for deleting all items when a menu is deleted.
     */
    public function allItems(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }
}
