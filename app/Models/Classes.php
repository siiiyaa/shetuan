<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    //
    protected $table = 'class';
    public $timestamps = false;

    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id', 'id');
    }

    public function student()
    {
        return $this->hasMany(Student::class,'class_id','id');
    }

    public function selectIndex()
    {
        return $this->get(['id','cls_name']);
    }
}
