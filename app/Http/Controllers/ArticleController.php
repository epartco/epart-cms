<?php

namespace App\Http\Controllers;

use App\Models\Article; // Import the Article model
use Illuminate\Http\Request;
use Illuminate\View\View; // Import View

class ArticleController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Article $article): View
    {
        // Eager load comments along with the article
        $article->load(['comments' => function ($query) {
            $query->where('status', 'approved')->orderBy('created_at', 'asc'); // Load only approved comments, oldest first
        }, 'user']); // Also load the author

        return view('articles.show', compact('article'));
    }
}
