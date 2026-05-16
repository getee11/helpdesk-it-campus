<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Ticket::with(['user', 'category', 'technicians']);
        
        // Filter by role
        if ($user->isTeknisi()) {
            $query->whereHas('technicians', function($q) use($user) { $q->where('users.id', $user->id); });
        } elseif ($user->isPelapor()) {
            $query->where('user_id', $user->id);
        }
        
        // Apply filters
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }
        
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category_id', $request->category);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('ticket_number', 'like', "%{$search}%");
            });
        }
        
        $tickets = $query->orderBy('created_at', 'desc')->paginate(15);
        $categories = Category::all();
        $technicians = User::where('role', 'teknisi')->where('is_active', true)->get();
        
        // Count by status
        $counts = [
            'all' => Ticket::when(!$user->isSuperAdmin() && !$user->isAdmin(), function($q) use ($user) {
                if ($user->isTeknisi()) {
                    return $q->whereHas('technicians', function($q2) use($user) { $q2->where('users.id', $user->id); });
                } elseif ($user->isPelapor()) {
                    return $q->where('user_id', $user->id);
                }
            })->count(),
            'open' => Ticket::where('status', 'open')->when(!$user->isSuperAdmin() && !$user->isAdmin(), function($q) use ($user) {
                if ($user->isTeknisi()) {
                    return $q->whereHas('technicians', function($q2) use($user) { $q2->where('users.id', $user->id); });
                } elseif ($user->isPelapor()) {
                    return $q->where('user_id', $user->id);
                }
            })->count(),
            'progress' => Ticket::where('status', 'progress')->when(!$user->isSuperAdmin() && !$user->isAdmin(), function($q) use ($user) {
                if ($user->isTeknisi()) {
                    return $q->whereHas('technicians', function($q2) use($user) { $q2->where('users.id', $user->id); });
                } elseif ($user->isPelapor()) {
                    return $q->where('user_id', $user->id);
                }
            })->count(),
            'resolved' => Ticket::where('status', 'resolved')->when(!$user->isSuperAdmin() && !$user->isAdmin(), function($q) use ($user) {
                if ($user->isTeknisi()) {
                    return $q->whereHas('technicians', function($q2) use($user) { $q2->where('users.id', $user->id); });
                } elseif ($user->isPelapor()) {
                    return $q->where('user_id', $user->id);
                }
            })->count(),
            'cancelled' => Ticket::where('status', 'cancelled')->when(!$user->isSuperAdmin() && !$user->isAdmin(), function($q) use ($user) {
                if ($user->isTeknisi()) {
                    return $q->whereHas('technicians', function($q2) use($user) { $q2->where('users.id', $user->id); });
                } elseif ($user->isPelapor()) {
                    return $q->where('user_id', $user->id);
                }
            })->count(),
        ];
        
        return view('tickets.index', compact('tickets', 'categories', 'technicians', 'counts'));
    }

    public function create()
    {
        $user = Auth::user();
        
        if (!$user->isSuperAdmin() && !$user->isAdmin() && !$user->isPelapor()) {
            abort(403);
        }
        
        $categories = Category::all();
        
        return view('tickets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isSuperAdmin() && !$user->isAdmin() && !$user->isPelapor()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:rendah,sedang,tinggi,kritis',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'room' => 'nullable|string|max:50',
        ]);
        
        DB::beginTransaction();
        
        try {
            $ticket = Ticket::create([
                'ticket_number' => Ticket::generateTicketNumber(),
                'user_id' => $user->id,
                'category_id' => $validated['category_id'],
                'subject' => $validated['subject'],
                'description' => $validated['description'],
                'location' => $validated['location'],
                'room' => $validated['room'] ?? null,
                'priority' => $validated['priority'],
                'status' => 'open',
            ]);
            
            DB::commit();
            
            return redirect()->route('tickets.show', $ticket->id)
                ->with('success', 'Tiket berhasil dibuat! ID: ' . $ticket->ticket_number);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat tiket. Silakan coba lagi.');
        }
    }

    public function show(Ticket $ticket)
    {
        $user = Auth::user();
        
        // Check access
        if ($user->isPelapor() && $ticket->user_id !== $user->id) {
            abort(403);
        }
        
        if ($user->isTeknisi() && !$ticket->technicians->contains($user->id)) {
            abort(403);
        }
        
        $ticket->load(['user.department', 'category', 'technicians', 'comments.user']);
        
        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $user = Auth::user();
        
        if (!$user->isSuperAdmin() && !$user->isAdmin()) {
            abort(403);
        }
        
        $categories = Category::all();
        $technicians = User::where('role', 'teknisi')->where('is_active', true)->get();
        
        return view('tickets.edit', compact('ticket', 'categories', 'technicians'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $user = Auth::user();
        
        if (!$user->isSuperAdmin() && !$user->isAdmin()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'technician_ids' => 'nullable|array',
            'technician_ids.*' => 'exists:users,id',
            'priority' => 'required|in:rendah,sedang,tinggi,kritis',
            'status' => 'required|in:open,progress,resolved,cancelled',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'room' => 'nullable|string|max:50',
        ]);
        
        $ticket->update([
            'category_id' => $validated['category_id'],
            'priority' => $validated['priority'],
            'status' => $validated['status'],
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'room' => $validated['room'] ?? null,
        ]);
        
        if (isset($validated['technician_ids'])) {
            $ticket->technicians()->sync($validated['technician_ids']);
        } else {
            $ticket->technicians()->detach();
        }
        
        // Set resolved_at if status is resolved
        if ($validated['status'] === 'resolved' && $ticket->resolved_at === null) {
            $ticket->update(['resolved_at' => now()]);
        }
        
        return redirect()->route('tickets.show', $ticket->id)
            ->with('success', 'Tiket berhasil diperbarui!');
    }

    public function assignTechnician(Request $request, Ticket $ticket)
    {
        $user = Auth::user();
        
        if (!$user->isSuperAdmin() && !$user->isAdmin()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'technician_id' => 'required|exists:users,id',
        ]);
        
        $ticket->technicians()->syncWithoutDetaching([$validated['technician_id']]);
        $ticket->update([
            'status' => 'progress',
        ]);
        
        return back()->with('success', 'Teknisi berhasil ditugaskan!');
    }

    public function takeTicket(Ticket $ticket)
    {
        $user = Auth::user();
        
        if (!$user->isTeknisi()) {
            abort(403);
        }
        
        if ($ticket->technicians->contains($user->id)) {
            return back()->with('error', 'Anda sudah mengambil tiket ini!');
        }
        
        $ticket->technicians()->attach($user->id);
        $ticket->update([
            'status' => 'progress',
        ]);
        
        return back()->with('success', 'Tugas berhasil diambil!');
    }

    public function resolveTicket(Ticket $ticket)
    {
        $user = Auth::user();
        
        if (!$user->isTeknisi() || !$ticket->technicians->contains($user->id)) {
            abort(403);
        }
        
        $ticket->update([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);
        
        return back()->with('success', 'Tiket ditandai selesai!');
    }

    public function destroy(Ticket $ticket)
    {
        $user = Auth::user();
        
        if (!$user->isSuperAdmin()) {
            abort(403);
        }
        
        $ticket->delete();
        
        return redirect()->route('tickets.index')
            ->with('success', 'Tiket berhasil dihapus!');
    }

    public function addComment(Request $request, Ticket $ticket)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'content' => 'required|string',
        ]);
        
        $ticket->comments()->create([
            'user_id' => $user->id,
            'content' => $validated['content'],
        ]);
        
        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }
}