<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Requests\Teacher\ActivityIndexRequest;
use App\Http\Requests\Teacher\UpdateActivityStatusRequest;
use App\Models\Activity;
use App\Traits\BaseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class ActivityController extends Controller
{
    use BaseTrait;


    //使用redis
    public function index(ActivityIndexRequest $request, Activity $activity)
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

    public function updateStatus(UpdateActivityStatusRequest $request, Activity $activity)
    {
        $status = $request->input('status');
        $activity_id = $request->input('activity_id');

        $result = $activity->where('activity_id',$activity_id)->update(['status' => $status]);
        if($result){
            return $this->result('',1,'success',200);
        }else{
            return $this->result('', 0, 'error', 200);

        }

    }





}

