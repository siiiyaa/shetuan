<?php

namespace App\Models;

use function foo\func;
use Illuminate\Database\Eloquent\Model;

class StudentScore extends Model
{
    protected $table = 'student_score';
    protected $fillable = ['student_id', 'assessment_id', 'score'];
    protected $appends = ['student_name', 'assessment_name'];

    public function student()
    {
        return $this->belongsTo(Student::class,'student_id','id');
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class,'assessment_id','id');
    }

    public function getStudentNameAttribute()
    {
        $student_id = $this->student_id;
        return  $this->with(['student' => function ($query){
            $query->select('id', 'stu_name');
        }])->where('student_id', $student_id)->first()->student->stu_name;
    }


    public function getAssessmentNameAttribute()
    {
        $assessment_id = $this->assessment_id;
        return $this->with(['assessment' => function($query){
            $query->select('id', 'ass_name');
        }])->where('assessment_id', $assessment_id)->first()->assessment->ass_name;
    }
}
