<?php

namespace App\Http\Controllers;

use App\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Http\Response; // Import Response facade

class RobotsController extends Controller
{
    /**
     * Display the contents of the robots.txt file from settings.
     *
     * @param GeneralSettings $settings
     * @return \Illuminate\Http\Response
     */
    public function index(GeneralSettings $settings)
    {
        $content = $settings->robots_txt ?? "User-agent: *\nAllow: /"; // Provide a default if empty

        return response($content, 200)
                  ->header('Content-Type', 'text/plain');
    }
}
