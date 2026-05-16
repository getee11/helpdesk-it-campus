<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'role',
        'feature',
        'can_create',
        'can_read',
        'can_update',
        'can_delete',
    ];

    protected $casts = [
        'can_create' => 'boolean',
        'can_read' => 'boolean',
        'can_update' => 'boolean',
        'can_delete' => 'boolean',
    ];

    public static function getPermissionsForRole(string $role): array
    {
        $permissions = self::where('role', $role)->get();
        $result = [];
        
        foreach ($permissions as $permission) {
            $result[$permission->feature] = [
                'c' => $permission->can_create,
                'r' => $permission->can_read,
                'u' => $permission->can_update,
                'd' => $permission->can_delete,
            ];
        }
        
        return $result;
    }

    public static function hasPermission(string $role, string $feature, string $action): bool
    {
        $permission = self::where('role', $role)
            ->where('feature', $feature)
            ->first();
            
        if (!$permission) return false;
        
        return match($action) {
            'create' => $permission->can_create,
            'read' => $permission->can_read,
            'update' => $permission->can_update,
            'delete' => $permission->can_delete,
            default => false,
        };
    }
}