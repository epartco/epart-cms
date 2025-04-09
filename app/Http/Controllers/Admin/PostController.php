<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
// Removed redundant: use App\Http\Controllers\Controller;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with(['user', 'category'])->latest()->paginate(10); // Eager load author and category
        return view('admin.posts.index', compact('posts'));
    }

    /**
use App\Models\Category; // Import Category model
use App\Models\Tag; // Import Tag model

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::pluck('name', 'id');
        $tags = Tag::pluck('name'); // Get existing tag names for suggestions/autocomplete later

        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
use Illuminate\Support\Str; // Import Str facade
use Illuminate\Validation\Rule; // Import Rule
use Illuminate\Support\Facades\Storage; // Import Storage facade

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'content' => ['nullable', 'string'],
            'tags' => ['nullable', 'string'], // Tags as comma-separated string for now
            'featured_image' => ['nullable', 'image', 'max:2048'], // Example validation: image, max 2MB
            'status' => ['required', Rule::in(['published', 'draft'])],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
        ]);

        // Handle featured image upload
        $imagePath = null;
        if ($request->hasFile('featured_image') && $request->file('featured_image')->isValid()) {
            // Store in 'public/posts' directory. Make sure to run `php artisan storage:link`
            $imagePath = $request->file('featured_image')->store('posts', 'public');
        }

        // Generate a unique slug
        $slug = Str::slug($validatedData['title']);
        $count = Post::where('slug', 'LIKE', $slug . '%')->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        // Prepare data for post creation
        $postData = $validatedData;
        $postData['slug'] = $slug;
        $postData['user_id'] = auth()->id();
        $postData['featured_image_path'] = $imagePath;
        // Set published_at if status is published
        $postData['published_at'] = ($validatedData['status'] === 'published') ? now() : null;

        // Remove tags from main data as it needs separate handling
        unset($postData['tags']);

        $post = Post::create($postData);

        // Handle Tags
        if (!empty($validatedData['tags'])) {
            $tagNames = explode(',', $validatedData['tags']);
            $tagIds = [];
            foreach ($tagNames as $tagName) {
                $trimmedTagName = trim($tagName);
                if (!empty($trimmedTagName)) {
                    $tag = Tag::firstOrCreate(
                        ['slug' => Str::slug($trimmedTagName)],
                        ['name' => $trimmedTagName]
                    );
                    $tagIds[] = $tag->id;
                }
            }
            $post->tags()->sync($tagIds); // Sync tags with the post
        } else {
            $post->tags()->sync([]); // Detach all tags if input is empty
        }


        return redirect()->route('admin.posts.index')
                         ->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $categories = Category::pluck('name', 'id');
        $tags = Tag::pluck('name'); // For potential autocomplete/suggestions
        $postTags = $post->tags->pluck('name')->implode(','); // Get current tags as comma-separated string

        return view('admin.posts.edit', compact('post', 'categories', 'tags', 'postTags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validatedData = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'content' => ['nullable', 'string'],
            'tags' => ['nullable', 'string'], // Tags as comma-separated string
            'featured_image' => ['nullable', 'image', 'max:2048'], // Optional update
            'status' => ['required', Rule::in(['published', 'draft'])],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
        ]);

        // Prepare data for update
        $postData = $validatedData;

        // Handle featured image update
        if ($request->hasFile('featured_image') && $request->file('featured_image')->isValid()) {
            // Delete old image if it exists
            if ($post->featured_image_path) {
                Storage::disk('public')->delete($post->featured_image_path);
            }
            // Store new image
            $postData['featured_image_path'] = $request->file('featured_image')->store('posts', 'public');
        } else {
            // Keep the existing image path if no new image is uploaded
            $postData['featured_image_path'] = $post->featured_image_path;
        }

        // Update slug only if the title has changed
        if ($post->title !== $validatedData['title']) {
            $slug = Str::slug($validatedData['title']);
            // Ensure uniqueness, excluding the current post
            $count = Post::where('slug', 'LIKE', $slug . '%')->where('id', '!=', $post->id)->count();
            if ($count > 0) {
                $slug = $slug . '-' . ($count + 1);
            }
            $postData['slug'] = $slug;
        } else {
            $postData['slug'] = $post->slug; // Keep original slug
        }

        // Update published_at based on status change
        if ($validatedData['status'] === 'published' && is_null($post->published_at)) {
            $postData['published_at'] = now();
        } elseif ($validatedData['status'] === 'draft') {
            $postData['published_at'] = null;
        } else {
             $postData['published_at'] = $post->published_at; // Keep original published_at if already published
        }


        // Remove tags from main data for separate handling
        unset($postData['tags']);

        $post->update($postData);

        // Handle Tags update
        if (!empty($validatedData['tags'])) {
            $tagNames = explode(',', $validatedData['tags']);
            $tagIds = [];
            foreach ($tagNames as $tagName) {
                $trimmedTagName = trim($tagName);
                if (!empty($trimmedTagName)) {
                    $tag = Tag::firstOrCreate(
                        ['slug' => Str::slug($trimmedTagName)],
                        ['name' => $trimmedTagName]
                    );
                    $tagIds[] = $tag->id;
                }
            }
            $post->tags()->sync($tagIds); // Sync tags
        } else {
            $post->tags()->sync([]); // Detach all tags if input is empty
        }

        return redirect()->route('admin.posts.index')
                         ->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Delete the featured image from storage if it exists
        if ($post->featured_image_path) {
            Storage::disk('public')->delete($post->featured_image_path);
        }

        // Detach tags before deleting the post to clean up pivot table entries
        $post->tags()->detach();

        // Delete the post
        $post->delete();

        return redirect()->route('admin.posts.index')
                         ->with('success', 'Post deleted successfully.');
    }
}
