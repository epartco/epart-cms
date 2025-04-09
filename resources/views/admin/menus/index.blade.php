@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-700">메뉴 관리</h1>
            <a href="{{ route('admin.menus.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                새 메뉴 추가
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white shadow-md rounded my-6">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs leading-4 font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs leading-4 font-semibold text-gray-600 uppercase tracking-wider">이름</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs leading-4 font-semibold text-gray-600 uppercase tracking-wider">위치</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs leading-4 font-semibold text-gray-600 uppercase tracking-wider">생성일</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-center text-xs leading-4 font-semibold text-gray-600 uppercase tracking-wider">작업</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse ($menus as $menu)
                    <tr>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $menu->id }}</td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $menu->name }}</td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $menu->location }}</td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $menu->created_at->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-center">
                            <a href="{{ route('admin.menus.edit', $menu->id) }}" class="text-indigo-600 hover:text-indigo-900 px-2">수정</a>
                            <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" class="inline-block" onsubmit="return confirm('정말 삭제하시겠습니까?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 px-2">삭제</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-center" colspan="5">
                                메뉴가 없습니다.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Links --}}
        <div class="mt-4">
            {{ $menus->links() }}
        </div>
    </div>
@endsection
