<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-semibold mb-6 text-gray-800">Boards</h1>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($boards->count())
                        <ul class="space-y-4">
                            @foreach ($boards as $board)
                                <li class="border p-4 rounded-md hover:bg-gray-50 transition">
                                    <a href="{{ route('boards.show', $board->slug) }}" class="block">
                                        <h2 class="text-xl font-semibold text-blue-600 hover:underline">{{ $board->name }}</h2>
                                        @if($board->description)
                                            <p class="text-gray-600 mt-1">{{ $board->description }}</p>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No boards found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
