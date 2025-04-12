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
     * Display the specified post by its slug.
     *
     * @param string $slug The URL slug from the route.
     * @return \Illuminate\View\View
      */
     public function show(string $slug): View // Accept slug string instead of model
     {
         // Manually find the post by slug, converting the input slug to lowercase
         // to match how it's likely stored by the sluggable package.
         $post = Post::where('slug', mb_strtolower($slug, 'UTF-8'))->firstOrFail();

         // Consider adding status checks here if needed, e.g.,
         // if ($post->status !== 'published') {
         //     abort(404);
         // }

         // Return the view, passing the found post data
         return view('posts.show', compact('post'));
     }
}
