<x-app-layout>
    <div class="container mx-auto px-4 py-8">
    {{-- Article Details --}}
    <article class="bg-white shadow-md rounded-lg p-6 mb-8">
        <h1 class="text-3xl font-bold mb-4">{{ $article->title }}</h1>
        <div class="text-sm text-gray-600 mb-4">
            <span>작성자: {{ $article->user->name }}</span> |
            <span>작성일: {{ $article->created_at->format('Y-m-d H:i') }}</span>
            {{-- Display board name if needed --}}
            {{-- | <span>게시판: {{ $article->board->name }}</span> --}}
        </div>
        <div class="prose max-w-none">
            {!! $article->content !!} {{-- Assuming content might contain HTML --}}
        </div>

        {{-- Back to List Link --}}
        <div class="mt-6">
            <a href="{{ route('boards.show', $article->board->slug) }}" class="text-blue-600 hover:underline">&larr; 목록으로 돌아가기</a>
        </div>
    </article>

    {{-- Comments Section --}}
    <section class="bg-white shadow-md rounded-lg p-6 mt-8"> {{-- Added mt-8 for spacing --}}
        <h2 class="text-2xl font-semibold mb-6">댓글 ({{ $article->comments->count() }})</h2>

        {{-- Display Comments --}}
        <div class="space-y-4 mb-6">
            @forelse ($article->comments as $comment)
                <div class="border-b pb-4">
                    <p class="font-semibold">{{ $comment->user ? $comment->user->name : $comment->guest_name ?? '익명' }}</p>
                    <p class="text-xs text-gray-500 mb-1">{{ $comment->created_at->diffForHumans() }}</p>
                    <p class="text-gray-700">{{ $comment->content }}</p>
                </div>
            @empty
                <p class="text-gray-500">아직 댓글이 없습니다.</p>
            @endforelse
        </div>

        {{-- Comment Form --}}
        <div class="mt-8 border-t pt-6">
            <h3 class="text-xl font-semibold mb-4">댓글 남기기</h3>

            {{-- Session Status Messages --}}
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mb-4 rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">오류가 발생했습니다.</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul role="list" class="list-disc space-y-1 pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('comments.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="article_id" value="{{ $article->id }}">

                @guest {{-- Show fields for non-logged-in users --}}
                <div>
                    <label for="guest_name" class="block text-sm font-medium text-gray-700">이름</label>
                    <input type="text" name="guest_name" id="guest_name" value="{{ old('guest_name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('guest_name') border-red-500 @enderror">
                    @error('guest_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="guest_password" class="block text-sm font-medium text-gray-700">비밀번호 (댓글 수정/삭제 시 필요)</label>
                    <input type="password" name="guest_password" id="guest_password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('guest_password') border-red-500 @enderror">
                     @error('guest_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @else {{-- Logged-in user doesn't need name/password --}}
                 <p class="text-sm text-gray-600">로그인 상태: {{ Auth::user()->name }}</p>
                @endguest

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700">댓글 내용</label>
                    <textarea name="content" id="content" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
                     @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition">
                        댓글 등록 (승인 후 표시)
                    </button>
                </div>
            </form>
        </div>
    </section>
    </div>
</x-app-layout>
