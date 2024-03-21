<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function dataform()
    {
        return $this->hasMany(DataForm::class, 'form_id');
    }
}
