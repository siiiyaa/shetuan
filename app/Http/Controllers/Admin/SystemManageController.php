<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SystemDefaultImageRequest;
use App\Http\Requests\Admin\SystemInterfaceSetupRequest;
use App\Models\System;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SystemManageController extends Controller
{
    use BaseTrait;


    public function index(System $system)
    {

        $result = $system::find(1);

        $data['avatarGroup'] = $this->avatarGroup($result);
        $data['authority'] = $this->authority($result);

        return $this->result($data,1,'success',200);
    }

    protected function avatarGroup($result)
    {
        $avatarGroup = [];
        $avatarGroup[] = $this->studentDefaultImageArr($result);
        $avatarGroup[] = $this->teacherDefaultImageArr($result);
        $avatarGroup[] = $this->associationDefaultImage($result);

        return $avatarGroup;
    }

    protected function authority($result)
    {
        $authority = [];
        $authority[] = $this->isAssociationStoreOn($result);
        $authority[] = $this->isTeacherRegisterOn($result);
        $authority[] = $this->isStudentRegisterOn($result);

        return $authority;
    }

    protected function studentDefaultImageArr($result)
    {
        $student_default_image_arr = [];
        $student_default_image_arr['desciption'] = '学生默认头像';
        $student_default_image_arr['imgSrc'] =  $result->student_default_images;
        $student_default_image_arr['submitLink'] =  route('admin_editStudentDefaultImages');

        return $student_default_image_arr;
    }

    protected function teacherDefaultImageArr($result)
    {
        $teacher_default_image_arr = [];
        $teacher_default_image_arr['desciption'] = '老师默认头像';
        $teacher_default_image_arr['imgSrc'] =  $result->teacher_default_images;
        $teacher_default_image_arr['submitLink'] =  route('admin_editTeacherDefaultImages');

        return $teacher_default_image_arr;
    }

    protected function associationDefaultImage($result)
    {
        $association_default_image_arr = [];
        $association_default_image_arr['desciption'] = '社团默认头像';
        $association_default_image_arr['imgSrc'] =  $result->association_default_images;
        $association_default_image_arr['submitLink'] =  route('admin_editAssociationDefaultImages');

        return $association_default_image_arr;
    }

    protected function activityDefaultImage($result)
    {
        $activity_default_image_arr = [];
        $activity_default_image_arr['desciption'] = '活动默认头像';
        $activity_default_image_arr['imgSrc'] =  $result->activity_default_images;
        $activity_default_image_arr['submitLink'] =  route('admin_editActivityDefaultImages');

        return $activity_default_image_arr;
    }

    protected function isAssociationStoreOn($result)
    {
        $associationStoreOn = [];
        $associationStoreOn['desciption'] = '新增社团接口';
        $associationStoreOn['initstate'] = $result->association_store_on;
        $associationStoreOn['modifyPort'] = route('admin_editAssociationStore');

        return $associationStoreOn;
    }

    protected function isTeacherRegisterOn($result)
    {
        $teacherStoreOn = [];
        $teacherStoreOn['desciption'] = '老师注册接口';
        $teacherStoreOn['initstate'] = $result->teacher_register_on;
        $teacherStoreOn['modifyPort'] = route('admin_editTeacherRegister');

        return $teacherStoreOn;
    }

    protected function isStudentRegisterOn($result)
    {
        $studentRegister = [];
        $studentRegister['desciption'] = '学生注册接口';
        $studentRegister['initstate'] = $result->student_register_on;
        $studentRegister['modifyPort'] = route('admin_editStudentRegister');

        return $studentRegister;
    }

    public function editStudentDefaultImages(SystemDefaultImageRequest $request, System $system)
    {
        $result = $system::first(['student_default_images']);
        Storage::disk('public')->delete($result[0]);

        if($request->hasFile('default_images')) {
            $path = $this->upload($request, 'default_images', 'user/default', '学生');
        }elseif ($request->input('default_images') && is_string($request)){
            $path = $this->uploadBase($request->input('default_images'),'user/default','学生');
        }

        if($path){
            $result->student_default_images = $path;
            $c = $result->save();
            if($c){
                return $this->result('',1,'修改成功',200);
            }else{
                return $this->result('',0,'修改失败',200);
            }
        }else{
            return $this->result('',0,'修改失败',200);
        }
    }

    public function editTeacherDefaultImages(SystemDefaultImageRequest $request, System $system)
    {
        $result = $system::first(['teacher_default_images']);
        Storage::disk('public')->delete($result[0]);

        if($request->hasFile('default_images')){
            $path = $this->upload($request,'default_images','user/default','老师');
        }elseif ($request->input('default_images') && is_string($request)){
            $path = $this->uploadBase($request->input('default_images'),'user/default','老师');
        }

        if($path){
            $result->teacher_default_images = $path;
            $c = $result->save();
            if($c){
                return $this->result('',1,'修改成功',200);
            }else{
                return $this->result('',0,'修改失败',200);
            }
        }else{
            return $this->result('',0,'修改失败',200);
        }
    }

    public function editAssociationDefaultImages(SystemDefaultImageRequest $request, System $system)
    {
        $result = $system::first(['association_default_images']);
        Storage::disk('public')->delete($result[0]);

        if($request->hasFile('default_images')){
            $path = $this->upload($request,'default_images','association/default','社团');

        }elseif ($request->input('default_images') && is_string($request)){
            $path = $this->uploadBase($request->input('default_images'),'association/default','社团');
        }

        if($path){
            $result->association_default_images = $path;
            $c = $result->save();
            if($c){
                return $this->result('',1,'修改成功',200);
            }else{
                return $this->result('',0,'修改失败',200);
            }
        }else{
            return $this->result('',0,'修改失败',200);
        }
    }

    public function editActivityDefaultImages(SystemDefaultImageRequest $request, System $system)
    {
        $result = $system::first(['activity_default_images']);
        Storage::disk('public')->delete($result[0]);
        if($request->hasFile('default_images')){
            $path = $this->upload($request,'default_images','activity/default','活动');
        }elseif ($request->input('default_images') && is_string($request)){
            $path = $this->uploadBase($request->input('default_images'),'activity/default','活动');
        }

        if($path){
            $result->activity_default_images = $path;
            $c = $result->save();
            if($c){
                return $this->result('',1,'修改成功',200);
            }else{
                return $this->result('',0,'修改失败',200);
            }
        }else{
            return $this->result('',0,'修改失败',200);
        }
    }

    public function editAssociationStore(SystemInterfaceSetupRequest $request, System $system)
    {
        $value = $request->input('value');

        $result = $system->first();
        $result->association_store_on = $value;
        $c = $result->save();
        if($c){
            return $this->result('',1,'修改成功',200);
        }else{
            return $this->result('',0,'修改失败',200);

        }

    }

    public function editTeacherRegister(SystemInterfaceSetupRequest $request, System $system)
    {
        $value = $request->input('value');
        $result = $system->first();
        $result->teacher_register_on = $value;
        $c = $result->save();
        if($c){
            return $this->result('',1,'修改成功',200);
        }else{
            return $this->result('',0,'修改失败',200);

        }
    }

    public function editStudentRegister(SystemInterfaceSetupRequest $request, System $system)
    {
        $value = $request->input('value');
        $result = $system->first();
        $result->student_register_on = $value;
        $c = $result->save();
        if($c){
            return $this->result('',1,'修改成功',200);
        }else{
            return $this->result('',0,'修改失败',200);

        }
    }








}
