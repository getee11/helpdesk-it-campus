<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('department');
        
        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $users = $query->orderBy('name')->paginate(15);
        $departments = Department::all();
        
        return view('users.index', compact('users', 'departments'));
    }

    public function create()
    {
        $departments = Department::all();
        
        return view('users.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', Password::defaults(), 'confirmed'],
            'role' => 'required|in:superadmin,admin,teknisi,pelapor',
            'department_id' => 'nullable|exists:departments,id',
            'nim_nip' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
        ]);
        
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'department_id' => $validated['department_id'] ?? null,
            'nim_nip' => $validated['nim_nip'] ?? null,
            'phone' => $validated['phone'] ?? null,
        ]);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        $departments = Department::all();
        
        return view('users.edit', compact('user', 'departments'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:superadmin,admin,teknisi,pelapor',
            'department_id' => 'nullable|exists:departments,id',
            'nim_nip' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ];
        
        if ($request->filled('password')) {
            $rules['password'] = ['required', Password::defaults(), 'confirmed'];
        }
        
        $validated = $request->validate($rules);
        
        $updateData = collect($validated)->except('password')->toArray();
        
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }
        
        $user->update($updateData);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Tidak dapat menghapus Super Admin!');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus!');
    }

    public function toggleStatus(User $user)
    {
        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Tidak dapat menonaktifkan Super Admin!');
        }
        
        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return back()->with('success', "Pengguna berhasil {$status}!");
    }
}