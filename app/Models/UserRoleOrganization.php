<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRoleOrganization extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
