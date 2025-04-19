<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu; // Import the Menu model
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // Add base controller import
use Illuminate\Support\Str; // Import Str facade for slug generation

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::latest()->paginate(15); // Fetch menus, paginate later if needed
        return view('admin.menus.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.menus.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // 'url' => 'required|string|max:255', // Removed url validation
            'description' => 'nullable|string',
        ]);

        // Ensure only necessary fields are passed to create
        Menu::create($validated);

        return redirect()->route('admin.menus.index')->with('success', '메뉴가 성공적으로 생성되었습니다.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $menu = Menu::findOrFail($id); // Find the menu or fail
        // Get all items for this menu, ordered by 'order'
        $allItems = $menu->allItems()->orderBy('order')->get();

        // Function to recursively assign depth to each item
        $assignDepth = function ($items, $parentId = null, $depth = 0) use (&$assignDepth) {
            $result = collect(); // Use a collection to store results
            foreach ($items->where('parent_id', $parentId) as $item) {
                $item->depth = $depth; // Assign depth property
                $result->push($item); // Add item to the result collection
                // Recursively get children and merge them into the result
                $children = $assignDepth($items, $item->id, $depth + 1);
                $result = $result->merge($children);
            }
            return $result;
        };

        // Assign depth to all items and get the ordered list with depth info
        $menuItems = $assignDepth($allItems);

        // Use the depth-assigned list for both the main list and the parent dropdown options
        $parentOptions = $menuItems;

        return view('admin.menus.edit', compact('menu', 'menuItems', 'parentOptions'));
    }

    // Note: $menuItems and $parentOptions now both contain the 'depth' property.

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $menu = Menu::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string', // Add description validation
        ]);

        $menu->update($validated);

        return redirect()->route('admin.menus.index')->with('success', '메뉴가 성공적으로 업데이트되었습니다.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $menu = Menu::findOrFail($id);
        // Consider deleting related menu items here if applicable in the future
        $menu->delete();

        return redirect()->route('admin.menus.index')->with('success', '메뉴가 성공적으로 삭제되었습니다.');
    }
}
