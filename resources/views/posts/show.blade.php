<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    <h1 class="text-3xl md:text-4xl font-semibold mb-4">{{ $post->title }}</h1>
                    <p class="text-sm text-gray-500 mb-6">
                        Published on {{ $post->created_at?->format('M d, Y') }}
                        {{-- Add author later if needed: by {{ $post->author->name }} --}}
                        {{-- Add category/tags later if needed --}}
                    </p>
                    <div class="prose max-w-none"> {{-- Restore 'prose' class --}}
                        {{-- Ensure content is displayed safely. Use {!! $post->content !!} if content contains HTML and is trusted. --}}
                        {!! $post->content !!}
                    </div>

                    {{-- Optional: Add back button --}}
                    <div class="mt-8">
                        <a href="{{ route('posts.index') }}" class="text-blue-500 hover:text-blue-700">&larr; Back to Posts</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
