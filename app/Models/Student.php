<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    use BaseTrait;
    protected $table = 'students';
    public $timestamps = false;

    protected $fillable = ['stu_number', 'sex','stu_name','password','class_id', 'head_image','email'];

    protected $hidden = ['pivot'];

    protected $appends = ['sex_value'];



    public function classes()
    {
        return $this->belongsTo(Classes::class,'class_id','id');
    }

    //学生和社团 多对多
    public function association()
    {
        return $this->belongsToMany(Association::class,'student_association','student_id','association_id','id','id')
            ->withPivot('status')->withTimestamps();
    }

    public function getHeadImageAttribute($path)
    {
        if(empty($path)) {
            $path = DB::table('systems')->find(1)->student_default_images;
        }

        return env('APP_IMAGE_URL').$path;

    }

    public function getSexValueAttribute()
    {
        if($this->sex == 1){
            return '男';
        }else{
            return '女';
        }
    }


    public function register($data)
    {
        $data['password'] = password_hash($data['password'],PASSWORD_DEFAULT);

        $result = $this->create($data);
        return $result;
    }

    public function login($data)
    {
        $user = $this->with(['classes.major'])->where('stu_number', $data['stu_number'])->first();
        if($user){
            if(password_verify($data['password'], $user->password)){
                return $user;
            }else{
                return -1;
            }
        }else{
            return 0; //没有这个人
        }
    }


    public function authentication($data)
    {
        $user = $this->where('stu_number', $data['stu_number'])->first();
        if($user){
            if(password_verify($data['identity_card'], $user->identity_card)){
                return $user;
            }else{
                return -1;
            }
        }else{
            return -1;
        }

    }

    public function selectData(Department $department)
    {
        $department_result = $department->where('type',1)->get();
        $result = [];
        foreach ($department_result as $value){
            $department_arr = [];
            $department_arr['value'] = $value->id;
            $department_arr['label'] = $value->department_name;
            $department_arr['children'] = [];
            foreach ($value->major as $value){
                $major_arr = [];
                $major_arr['value'] = $value->id;
                $major_arr['label'] = $value->major_name;
                $major_arr['children'] = [];
                foreach ($value->classes as $value){
                    $class_arr = [];
                    $class_arr['value'] = $value->id;
                    $class_arr['label'] = $value->cls_name;
                    $major_arr['children'][] = $class_arr;
                }
                $department_arr['children'][] = $major_arr;
            }
            $result[] = $department_arr;
        }

        return $result;
    }

}
