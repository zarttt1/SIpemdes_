<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Petugas extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'petugas';
    protected $primaryKey = 'id_petugas'; // penting: ubah dari 'id' ke 'id_petugas'
    public $incrementing = false;         // karena tidak auto increment
    protected $keyType = 'int';           // tipe data bigint = integer

    protected $fillable = [
        'nama',
        'email',
        'username',
        'password',
        'level',
        'status',
        'remember_token', // tambahkan ini juga karena tabel punya kolom ini
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function tanggapan()
    {
        return $this->hasMany(Tanggapan::class, 'id_petugas');
    }
}
