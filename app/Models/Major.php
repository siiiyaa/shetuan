<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $table = 'majors';

    public $timestamps = true;

    public $hidden = ['created_at', 'updated_at'];

    public function classes()
    {
        return $this->hasMany(Classes::class,'major_id','id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class,'department_id','id');
    }
}
