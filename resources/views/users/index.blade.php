@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h2 class="display-sm fw-900 mb-1">Manajemen Pengguna</h2>
        <p class="text-muted mb-0">Kelola akun Admin, Teknisi IT, dan Pelapor dalam sistem.</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-person-plus me-2"></i> Tambah Pengguna
        </button>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nama atau email..." value="{{ request('search') }}">
                </div>
            </div>
            
            <div class="col-md-4">
                <select name="role" class="form-select">
                    <option value="all">Semua Role</option>
                    <option value="superadmin" {{ request('role') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="teknisi" {{ request('role') == 'teknisi' ? 'selected' : '' }}>Teknisi</option>
                    <option value="pelapor" {{ request('role') == 'pelapor' ? 'selected' : '' }}>Pelapor</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Pengguna</th>
                        <th>Role</th>
                        <th>Departemen / NIM</th>
                        <th>Kontak</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold flex-shrink-0" 
                                     style="width: 40px; height: 40px; background-color: {{ $user->avatar_color }}; color: {{ $user->avatar_text_color }};">
                                    {{ $user->avatar }}
                                </div>
                                <div>
                                    <div class="fw-600" style="color: var(--ink);">{{ $user->name }}</div>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background-color: {{ $user->avatar_color }}; color: {{ $user->avatar_text_color }};">{{ $user->role_label }}</span>
                        </td>
                        <td>
                            <div class="fw-600">{{ $user->department->name ?? '-' }}</div>
                            <small class="text-muted">{{ $user->nim_nip ?? '-' }}</small>
                        </td>
                        <td>
                            <small>{{ $user->phone ?? '-' }}</small>
                        </td>
                        <td>
                            @if($user->is_active)
                                <span class="badge bg-success rounded-pill px-3">Aktif</span>
                            @else
                                <span class="badge bg-danger rounded-pill px-3">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-secondary me-1" style="padding: 4px 12px; font-size: 12px;" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">Edit</button>
                            @if(!$user->isSuperAdmin())
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini? Semua tiket terkait mungkin akan terpengaruh.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" style="padding: 4px 12px; font-size: 12px;">Hapus</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    
                    <!-- Edit Modal -->
                    <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content" style="border-radius: var(--rounded-xl);">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-900">Edit Pengguna</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-600">Nama Lengkap <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-600">Email <span class="text-danger">*</span></label>
                                                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-600">Role <span class="text-danger">*</span></label>
                                                <select name="role" class="form-select" required>
                                                    <option value="superadmin" {{ $user->role == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                                    <option value="teknisi" {{ $user->role == 'teknisi' ? 'selected' : '' }}>Teknisi IT</option>
                                                    <option value="pelapor" {{ $user->role == 'pelapor' ? 'selected' : '' }}>Pelapor</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-600">Departemen</label>
                                                <select name="department_id" class="form-select">
                                                    <option value="">-- Pilih Departemen --</option>
                                                    @foreach($departments as $dept)
                                                        <option value="{{ $dept->id }}" {{ $user->department_id == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-600">NIM / NIP</label>
                                                <input type="text" name="nim_nip" class="form-control" value="{{ $user->nim_nip }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-600">No. Telepon</label>
                                                <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                                            </div>
                                            <div class="col-12 mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="active{{ $user->id }}" {{ $user->is_active ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="active{{ $user->id }}">Akun Aktif</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <hr>
                                                <p class="text-muted small">Kosongkan kolom password jika tidak ingin mengubah password.</p>
                                            </div>
                                            <div class="col-md-6 mb-4">
                                                <label class="form-label fw-600">Password Baru</label>
                                                <input type="password" name="password" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-4">
                                                <label class="form-label fw-600">Konfirmasi Password Baru</label>
                                                <input type="password" name="password_confirmation" class="form-control">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            Tidak ada pengguna yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: var(--rounded-xl);">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-900">Tambah Pengguna Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-600">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-600">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-600">Role <span class="text-danger">*</span></label>
                            <select name="role" class="form-select" required>
                                <option value="pelapor">Pelapor</option>
                                <option value="teknisi">Teknisi IT</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-600">Departemen</label>
                            <select name="department_id" class="form-select">
                                <option value="">-- Pilih Departemen --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-600">NIM / NIP</label>
                            <input type="text" name="nim_nip" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-600">No. Telepon</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-600">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-600">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan Pengguna</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
