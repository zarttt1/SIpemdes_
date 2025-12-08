<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable; // Pastikan file Trait ini ada

class Tanggapan extends Model
{
    use HasFactory;
    // use Auditable; // Uncomment jika file Trait Auditable benar-benar ada

    protected $table = 'tanggapan';
    
    // PENTING: Primary Key harus 'id_tanggapan' sesuai database kamu
    // Jangan ubah jadi 'id' karena akan menyebabkan error SQLSTATE[42703]
    protected $primaryKey = 'id_tanggapan';
    
    public $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'id_pengaduan',
        'id_petugas',
        'tanggal_tanggapan',
        'isi_tanggapan',
    ];

    // Casting tipe data
    protected $casts = [
        'tanggal_tanggapan' => 'datetime',
    ];

    // Relasi ke Pengaduan
    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class, 'id_pengaduan', 'id_pengaduan');
    }

    // Relasi ke Petugas
    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas', 'id_petugas');
    }
}