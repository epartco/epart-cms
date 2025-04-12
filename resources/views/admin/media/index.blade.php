@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-semibold mb-4">Media Library</h1>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
             <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Upload Form --}}
        <div class="mb-8 p-6 bg-white shadow rounded">
            <h2 class="text-xl font-semibold mb-4">Upload New Media</h2>
            <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="file" class="block text-sm font-medium text-gray-700">Select file(s):</label>
                    <input type="file" name="file[]" id="file" multiple required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('file')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                     @error('file.*') {{-- Show errors for individual files in the array --}}
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Upload
                </button>
            </form>
        </div>

        {{-- Media List --}}
        <div class="bg-white shadow rounded p-6">
            <h2 class="text-xl font-semibold mb-4">Uploaded Media</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                {{-- Media items will be listed here --}}
                @forelse ($mediaItems as $item)
                    <div class="border rounded p-2 text-center relative group">
                        @if (Str::startsWith($item->mime_type, 'image/'))
                            <img src="{{ $item->getUrl('thumbnail') }}" alt="{{ $item->name }}" class="w-full h-24 object-cover mb-2">
                        @else
                            {{-- Placeholder for non-image files --}}
                            <div class="w-full h-24 bg-gray-200 flex items-center justify-center mb-2">
                                <span class="text-gray-500 text-sm">{{ $item->mime_type }}</span>
                            </div>
                        @endif
                        <p class="text-xs truncate" title="{{ $item->name }}">{{ $item->name }}</p>
                        <p class="text-xs text-gray-500">{{ $item->human_readable_size }}</p>
                        {{-- Action Buttons Container --}}
                        <div class="absolute top-1 right-1 flex space-x-1 transition-opacity opacity-0 group-hover:opacity-100">
                            {{-- Edit Button --}}
                            <a href="{{ route('admin.media.edit', $item->id) }}" class="text-blue-500 hover:text-blue-700 p-1 bg-white rounded-full shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            {{-- Delete Button --}}
                            <form action="{{ route('admin.media.destroy', $item->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 p-1 bg-white rounded-full shadow" onclick="return confirm('Are you sure you want to delete this item?');">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="col-span-full text-gray-500">No media items uploaded yet.</p>
                @endforelse
            </div>

            {{-- Pagination --}}
             <div class="mt-6">
                {{ $mediaItems->links() }}
            </div>
        </div>
    </div>
@endsection
