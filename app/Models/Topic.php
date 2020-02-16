<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $table = 'topics';

    public $timestamps = true;

    protected $fillable = ['type', 'top_name', 'options', 'correct', 'top_score', 'course_id', 'chapter_id', 'association_id'];

    protected $hidden = ['pivot'];

    public function assessments()
    {
        return $this->belongsToMany(Assessment::class,'assessment_topic','topic_id','asses_id','id','id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class,'course_id','id');
    }

//    public function chapter()
//    {
//        return $this->belongsTo(Chapter::class,'chapter_id','id');
//    }

    public function insertTopic($data)
    {
        return $this->create($data);
    }

    public function showTopic($data, $perpage)
    {
       $query = $this->query();
       if(isset($data['course_id'])) {
           $query->where('course_id', $data['course_id']);
       }
       if (isset($data['chapter_id'])) {
           $query->where('chapter_id', $data['chapter_id']);
       }

        return $query->where('association_id', $data['association_id'])
            ->select('id', 'type', 'top_name', 'options', 'correct', 'top_score')
            ->paginate($perpage)->appends($data);
    }

    public function oneTopic($assessment_id)
    {
        $result = $this->whereHas('assessments',function ($query) use ($assessment_id){
            $query->where('assessments.id', $assessment_id);
        })->select('id','type','top_name','options', 'top_score')->paginate(1)->withPath(route('student_nextTopic'))->appends(['assessment_id' => $assessment_id]);

        return $result;
    }
}
