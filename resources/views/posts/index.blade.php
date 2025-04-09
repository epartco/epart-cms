<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-semibold mb-6 text-gray-800">Blog Posts</h1>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($posts->count())
                        <div class="space-y-8">
                            @foreach ($posts as $post)
                                <article class="border-b pb-6 mb-6">
                                    <h2 class="text-2xl font-semibold mb-2">
                                        <a href="{{ route('posts.show', $post->slug) }}" class="text-blue-600 hover:underline">
                                            {{ $post->title }}
                                        </a>
                                    </h2>
                                    <p class="text-sm text-gray-500 mb-2">
                                        Published on {{ $post->created_at->format('M d, Y') }}
                                        {{-- Add author later if needed: by {{ $post->author->name }} --}}
                                    </p>
                                    <div class="prose max-w-none text-gray-700">
                                        {{-- Display excerpt or truncated content --}}
                                        {{-- Option 1: If you have an 'excerpt' field --}}
                                        {{-- $post->excerpt --}}

                                        {{-- Option 2: Truncate main content (basic example) --}}
                                        {!! Str::limit(strip_tags($post->content), 200) !!}
                                        {{-- Note: Using strip_tags and Str::limit. Consider a dedicated excerpt field for better control. --}}
                                    </div>
                                    <a href="{{ route('posts.show', $post->slug) }}" class="inline-block mt-4 text-blue-500 hover:text-blue-700">Read more &rarr;</a>
                                </article>
                            @endforeach
                        </div>

                        <div class="mt-8">
                            {{ $posts->links() }} {{-- Pagination links --}}
                        </div>
                    @else
                        <p>No posts found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
