<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Auth\TokenController;
use App\Http\Requests\Student\ResetPassRequest;
use App\Http\Requests\Teacher\SendMailRequest;
use App\Http\Requests\Student\UpdateHeadImgRequest;
use App\Http\Requests\Student\ValidateCodeRequest;
use App\Http\Requests\Teacher\AssociationUpdateRequest;
use App\Http\Requests\Teacher\ChangePowerRequest;
use App\Http\Requests\Teacher\ExpelTeacherRequest;
use App\Http\Requests\Teacher\LoginRequest;
use App\Http\Requests\Teacher\RegisterRequest;
use App\Http\Requests\Teacher\StoreAssociationRequest;
use App\Http\Requests\teacher\UpdateCorporateSlogan;
use App\Http\Requests\teacher\UpdateCorporateSloganRequest;
use App\Http\Requests\Teacher\UpdateHeadImageRequest;
use App\Http\Requests\Teacher\UpdateIntroduceRequest;
use App\Http\Requests\Teacher\UpdateLearnRequest;
use App\Http\Requests\Teacher\whetherAllowTeacherRequest;
use App\Jobs\SendMail;
use App\Models\Association;
use App\Models\Department;
use App\Models\Student;
use App\Models\System;
use App\models\Teacher;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\DocBlock\Description;

class IndexController
{
    use BaseTrait;

    public function index(Association $association)
    {
        $data = $association->allAssociation();

        return $this->result($data,1,'success',200);
    }

    public function associationUpdateBaseInfo(AssociationUpdateRequest $request, Association $association)
    {
        $data = $request->all();

        $association_id = $request->input('association_id');

        $ass_result = $association->where('id', $association_id)->first(['id', 'ass_name','images']);
        if($ass_result){
            $path = $this->upload($request,'images','association/head_image');
            if ($path) {
                $disk = Storage::disk('public');
                $disk->delete($ass_result['images']);
                $ass_result->images = $path;
            }
            $ass_result->ass_name = $data['ass_name'];
            $ass_result->english_name = $data['english_name'];
            $result = $ass_result->save();

            if($result){
                return $this->result('',1,'修改成功',200);
            }else{
                return $this->result('',0,'修改失败',200);
            }
        }else{
            return $this->result('',0,'error',200);
        }
    }

    public function associationUpdateLearn(UpdateLearnRequest $request, Association $association)
    {
        $association_id = $request->input('association_id');
        $learning_objectives = $request->input('learning_objectives');

        $objectives_result = $association::find($association_id);
        $objectives_result->learning_objectives = $learning_objectives;
        $result = $objectives_result->save();

        if($result){
            return $this->result('',1,'修改成功',200);
        }else{
            return $this->result('',0,'修改失败',200);
        }

    }

    public function associationUpdateIntroduce(UpdateIntroduceRequest $request, Association $association)
    {
        $association_id = $request->input('association_id');
        $introduce = $request->input('introduce');

        $objectives_result = $association::find($association_id);
        $objectives_result->introduce = $introduce;
        $result = $objectives_result->save();

        if($result){
            return $this->result('',1,'修改成功',200);
        }else{
            return $this->result('',0,'修改失败',200);
        }
    }

    public function associationUpdateCorporateSlogan(UpdateCorporateSloganRequest $request, Association $association)
    {
        $association_id = $request->input('association_id');
        $corporate_slogan = $request->input('corporate_slogan');

        $objectives_result = $association::find($association_id);
        $objectives_result->corporate_slogan = $corporate_slogan;
        $result = $objectives_result->save();

        if($result){
            return $this->result('',1,'修改成功',200);
        }else{
            return $this->result('',0,'修改失败',200);
        }
    }


    public function login(LoginRequest $request, Teacher $teacher)
    {
        $tea_name = $request->input('teacher_name');
        $password = $request->input('password');
        $result = $teacher->where('te_name', $tea_name)->first();

        if( $result ){
            if(password_verify($password, $result->password)){
                $user = collect($result)->only(['id'])->toArray();
                $user['token_rule'] = 'teacher';

                $token = TokenController::getToken($user);
                $data['token'] = $token;

                return $this->result($data,1,'登录成功',200);
            }else{
                return $this->result('',0,'登录失败 账号密码错误',200);
            }

        } else {
            return $this->result('',0,'登录失败 没有此用户',200);
        }
    }

    public function teacherInfo(Request $request, Teacher $teacher)
    {
        $token = $request->get('token_data');

        $teacher_result = $teacher::find($token->id,['id','te_name','head_image']);
        $teacher_result['user_type'] = '老师用户';

        return $this->result($teacher_result,1,'success',200);

    }

    //没做表单验证 一定要验证teacher_id的值不能是数组
    public function storeAssociation(StoreAssociationRequest $request, Association $association, System $system)
    {
        $association_is_on = $system->first(['association_store_on'])->association_store_on;

        $data = $request->all();

        if($association_is_on == 0){
            return response()->json(['data' => '', 'code' => 0, 'message' => '功能已关闭，详情请联系管理员', 'status' => 200]);
        }

        $data['teacher_id'] = $request->get('token_data')->id;
        if($request->hasFile('images')){
            $data['images'] = $request->file('images');
        }else{
            $data['images'] = '';
        }

        $result = $association->addAssociation($data);
        if($result){
            return $this->result('',1,'添加成功',200);
        }else{
            return $this->result('',0,'添加失败',200);
        }
    }

    public function ApplyManageAssociation(Request $request, Association $association)
    {
        $association_id = $request->input('association_id');

        $teacher_id = $request->get('token_data')->id; // 1 1

        $association_result = $association::find($association_id);

        $cc = $association->associationHasTeacher($association_id, $teacher_id, 1);
        $aa = $association->associationHasTeacher($association_id, $teacher_id, 0);

        if($cc){
            return $this->result('',0,'你已经管理此社团',200);
        }elseif ($aa){
            return $this->result('',0,'不得重复申请',200);
        }

        $association_result->teacher()->attach($teacher_id,['status' => 0, 'is_admin' => 0]);
        return $this->result('',1,'申请成功，等待审核',200);
    }

    public function showTeacherApply(Request $request, Association $association)
    {
        $association_id = $request->input('association_id');

        $result = $association->allAssociationTeacher($association_id, 0);

       return $this->result($result,1,'success',200);
    }

    public function whetherAllowTeacher(WhetherAllowTeacherRequest $request, Association $association)
    {
        $association_id = $request->input('association_id');
        $teacher_id = $request->input('teacher_id');
        $status = $request->input('status');

        $association::find($association_id)->teacher()->updateExistingPivot($teacher_id, ['status' => $status]);

        return $this->result('',1,'success',200);
    }

    public function isAdmin(Request $request, Association $association)
    {
        $association_id = $request->input('association_id');

        $teacher_id = $request->get('token_data')->id;

        $result = $association::with(['teacher' => function($query) use ($teacher_id){
            $query->where('teachers.id', $teacher_id);
        }])->where('associations.id', $association_id)->first()->teacher->toArray();


        if($result[0]['pivot']['is_admin'] == 1){
            return $this->result('',1,'是管理员',200);
        }else{
            return $this->result('',0,'不是管理员',200);
        }
    }

    //还有一个确认密码，表单验证的时候验证
    public function register(RegisterRequest $request, Teacher $teacher, System $system)
    {
        $teacher_register_on = $system::first(['teacher_register_on'])->teacher_register_on;

        if($teacher_register_on == 0){
            return response()->json(['data' => '', 'code' => 0, 'message' => '功能已关闭，详情请联系管理员', 'status' => 200]);
        }

        $teacher_name = $request->input('teacher_name');
        $password = $request->input('password');
        $email = $request->input('email');

        $result = $teacher->insert([
            'te_name' => $teacher_name,
            'password' => password_hash($password,PASSWORD_DEFAULT),
            'email' => $email,
        ]);

        if($result){

            return $this->result('',1,'success',200);
        }else{
            return $this->result('',0,'error',200);
        }
    }

    //还有一个确认密码，表单验证的时候验证
    public function updatePassword(Request $request, Teacher $teacher)
    {
        $teacher_id = $request->get('token_data')->id;
        $old_password = $request->input('old_password');
        $new_password = $request->input('new_password');


        $teacher_result = $teacher::find($teacher_id);
        if(password_verify($old_password, $teacher_result->password)){
            $teacher_result->password = password_hash($new_password,PASSWORD_DEFAULT);
            $teacher_result->save();
            return $this->result('',1,'修改成功',200);
        }else{
            return $this->result('',0,'修改失败，旧密码错误',200);
        }
    }


    public function updateHeadImage(UpdateHeadImageRequest $request, Teacher $teacher)
    {

        $teacher_id = $request->get('token_data')->id;
        $teacher_result = $teacher::find($teacher_id);
        $head_image = $teacher_result->head_image;


        $path = $this->upload($request,'head_image','user/head_image');
        if($path){
            $teacher_result->head_image = $path;
            $teacher_result->save();

            if($head_image){
                Storage::disk('public')->delete($head_image);
            }
            return $this->result('',1,'修改成功',200);
        }else{
            return $this->result('',0,'修改失败',200);
        }
    }


    public function myJoinAssociation(Request $request, Association $association)
    {
        $id = $request->get('token_data')->id;

        $result = $association->whereHas('teacher',  function($query) use ($id){
            $teachers = \DB::table('teacher_association')->where('status',1)->where('teacher_id', $id)->get(['teacher_id'])->toArray();
            $teachers_id = array_column($teachers,'teacher_id');
            return $query->whereIn('teachers.id',$teachers_id);
        })->with(['teacher:teachers.id, te_name'])->select('id', 'ass_name', 'number_people', 'introduce', 'images')->get();

        $result->each->setAppends([]);

        $result->each(function ($item){
            $item->teacher->each(function ($item){
                unset($item->pivot);
            });
        });

        return $this->result($result,1,'success',200);
    }

   public function show(Request $request, Association $association)
   {
       $association_id = $request->input('association_id');
       $id = $request->get('token_data')->id;

       $result = $association->onceAssociation($association_id);

       $a = \DB::table('teacher_association')->where('status',1)->where('association_id', $association_id)->where('teacher_id', $id)->first();

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



    public function associationCreate(Department $department)
    {
        $result = $department->DepartmentSelectValue();

        return $this->result($result,1,'success',200);
    }


    public function teacherManageIndex(Request $request, Association $association)
    {
        $association_id = $request->input('association_id');

        $result = $association->allAssociationTeacher($association_id,1);

        return $this->result($result,1,'success',200);
    }

    public function changePower(ChangePowerRequest $request, Teacher $teacher)
    {
        $teacher_id = $request->input('teacher_id');
        $association_id = $request->input('association_id');
        $my_teacher_id = $request->get('token_data')->id;

        DB::beginTransaction();
        try{
            $teacher::find($teacher_id)->association()->updateExistingPivot($association_id, ['is_admin' => 1]);
            $teacher::find($my_teacher_id)->association()->updateExistingPivot($association_id, ['is_admin' => 0]);
            DB::commit();

            return $this->result('',1,'设置成功',200);
        }catch (\Exception $exception){
            DB::rollBack();

            return $this->result('',0,'设置失败',200);
        }
    }

    public function expelTeacher(ExpelTeacherRequest $request, Teacher $teacher)
    {
        $teacher_id = $request->input('teacher_id');
        $association_id = $request->input('association_id');

        $teacher::find($teacher_id)->association()->detach($association_id);

        return $this->result('',1,'移除成功',200);
    }

    public function associationDetails(Request $request, Association $association)
    {
        $association_id = $request->input('association_id');

        $result = $association::find($association_id, ['id', 'ass_name', 'english_name', 'images','introduce', 'corporate_slogan', 'learning_objectives']);
        $result->setAppends([]);
        return $this->result($result,1,'success','200');
    }

    public function forgetIndex(SendMailRequest $request, Teacher $teacher)
    {
        $teacher_name = $request->input('teacher_name');
        $email = $teacher::where('te_name', $teacher_name)->first(['id','email'])->email;

        if($email){
            $token = TokenController::getToken(['teacher_name' => $teacher_name, 'token_rule' => 'teacher']);

            $result['token'] = $token;
            $result['email'] = $email;
            return $this->result($result,1,'success',200);
        }
    }

    public function sendMail(Request $request, Teacher $teacher)
    {
        $teacher_name = $request->get('token_data')->teacher_name;
        $email = $teacher::where('te_name', $teacher_name)->first(['email'])->email;
        $key = 'teacher_'.$teacher_name;

        $code = random_int(100000,999999);
        Redis::set($key, $code, 'ex', 300); //300秒过期

        $data['user_name'] = $teacher_name;
        $data['email'] = $email;
        $data['code'] = $code;

        SendMail::dispatch($data)->onQueue('email');

        return $this->result('',1,'发送成功',200);
    }

    public function validateCode(ValidateCodeRequest $request)
    {
        $teacher_name = $request->get('token_data')->teacher_name;
        $user_code = $request->input('code');
        $key = 'teacher_'.$teacher_name;

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

    public function resetPass(ResetPassRequest $request, Teacher $teacher)
    {
        $password = $request->input('password');
        $teacher_name = $request->get('token_data')->teacher_name;

        $result = $teacher::where('te_name', $teacher_name)->first();
        $result->password = password_hash($password,PASSWORD_DEFAULT);
        $c = $result->save();

        if($c){
            return $this->result('',1,'重置成功',200);
        }else{
            return $this->result('',0,'重置失败',200);
        }
    }

    public function allDepartment(Department $department)
    {
        $data = $department::where('type', 1)->get(['id','department_name']);

        $data->each(function ($item){
            $item->setAppends([]);
            $item['value'] = $item['id'];
            $item['label'] = $item['department_name'];
            unset($item['id']);
            unset($item['department_name']);
            return $item;
        });

        return $this->result($data,1,'success',200);
    }

}
