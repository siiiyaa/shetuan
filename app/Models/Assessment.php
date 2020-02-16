<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//测试表
class Assessment extends Model
{
    public $table = 'assessments';

    protected $fillable = ['ass_name', 'study_avi', 'clipping_time','experience'];

    public $timestamps = false;

    //测试与题目 多对多
    public function topics()
    {
        return $this->belongsToMany(Topic::class,'assessment_topic','asses_id','topic_id','id','id');
    }


    public function getStudyAviAttribute($value)
    {
        if($value){
            return env('APP_IMAGE_URL').$value;
        }
    }
}
