<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class, "user_role_organizations");
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, "user_role_organizations");
    }
}
