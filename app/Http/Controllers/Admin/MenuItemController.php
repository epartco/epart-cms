<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu; // Import Menu model
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Import Rule for validation

class MenuItemController extends Controller
{
    // Note: index, create, show, edit methods are not used for AJAX management within menu edit page.

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created menu item in storage.
     */
    public function store(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'target' => ['nullable', 'string', Rule::in(['_self', '_blank'])],
            'parent_id' => 'nullable|exists:menu_items,id', // Ensure parent exists if provided
        ]);

        // Determine order: get max order for this menu and parent + 1
        $maxOrder = $menu->allItems() // Use allItems to check across the entire menu for simplicity or filter by parent_id
                        ->where('parent_id', $validated['parent_id'] ?? null)
                        ->max('order');

        $menuItem = $menu->allItems()->create([ // Use allItems relationship to associate with the menu
            'title' => $validated['title'],
            'url' => $validated['url'],
            'target' => $validated['target'] ?? '_self',
            'parent_id' => $validated['parent_id'] ?? null,
            'order' => $maxOrder + 1,
        ]);

        return response()->json($menuItem);
    }


    /**
     * Update the specified menu item in storage.
     */
    public function update(Request $request, Menu $menu, MenuItem $item) // Correct parameter name $item
    {
         // Optional: Check if the item belongs to the menu for extra security
         if ($item->menu_id !== $menu->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
         }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'target' => ['nullable', 'string', Rule::in(['_self', '_blank'])],
            'parent_id' => [
                'nullable',
                'exists:menu_items,id',
                Rule::notIn([$item->id]) // Prevent setting item as its own parent
            ],
            // Order is typically handled separately
        ]);

        $item->update([
            'title' => $validated['title'],
            'url' => $validated['url'],
            'target' => $validated['target'] ?? '_self',
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        return response()->json($item);
    }

    /**
     * Remove the specified menu item from storage.
     */
    public function destroy(Menu $menu, MenuItem $item) // Correct parameter name $item
    {
         // Optional: Check if the item belongs to the menu
         if ($item->menu_id !== $menu->id) {
             return response()->json(['message' => 'Unauthorized'], 403);
         }

        // Deleting the item. Cascading delete for children should be handled by DB constraint.
        $item->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Update the order of menu items.
     * (Requires frontend implementation like SortableJS)
     */
    public function updateOrder(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'orderedIds' => 'required|array', // Change 'order' to 'orderedIds'
            'orderedIds.*' => 'integer|exists:menu_items,id', // Ensure all IDs exist
        ]);

        foreach ($validated['orderedIds'] as $index => $itemId) { // Change 'order' to 'orderedIds'
            // Ensure the item belongs to the current menu before updating
            $item = $menu->allItems()->find($itemId);
            if ($item) {
                // Update order based on array index
                $item->update(['order' => $index]);
            }
        }

        return response()->json(['success' => true, 'message' => '순서가 업데이트되었습니다.']);
    }
}
