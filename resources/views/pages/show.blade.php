<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-semibold mb-4">{{ $page->title }}</h1>
                    <div class="prose max-w-none">
                        {{-- Ensure content is displayed safely. Use {!! $page->content !!} if content contains HTML and is trusted. --}}
                        {{-- For basic text content: --}}
                         {{-- nl2br(e($page->content)) --}}

                         {{-- If using a WYSIWYG editor that outputs HTML: --}}
                         {!! $page->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
