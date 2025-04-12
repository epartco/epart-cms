<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-semibold text-gray-800">{{ $board->name }}</h1>
                {{-- Add "Write Article" button later if needed --}}
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($articles->count())
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Title
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Author
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    {{-- Add views/comments count later if needed --}}
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($articles as $article)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{-- Link to article show page --}}
                                            <a href="{{ route('articles.show', $article) }}" class="text-sm font-medium text-blue-600 hover:underline">
                                                {{ $article->title }}
                                            </a>
                                            @if($article->comments_count > 0) {{-- Assuming comments_count exists --}}
                                                <span class="text-xs text-gray-500 ml-1">[{{ $article->comments_count }}]</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $article->user->name ?? 'N/A' }} {{-- Assuming user relationship exists --}}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $article->created_at->format('Y-m-d') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-8">
                            {{ $articles->links() }} {{-- Pagination links --}}
                        </div>
                    @else
                        <p>No articles found in this board.</p>
                    @endif

                    <div class="mt-8">
                        <a href="{{ route('boards.index') }}" class="text-blue-500 hover:text-blue-700">&larr; Back to Boards</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
