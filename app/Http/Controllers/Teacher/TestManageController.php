<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Requests\Teacher\AddAssessmentRequest;
use App\Http\Requests\Teacher\showTopicRequest;
use App\Jobs\UploadFile;
use App\Models\Activity;
use App\Models\Assessment;
use App\Models\Association;
use App\Models\StudentAssociationExperience;
use App\Models\StudentScore;
use App\Models\Topic;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class TestManageController extends Controller
{
    use BaseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //还要验证数据是否合法
    public function store(AddAssessmentRequest $request, Assessment $assessment, Association $association, Activity $activity)
    {
        $data = $request->all();

        $path = $this->upload($request,'study_avi','activity/study_avi');
        if($path){
            $data['study_avi'] = $path;
        }

        DB::beginTransaction();


        try {
            $result = $assessment->create($data);
            $result->topics()->attach($data['topic_ids']); //一定会有题目 前后端判断他是否为空
            $activity->create(['type' => 1, 'activity_id' => $result->id, 'assoc_id' => $data['association_id']]);
            //删除redis

            DB::commit();

            return $this->result('',1,'新增成功',200);
        }catch (\Exception $exception){
            DB::rollBack();

            return $this->result('',0,'新增失败',200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(showTopicRequest $request, Topic $topic)
    {
       $data = $request->all();
       $perpage = $request->input('perpage',10); //每页显示数量

        $result = $topic->showTopic($data, $perpage);

        return $this->result($result,1,'success',200);
    }

    public function showTopic(Request $request, Topic $topic)
    {
        $id = $request->input('topic_id');
        $result = $topic->where('id',$id)->first(['id','type','top_name','options','correct']);

        return $result;
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Assessment $assessment, Topic $topic)
    {

        $id = $request->input('activity_id');
        if(!$id){
            return response()->json(['data' => ['activity_id' => ['参数不完整']], 'code' => 422, 'message' => 'error', 'status' => 200]);
        }

        $result = $assessment->where('id', $id)->with(['topics' => function ($query){
            $query->select('topics.id');
        }])->first(['id', 'ass_name', 'clipping_time', 'experience']);

        if($result){
            $result = $result->toArray();
            if($result['topics']){
                $result['first_topic'] = $topic::where('id', $result['topics'][0])->first(['type', 'top_name', 'options', 'correct', 'top_score']);
            }else{
                $result['first_topic'] = [];
            }
            $result['topics'] = array_column($result['topics'],'id');

            return $this->result($result,1,'success',200);
        }else{
            return $this->result([],1,'success',200);
        }



    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AddAssessmentRequest $request, Association $association, Assessment $assessment)
    {
        /**
         * 如果有提交新学习视频 则删除原有的学习视频
         * 更新中间表
         * 更新考试表
         * 删除redis
         */
        $data = $request->all();
        if(!in_array('assessment_id',$data) || !$data['assessment_id']){
            return response()->json(['data' => ['assessment_id'=>['参数不完整']], 'code' => 422, 'message' => 'error', 'status' => 200]);
        }

        $ass_result = $association->where('id', $data['association_id'])->first(['id', 'ass_name']);
        $assessment_result = $assessment::find($data['assessment_id']);
//        UploadFile::dispatch($request,'study_avi',$ass_result->ass_name)->onQueue('File');

        $path = $this->upload($request,'study_avi','activity/study_avi');
        if ($path) {
            $data['study_avi'] = $path;
            $disk = Storage::disk('public');
            $disk->delete($assessment_result['study_avi']);
        }

        DB::beginTransaction();
        try{
            $assessment->where('id', $data['assessment_id'])->update(collect($data)->except(['assessment_id','association_id','topic_ids'])->toArray());
            $assessment_result->topics()->sync($data['topic_ids']);

            DB::commit();

            return $this->result('',1,'修改成功',200);
        }catch (\Exception $exception){
            DB::rollBack();

            return $this->result('',0,'修改失败',200);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Assessment  $assessment, Activity $activity, StudentScore $studentScore)
    {
        //前端根据type请求这个接口
        /**
         * 删除文件
         * 删除记录
         * 接触中间表关系
         * 删除redis
         * 删除学生在此考试的所有成绩
         *
         */
        $id = $request->input('activity_id');

        if(!$id){
            return response()->json(['data' => ['activity_id'=>['参数不完整']], 'code' => 422, 'message' => 'error', 'status' => 200]);
        }

        $assessment_result = $assessment::find($id);

        Storage::disk('public')->delete($assessment_result['study_avi']);
        DB::beginTransaction();

        try{
            $assessment_result->topics()->detach();
            $assessment_result->delete();
            $activity->where('type', 1)->where('activity_id', $id)->delete();
            $studentScore->where('assessment_id', $id)->delete();

            DB::commit();
            return $this->result('',1,'删除成功',200);
        }catch (\Exception $exceshowTopicption){
            DB::rollBack();
            return $this->result('',1,'删除成功',200);
        }

    }

    public function oneTopic(Request $request, Topic $topic)
    {
        $topic_id = $request->input('topic_id');
        if(!$topic_id){
            return response()->json(['data' => ['topic_id'=>['参数不完整']], 'code' => 422, 'message' => 'error', 'status' => 200]);
        }
        $result = $topic::find($topic_id, ['id','type','top_name','options','correct','top_score','course_id','chapter_id']);

        return $this->result($result,1,'success',200);
    }

    public function searchActivity(Request $request,Activity $activity)
    {
        $association_id = $request->input('association_id');
        if(!$association_id){
            return response()->json(['data' => ['association_id'=>['参数不完整']], 'code' => 422, 'message' => 'error', 'status' => 200]);
        }
    }
}
