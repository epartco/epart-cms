<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Import Str facade for slug generation
use Illuminate\Validation\Rule; // Import Rule for validation

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $boards = Board::latest()->paginate(10); // Get latest boards with pagination
        return view('admin.boards.index', compact('boards'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.boards.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:boards,slug'], // Ensure slug is unique and follows URL rules
            'description' => ['nullable', 'string'],
            'read_permission' => ['nullable', 'string', 'max:255'], // Add more specific validation if needed (e.g., Rule::in)
            'write_permission' => ['nullable', 'string', 'max:255'], // Add more specific validation if needed
        ]);

        // Optionally, auto-generate slug if left empty (or handle uniqueness if provided slug exists)
        // if (empty($validatedData['slug'])) {
        //     $slug = Str::slug($validatedData['name']);
        //     $count = Board::where('slug', 'LIKE', $slug . '%')->count();
        //     if ($count > 0) {
        //         $slug = $slug . '-' . ($count + 1);
        //     }
        //     $validatedData['slug'] = $slug;
        // }
        // For simplicity now, we rely on the 'unique' validation rule for the provided slug.

        Board::create($validatedData);

        return redirect()->route('admin.boards.index')
                         ->with('success', 'Board created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Board $board)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Board $board)
    {
        // Pass the board data to the edit view
        return view('admin.boards.edit', compact('board'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Board $board)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // Ensure slug is unique, ignoring the current board's ID
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('boards')->ignore($board->id)],
            'description' => ['nullable', 'string'],
            'read_permission' => ['nullable', 'string', 'max:255'],
            'write_permission' => ['nullable', 'string', 'max:255'],
        ]);

        // Optionally, auto-generate slug if name changed and slug wasn't manually updated
        // (For simplicity, we rely on the validation rule for now)

        $board->update($validatedData);

        return redirect()->route('admin.boards.index')
                         ->with('success', 'Board updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Board $board)
    {
        // Cascading deletes for articles and comments are handled by the database
        // due to the onDelete('cascade') constraint in migrations.
        $board->delete();

        return redirect()->route('admin.boards.index')
                         ->with('success', 'Board deleted successfully.');
    }
}
