<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//课程表
class Course extends Model
{
    protected $table='courses';
    public $fillable = ['cou_name', 'association_id'];

    public function association()
    {
        return $this->belongsTo(Association::class,'association_id','id');
    }

    public function topic()
    {
        return $this->hasMany(Topic::class,'course_id','id');
    }

    public function insertCourse($data)
    {

        return $this->create($data);
    }

    public function chapter()
    {
        return $this->hasMany(Chapter::class, 'course_id', 'id');
    }


}
