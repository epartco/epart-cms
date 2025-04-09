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
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function show(string $slug): View
    {
        // Find the post by its slug
        // Consider adding status checks later (e.g., only show 'published' posts)
        $post = Post::where('slug', $slug)->firstOrFail();

        // Return the view, passing the post data
        // We'll create this view in the next step
        return view('posts.show', compact('post'));
    }
}
