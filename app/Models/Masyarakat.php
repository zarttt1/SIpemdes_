<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Masyarakat extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'masyarakat';
    protected $primaryKey = 'id_masyarakat';
    public $incrementing = true;           
    protected $keyType = 'int';            

    protected $fillable = [
        'nik',
        'nama',
        'alamat',
        'no_hp',
        'username',
        'email',
        'password',
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

    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class, 'id_masyarakat');
    }
}
