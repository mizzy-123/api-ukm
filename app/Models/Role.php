<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'user_role_organizations');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, "user_role_organizations");
    }
}
