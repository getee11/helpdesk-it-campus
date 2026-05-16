<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'department_id', 'nim_nip', 'phone'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function assignedTickets(): BelongsToMany
    {
        return $this->belongsToMany(Ticket::class, 'ticket_teknisi', 'user_id', 'ticket_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTeknisi(): bool
    {
        return $this->role === 'teknisi';
    }

    public function isPelapor(): bool
    {
        return $this->role === 'pelapor';
    }

    public function getAvatarAttribute(): string
    {
        $initials = '';
        $words = explode(' ', $this->name);
        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return $initials;
    }

    public function getAvatarColorAttribute(): string
    {
        return match($this->role) {
            'superadmin' => '#9fe870',
            'admin' => '#dbeafe',
            'teknisi' => '#fef3c7',
            'pelapor' => '#ede9fe',
            default => '#e5e5e5',
        };
    }

    public function getAvatarTextColorAttribute(): string
    {
        return match($this->role) {
            'superadmin' => '#0e0f0c',
            'admin' => '#1d4ed8',
            'teknisi' => '#92400e',
            'pelapor' => '#5b21b6',
            default => '#000000',
        };
    }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'superadmin' => 'Super Admin',
            'admin' => 'Administrator',
            'teknisi' => 'Teknisi IT',
            'pelapor' => 'Pelapor',
            default => 'Unknown',
        };
    }

    public function getRoleChipClassAttribute(): string
    {
        return match($this->role) {
            'superadmin' => 'chip-superadmin',
            'admin' => 'chip-admin',
            'teknisi' => 'chip-teknisi',
            'pelapor' => 'chip-pelapor',
            default => '',
        };
    }
}