@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 sm:px-8">
        <div class="py-8">
            <div>
                <h2 class="text-2xl font-semibold leading-tight">Create New Board</h2>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                {{-- Display validation errors --}}
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Oops!</strong>
                        <span class="block sm:inline">There were some problems with your input.</span>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.boards.store') }}" method="POST">
                    @csrf
                    <div class="shadow sm:rounded-md sm:overflow-hidden">
                        <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                            {{-- Name --}}
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Board Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                             {{-- Slug (Consider auto-generation from name using JS or server-side) --}}
                            <div>
                                <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                                <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <p class="mt-2 text-sm text-gray-500">Unique identifier for the board URL (e.g., 'notice', 'free-board'). Use lowercase letters, numbers, and hyphens.</p>
                            </div>

                            {{-- Description --}}
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="3"
                                          class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('description') }}</textarea>
                            </div>

                             {{-- Read Permission --}}
                            <div>
                                <label for="read_permission" class="block text-sm font-medium text-gray-700">Read Permission</label>
                                <select name="read_permission" id="read_permission"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="all" {{ old('read_permission', 'all') == 'all' ? 'selected' : '' }}>All Users</option>
                                    <option value="authenticated" {{ old('read_permission') == 'authenticated' ? 'selected' : '' }}>Logged-in Users</option>
                                    {{-- Add role-based options if needed, e.g., using Spatie roles --}}
                                    {{-- @foreach($roles as $role)
                                        <option value="role:{{ $role->name }}" {{ old('read_permission') == 'role:'.$role->name ? 'selected' : '' }}>Role: {{ $role->name }}</option>
                                    @endforeach --}}
                                </select>
                                <p class="mt-2 text-sm text-gray-500">Who can view articles in this board?</p>
                            </div>

                             {{-- Write Permission --}}
                            <div>
                                <label for="write_permission" class="block text-sm font-medium text-gray-700">Write Permission</label>
                                <select name="write_permission" id="write_permission"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="authenticated" {{ old('write_permission', 'authenticated') == 'authenticated' ? 'selected' : '' }}>Logged-in Users</option>
                                     <option value="role:editor" {{ old('write_permission') == 'role:editor' ? 'selected' : '' }}>Role: Editor</option>
                                     <option value="role:admin" {{ old('write_permission') == 'role:admin' ? 'selected' : '' }}>Role: Admin</option>
                                    {{-- Add more role-based options if needed --}}
                                </select>
                                 <p class="mt-2 text-sm text-gray-500">Who can create articles in this board?</p>
                            </div>

                        </div>
                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <a href="{{ route('admin.boards.index') }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Create Board
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
