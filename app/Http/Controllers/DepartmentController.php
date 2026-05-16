<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('users')->orderBy('name')->get();
        
        return view('master.departments', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'code' => 'nullable|string|max:20',
        ]);
        
        Department::create($validated);
        
        return back()->with('success', 'Departemen berhasil ditambahkan!');
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'code' => 'nullable|string|max:20',
        ]);
        
        $department->update($validated);
        
        return back()->with('success', 'Departemen berhasil diperbarui!');
    }

    public function destroy(Department $department)
    {
        if ($department->users()->count() > 0) {
            return back()->with('error', 'Departemen tidak dapat dihapus karena masih memiliki pengguna!');
        }
        
        $department->delete();
        
        return back()->with('success', 'Departemen berhasil dihapus!');
    }
}