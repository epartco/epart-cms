<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Board; // Import Board model
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Import Rule for validation

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager load board and user relationships, order by latest, paginate
        $articles = Article::with(['board', 'user'])->latest()->paginate(15);
        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $boards = Board::pluck('name', 'id'); // Get boards for dropdown selection
        return view('admin.articles.create', compact('boards'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'board_id' => ['required', 'exists:boards,id'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'status' => ['required', Rule::in(['published', 'draft', 'pending'])],
            'is_notice' => ['nullable', 'boolean'],
        ]);

        // Prepare data for creation
        $articleData = $validatedData;
        $articleData['user_id'] = auth()->id(); // Set the author
        // Ensure is_notice is 0 if not present in the request
        $articleData['is_notice'] = $request->has('is_notice') ? 1 : 0;

        Article::create($articleData);

        return redirect()->route('admin.articles.index')
                         ->with('success', 'Article created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        $boards = Board::pluck('name', 'id'); // Get boards for dropdown selection
        // Pass the article and boards data to the edit view
        return view('admin.articles.edit', compact('article', 'boards'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $validatedData = $request->validate([
            'board_id' => ['required', 'exists:boards,id'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'status' => ['required', Rule::in(['published', 'draft', 'pending'])],
            'is_notice' => ['nullable', 'boolean'],
        ]);

        // Prepare data for update
        $articleData = $validatedData;
        // Ensure is_notice is 0 if not present in the request
        $articleData['is_notice'] = $request->has('is_notice') ? 1 : 0;
        // user_id (author) should generally not be updated

        $article->update($articleData);

        return redirect()->route('admin.articles.index')
                         ->with('success', 'Article updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        // SoftDeletes trait is used, so this will perform a soft delete.
        // Cascading deletes for comments are handled by the database.
        $article->delete();

        return redirect()->route('admin.articles.index')
                         ->with('success', 'Article deleted successfully.');
    }
}
