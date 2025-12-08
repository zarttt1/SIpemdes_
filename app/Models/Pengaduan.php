<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Pengaduan extends Model
{
    use HasFactory, Auditable;

    protected $table = 'pengaduan';
    protected $primaryKey = 'id_pengaduan';
    public $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'id_masyarakat',
        'tanggal_pengaduan',
        'isi_laporan',
        'foto',
        'status',
    ];

    protected $dates = ['tanggal_pengaduan', 'created_at', 'updated_at'];

    public function masyarakat()
    {
        return $this->belongsTo(Masyarakat::class, 'id_masyarakat', 'id_masyarakat');
    }

    public function tanggapan()
    {
        return $this->hasMany(Tanggapan::class, 'id_pengaduan', 'id_pengaduan');
    }
}
