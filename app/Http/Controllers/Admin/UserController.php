<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Import User model
use Illuminate\Http\Request;
// Removed redundant: use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role; // Import Role model

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->paginate(10); // Eager load roles and paginate
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::pluck('name', 'id'); // Get all roles (name as value, id as key)
        return view('admin.users.create', compact('roles'));
    }

    /**
use Illuminate\Support\Facades\Hash; // Import Hash facade
use Illuminate\Validation\Rules; // Import Rules

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,id'], // Ensure selected roles exist
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign roles using Spatie package method
        $user->syncRoles($request->input('roles'));

        return redirect()->route('admin.users.index')
                         ->with('success', 'User created successfully.'); // Add success message
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user) // Use route model binding
    {
        $roles = Role::pluck('name', 'id');
        $userRoles = $user->roles->pluck('id')->toArray(); // Get IDs of roles assigned to the user

        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
use Illuminate\Validation\Rule; // Import Rule for unique email check

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user) // Use route model binding
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // Ignore the current user's email when checking for uniqueness
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            // Password validation is optional, only if password field is filled
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        // Prepare data for update
        $data = $request->only('name', 'email');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Sync roles
        $user->syncRoles($request->input('roles'));

        return redirect()->route('admin.users.index')
                         ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user) // Use route model binding
    {
        // Optional: Add checks here to prevent deleting the currently logged-in user
        // or the last remaining admin user.
        // if (auth()->id() === $user->id) {
        //     return redirect()->route('admin.users.index')
        //                      ->with('error', 'You cannot delete your own account.');
        // }

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'User deleted successfully.');
    }
}
