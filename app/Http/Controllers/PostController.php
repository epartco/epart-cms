<?php

namespace App\Http\Controllers;

use App\Models\Post; // Import the Post model
use Illuminate\Http\Request;
use Illuminate\View\View; // Import View

class PostController extends Controller
{
    /**
     * Display a listing of the posts.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        // Fetch posts, ordered by creation date descending, with pagination
        // Consider adding status checks later (e.g., only show 'published' posts)
        $posts = Post::latest()->paginate(10); // Paginate 10 posts per page

        // Return the view, passing the posts data
        // We'll create this view in the next step
        return view('posts.index', compact('posts'));
    }

    /**
     * Display the specified post.
     *
     * Display the specified post using route model binding with the slug.
     *
     * @param \App\Models\Post $post The post instance resolved by slug.
     * @return \Illuminate\View\View
     */
    public function show(Post $post): View // Use implicit route model binding
    {
        // Laravel automatically finds the post by slug due to the route definition {slug}
        // and the Post model using Sluggable (or having getRouteKeyName() return 'slug').
        // The $post variable is already populated with the correct Post model instance.

        // Consider adding status checks here if needed, e.g.,
        // if ($post->status !== 'published') {
        //     abort(404);
        // }

        // Return the view, passing the automatically resolved post data
        return view('posts.show', compact('post'));
    }
}
