<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; // Import the HasRoles trait
use Spatie\MediaLibrary\HasMedia; // Import the HasMedia interface
use Spatie\MediaLibrary\InteractsWithMedia; // Import the InteractsWithMedia trait

class User extends Authenticatable implements HasMedia // Implement the interface
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, InteractsWithMedia; // Add the InteractsWithMedia trait

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Register the media conversions.
     *
     * @param \Spatie\MediaLibrary\MediaCollections\Models\Media|null $media
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
              ->width(150) // Define thumbnail width
              ->height(150) // Define thumbnail height
              ->sharpen(10)
              ->nonQueued(); // Perform conversion immediately (can be queued for performance)
    }
}
