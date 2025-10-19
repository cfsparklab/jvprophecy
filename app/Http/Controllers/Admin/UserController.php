<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Handle per_page parameter with allowed values
        $perPage = $request->get('per_page', 20);
        $allowedPerPage = [20, 30, 40, 50];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 20; // Default to 20 if invalid value
        }

        $users = $query->paginate($perPage)->withQueryString();
        $roles = Role::active()->orderBy('name')->get();

        // Calculate statistics
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $adminUsers = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->count();
        $regularUsers = $totalUsers - $adminUsers;
        $recentUsers = User::where('created_at', '>=', now()->startOfMonth())->count();

        return view('admin.users.index', compact('users', 'roles', 'totalUsers', 'activeUsers', 'adminUsers', 'regularUsers', 'recentUsers', 'perPage'));
    }

    public function create()
    {
        $roles = Role::active()->orderBy('level')->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|string|max:15|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'preferred_language' => 'required|string|in:en,ta,kn,te,ml,hi',
            'status' => 'required|in:active,inactive,suspended',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'preferred_language' => $request->preferred_language,
            'status' => $request->status,
            'email_verified_at' => now(),
        ]);

        $user->roles()->sync($request->roles);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load(['roles', 'prophecies']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::active()->orderBy('level')->get();
        $user->load('roles');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'mobile' => ['required', 'string', 'max:15', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'preferred_language' => 'required|string|in:en,ta,kn,te,ml,hi',
            'status' => 'required|in:active,inactive,suspended',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'preferred_language' => $request->preferred_language,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);
        $user->roles()->sync($request->roles);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Prevent deletion of current user
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Check if user has prophecies
        if ($user->prophecies()->count() > 0) {
            return back()->with('error', 'Cannot delete user with existing prophecies.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);

        return back()->with('success', "User status updated to {$newStatus}.");
    }
}
