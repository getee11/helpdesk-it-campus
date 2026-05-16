@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-end">
    <div class="col-md-8">
        <h2 class="display-sm fw-900 mb-1">Daftar Tiket Helpdesk</h2>
        <p class="text-muted mb-0">Kelola dan pantau semua laporan kerusakan fasilitas IT.</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="{{ route('tickets.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i> Buat Tiket Baru
        </a>
    </div>
</div>

<!-- Tabs Status -->
<div class="card mb-4" style="border-radius: var(--rounded-md);">
    <div class="card-body p-0">
        <ul class="nav nav-tabs border-0 p-3 pb-0">
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'all' || !request('status') ? 'active border-bottom border-3 border-dark fw-bold text-dark' : 'text-muted border-0' }}" href="{{ route('tickets.index', array_merge(request()->query(), ['status' => 'all'])) }}">
                    All <span class="badge bg-secondary ms-1">{{ $counts['all'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'open' ? 'active border-bottom border-3 border-dark fw-bold text-dark' : 'text-muted border-0' }}" href="{{ route('tickets.index', array_merge(request()->query(), ['status' => 'open'])) }}">
                    Open <span class="badge badge-open ms-1">{{ $counts['open'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'progress' ? 'active border-bottom border-3 border-dark fw-bold text-dark' : 'text-muted border-0' }}" href="{{ route('tickets.index', array_merge(request()->query(), ['status' => 'progress'])) }}">
                    On Progress <span class="badge badge-progress ms-1">{{ $counts['progress'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'resolved' ? 'active border-bottom border-3 border-dark fw-bold text-dark' : 'text-muted border-0' }}" href="{{ route('tickets.index', array_merge(request()->query(), ['status' => 'resolved'])) }}">
                    Resolved <span class="badge badge-resolved ms-1">{{ $counts['resolved'] }}</span>
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <!-- Filter Bar -->
        <form action="{{ route('tickets.index') }}" method="GET" class="row g-3 mb-4">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari No. Tiket atau Subjek..." value="{{ request('search') }}">
                </div>
            </div>
            
            <div class="col-md-3">
                <select name="priority" class="form-select">
                    <option value="all">Semua Prioritas</option>
                    <option value="rendah" {{ request('priority') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                    <option value="sedang" {{ request('priority') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                    <option value="tinggi" {{ request('priority') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                    <option value="kritis" {{ request('priority') == 'kritis' ? 'selected' : '' }}>Kritis</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="all">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">Filter</button>
            </div>
        </form>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No. Tiket</th>
                        <th>Subject</th>
                        <th>Prioritas</th>
                        <th>Pelapor</th>
                        <th>Teknisi</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr>
                        <td><span class="fw-600">{{ $ticket->ticket_number }}</span></td>
                        <td>
                            <div class="fw-600" style="color: var(--ink);">{{ Str::limit($ticket->subject, 40) }}</div>
                            <small class="text-muted">{{ $ticket->category->name }} &bull; {{ $ticket->location }}</small>
                        </td>
                        <td>
                            <span class="badge {{ $ticket->priority_badge_class }}">{{ ucfirst($ticket->priority) }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold" 
                                     style="width: 24px; height: 24px; background-color: {{ $ticket->user->avatar_color }}; color: {{ $ticket->user->avatar_text_color }}; font-size: 10px;">
                                    {{ $ticket->user->avatar }}
                                </div>
                                <small class="fw-600">{{ $ticket->user->name }}</small>
                            </div>
                        </td>
                        <td>
                            @if($ticket->technicians->count() > 0)
                                <div class="d-flex">
                                    @foreach($ticket->technicians as $tech)
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-1 fw-bold shadow-sm" title="{{ $tech->name }}"
                                             style="width: 24px; height: 24px; background-color: {{ $tech->avatar_color }}; color: {{ $tech->avatar_text_color }}; font-size: 10px; border: 1px solid white;">
                                            {{ $tech->avatar }}
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted small fst-italic">Belum ditugaskan</span>
                            @endif
                        </td>
                        <td>
                            <small class="fw-600">{{ $ticket->created_at->format('d M Y') }}</small>
                            <br><small class="text-muted">{{ $ticket->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            <span class="badge {{ $ticket->status_badge_class }}">{{ $ticket->status_label }}</span>
                        </td>
                        <td>
                            <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-sm btn-secondary" style="padding: 4px 12px; font-size: 12px;">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            Tidak ada tiket yang ditemukan dengan filter saat ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $tickets->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
