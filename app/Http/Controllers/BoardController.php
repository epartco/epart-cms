<?php

namespace App\Http\Controllers;

use App\Models\Board; // Import the Board model
use Illuminate\Http\Request;
use Illuminate\View\View; // Import View

class BoardController extends Controller
{
    /**
     * Display a listing of the boards.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        // Fetch all boards, ordered by name or creation date
        $boards = Board::orderBy('name')->get();

        // Return the view, passing the boards data
        // We'll create this view in the next step
        return view('boards.index', compact('boards'));
    }

    /**
     * Display the specified board and its articles.
     *
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function show(string $slug): View
    {
        // Find the board by its slug
        $board = Board::where('slug', $slug)->firstOrFail();

        // Fetch articles belonging to this board, ordered by creation date descending, with pagination
        $articles = $board->articles()->latest()->paginate(15); // Paginate 15 articles per page

        // Return the view, passing the board and articles data
        // We'll create this view in the next step
        return view('boards.show', compact('board', 'articles'));
    }
}
