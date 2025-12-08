<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    protected $fillable = [
        'user_id',
        'user_type',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
    ];

    /**
     * Catat aksi untuk audit
     */
    public static function log($action, $modelType, $modelId, $oldValues = null, $newValues = null)
    {
        $petugas = auth('petugas')->user();
        $user = auth('web')->user();

        return static::create([
            'user_id' => $petugas?->id_petugas ?? $user?->id_masyarakat,
            'user_type' => $petugas ? 'petugas' : 'masyarakat',
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
