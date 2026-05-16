@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-secondary mb-3"><i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Tiket</a>
        <h2 class="display-sm fw-900 mb-1">Tiket #{{ $ticket->ticket_number }}</h2>
        <div class="d-flex align-items-center mt-2">
            <span class="badge {{ $ticket->status_badge_class }} me-2 fs-6 py-2 px-3">{{ $ticket->status_label }}</span>
            <span class="badge {{ $ticket->priority_badge_class }} me-3 py-2 px-3">{{ ucfirst($ticket->priority) }} Priority</span>
            <span class="text-muted"><i class="bi bi-calendar3 me-1"></i> {{ $ticket->created_at->format('d M Y H:i') }}</span>
        </div>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        @if(Auth::user()->isTeknisi() && $ticket->status == 'open')
            <form action="{{ route('tickets.take', $ticket->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-primary shadow-sm"><i class="bi bi-person-check me-2"></i>Ambil Tugas</button>
            </form>
        @endif
        
        @if(Auth::user()->isTeknisi() && in_array($ticket->status, ['open', 'progress']) && $ticket->technicians->contains(Auth::id()))
            <form action="{{ route('tickets.resolve', $ticket->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success" style="border-radius: var(--rounded-xl); font-weight: 600;"><i class="bi bi-check2-circle me-2"></i>Tandai Selesai</button>
            </form>
        @endif
    </div>
</div>

<div class="row">
    <!-- Kolom Kiri: Detail Tiket -->
    <div class="col-lg-8 mb-4">
        <div class="card mb-4 h-100">
            <div class="card-body">
                <h3 class="fw-900 mb-4" style="color: var(--ink);">{{ $ticket->subject }}</h3>
                
                <div class="bg-light p-4 rounded-3 mb-4 text-dark" style="white-space: pre-line; border-left: 4px solid var(--bs-primary);">
                    {{ $ticket->description }}
                </div>
                
                <h5 class="fw-900 mb-3 border-bottom pb-2 mt-5">Komentar & Aktivitas</h5>
                
                <div class="comments-section mb-4">
                    @forelse($ticket->comments as $comment)
                        <div class="d-flex mb-4">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold flex-shrink-0" 
                                 style="width: 40px; height: 40px; background-color: {{ $comment->user->avatar_color }}; color: {{ $comment->user->avatar_text_color }};">
                                {{ $comment->user->avatar }}
                            </div>
                            <div class="w-100 bg-light p-3" style="border-radius: 0 var(--rounded-md) var(--rounded-md) var(--rounded-md);">
                                <div class="d-flex justify-content-between mb-2">
                                    <strong>{{ $comment->user->name }}</strong>
                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                                <div>{{ $comment->content }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">Belum ada komentar.</div>
                    @endforelse
                </div>
                
                <!-- Form Tambah Komentar -->
                @if($ticket->status !== 'resolved' && $ticket->status !== 'cancelled')
                <div class="mt-4 pt-3 border-top">
                    <form action="{{ route('tickets.comment', $ticket->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-600">Tambahkan Komentar</label>
                            <textarea name="content" class="form-control" rows="3" placeholder="Tulis update atau pertanyaan di sini..." required></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-secondary btn-sm px-4">Kirim Komentar</button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Kolom Kanan: Info Pelapor & Teknisi -->
    <div class="col-lg-4">
        <!-- Informasi Pelapor -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="fw-900 border-bottom pb-2 mb-3">Informasi Pelapor</h5>
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold" 
                         style="width: 48px; height: 48px; background-color: {{ $ticket->user->avatar_color }}; color: {{ $ticket->user->avatar_text_color }};">
                        {{ $ticket->user->avatar }}
                    </div>
                    <div>
                        <div class="fw-900">{{ $ticket->user->name }}</div>
                        <div class="text-muted small">{{ $ticket->user->role_label }}</div>
                    </div>
                </div>
                <div class="mb-2">
                    <small class="text-muted d-block">NIM/NIP</small>
                    <span class="fw-600">{{ $ticket->user->nim_nip }}</span>
                </div>
                <div class="mb-2">
                    <small class="text-muted d-block">Departemen</small>
                    <span class="fw-600">{{ $ticket->user->department->name ?? '-' }}</span>
                </div>
                <div class="mb-2">
                    <small class="text-muted d-block">Kontak</small>
                    <span class="fw-600">{{ $ticket->user->email }}<br>{{ $ticket->user->phone }}</span>
                </div>
            </div>
        </div>
        
        <!-- Informasi Lokasi -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="fw-900 border-bottom pb-2 mb-3">Lokasi</h5>
                <div class="mb-2">
                    <small class="text-muted d-block">Gedung / Area</small>
                    <span class="fw-600">{{ $ticket->location }}</span>
                </div>
                <div class="mb-2">
                    <small class="text-muted d-block">Ruangan</small>
                    <span class="fw-600">{{ $ticket->room ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Penugasan Teknisi -->
        <div class="card mb-4 card-feature-sage">
            <div class="card-body">
                <h5 class="fw-900 border-bottom border-secondary pb-2 mb-3">Penugasan Teknisi</h5>
                
                @if($ticket->technicians->count() > 0)
                    @foreach($ticket->technicians as $tech)
                    <div class="d-flex align-items-center mb-3 p-2 bg-white rounded-3 shadow-sm">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold" 
                             style="width: 40px; height: 40px; background-color: {{ $tech->avatar_color }}; color: {{ $tech->avatar_text_color }};">
                            {{ $tech->avatar }}
                        </div>
                        <div>
                            <div class="fw-900">{{ $tech->name }}</div>
                            <div class="text-muted small">Teknisi IT</div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center p-4">
                        <i class="bi bi-person-x fs-1 text-muted d-block mb-2"></i>
                        <span class="text-muted fw-600">Belum ada teknisi yang ditugaskan.</span>
                    </div>
                @endif
                
                @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                <div class="mt-3">
                    <button type="button" class="btn btn-secondary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#assignModal">
                        Kelola Teknisi
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
<!-- Assign Modal -->
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: var(--rounded-xl);">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-900">Tugaskan Teknisi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tickets.assign', $ticket->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-600">Pilih Teknisi</label>
                        <select name="technician_id" class="form-select" required>
                            <option value="">-- Pilih Teknisi --</option>
                            @php
                                $technicians = \App\Models\User::where('role', 'teknisi')->where('is_active', true)->get();
                            @endphp
                            @foreach($technicians as $tech)
                                <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Tugaskan & Update ke Progress</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
