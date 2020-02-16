<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

//活动表
class Activity extends Model
{
    use BaseTrait;
    protected $table = 'activity';

    protected $fillable = ['type', 'activity_id', 'status', 'assoc_id'];

    protected $appends = ['status_value', 'head_image'];


    //活动社团一对多
    public function association()
    {
        return $this->belongsTo(Association::class,'assoc_id','id');
    }

    //活动考试一对一
    public function assessment()
    {
        return $this->belongsTo(Assessment::class,'activity_id','id');
    }

    //活动投票一对一 有再说

    //访问器
    public function getStatusValueAttribute($value)
    {
        if($this->status == 1){
            return '进行中';
        }elseif ($this->status == 2){
            return '未开始';
        }elseif ($this->status == 3){
            return '已结束';
        }
    }



    public function getHeadImageAttribute($path)
    {
        if(empty($path)){
            $path = DB::table('systems')->find(1)->activity_default_images;
        }

        return env('APP_IMAGE_URL').$path;

    }

    public function allData($assoc_id, $status, $activity_name)
    {
        $query = $this::query();
        if($activity_name || !$activity_name == ''){
            $query->whereHas('assessment', function ($query) use ($activity_name){
                $query->where('ass_name', 'like', '%'.$activity_name.'%');
            }); //如果还有其他类型活动，用orWhereHas即可！因为现在还没有，所以暂时先匹配考试表。
        }

        if($status){
            if($status == 0){
                $status = '';
            }
            $query->where('status', $status);
        }

        $results = $query->where('assoc_id', $assoc_id)->get();
        if($results){
            $data = [];
            foreach ($results as $key => $value){
                if($value->type == 1){
                    $data[$key]['id'] = $value->assessment['id'];
                    $data[$key]['type'] = $value->type;
                    $data[$key]['activity_name'] = $value->assessment['ass_name'];
                    $data[$key]['topic_count'] = collect($value->assessment['topics'])->count();
                    $data[$key]['status_value'] = $value->status_value;
                    $data[$key]['status'] = $value->status;
                    $data[$key]['created_at'] = $value->created_at->toDateTimeString();
                    $data[$key]['head_image'] = $value->head_image;
                }
            }
            return $data;
        }else{
            return '';
        }




    }





}
