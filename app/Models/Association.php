<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

//社团表
class Association extends Model
{
    use BaseTrait;
    protected $table = 'associations';

    protected $appends = ['status_value', 'distance_time'];

    protected $fillable = ['ass_name', 'english_name', 'corporate_slogan', 'introduce', 'images', 'learning_objectives', 'department_id'];

    public function course()
    {
        return $this->hasMany(Course::class,'association_id','id');
    }

    //社团与活动 一对多
    public function activity()
    {
        return $this->hasMany(Activity::class,'assoc_id','id');
    }

    //社团与学生 多对多
    public function student()
    {
        return $this->belongsToMany(Student::class,'student_association','association_id','student_id','id','id')
            ->withPivot('status')->withTimestamps();
    }

    public function teacher()
    {
        return $this->belongsToMany(Teacher::class,'teacher_association','association_id','teacher_id','id','id')
            ->withPivot('status','is_admin')->withTimestamps();
    }

    public function department()
    {
        return $this->belongsTo(Department::class,'department_id','id');
    }


    public function getStatusValueAttribute()
    {
        $pivot = $this->pivot;
        if($pivot){
            $status = $pivot->status;
            if($status == 0){
                return '等待验证';
            }elseif ($status == 1){
                return '已通过';
            }elseif($status == 2){
                return '未通过';
            }
        }

    }

    public function getImagesAttribute($path)
    {
        if(empty($path)){
            $path = DB::table('systems')->find(1)->association_default_images;
        }

        return env('APP_IMAGE_URL').$path;

    }

    public function getStudyAviAttribute($value)
    {
        return env('APP_IMAGE_URL').$value;
    }

    public function getDistanceTimeAttribute() {
        $date = $this->created_at;
        if (Carbon::now() > Carbon::parse($date)->addDays(15)) {
            return Carbon::parse($date);
        }

        return Carbon::parse($date)->diffForHumans();
    }


    //显示这个社团所有学生
    public function assocStudents($association_id)
    {
        $result = $this->with(['student' => function ($query){
            $student = \DB::table('student_association')->where('status', 1)->get(['student_id'])->toArray();
            $student_ids = array_column($student,'student_id');

            $query->select('students.id','students.stu_number','students.stu_name', 'experience')->whereIn('students.id',$student_ids);
        }])->where('id',$association_id)->first();

        return $result;
    }


    public function addAssociation($data)
    {
        $path = $this->headImagepath($data);

        DB::beginTransaction();
        try{
            $result = $this->create([
                'ass_name' => $data['ass_name'],
                'english_name' => $data['english_name'],
                'corporate_slogan' => $data['corporate_slogan'],
                'introduce' => $data['introduce'],
                'images' => $path,
                'learning_objectives' => $data['learning_objectives'],
                'department_id' => $data['department_id']
            ]);

            $result->teacher()->attach($data['teacher_id'], ['status' => 1, 'is_admin' => 1]);
            DB::commit();
            return true;
        }catch (\Exception $exception){
            DB::rollBack();
            return false;
        }
    }

    public function updateAssociation($data)
    {
        $path = $this->headImagepath($data);

        $result = $this::find($data['association_id']);

        if($result->images){
            Storage::disk('public')->delete($result->images);
        }

        $cc = $data;

        $cc = collect($cc)->except(['images','association_id','teacher_id']);

        DB::beginTransaction();
        try{
            foreach ($cc as $key => $value){
                $result->{$key} = $value;
            }

            $result->images = $path;
            $result->save();

            if($data['teacher_id']){
                DB::table('teacher_association')->where('association_id', $data['association_id'])->where('status', 1)->where('is_admin',1)->update(['is_admin' => 0]);

                $rr = $this::whereHas('teacher',function ($query) use ($data){
                    $query->where('teachers.id', $data['teacher_id']);
                })->where('id', $data['association_id'])->first();

                if($rr){
                    $rr->teacher()->updateExistingPivot($data['teacher_id'], ['status' => 1, 'is_admin' => 1]);
                }else{
                    $this::find($data['association_id'])->teacher()->attach($data['teacher_id'], ['status' => 1, 'is_admin' => 1]);
                }
            }

            DB::commit();
            return true;
        }catch (\Exception $exception){
            DB::rollBack();
            return false;
        }
    }

    protected function headImagepath($data){
        $field = $data['images'];

        if($field && !is_string($field) && $field->isValid()){
            $txt = $field->getClientOriginalExtension();
            $path = $field->storeAs('association/head_image',time().mt_rand(999, 9999).'.'.$txt,'public');

        }elseif ($field && is_string($field)){
            $path = $this->uploadBase($field,'association/head_image');

        }else{
            $path = '';
        }

        return $path;
    }



    public function allAssociation()
    {
        $data = $this->select('id','ass_name','images')->paginate(16);

        $data->getCollection()->transform(function ($item){
            $item->setAppends([]);
            return $item;
        });

        return $data;
    }


    //首页特定社团
    public function onceAssociation($association_id)
    {
        $result = $this->with(['teacher' => function( $query ) use ($association_id){
            $result = \DB::table('teacher_association')->where('association_id', $association_id)->where('status',1)->get(['teacher_id'])->toArray();
            $teacher_ids = array_column($result,'teacher_id');
            return $query->select('teachers.id', 'te_name')->whereIn('teachers.id', $teacher_ids);
        }])
            ->where('id', $association_id)
            ->select('id','ass_name','english_name','images','number_people','corporate_slogan','introduce')
            ->first();

        $result->setAppends([]);
        $result->teacher->transform(function($value){
            unset($value->pivot);
            return $value;
        });

        return $result;

    }


    //显示特定社团全部老师（非管理员）status=1表示显示全部通过的老师 status=0表示显示等待验证的老师
    public function allAssociationTeacher($association_id, $status)
    {
        $result = $this->with(['teacher' => function($query) use ($status) {
            $result = \DB::table('teacher_association')->where('is_admin',0)->where('status', $status)->get(['teacher_id'])->toArray();
            $teacher_ids = array_column($result,'teacher_id');
            $query->select('teachers.id', 'te_name', 'head_image')->whereIn('teachers.id', $teacher_ids);
        }])->where('associations.id', $association_id)->first()->teacher->toArray();

        $result = array_map(function ($item){
            unset($item['pivot']);
            return $item;
        }, $result);

        return $result;
    }


    //特定社团是否已经拥有特定状态的老师 status=1表示，此社团是否有通过的老师，
    public function associationHasTeacher($association_id, $teacher_id, $status)
    {

        $result = DB::table('teacher_association')->where(function ($query) use ($teacher_id, $status, $association_id){
            $query->where('teacher_id', $teacher_id)->where('status', $status)->where('association_id', $association_id);
        })->orWhere(function ($query) use ($teacher_id, $status, $association_id){
            $query->where('teacher_id', $teacher_id)->where('status', $status)->where('association_id', $association_id);
        })->first();

        return $result;
    }

}
