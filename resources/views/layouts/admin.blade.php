<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ app(App\Settings\GeneralSettings::class)->site_name ?? config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    {{-- Add admin-specific CSS here if needed --}}
    <style>
        /* Basic Admin Layout Styles */
        body { font-family: 'Figtree', sans-serif; }
        .admin-container { display: flex; min-height: 100vh; }
        .admin-sidebar { width: 250px; background-color: #f8f9fa; border-right: 1px solid #dee2e6; padding: 1rem; }
        .admin-content { flex-grow: 1; padding: 2rem; background-color: #ffffff; }
        .admin-header { background-color: #e9ecef; padding: 1rem; margin-bottom: 1rem; border-radius: 0.25rem; }
        /* Add more specific styles as needed */
    </style>
</head>
<body class="font-sans antialiased">
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <h1 class="text-xl font-semibold mb-4">{{ app(App\Settings\GeneralSettings::class)->site_name ?? config('app.name', 'Laravel') }} Admin</h1>
            <nav>
                <ul>
                    <li class="mb-2"><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a></li>
                    {{-- Add more sidebar links here --}}
                    <li class="mb-2"><a href="{{ route('admin.pages.index') }}" class="{{ request()->routeIs('admin.pages.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }} hover:underline">Pages</a></li>
                    <li class="mb-2"><a href="{{ route('admin.posts.index') }}" class="{{ request()->routeIs('admin.posts.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }} hover:underline">Posts</a></li>
                    <li class="mb-2"><a href="{{ route('admin.boards.index') }}" class="{{ request()->routeIs('admin.boards.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }} hover:underline">Boards</a></li>
                    <li class="mb-2"><a href="{{ route('admin.articles.index') }}" class="{{ request()->routeIs('admin.articles.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }} hover:underline">Articles</a></li>
                    <li class="mb-2"><a href="{{ route('admin.comments.index') }}" class="{{ request()->routeIs('admin.comments.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }} hover:underline">Comments</a></li>
                    <li class="mb-2"><a href="{{ route('admin.menus.index') }}" class="{{ request()->routeIs('admin.menus.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }} hover:underline">Menus</a></li> {{-- Menu Link Added --}}
                    <li class="mb-2"><a href="{{ route('admin.media.index') }}" class="{{ request()->routeIs('admin.media.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }} hover:underline">Media Library</a></li>
                    <li class="mb-2"><a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }} hover:underline">Users</a></li>
                    <li class="mb-2"><a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('admin.settings.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }} hover:underline">Settings</a></li>
                </ul>
            </nav>
            <div class="mt-auto pt-4 border-t">
                 <!-- Authentication -->
                 <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="text-red-600 hover:underline">
                        {{ __('Log Out') }}
                    </a>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-content">
            <div class="admin-header">
                {{-- Header content can go here, e.g., user menu --}}
                Logged in as: {{ Auth::user()->name }}
            </div>

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                 <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </main>
    </div>

    {{-- Include the Media Library Modal --}}
    <x-admin.media-library-modal />

</body>
</html>
