<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendances';

    public $timestamps = false;

    //学生和考核  多对多
    public function student()
    {
        return $this->belongsToMany(Student::class,'student_attendance','attendance_id','student_id','id','id');
    }
}
