@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-semibold mb-4">Edit Media Item</h1>

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

        <div class="bg-white shadow rounded p-6">
            <form action="{{ route('admin.media.update', $medium->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Media Preview --}}
                    <div class="md:col-span-1">
                        <h2 class="text-lg font-medium text-gray-900 mb-2">Preview</h2>
                        @if (Str::startsWith($medium->mime_type, 'image/'))
                            <img src="{{ $medium->getUrl('thumbnail') }}" alt="{{ $medium->name }}" class="w-full h-auto object-cover rounded border">
                        @else
                            <div class="w-full h-32 bg-gray-200 flex items-center justify-center rounded border">
                                <span class="text-gray-500 text-sm">{{ $medium->mime_type }}</span>
                            </div>
                        @endif
                        <p class="text-xs text-gray-500 mt-2">Filename: {{ $medium->file_name }}</p>
                        <p class="text-xs text-gray-500">Size: {{ $medium->human_readable_size }}</p>
                        <p class="text-xs text-gray-500">Uploaded: {{ $medium->created_at->format('Y-m-d H:i') }}</p>
                    </div>

                    {{-- Edit Fields --}}
                    <div class="md:col-span-2">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Details</h2>

                        {{-- Name Field --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $medium->name) }}" required
                                   class="mt-1 block w-full px-3 py-2 border @error('name') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Display name for the media item (without extension).</p>
                        </div>

                        {{-- Alt Text Field --}}
                        <div class="mb-4">
                            <label for="alt" class="block text-sm font-medium text-gray-700">Alt Text (for images)</label>
                            <input type="text" name="alt" id="alt" value="{{ old('alt', $medium->getCustomProperty('alt')) }}"
                                   class="mt-1 block w-full px-3 py-2 border @error('alt') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('alt')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Descriptive text for screen readers and SEO.</p>
                        </div>

                        {{-- Add other fields like caption if needed --}}
                        {{-- <div class="mb-4">
                            <label for="caption" class="block text-sm font-medium text-gray-700">Caption</label>
                            <textarea name="caption" id="caption" rows="3"
                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('caption', $medium->getCustomProperty('caption')) }}</textarea>
                        </div> --}}

                        {{-- Submit Button --}}
                        <div class="flex justify-end mt-6">
                            <a href="{{ route('admin.media.index') }}" class="mr-4 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
