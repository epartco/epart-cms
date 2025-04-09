@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 sm:px-8">
        <div class="py-8">
            <div>
                <h2 class="text-2xl font-semibold leading-tight">Comments</h2>
            </div>
            <div class="my-2 flex sm:flex-row flex-col">
                {{-- Add search/filter elements here later (e.g., filter by status, article) --}}
            </div>
            <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
                <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Comment
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Author
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    In Response To
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Status
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Submitted On
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th> {{-- Actions column --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($comments as $comment)
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ Str::limit($comment->content, 100) }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $comment->user?->name ?? 'N/A' }}</p>
                                    <p class="text-gray-600 whitespace-no-wrap text-xs">{{ $comment->user?->email }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{-- Link to the article view page (front-end) if available, or admin edit page --}}
                                    <a href="{{ route('admin.articles.edit', $comment->article->id) }}" target="_blank" class="text-blue-600 hover:underline">
                                        {{ $comment->article?->title ?? 'N/A' }}
                                    </a>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                     <span class="relative inline-block px-3 py-1 font-semibold leading-tight
                                        @switch($comment->status)
                                            @case('approved') text-green-900 @break
                                            @case('pending') text-yellow-900 @break
                                            @case('spam') text-red-900 @break
                                            @default text-gray-900
                                        @endswitch">
                                        <span aria-hidden class="absolute inset-0 opacity-50 rounded-full
                                            @switch($comment->status)
                                                @case('approved') bg-green-200 @break
                                                @case('pending') bg-yellow-200 @break
                                                @case('spam') bg-red-200 @break
                                                @default bg-gray-200
                                            @endswitch"></span>
                                        <span class="relative">{{ ucfirst($comment->status) }}</span>
                                    </span>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $comment->created_at->format('Y-m-d H:i') }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right whitespace-no-wrap">
                                    {{-- Status Change Actions --}}
                                    @if($comment->status != 'approved')
                                    <form action="{{ route('admin.comments.update', $comment->id) }}" method="POST" class="inline-block mr-1">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="text-xs text-green-600 hover:text-green-900">Approve</button>
                                    </form>
                                    @endif
                                     @if($comment->status != 'pending')
                                    <form action="{{ route('admin.comments.update', $comment->id) }}" method="POST" class="inline-block mr-1">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="pending">
                                        <button type="submit" class="text-xs text-yellow-600 hover:text-yellow-900">Pend</button>
                                    </form>
                                     @endif
                                     @if($comment->status != 'spam')
                                    <form action="{{ route('admin.comments.update', $comment->id) }}" method="POST" class="inline-block mr-1">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="spam">
                                        <button type="submit" class="text-xs text-orange-600 hover:text-orange-900">Spam</button>
                                    </form>
                                     @endif
                                    {{-- Delete Action --}}
                                    <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                        No comments found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                     <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
                         {{ $comments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
