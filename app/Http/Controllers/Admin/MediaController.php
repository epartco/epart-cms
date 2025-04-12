<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media; // Import the Media model

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get paginated media items, ordered by latest first
        $mediaItems = Media::latest()->paginate(12); // Adjust pagination size as needed

        // Generate thumbnail URLs if they don't exist yet (optional, depends on config)
        // This can be intensive, consider doing it on upload or via a job
        // foreach ($mediaItems as $item) {
        //     if ($item->hasGeneratedConversion('thumbnail')) {
        //         $item->getUrl('thumbnail'); // Ensure conversion is generated if needed
        //     }
        // }

        return view('admin.media.index', compact('mediaItems'));
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
        $request->validate([
            // Validate that 'file' is present and is an array
            'file' => 'required|array',
            // Validate each file in the 'file' array
            'file.*' => 'required|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,zip|max:10240', // Max 10MB per file, adjust mimes and max size as needed
        ]);

        try {
            // The medialibrary requires a model to attach the media to.
            // Since we don't have a specific model for the library itself,
            // we can attach it to the currently authenticated user or a dummy model.
            // Attaching to the user makes sense for tracking who uploaded what.
            $user = $request->user();

            if (!$user) {
                 // Handle cases where there's no authenticated user (shouldn't happen with 'auth' middleware)
                 return back()->with('error', 'Authentication error.');
            }

            foreach ($request->file('file') as $file) {
                // Add the file to the 'default' media collection associated with the user
                $user->addMedia($file)->toMediaCollection();
                // You can specify a different collection name if needed, e.g., 'images', 'documents'
            }

            return back()->with('success', 'Media uploaded successfully.');

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Media upload failed: ' . $e->getMessage());
            return back()->with('error', 'Media upload failed. Please try again.');
        }
    }

    /**
     * Return a list of media items as JSON for the media library modal.
     */
    public function listJson()
    {
        try {
            // Use pagination instead of get() to avoid memory issues
            $perPage = 24; // Number of items per page, adjust as needed
            $mediaItems = Media::latest()->paginate($perPage);

            // Map the items for the current page
            $formattedMedia = $mediaItems->getCollection()->map(function ($item) {
                // Default placeholder image as data URI
                $placeholderImage = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2VlZWVlZSIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMjAiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGRvbWluYW50LWJhc2VsaW5lPSJtaWRkbGUiIGZpbGw9IiM5OTk5OTkiPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg==';
                
                // Get thumbnail URL with fallbacks
                try {
                    // Check if the media has a thumbnail conversion
                    if ($item->hasGeneratedConversion('thumbnail')) {
                        $thumbnailUrl = $item->getUrl('thumbnail');
                    } else {
                        // If no thumbnail, use original
                        $thumbnailUrl = $item->getUrl();
                        \Log::info("No thumbnail conversion for media ID: {$item->id}. Using original.");
                    }
                } catch (\Exception $e) {
                    // Any exception, use placeholder
                    $thumbnailUrl = $placeholderImage;
                    \Log::error("Error getting thumbnail URL for media ID: {$item->id}. Error: {$e->getMessage()}");
                }

                // Get original URL with fallback
                try {
                    $originalUrl = $item->getUrl();
                } catch (\Exception $e) {
                    $originalUrl = $placeholderImage;
                    \Log::error("Error getting original URL for media ID: {$item->id}. Error: {$e->getMessage()}");
                }

                // Get custom properties safely
                $altText = '';
                try {
                    $altText = $item->getCustomProperty('alt', '');
                } catch (\Exception $e) {
                    \Log::error("Error getting alt text for media ID: {$item->id}. Error: {$e->getMessage()}");
                }

                return [
                    'id' => $item->id,
                    'name' => $item->name ?? 'Unnamed Media',
                    'file_name' => $item->file_name ?? 'unknown.file',
                    'mime_type' => $item->mime_type ?? 'application/octet-stream',
                    'size' => $item->size ?? 0,
                    'url' => $originalUrl,
                    'thumbnail_url' => $thumbnailUrl,
                    'alt_text' => $altText,
                    'created_at' => $item->created_at ? $item->created_at->toIso8601String() : now()->toIso8601String(),
                ];
            });

            // Create a simple array response instead of using the paginator
            $response = [
                'data' => $formattedMedia->toArray(),
                'total' => $mediaItems->total(),
                'per_page' => $mediaItems->perPage(),
                'current_page' => $mediaItems->currentPage(),
                'last_page' => $mediaItems->lastPage(),
            ];

            // Return a simple JSON response
            return response()->json($response);
        } catch (\Exception $e) {
            \Log::error("Error in listJson method: {$e->getMessage()}");
            \Log::error("Stack trace: " . $e->getTraceAsString());
            return response()->json(['error' => 'Failed to load media library: ' . $e->getMessage()], 500);
        }
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
    public function edit(Media $medium) // Use route model binding
    {
        // Pass the media item to the edit view
        return view('admin.media.edit', compact('medium'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Media $medium) // Use route model binding
    {
        $request->validate([
            'alt' => 'nullable|string|max:255', // Validate alt text
            'name' => 'required|string|max:255', // Validate name (filename without extension)
            // Add validation for other fields if needed (e.g., caption)
        ]);

        try {
            // Update custom properties
            $medium->setCustomProperty('alt', $request->input('alt'));

            // Update the name (filename without extension)
            // Be careful with this, as it might affect URLs if not handled properly.
            // The medialibrary package uses 'name' for the display name,
            // and 'file_name' for the actual stored file name.
            // We'll update the 'name' property which is often used for display.
            $medium->name = $request->input('name');

            // Save the changes
            $medium->save();

            return redirect()->route('admin.media.index')->with('success', 'Media item updated successfully.');

        } catch (\Exception $e) {
            \Log::error('Media update failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update media item. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $mediaItem = Media::findOrFail($id);

            // Optional: Check permissions if needed (e.g., only allow uploader or admin to delete)
            // if (auth()->user()->cannot('delete', $mediaItem)) { // Assuming a policy exists
            //     return back()->with('error', 'You do not have permission to delete this item.');
            // }

            $mediaItem->delete();

            return back()->with('success', 'Media item deleted successfully.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Media item not found.');
        } catch (\Exception $e) {
            \Log::error('Media deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete media item. Please try again.');
        }
    }
}
