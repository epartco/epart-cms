<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
// Removed redundant: use App\Http\Controllers\Controller;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::with('user')->latest()->paginate(10); // Eager load author, order by latest, paginate
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
use Illuminate\Support\Str; // Import Str facade for slug generation
use Illuminate\Validation\Rule; // Import Rule for status validation

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['published', 'draft'])],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
        ]);

        // Generate a unique slug from the title
        $slug = Str::slug($validatedData['title']);
        $count = Page::where('slug', 'LIKE', $slug . '%')->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        // Add slug and user_id to the validated data
        $validatedData['slug'] = $slug;
        $validatedData['user_id'] = auth()->id(); // Assign the currently logged-in user as author

        Page::create($validatedData);

        return redirect()->route('admin.pages.index')
                         ->with('success', 'Page created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $validatedData = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['published', 'draft'])],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
        ]);

        // Check if the title has changed to regenerate the slug
        if ($page->title !== $validatedData['title']) {
            $slug = Str::slug($validatedData['title']);
            $count = Page::where('slug', 'LIKE', $slug . '%')->where('id', '!=', $page->id)->count();
            if ($count > 0) {
                $slug = $slug . '-' . ($count + 1);
            }
            $validatedData['slug'] = $slug;
        } else {
            // Keep the existing slug if title hasn't changed
            $validatedData['slug'] = $page->slug;
        }

        // user_id should generally not be updated, keep the original author
        // $validatedData['user_id'] = auth()->id();

        $page->update($validatedData);

        return redirect()->route('admin.pages.index')
                         ->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.pages.index')
                         ->with('success', 'Page deleted successfully.');
    }
}
