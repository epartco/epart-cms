<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Use site name from settings, fallback to config --}}
        <title>{{ app(App\Settings\GeneralSettings::class)->site_name ?? config('app.name', 'Laravel') }}</title>
        {{-- Add default meta description from settings --}}
        <meta name="description" content="{{ app(App\Settings\GeneralSettings::class)->default_meta_description ?? '' }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 flex flex-col">
            {{-- @include('layouts.navigation') --}}
            <nav class="bg-white shadow-md pt-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {{-- items-center 클래스 추가 --}}
                    <div class="flex justify-between h-16 items-center">
                        {{-- 로고와 메뉴를 묶는 div에도 items-center 추가 --}}
                        <div class="flex items-center">
                            <div class="shrink-0 flex items-center">
                                <a href="/">
                                    {{-- Placeholder for Logo - Use Site Name --}}
                                    <span class="font-semibold text-xl text-gray-800">{{ app(App\Settings\GeneralSettings::class)->site_name ?? config('app.name', 'Laravel') }}</span>
                                </a>
                            </div>
                            <!-- Dynamic Navigation Menu -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                {{-- MenuComposer에서 전달된 $mainMenuItems 사용 --}}
                                @isset($mainMenuItems)
                                    @foreach ($mainMenuItems as $menuItem)
                                        <div class="relative group"> {{-- 드롭다운을 위한 relative positioning 및 group 클래스 --}}
                                            <a href="{{ url($menuItem->url) }}" target="{{ $menuItem->target ?? '_self' }}"
                                               class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->is(ltrim($menuItem->url, '/')) ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out text-sm font-medium leading-5">
                                                <span>{{ $menuItem->title }}</span>
                                                {{-- 하위 항목이 있으면 드롭다운 아이콘 표시 --}}
                                                @if ($menuItem->children->isNotEmpty())
                                                    <svg class="ml-1 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                            </a>
                                            {{-- 하위 메뉴 드롭다운 --}}
                                            @if ($menuItem->children->isNotEmpty())
                                                <div class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-150 ease-in-out z-10">
                                                    <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                                        {{-- submenu partial 포함 --}}
                                                        @include('layouts.partials.submenu', ['items' => $menuItem->children])
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @endisset
                            </div>
                        </div>
                        {{-- Optional: Right side navigation (e.g., Login/Register, User menu) --}}
                        {{-- If using Breeze/Jetstream auth, include relevant components here --}}
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            @auth
                                <!-- Settings Dropdown -->
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                            <div>{{ Auth::user()->name }}</div>

                                            <div class="ml-1">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('profile.edit')">
                                            {{ __('Profile') }}
                                        </x-dropdown-link>

                                        @can('view_admin') {{-- Assuming 'view_admin' permission exists --}}
                                            <x-dropdown-link :href="route('admin.dashboard')">
                                                {{ __('Admin Dashboard') }}
                                            </x-dropdown-link>
                                        @endcan

                                        <!-- Authentication -->
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf

                                            <x-dropdown-link :href="route('logout')"
                                                    onclick="event.preventDefault();
                                                                this.closest('form').submit();">
                                                {{ __('Log Out') }}
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            @else
                                <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            {{-- <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif --}}

            <!-- Page Content -->
            <main class="flex-grow">
                {{ $slot }}
            </main>

            <footer class="bg-gray-800 text-white mt-auto">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-center text-sm">
                    &copy; {{ date('Y') }} {{ app(App\Settings\GeneralSettings::class)->site_name ?? config('app.name', 'Laravel') }}. All rights reserved.
                </div>
            </footer>
        </div>
    </body>
</html>
