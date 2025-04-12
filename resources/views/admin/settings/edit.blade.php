@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-semibold mb-4">Site Settings</h1>

    {{-- Session Status --}}
    @if (session('status') === 'settings-updated')
        <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-400 rounded">
            Settings updated successfully.
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-400 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')

        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-xl font-semibold mb-4">General Information</h2>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="site_name">
                    Site Name <span class="text-red-500">*</span>
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('site_name') border-red-500 @enderror" id="site_name" name="site_name" type="text" placeholder="Your Website Name" value="{{ old('site_name', $settings->site_name) }}" required>
                @error('site_name')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="site_description">
                    Site Description
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('site_description') border-red-500 @enderror" id="site_description" name="site_description" rows="3" placeholder="A short description of your website">{{ old('site_description', $settings->site_description) }}</textarea>
                @error('site_description')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-xl font-semibold mb-4">Default SEO Settings</h2>
             <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="default_meta_title">
                    Default Meta Title
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('default_meta_title') border-red-500 @enderror" id="default_meta_title" name="default_meta_title" type="text" placeholder="Default title for search engines" value="{{ old('default_meta_title', $settings->default_meta_title) }}">
                 @error('default_meta_title')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="default_meta_description">
                    Default Meta Description
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('default_meta_description') border-red-500 @enderror" id="default_meta_description" name="default_meta_description" rows="3" placeholder="Default description for search engines">{{ old('default_meta_description', $settings->default_meta_description) }}</textarea>
                 @error('default_meta_description')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="robots_txt">
                    Robots.txt Content
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline font-mono text-sm @error('robots_txt') border-red-500 @enderror" id="robots_txt" name="robots_txt" rows="10" placeholder="User-agent: *&#10;Allow: /">{{ old('robots_txt', $settings->robots_txt) }}</textarea>
                 <p class="text-gray-600 text-xs italic mt-1">Content for your robots.txt file. Be careful with syntax.</p>
                 @error('robots_txt')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-xl font-semibold mb-4">Tracking & Verification</h2>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="google_analytics_id">
                    Google Analytics Tracking ID
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('google_analytics_id') border-red-500 @enderror" id="google_analytics_id" name="google_analytics_id" type="text" placeholder="e.g., UA-XXXXX-Y or G-XXXXXXXXXX" value="{{ old('google_analytics_id', $settings->google_analytics_id) }}">
                 @error('google_analytics_id')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="google_site_verification">
                    Google Site Verification Code
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('google_site_verification') border-red-500 @enderror" id="google_site_verification" name="google_site_verification" type="text" placeholder="Paste the verification code here" value="{{ old('google_site_verification', $settings->google_site_verification) }}">
                 <p class="text-gray-600 text-xs italic mt-1">Used for Google Search Console verification.</p>
                 @error('google_site_verification')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex items-center justify-between">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection
