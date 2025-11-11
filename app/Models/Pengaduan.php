<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     */
    protected $table = 'pengaduan'; // pastikan sesuai dengan tabel di database kamu

    /**
     * Primary key tabel (opsional, kalau pakai id_pengaduan).
     */
    protected $primaryKey = 'id_pengaduan';

    /**
     * Kolom yang bisa diisi (mass assignable).
     */
    protected $fillable = [
        'id_masyarakat',
        'tanggal_pengaduan',
        'isi_laporan',
        'foto',
        'status',
    ];

    /**
     * Kolom yang otomatis dianggap tanggal oleh Laravel.
     */
    protected $dates = ['tanggal_pengaduan', 'created_at', 'updated_at'];

    /**
     * Relasi ke model Masyarakat.
     * Setiap pengaduan dimiliki oleh satu masyarakat.
     */
    public function masyarakat()
    {
        return $this->belongsTo(Masyarakat::class, 'id_masyarakat');
    }

    /**
     * Relasi ke model Tanggapan.
     * Satu pengaduan bisa punya banyak tanggapan dari petugas.
     */
    public function tanggapan()
    {
        return $this->hasMany(Tanggapan::class, 'id_pengaduan');
    }
}
