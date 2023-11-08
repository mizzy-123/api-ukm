<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPiket extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user_piket()
    {
        return $this->hasMany(UserPiket::class, 'jadwal_piket_id');
    }
}
