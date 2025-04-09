<?php

namespace App\Http\Controllers;

use App\Models\Page; // Import the Page model
use Illuminate\Http\Request;
use Illuminate\View\View; // Import View

class PageController extends Controller
{
    /**
     * Display the specified page.
     *
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function show(string $slug): View
    {
        // Find the page by its slug
        // Consider adding status checks later (e.g., only show 'published' pages)
        $page = Page::where('slug', $slug)->firstOrFail();

        // Return the view, passing the page data
        // We'll create this view in the next step
        return view('pages.show', compact('page'));
    }
}
