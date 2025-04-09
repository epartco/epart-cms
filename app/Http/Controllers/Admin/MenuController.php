<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu; // Import the Menu model
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // Add base controller import

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
            'location' => 'required|string|max:255|unique:menus,location', // Location should be unique
        ]);

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
        return view('admin.menus.edit', compact('menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $menu = Menu::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // Allow the current menu's location, otherwise check for uniqueness
            'location' => 'required|string|max:255|unique:menus,location,' . $menu->id,
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
