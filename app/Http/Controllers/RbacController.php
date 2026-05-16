<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class RbacController extends Controller
{
    public function index()
    {
        $features = $this->getFeatures();
        $roles = ['admin', 'teknisi', 'pelapor'];
        
        $permissions = [];
        foreach ($roles as $role) {
            $permissions[$role] = Permission::where('role', $role)->get()->keyBy('feature');
        }
        
        return view('rbac.index', compact('features', 'roles', 'permissions'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,teknisi,pelapor',
            'feature' => 'required|string',
            'action' => 'required|in:can_create,can_read,can_update,can_delete',
            'value' => 'boolean',
        ]);
        
        $permission = Permission::updateOrCreate(
            [
                'role' => $validated['role'],
                'feature' => $validated['feature'],
            ],
            [
                $validated['action'] => $validated['value'],
            ]
        );
        
        return response()->json(['success' => true]);
    }

    private function getFeatures(): array
    {
        return [
            [
                'key' => 'dashboard',
                'name' => 'Dashboard Statistik',
                'desc' => 'Lihat ringkasan & grafik sistem',
                'lockForPelapor' => true,
            ],
            [
                'key' => 'tiket',
                'name' => 'Manajemen Tiket',
                'desc' => 'Buat, kelola, update tiket laporan',
                'lockForPelapor' => false,
            ],
            [
                'key' => 'kategori',
                'name' => 'Master Data — Kategori',
                'desc' => 'CRUD kategori kerusakan',
                'lockForPelapor' => true,
            ],
            [
                'key' => 'departemen',
                'name' => 'Master Data — Departemen',
                'desc' => 'CRUD departemen/prodi',
                'lockForPelapor' => true,
            ],
            [
                'key' => 'users',
                'name' => 'Manajemen Pengguna',
                'desc' => 'Kelola akun pengguna sistem',
                'lockForPelapor' => true,
            ],
            [
                'key' => 'rbac',
                'name' => 'Panel RBAC',
                'desc' => 'Atur hak akses role',
                'isSystemLock' => true,
            ],
            [
                'key' => 'riwayat',
                'name' => 'Riwayat Tiket Saya',
                'desc' => 'Lihat tiket pribadi pelapor',
                'lockForPelapor' => false,
            ],
            [
                'key' => 'laporan',
                'name' => 'Laporan & Ekspor',
                'desc' => 'Unduh laporan bulanan CSV/PDF',
                'lockForPelapor' => true,
            ],
        ];
    }
}