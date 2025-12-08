<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Pengaduan extends Model
{
    use HasFactory, Auditable;

    protected $table = 'pengaduan';
<<<<<<< HEAD
    protected $primaryKey = 'id_pengaduan';
    public $keyType = 'int';
    public $incrementing = true;
=======
>>>>>>> 5739e6f1e01310efbbc53dda653e2eabca4fc289

    protected $fillable = [
        'id_masyarakat',
        'tanggal_pengaduan',
        'isi_laporan',
        'foto',
        'status',
    ];

<<<<<<< HEAD
    protected $dates = ['tanggal_pengaduan', 'created_at', 'updated_at'];
=======
    protected function casts(): array
    {
        return [
            'tanggal_pengaduan' => 'datetime',
        ];
    }
>>>>>>> 5739e6f1e01310efbbc53dda653e2eabca4fc289

    public function masyarakat()
    {
        return $this->belongsTo(Masyarakat::class, 'id_masyarakat', 'id_masyarakat');
    }

    public function tanggapan()
    {
        return $this->hasMany(Tanggapan::class, 'id_pengaduan', 'id_pengaduan');
    }
}
