<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SettingController extends Controller
{
    /**
     * Show the form for editing the application settings.
     *
     * @param GeneralSettings $settings
     * @return \Illuminate\View\View
     */
    public function edit(GeneralSettings $settings)
    {
        // Automatically injects the settings instance
        return view('admin.settings.edit', compact('settings'));
    }

    /**
     * Update the application settings.
     *
     * @param Request $request
     * @param GeneralSettings $settings
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, GeneralSettings $settings)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:1000',
            'default_meta_title' => 'nullable|string|max:255',
            'default_meta_description' => 'nullable|string|max:1000',
            'robots_txt' => 'nullable|string',
            'google_analytics_id' => 'nullable|string|max:50',
            'google_site_verification' => 'nullable|string|max:100',
        ]);

        // Fill the settings instance with validated data
        $settings->fill($validated);

        // Save the settings
        $settings->save();

        return Redirect::route('admin.settings.edit')->with('status', 'settings-updated');
    }
}
