<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    use HasFactory;

    protected $table = 'pengaduan';

    protected $fillable = [
        'id_masyarakat',
        'tanggal_pengaduan',
        'isi_laporan',
        'foto',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pengaduan' => 'datetime',
        ];
    }

    public function masyarakat()
    {
        return $this->belongsTo(Masyarakat::class, 'id_masyarakat');
    }

    public function tanggapan()
    {
        return $this->hasMany(Tanggapan::class, 'id_pengaduan');
    }
}
