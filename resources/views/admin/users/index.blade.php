@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 sm:px-8">
        <div class="py-8">
            {{-- Title and Create Button Container --}}
            <div class="flex justify-between items-center mb-4 px-4 py-3"> {{-- Removed bg-gray-100, border-b, rounded-t-lg --}}
                <h2 class="text-2xl font-semibold leading-tight text-gray-700">Users</h2>
                <a href="{{ route('admin.users.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Create User
                </a>
            </div>

            {{-- Search/Filter Area (Optional - kept separate for clarity) --}}
            <div class="my-2 flex sm:flex-row flex-col">
                {{-- Add search/filter elements here later --}}
                <div class="block relative">
                    {{-- Example Search Input --}}
                    {{-- <span class="h-full absolute inset-y-0 left-0 flex items-center pl-2">
                        <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current text-gray-500">
                            <path
                                d="M10 4a6 6 0 100 12 6 6 0 000-12zm-8 6a8 8 0 1114.32 4.906l5.387 5.387a1 1 0 01-1.414 1.414l-5.387-5.387A8 8 0 012 10z">
                            </path>
                        </svg>
                    </span>
                    <input placeholder="Search"
                           class="appearance-none rounded-r rounded-l sm:rounded-l-none border border-gray-400 border-b block pl-8 pr-6 py-2 w-full bg-white text-sm placeholder-gray-400 text-gray-700 focus:bg-white focus:placeholder-gray-600 focus:text-gray-700 focus:outline-none" /> --}}
                </div>
                {{-- Button moved to the title container --}}
            </div>

            {{-- Table Container --}}
            <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
                {{-- Apply rounded-t-lg and rounded-b-lg --}}
                <div class="w-full shadow rounded-t-lg rounded-b-lg overflow-hidden"> {{-- Replaced inline-block min-w-full with w-full --}}
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr> {{-- Removed bg-gray-100 from the row --}}
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"> {{-- Added bg-gray-100 --}}
                                    Name
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"> {{-- Added bg-gray-100 --}}
                                    Email
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"> {{-- Added bg-gray-100 --}}
                                    Roles
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"> {{-- Added bg-gray-100 --}}
                                    Created At
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th> {{-- Added bg-gray-100, Actions column --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $user->name }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $user->email }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    @foreach ($user->roles as $role)
                                        <span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight mr-1">
                                            <span aria-hidden class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                            <span class="relative">{{ $role->name }}</span>
                                        </span>
                                    @endforeach
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $user->created_at->format('Y-m-d') }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    {{-- Delete button requires form and potentially confirmation --}}
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-5 border-gray-200 bg-white text-sm text-center">
                                        No users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="px-5 py-5 bg-white flex flex-col xs:flex-row items-center xs:justify-between">
                         {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
