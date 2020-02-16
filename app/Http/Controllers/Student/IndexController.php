<?php
namespace App\Http\Controllers\Student;

use App\Http\Requests\Student\AssessmentEndTimeRequest;
use App\Http\Requests\Student\FirstTopicRequest;
use App\Http\Requests\Student\NextTopicRequest;
use App\Http\Requests\Student\SubmitAssessmentRequest;
use App\Models\Activity;
use App\Models\Assessment;
use App\Models\Association;
use App\Models\Student;
use App\Models\StudentAssociation;
use App\Models\StudentAssociationExperience;
use App\Models\StudentScore;
use App\Models\Topic;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;


class IndexController
{
    use BaseTrait;

    //用redis
    public function index(Association $association)
    {
        $data = $association->allAssociation();
        return $this->result($data,1,'success',200);
    }

    public function show(Request $request, Association $association, StudentAssociation $studentAssociation)
    {
        $association_id = $request->input('association_id');
        $id = $request->get('token_data')->id;

        $result = $association->onceAssociation($association_id);

        $a = $studentAssociation->where(function ($query) use ($id, $association_id){
            $query->where(['student_id' => $id, 'association_id' => $association_id, 'status' => 0]);
        })->orWhere(function ($query) use ($id, $association_id){
            $query->where(['student_id' => $id, 'association_id' => $association_id, 'status' => 1]);
        })->first();

        if($a){
            $result->is_join = 1;
        }else{
            $result->is_join = 0;
        }

        if(!$result){
            $result = [];
        }

        return $this->result($result,1,'success',200);
    }

    public function addAssociation(Request $request, Student $student)
    {
        $user_data = $request->get('token_data');
        $student_id = $user_data->id;
        $association_id = $request->input('association_id');

        $result = DB::table('student_association')->where(function ($query) use ($student_id, $association_id){
            $query->where(['student_id' => $student_id, 'association_id' => $association_id, 'status' => 0]);
        })->orWhere(function ($query) use ($student_id, $association_id){
            $query->where(['student_id' => $student_id, 'association_id' => $association_id, 'status' => 1]);
        })->first();

        if($result){
            return $this->result('',0,'你已申请，不得重复操作',200);
        }

        $student::find($student_id)->association()->attach($association_id, ['status' => 0]);
        return $this->result('',1,'申请成功，等待管理老师同意',200);

    }



    //用redis
    public function myJoinAssociation(Request $request, Association $association, StudentAssociationExperience $associationExperience)
    {
        $id = $request->get('token_data')->id;

        $result = $association->where(function ($query) use ($id){
            $associations = \DB::table('student_association')->where('status',1)->where('student_id', $id)->get(['association_id'])->toArray();
            $associations_id = array_column($associations,'association_id');
            return $query->whereIn('associations.id', $associations_id);
        })->with(['teacher:teachers.id,te_name'])->select('id', 'ass_name', 'number_people', 'introduce', 'images')->get();

        $result->each->setAppends([]);

        $result->each(function ($item){
            $item->teacher->each(function ($item){
                unset($item->pivot);
            });
        });

        foreach ($result as $key => $value){
            $cc = $associationExperience::where('association_id', $value['id'])->where('student_id', $id)->first();
            if($cc){
                $value['experience'] = $cc->experience;
            }else{
                $value['experience'] = 0;
            }

        }

        return $this->result($result,1,'success',200);
    }


    public function whetherHaveStudyAvi(Request $request, Assessment $assessment)
    {
        $assessment_id = $request->input('assessment_id');

        if(!$assessment_id){
            return response()->json([
                'data' => ['assessment_id' => ['参数不完整']],
                'code' => 422,
                'message' => 'error'
            ],422);
        }

        $assments = $assessment::find($assessment_id);
        if($assments->study_avi){
            return $this->result($assments->study_avi,1,'success',200);
        }else{
            return $this->result('',0,'success',200);
        }
    }

    public function assessmentEndTime(AssessmentEndTimeRequest $request, Assessment $assessment)
    {
        $assessment_id = $request->input('assessment_id');
        $student_id = $request->get('token_data')->id;

        $assments = $assessment::find($assessment_id);

        if($assments->clipping_time != 0){
            $end_time = time() + $assments->clipping_time * 60;
            Redis::hsetnx('assessment_'.$assessment_id.'_end_time', 'student_'.$student_id, $end_time);
            $end_time = Redis::hget('assessment_'.$assessment_id.'_end_time', 'student_'.$student_id);
        }else{
            $end_time = 0;
        }


        return $this->result($end_time,1,'success',200);
    }



    public function firstTopic(FirstTopicRequest $request, Assessment $assessment, Topic $topic, Activity $activity, StudentScore $studentScore)
    {
        $assessment_id = $request->input('assessment_id');
        $student_id = $request->get('token_data')->id;

        $assments = $assessment::find($assessment_id);


        $end_time = Redis::hget('assessment_'.$assessment_id.'_end_time', 'student_'.$student_id);
        if($end_time){
            $res3 = $this->checkActivityTime($end_time);
            if($res3){
                return $res3;
            }
        }

        $res1 = $this->checkActivityStatus($assessment_id, $activity);
        $res2 = $this->checkAgainActivity($studentScore, $student_id, $assessment_id);


        if($res1){
            return $res1;
        }elseif($res2){
            return $res2;
        }

        $result = $topic->oneTopic($assessment_id);

        $result = collect($result)->except(['first_page_url', 'from', 'last_page', 'path', 'per_page', 'last_page_url', 'to'])->toArray();
        $result['start_time'] = time();
        $result['assessment_name'] = $assments->ass_name;

        return $this->result($result, 1, 'success', 200);

    }

    public function nextTopic(NextTopicRequest $request, Topic $topic, Activity $activity)
    {
        $student_id = $request->get('token_data')->id;
        $topic_id = $request->input('topic_id');
        $answer = $request->input('answer');
        $assessment_id = $request->input('assessment_id');
        $score = $request->input('score', 0);

        $res1 = $this->checkActivityStatus($assessment_id, $activity);

        $end_time = Redis::hget('assessment_'.$assessment_id.'_end_time', 'student_'.$student_id);
        if($end_time){
            $res2 = $this->checkActivityTime($end_time);
            if($res2){
                return $res2;
            }
        }

        if($res1){
            return $res1;
        }

        $result = $topic->oneTopic($assessment_id);

        $result = collect($result)->except(['first_page_url', 'from', 'last_page', 'path', 'per_page', 'last_page_url', 'to', 'total'])->toArray();
        $result['score'] = $score;

        if(isset($topic_id) && isset($answer)){
            $score = $this->checkScore($topic, $topic_id, $answer);
            $result['score'] += $score;
        }

        return $this->result($result,2,'success',200);
    }

    protected function checkActivityStatus($assessment_id, Activity $activity)
    {
        $result = $activity::where('activity_id', $assessment_id)->first(['id', 'status']);

        if($result->status != 1){
            return $this->result('', -1,'考试已关闭',200);
        }
    }

    protected function checkActivityTime($end_time)
    {

        if(time() > $end_time){
            return $this->result('', -2,'超时未完成考试',200);
        }


    }

    protected function checkAgainActivity(StudentScore $studentScore, $student_id, $assessment_id)
    {

        if($studentScore::where('student_id', $student_id)->where('assessment_id', $assessment_id)->first()){
            return $this->result('',-3,'不得重复参加考试', 200);
        }

    }

    protected function checkScore($topic, $topic_id, $answer)
    {
        $result = $topic::find($topic_id, ['correct', 'top_score']);
        $correct = json_decode($result->correct);

        $answer = array_map('strtolower',$answer);
        $correct = array_map('strtolower',$correct);

        if(array_diff($correct, $answer) == []){
            $score = $result->top_score;
        }else{
            $score = 0;
        }

        return $score;

    }

    public function submitAssessment(SubmitAssessmentRequest $request, StudentScore $studentScore, Assessment $assessment, Student $student, Topic $topic, StudentAssociationExperience $studentAssociationExperience, Activity $activity)
    {
        $association_id = $request->input('association_id');
        $assessment_id = $request->input('assessment_id');
        $score = $request->input('score');
        $topic_id = $request->input('topic_id');
        $answer = $request->input('answer');
        $student_id = $request->get('token_data')->id;

        $end_time = Redis::hget('assessment_'.$assessment_id.'_end_time', 'student_'.$student_id);

        $res1 = $this->checkActivityStatus($assessment_id, $activity);
        if($end_time){
            $res2 = $this->checkActivityTime($end_time);
            if($res2){
                return $res2;
            }
        }

        if($res1){
            return $res1;
        }

        $this_score = $this->checkScore($topic, $topic_id, $answer);
        $score += $this_score;

        $assessment_result = $assessment::find($assessment_id);
        $all_topics = $assessment_result->topics->toArray();

        $sum_score = 0;

        foreach ($all_topics as $value){
            $sum_score += $value['top_score'];
        }

        $value = $score/$sum_score; //当前考试分数除以总分

        $experience = $assessment_result->experience;

        $student_experience = floor($value*$experience);

        //社团经验值
        $result = $studentAssociationExperience::where('association_id', $association_id)->where('student_id',$student_id)->first();

        if($result){
            $result->experience = $result->experience + $student_experience;
            $result->save();
        }else{
            $studentAssociationExperience::create(['association_id' => $association_id, 'student_id' => $student_id, 'experience' => $student_experience]);
        }

        $student_result = $student::find($student_id);
        $student_result->experience = $student_result->experience + $student_experience;
        $student_result->save();

        $result = $studentScore->create([
            'student_id' => $student_id,
            'assessment_id' => $assessment_id,
            'score' => $score,
        ]);

        Redis::hdel('assessment_'.$assessment_id.'_end_time', 'student_'.$student_id);

        if($result){
            return $this->result('',1,'success',200);
        }else{
            return $this->result('',0,'error',200);
        }


    }

    //还差表单验证和活动图片
    public function scoreIndex(Request $request, StudentScore $studentScore, Activity $activity)
    {
        $assessment_id = $request->input('assessment_id');
        $student_id = $request->get('token_data')->id;

        if(!$assessment_id){
            return response()->json(['data' => ['assessment_id'=>['参数不完整']], 'code' => 422, 'message' => 'error', 'status' => 200]);
        }
        $result = $studentScore->where('assessment_id', $assessment_id)->orderBy('score', 'desc')->select('id', 'student_id', 'assessment_id','score')->get()->toArray();
        $c = $activity::with(['assessment' => function($query) use ($assessment_id) {
            return $query->select('id', 'ass_name');
        }])->where('activity_id', $assessment_id)->where('type', 1)->where('status', 1)->first();


        $result = array_map(function ($item){
            unset($item['assessment_name']);
            return $item;
        }, $result);

        foreach ($result as $key => $value){
            $result[$key]['ranking'] = $key + 1;
        }

        $for_index = array_search($student_id, array_column($result,'student_id'));

        $big['data'] = $result;
        $big['assessment']['assessment_head_image'] = $c->head_image;
        $big['assessment']['assessment_name'] = $c->assessment->ass_name;
        $big['current_student'] = $result[$for_index];

        return $this->result($big,1,'success',200);
    }


    public function showAllStudent(Request $request, Association $association, StudentAssociationExperience $studentAssociationExperience)
    {
        $association_id = $request->input('association_id');
        $student_id = $request->get('token_data')->id;
        $page = $request->input('page', 1)-1; //当前页数

        $perPage = 2; //每页显示几条

        $data = $association->with(['student' => function ($query){
            $student = \DB::table('student_association')->where('status', 1)->get(['student_id'])->toArray();
            $student_ids = array_column($student,'student_id');

            $query->select('students.id', 'students.stu_name', 'students.head_image')->whereIn('students.id',$student_ids);
        }])->where('id',$association_id)->first();


        $all_student = $data->student;

        foreach ($all_student as $key => $value)
        {
            $result = $studentAssociationExperience::where('student_id', $all_student[$key]['id'])->where('association_id', $association_id)->first();

            if($result){
                $all_student[$key]['experience'] = $result->experience;
            }else{
                $all_student[$key]['experience'] = 0;
            }
        }

        $all_student = collect($all_student->toArray())->sortByDesc('experience')->values()->all();

        foreach ($all_student as $key=>$value){
            $all_student[$key]['ranking'] = $key+1;
        }

        $total = count($all_student);
        $for_key = array_search($student_id, array_column($all_student,'id'));
        $current_student = $all_student[$for_key];


        $all_student = array_slice($all_student,$page*$perPage,$perPage); //手动先进行切片
        $result = new LengthAwarePaginator($all_student, $total, $perPage,$page+1,['path' => $request->url(), 'query' => $request->query()]);

        $result = collect($result)->except(['current_page','first_page_url','from','last_page','path','per_page','to','total','last_page_url'])->toArray();


        $result['current_student'] = $current_student;
        $result['all_number'] = $total;

        return $this->result($result,1,'success',200);
    }


    public function activityIndex(Request $request, Activity $activity)
    {
        $assoc_id = $request->input('association_id');
        $status = $request->input('status',0);
        $activity_name = $request->input('activity_name');  //查询关键字

        $result = $activity->allData($assoc_id, $status, $activity_name);
        if($result){
            return $this->result($result,1,'success',200);
        }else{
            return $this->result('',0,'success',200);
        }
    }






}
