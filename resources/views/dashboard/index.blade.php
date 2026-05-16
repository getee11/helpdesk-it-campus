@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="display-sm fw-900 mb-1">Dashboard</h2>
        <p class="text-muted">Selamat datang kembali, {{ Auth::user()->name }}. Ini adalah ringkasan sistem helpdesk Anda.</p>
    </div>
</div>

<!-- Stats Row -->
<div class="row mb-4">
    @foreach($stats as $stat)
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card h-100 {{ isset($stat['accent']) && $stat['accent'] ? 'card-feature-sage' : '' }}">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                     style="width: 48px; height: 48px; background-color: {{ isset($stat['color']) ? $stat['color'].'20' : 'var(--bs-primary-pale)' }}; color: {{ $stat['color'] ?? 'var(--ink)' }}; font-size: 24px;">
                    <i class="bi {{ $stat['icon'] }}"></i>
                </div>
                <div>
                    <h3 class="display-md fw-900 mb-0" style="color: var(--ink);">{{ $stat['num'] }}</h3>
                    <div class="text-muted fw-600" style="font-size: 14px;">{{ $stat['label'] }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row">
    <!-- Recent Tickets -->
    <div class="col-md-8 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="display-xs fw-900 mb-0">Tiket Terbaru</h4>
                    <a href="{{ route('tickets.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>No. Tiket</th>
                                <th>Subjek</th>
                                <th>Kategori</th>
                                <th>Prioritas</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTickets as $ticket)
                            <tr>
                                <td><span class="fw-600">{{ $ticket->ticket_number }}</span></td>
                                <td>
                                    <div class="fw-600" style="color: var(--ink);">{{ Str::limit($ticket->subject, 30) }}</div>
                                    <small class="text-muted">{{ $ticket->created_at->diffForHumans() }}</small>
                                </td>
                                <td>{{ $ticket->category->name }}</td>
                                <td>
                                    <span class="badge {{ $ticket->priority_badge_class }}">{{ ucfirst($ticket->priority) }}</span>
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
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada tiket yang dibuat.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right Sidebar -->
    <div class="col-md-4 mb-4">
        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
        <!-- Status Distribution -->
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="display-xs fw-900 mb-4">Distribusi Status</h4>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-600" style="font-size: 14px;">Open</span>
                        <span class="fw-600" style="font-size: 14px;">{{ $statusDistribution['open']['count'] }}</span>
                    </div>
                    <div class="progress" style="height: 8px; border-radius: 4px;">
                        <div class="progress-bar" style="width: {{ $statusDistribution['open']['percent'] }}%; background-color: var(--bs-primary);"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-600" style="font-size: 14px;">On Progress</span>
                        <span class="fw-600" style="font-size: 14px;">{{ $statusDistribution['progress']['count'] }}</span>
                    </div>
                    <div class="progress" style="height: 8px; border-radius: 4px;">
                        <div class="progress-bar bg-warning" style="width: {{ $statusDistribution['progress']['percent'] }}%;"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-600" style="font-size: 14px;">Resolved</span>
                        <span class="fw-600" style="font-size: 14px;">{{ $statusDistribution['resolved']['count'] }}</span>
                    </div>
                    <div class="progress" style="height: 8px; border-radius: 4px;">
                        <div class="progress-bar bg-success" style="width: {{ $statusDistribution['resolved']['percent'] }}%;"></div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Quick Action -->
        <div class="card card-feature-sage border-0">
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center bg-white shadow-sm" style="width: 64px; height: 64px;">
                        <i class="bi bi-plus-lg fs-2" style="color: var(--bs-primary);"></i>
                    </div>
                </div>
                <h4 class="display-xs fw-900 mb-2">Buat Laporan Baru</h4>
                <p class="text-muted mb-4" style="font-size: 14px;">Ada kendala IT? Laporkan sekarang agar teknisi kami dapat segera menanganinya.</p>
                <a href="{{ route('tickets.create') }}" class="btn btn-primary w-100">Ajukan Tiket Baru</a>
            </div>
        </div>
    </div>
</div>
@endsection
