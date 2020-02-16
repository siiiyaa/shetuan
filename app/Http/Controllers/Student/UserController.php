<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Auth\TokenController;
use App\Http\Requests\Student\LoginRequest;
use App\Http\Requests\Student\RegisterRequest;
use App\Http\Requests\Student\ResetPassRequest;
use App\Http\Requests\Student\SendMailRequest;
use App\Http\Requests\Student\StudentBaseInfoRequest;
use App\Http\Requests\Student\UpdateHeadImgRequest;
use App\Http\Requests\Student\UpdatePasswordRequest;
use App\Http\Requests\Student\ValidateCodeRequest;
use App\Jobs\SendMail;
use App\Models\Association;
use App\Models\Department;
use App\Models\Student;
use App\Http\Controllers\Controller;
use App\Models\System;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use BaseTrait;



    public function registerIndex(Department $department, Student $student)
    {

        $result = $student->selectData($department);
        return $this->result($result,1,'success',200);
    }

    public function register(RegisterRequest $request, Student $student, System $system)
    {
        $student_register_on = $system::first(['student_register_on'])->student_register_on;

        if($student_register_on == 0){
            return response()->json(['data' => '', 'code' => 0, 'message' => '功能已关闭，详情请联系管理员', 'status' => 200]);
        }

        $data = $request->all();
        $result = $student->register($data);
        if(!$result){
            return $this->result('',0,'注册失败',200);
        }else{
            return $this->result('',1,'注册成功',200);
        }
    }

    public function login(LoginRequest $request, Student $student)
    {
        $data = $request->all();
        $result = $student->login($data);
        if($result === -1){
            return $this->result('',0,'账号密码错误', 200);
        }elseif($result === 0){
            return $this->result('',0,'不存在此用户', 200);
        }else{
            $user = collect($result)->only(['id'])->toArray();
            $user['token_rule'] = 'student';
            $token = TokenController::getToken($user);

            $datas['token'] = $token;

            return $this->result($datas,1,'登录成功',200);
        }
    }

    public function studentInfo(Request $request, Student $student)
    {
        $stu_id = $request->get('token_data')->id;

        $result = $student->with(['classes.major'])->where('id', $stu_id)->first();
        $user = collect($result)->only(['id','stu_name','head_image','sex','classes','experience','stu_number'])->toArray();
        $user['major_name'] = $user['classes']['major']['major_name'];
        $user = collect($user)->except('classes')->toArray();
        $user['user_type']  = '学生用户';

        return $this->result($user,1,'登录成功',200);
    }


    public function updateBaseInfo(StudentBaseInfoRequest $request, Student $student)
    {
        $stu_name = $request->input('stu_name');
        $sex = $request->input('sex');
        $id = $request->get('token_data')->id;

        $student_result = $student::find($id);
        $student_result->stu_name = $stu_name;
        $student_result->sex = $sex;
        $result = $student_result->save();

        if($result){
            return $this->result('',1,'修改成功',200);
        }else{
            return $this->result('',0,'修改失败',200);
        }

    }


    //验证字段没做
    public function updatePassword(UpdatePasswordRequest $request, Student $student)
    {
        $old_password = $request->input('old_password');
        $new_password = $request->input('password');
        $id = $request->get('token_data')->id;


        $student_result = $student::find($id);
        if(password_verify($old_password,$student_result['password'])){
            $student_result->password = password_hash($new_password,PASSWORD_DEFAULT);
            $result = $student_result->save();
            if($result){
                return $this->result('',1,'修改成功',200);
            }else{
                return $this->result('',0,'修改失败',200);
            }

        }else{
            return response()->json([
                'data' => ['old_password' => ['旧密码错误']],
                'code' => 422,
                'message' => 'error'
            ],422);
        }

    }

    public function updateHeadImg(UpdateHeadImgRequest $request, Student $student)
    {
        $stu_id = $request->get('token_data')->id;
        $field = $request->file('field');

        if($request->hasFile('field') && $field->isValid()){
            $result = $student::find($stu_id);

            if($result->head_image){
                Storage::delete($result->head_image);
            }

            $txt = $field->getClientOriginalExtension();
            $path = $field->storeAs('user/head_image',time().mt_rand(999,9999).'.'.$txt, 'public');
            $result->head_image = $path;
            $cs = $result->save();
            if($cs){
                return $this->result('',1,'修改成功',200);
            }else{
                return $this->result('',0,'修改失败',200);
            }

        }else{
            return $this->result('',2,'修改失败，没有图片或图片错误',200);
        }
    }

    public function showRecord(Request $request, Student $student)
    {
        $stu_id = $request->get('token_data')->id;

        $result = $student::find($stu_id)->with(['association:associations.id,ass_name,english_name,images'])->first()->association->toArray();

        $result = collect($result)->except('pivot')->toArray();
        $result = array_map(function ($item){
            $item['created_at'] = $item['pivot']['created_at'];
            unset($item['pivot']);
            return $item;
        },$result);

        return $this->result($result,1,'success',200);
    }


    public function searchAssociation(Request $request, Association $association)
    {
        $name = $request->input('association_name');
        if(!$name){
            return response()->json(['data' => ['association_name'=>['参数不完整']], 'code' => 422, 'message' => 'error', 'status' => 200]);
        }
        $result = $association->where('ass_name','like','%'.$name.'%')->get(['id','ass_name','images'])->toArray();

        $result = array_map(function ($item){
            unset($item['status_value']);
            unset($item['distance_time']);
            return $item;
        }, $result);
        return $this->result($result,1,'success',200);

    }

    public function forgetIndex(SendMailRequest $request, Student $student)
    {
        $stu_number = $request->input('stu_number');
        $email = $student::where('stu_number', $stu_number)->first(['id','email'])->email;
        if($email){
            $token = TokenController::getToken(['stu_number' => $stu_number,'token_rule' => 'student']);

            $result['token'] = $token;
            $result['email'] = $email;
            return $this->result($result,1,'success',200);
        }
    }

    public function sendMail(Request $request, Student $student)
    {
        $stu_number = $request->get('token_data')->stu_number;
        $email = $student::where('stu_number', $stu_number)->first(['email'])->email;
        $key = 'student_'.$stu_number;

        $code = random_int(100000,999999);
        Redis::set($key, $code, 'ex', 300); //300秒过期

        $data['user_name'] = $stu_number;
        $data['email'] = $email;
        $data['code'] = $code;

        SendMail::dispatch($data)->onQueue('email');

        return $this->result('',1,'发送成功',200);
    }

    public function validateCode(ValidateCodeRequest $request)
    {
        $stu_number = $request->get('token_data')->stu_number;
        $user_code = $request->input('code');
        $key = 'student_'.$stu_number;

        $code = Redis::get($key);
        if($code){
            if($user_code == $code){
                Redis::del($key);
                return $this->result('',1,'验证成功',200);
            }else{
                return $this->result('',0,'验证失败',200);
            }
        }
    }

    public function resetPass(ResetPassRequest $request, Student $student)
    {
        $password = $request->input('password');
        $stu_number = $request->get('token_data')->stu_number;

        $result = $student::where('stu_number', $stu_number)->first();
        $result->password = password_hash($password,PASSWORD_DEFAULT);
        $c = $result->save();

        if($c){
            return $this->result('',1,'重置成功',200);
        }else{
            return $this->result('',0,'重置失败',200);
        }
    }


}
