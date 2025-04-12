<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_name;
    public ?string $site_description; // Nullable in case user doesn't provide one
    public ?string $default_meta_title; // Nullable
    public ?string $default_meta_description; // Nullable
    public ?string $robots_txt; // Nullable
    public ?string $google_analytics_id; // Nullable
    public ?string $google_site_verification; // Nullable

    public static function group(): string
    {
        return 'general';
    }
}
