@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-semibold text-gray-700 mb-6">새 메뉴 추가</h1>

        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <form action="{{ route('admin.menus.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        메뉴 이름 <span class="text-red-500">*</span>
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" id="name" name="name" type="text" placeholder="예: 메인 네비게이션" value="{{ old('name') }}" required>
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description 필드 추가 --}}
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                        설명 (선택 사항)
                    </label>
                    <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror" id="description" name="description" rows="3" placeholder="메뉴에 대한 간단한 설명">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 메뉴 항목 추가/관리 기능은 추후 구현 --}}
                <div class="mb-6 p-4 bg-gray-100 rounded">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">메뉴 항목</h3>
                    <p class="text-gray-600 text-sm">메뉴 항목 추가 및 순서 변경 기능은 메뉴 생성 후 수정 화면에서 제공됩니다.</p>
                </div>


                <div class="flex items-center justify-between">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        메뉴 생성
                    </button>
                    <a href="{{ route('admin.menus.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        취소
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
