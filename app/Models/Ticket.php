<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'user_id',
        'category_id',
        'subject',
        'description',
        'location',
        'room',
        'priority',
        'status',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function technicians(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'ticket_teknisi', 'ticket_id', 'user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class);
    }

    public static function generateTicketNumber(): string
    {
        $date = now()->format('ymd');
        $count = self::whereDate('created_at', today())->count() + 1;
        return 'TKT-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'open' => 'badge-open',
            'progress' => 'badge-progress',
            'resolved' => 'badge-resolved',
            'cancelled' => 'badge-cancelled',
            default => 'badge-open',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'open' => 'Open',
            'progress' => 'On Progress',
            'resolved' => 'Resolved',
            'cancelled' => 'Cancelled',
            default => 'Open',
        };
    }

    public function getPriorityBadgeClassAttribute(): string
    {
        return match($this->priority) {
            'kritis' => 'badge-kritis',
            'tinggi' => 'badge-tinggi',
            'sedang' => 'badge-sedang',
            'rendah' => 'badge-rendah',
            default => 'badge-rendah',
        };
    }
}