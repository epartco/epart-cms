<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Import Str facade for slug generation
use Illuminate\Validation\Rule; // Import Rule for status validation
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

         // Add user_id to the validated data
         // Slug will be generated automatically by the Sluggable trait in the Page model
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

         // Slug will be updated automatically by the Sluggable trait if the title changes.
         // user_id should generally not be updated, keep the original author.

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
