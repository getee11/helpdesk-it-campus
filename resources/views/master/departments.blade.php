@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h2 class="display-sm fw-900 mb-1">Master Departemen</h2>
        <p class="text-muted mb-0">Kelola departemen/prodi asal pengguna.</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDeptModal">
            <i class="bi bi-plus-lg me-2"></i> Tambah Departemen
        </button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama Departemen / Prodi</th>
                        <th>Total Pengguna</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $dept)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $dept->code ?? '-' }}</span></td>
                        <td class="fw-600" style="color: var(--ink);">{{ $dept->name }}</td>
                        <td><span class="badge bg-secondary rounded-pill">{{ $dept->users_count }}</span></td>
                        <td>
                            <button class="btn btn-sm btn-secondary me-1" style="padding: 4px 12px; font-size: 12px;" data-bs-toggle="modal" data-bs-target="#editDeptModal{{ $dept->id }}">Edit</button>
                            <form action="{{ route('admin.departments.destroy', $dept->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus departemen ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" style="padding: 4px 12px; font-size: 12px;" {{ $dept->users_count > 0 ? 'disabled' : '' }}>Hapus</button>
                            </form>
                        </td>
                    </tr>
                    
                    <!-- Edit Modal -->
                    <div class="modal fade" id="editDeptModal{{ $dept->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content" style="border-radius: var(--rounded-xl);">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-900">Edit Departemen</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('admin.departments.update', $dept->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label class="form-label fw-600">Kode (Opsional)</label>
                                            <input type="text" name="code" class="form-control" value="{{ $dept->code }}">
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label fw-600">Nama Departemen</label>
                                            <input type="text" name="name" class="form-control" value="{{ $dept->name }}" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada departemen yang ditambahkan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addDeptModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: var(--rounded-xl);">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-900">Tambah Departemen Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.departments.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-600">Kode (Opsional)</label>
                        <input type="text" name="code" class="form-control">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-600">Nama Departemen</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan Departemen</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
