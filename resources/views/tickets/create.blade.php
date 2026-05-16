@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-secondary mb-3"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
        <h2 class="display-sm fw-900 mb-1">Buat Tiket Baru</h2>
        <p class="text-muted mb-0">Silakan isi formulir di bawah ini dengan detail kerusakan yang Anda temukan.</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('tickets.store') }}" method="POST" id="ticketForm">
                    @csrf
                    
                    <h5 class="fw-900 mb-4 pb-2 border-bottom">Informasi Pelapor</h5>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-600">Nama Lengkap</label>
                            <input type="text" class="form-control bg-light" value="{{ Auth::user()->name }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-600">Email</label>
                            <input type="email" class="form-control bg-light" value="{{ Auth::user()->email }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-600">NIM / NIP</label>
                            <input type="text" class="form-control bg-light" value="{{ Auth::user()->nim_nip }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-600">Departemen / Prodi</label>
                            <input type="text" class="form-control bg-light" value="{{ Auth::user()->department->name ?? '-' }}" readonly>
                        </div>
                    </div>
                    
                    <h5 class="fw-900 mb-4 pb-2 border-bottom">Detail Kerusakan</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label fw-600">Kategori Masalah <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">Pilih Kategori...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="priority" class="form-label fw-600">Tingkat Prioritas <span class="text-danger">*</span></label>
                            <select name="priority" id="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                <option value="rendah" {{ old('priority') == 'rendah' ? 'selected' : '' }}>Rendah (Tidak mengganggu kegiatan)</option>
                                <option value="sedang" {{ old('priority') == 'sedang' ? 'selected' : '' }}>Sedang (Sebagian kegiatan terganggu)</option>
                                <option value="tinggi" {{ old('priority') == 'tinggi' ? 'selected' : '' }}>Tinggi (Kegiatan utama terganggu)</option>
                                <option value="kritis" {{ old('priority') == 'kritis' ? 'selected' : '' }}>Kritis (Seluruh sistem lumpuh)</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="subject" class="form-label fw-600">Subjek Tiket <span class="text-danger">*</span></label>
                        <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" placeholder="Contoh: Proyektor di Kelas A tidak menyala" required>
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label fw-600">Deskripsi Lengkap <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" rows="5" class="form-control @error('description') is-invalid @enderror" placeholder="Jelaskan secara detail masalah yang terjadi, kapan mulai terjadi, dan tanda-tanda kerusakannya..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <h5 class="fw-900 mb-4 pb-2 border-bottom">Lokasi Kejadian</h5>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label fw-600">Gedung / Area <span class="text-danger">*</span></label>
                            <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}" placeholder="Contoh: Gedung Fakultas Teknik" required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="room" class="form-label fw-600">Ruangan (Opsional)</label>
                            <input type="text" name="room" id="room" class="form-control @error('room') is-invalid @enderror" value="{{ old('room') }}" placeholder="Contoh: Lab Komputer 2">
                            @error('room')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                        <button type="reset" class="btn btn-secondary me-2">Batal</button>
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-send me-1"></i> Kirim Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card card-feature-sage">
            <div class="card-body">
                <h5 class="fw-900 mb-3"><i class="bi bi-info-circle text-primary me-2"></i> Panduan Pengisian</h5>
                <ul class="text-muted" style="font-size: 14px; padding-left: 20px;">
                    <li class="mb-2"><strong>Kategori:</strong> Pilih kategori yang paling sesuai agar tiket langsung diteruskan ke teknisi yang tepat.</li>
                    <li class="mb-2"><strong>Prioritas:</strong> Gunakan prioritas Kritis hanya untuk masalah yang menghentikan seluruh layanan kampus (seperti server down).</li>
                    <li class="mb-2"><strong>Deskripsi:</strong> Semakin lengkap deskripsi Anda, semakin cepat teknisi dapat mendiagnosa masalah.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#ticketForm').on('submit', function(e) {
        let isValid = true;
        
        // Basic Client-Side Validation
        if(!$('#category_id').val()) {
            isValid = false;
            $('#category_id').addClass('is-invalid');
        } else {
            $('#category_id').removeClass('is-invalid');
        }
        
        if(!$('#subject').val() || $('#subject').val().length < 5) {
            isValid = false;
            $('#subject').addClass('is-invalid');
        } else {
            $('#subject').removeClass('is-invalid');
        }
        
        if(!isValid) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang diwajibkan dengan benar.');
        }
    });
});
</script>
@endpush
