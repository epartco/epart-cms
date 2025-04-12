{{-- resources/views/layouts/partials/submenu.blade.php --}}
@foreach ($items as $item)
    <div class="relative group"> {{-- 하위 드롭다운을 위한 relative positioning 및 group 클래스 --}}
        <a href="{{ url($item->url) }}" target="{{ $item->target ?? '_self' }}"
           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 flex justify-between items-center" {{-- 드롭다운 항목 스타일 조정 --}}
           role="menuitem">
            <span>{{ $item->title }}</span>
            {{-- 하위 항목이 더 있으면 표시기 추가 --}}
            @if ($item->children->isNotEmpty())
                <svg class="ml-1 h-4 w-4 fill-current transform -rotate-90" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"> {{-- 오른쪽 화살표 --}}
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            @endif
        </a>
        {{-- 중첩된 드롭다운 메뉴 --}}
        @if ($item->children->isNotEmpty())
            <div class="absolute left-full top-0 mt-0 ml-1 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-150 ease-in-out z-20"> {{-- 중첩 드롭다운 스타일 --}}
                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                    {{-- 재귀적으로 partial 포함 --}}
                    @include('layouts.partials.submenu', ['items' => $item->children])
                </div>
            </div>
        @endif
    </div>
@endforeach
