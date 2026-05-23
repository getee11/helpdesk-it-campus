<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $stats = $this->getStatsForRole($user);
        $recentTickets = $this->getRecentTicketsForRole($user);
        $statusDistribution = $this->getStatusDistribution();
        $technicians = User::where('role', 'teknisi')->where('is_active', true)->get();
        
        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentTickets' => $recentTickets,
            'statusDistribution' => $statusDistribution,
            'technicians' => $technicians
        ]);
    }

    private function getStatsForRole($user): array
    {
        $stats = [];
        
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            $stats = [
                ['num' => Ticket::count(), 'label' => 'Total Tiket', 'icon' => 'bi-ticket', 'accent' => true],
                ['num' => Ticket::where('status', 'open')->count(), 'label' => 'Tiket Open', 'icon' => 'bi-circle-fill', 'color' => '#3b82f6'],
                ['num' => Ticket::where('status', 'progress')->count(), 'label' => 'On Progress', 'icon' => 'bi-hourglass-split', 'color' => '#f59e0b'],
                ['num' => User::where('role', 'teknisi')->where('is_active', true)->count(), 'label' => 'Teknisi Aktif', 'icon' => 'bi-person-gear', 'color' => '#a855f7'],
            ];
        } elseif ($user->isTeknisi()) {
            $stats = [
                ['num' => Ticket::whereHas('technicians', function($q) use($user) { $q->where('users.id', $user->id); })->whereIn('status', ['open', 'progress'])->count(), 'label' => 'Tugas Aktif', 'icon' => 'bi-hourglass-split', 'accent' => true],
                ['num' => Ticket::whereHas('technicians', function($q) use($user) { $q->where('users.id', $user->id); })->where('status', 'resolved')->whereMonth('resolved_at', now()->month)->count(), 'label' => 'Selesai Bulan Ini', 'icon' => 'bi-check-circle', 'color' => '#2ead4b'],
                ['num' => Ticket::whereHas('technicians', function($q) use($user) { $q->where('users.id', $user->id); })->where('priority', 'kritis')->whereIn('status', ['open', 'progress'])->count(), 'label' => 'Kritis', 'icon' => 'bi-exclamation-triangle', 'color' => '#d03238'],
                ['num' => '4.8', 'label' => 'Rating', 'icon' => 'bi-star', 'color' => '#ffd11a'],
            ];
        } else {
            $stats = [
                ['num' => Ticket::where('user_id', $user->id)->count(), 'label' => 'Total Laporan', 'icon' => 'bi-ticket', 'accent' => true],
                ['num' => Ticket::where('user_id', $user->id)->where('status', 'open')->count(), 'label' => 'Open', 'icon' => 'bi-circle-fill', 'color' => '#3b82f6'],
                ['num' => Ticket::where('user_id', $user->id)->where('status', 'progress')->count(), 'label' => 'On Progress', 'icon' => 'bi-hourglass-split', 'color' => '#f59e0b'],
                ['num' => Ticket::where('user_id', $user->id)->where('status', 'resolved')->count(), 'label' => 'Selesai', 'icon' => 'bi-check-circle', 'color' => '#2ead4b'],
            ];
        }
        
        return $stats;
    }

    private function getRecentTicketsForRole($user)
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return Ticket::with(['user', 'category', 'technicians'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        } elseif ($user->isTeknisi()) {
            return Ticket::with(['user', 'category', 'technicians'])
                ->whereHas('technicians', function($q) use($user) { $q->where('users.id', $user->id); })
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        } else {
            return Ticket::with(['category', 'technicians'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }
    }

    private function getStatusDistribution(): array
    {
        $total = Ticket::count();
        if ($total == 0) {
            return [
                'open' => ['count' => 0, 'percent' => 0],
                'progress' => ['count' => 0, 'percent' => 0],
                'resolved' => ['count' => 0, 'percent' => 0],
            ];
        }

        $open = Ticket::where('status', 'open')->count();
        $progress = Ticket::where('status', 'progress')->count();
        $resolved = Ticket::where('status', 'resolved')->count();

        return [
            'open' => ['count' => $open, 'percent' => round($open / $total * 100)],
            'progress' => ['count' => $progress, 'percent' => round($progress / $total * 100)],
            'resolved' => ['count' => $resolved, 'percent' => round($resolved / $total * 100)],
        ];
    }
}