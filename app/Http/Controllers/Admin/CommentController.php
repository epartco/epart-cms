<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Import Rule for validation

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager load article and user relationships, order by latest, paginate
        $comments = Comment::with(['article', 'user'])->latest()->paginate(20);
        return view('admin.comments.index', compact('comments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        // Validate the status input
        $validatedData = $request->validate([
            'status' => ['required', Rule::in(['approved', 'pending', 'spam'])],
        ]);

        // Update the comment status
        $comment->update(['status' => $validatedData['status']]);

        return redirect()->route('admin.comments.index')
                         ->with('success', 'Comment status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        // SoftDeletes trait is used, so this will perform a soft delete.
        $comment->delete();

        return redirect()->route('admin.comments.index')
                         ->with('success', 'Comment deleted successfully.');
    }
}
