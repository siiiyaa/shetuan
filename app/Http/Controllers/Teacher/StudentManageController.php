<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Requests\Teacher\AttendanceStoreRequest;
use App\Http\Requests\Teacher\AttendEditRequest;
use App\Http\Requests\Teacher\RemoveStudentRequest;
use App\Http\Requests\Teacher\WhetherToPassRequest;
use App\Models\Association;
use App\Models\Attendance;
use App\Models\StudentAssociation;
use App\Traits\BaseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentManageController extends Controller
{
    use BaseTrait;



    public function studentIndex(Request $request, Association $association)
    {
        $status = $request->input('status',0);
        $association_id = $request->input('association_id');

        if(!$association_id){
            return response()->json(['data' => ['association_id'=>['参数不完整']], 'code' => 422, 'message' => 'error', 'status' => 200]);
        }

        $result = $association->with(['student' => function ($query) use ($status, $association_id){
            $student = DB::table('student_association')->where('status', $status)->where('association_id', $association_id)->get(['student_id'])->toArray();
            $student_ids = array_column($student,'student_id');
            $query->select('students.id','students.stu_number','students.stu_name','sex')->whereIn('students.id',$student_ids);
        }])->where('id',$association_id)->first();


        if($result){
            return $this->result($result->student,1,'success',200);
        }else{
            return $this->result('',1,'success',200);
        }
    }

    //api


    public function whetherToPass(WhetherToPassRequest $request, StudentAssociation $studentAssociation, Association $association)
    {
        $pass = $request->input('pass');
        $student_id = $request->input('student_id');
        $association_id = $request->input('association_id');

        if($pass == -1){
            $pass = 2; //拒绝
        }

        $result = $studentAssociation->where('student_id', $student_id)->where('association_id', $association_id)->first();

        if($result){
            if($pass == 1){
                $association_result = $association::find($association_id);
                $association_result->number_people += 1;
                $association_result->save();
            }
            $result->status = $pass;
            $x = $result->save();
            if($x){
                return $this->result('',1,'success',200);
            }else{
                return $this->result('',0,'error',200);
            }
        }else{
            return $this->result('',0,'error',200);

        }

    }

    public function destroy(RemoveStudentRequest $request, Association $association)
    {
        $student_id = $request->input('student_id');
        $association_id = $request->input('association_id');

        $association_result = $association::find($association_id);
        if($association_result){
            $association_result->student()->detach($student_id);
            return $this->result('',1,'移除成功',200);
        }else{
            return $this->result('',0,'移除失败',200);
        }


    }

    public function searchStudent(Request $request, Association $association)
    {
        $name = $request->input('name'); //不能为空
        $association_id = $request->input('association_id');

        if(!$association_id){
            return response()->json(['data' => ['association_id'=>['参数不完整']], 'code' => 422, 'message' => 'error', 'status' => 200]);
        }

        $result = $association->with(['student' => function ($query) use ($name){
            $student = DB::table('student_association')->where('status', 1)->get(['student_id'])->toArray();
            $student_ids = array_column($student,'student_id');
            if($name){
                $query->where('students.stu_name','like','%'.$name.'%');
            }

            return $query->select('students.id','students.stu_number','students.stu_name')->whereIn('students.id',$student_ids);
        }])->where('id',$association_id)->first();

        if($result){
            return $this->result($result->student,1,'success',200);
        }else{
            return $this->result('',1,'success',200);
        }
    }

    public function attendanceIndex(Request $request, Association $association)
    {
        $association_id = $request->input('association_id');

        if(!$association_id){
            return response()->json(['data' => ['association_id'=>['参数不完整']], 'code' => 422, 'message' => 'error', 'status' => 200]);
        }

        $result = $association->assocStudents($association_id);

        if($result){
            return $this->result($result->student,1,'success',200);
        }else{
            return $this->result('',1,'success',200);
        }
    }



    public function attendanceStore(AttendanceStoreRequest $request, Attendance $attendance)
    {

        $time = $request->input('time');
        $student_ids = $request->input('student_ids');
        $assoc_id = $request->input('association_id');

        $attendance->time = $time;
        $attendance->assoc_id = $assoc_id;
        $result = $attendance->save();
        if($result){
            $attendance->student()->attach($student_ids);

            return $this->result('',1,'保存成功',200);
        }else{
            return $this->result('',0,'保存失败',200);
        }



    }

    public function attendanceShow(Request $request, Attendance $attendance, Association $association)
    {
        $association_id = $request->input('association_id');

        $result = $attendance->withCount(['student as arrived'])->where('assoc_id', $association_id)->get(['id','time'])->toArray();

        $data = [];

        if($result){
            $all_stuent = $association->assocStudents($association_id);
            $all_student_number = collect($all_stuent['student'])->count();

            foreach ($result as $key => $value){
                $result[$key]['no_arrived'] = $all_student_number - $value['arrived'];
            }
            $data['tableData'] = $result;
            $data['all_student_number'] = collect($all_stuent['student'])->count();

            return $this->result($data,1,'success',200);
        }else{
            return $this->result([],1,'success',200);
        }

    }

    public function attendEdit(AttendEditRequest $request, Association $association, Attendance $attendance)
    {
        $association_id = $request->input('association_id');
        $attendance_id = $request->input('attendance_id');

        $all_stuent = $association->assocStudents($association_id);
        $all_stuent = collect($all_stuent['student'])->toArray();

        $attendance_result = $attendance::find($attendance_id)->student;
        $student_id_array = array_column(collect($attendance_result)->toArray(),'id');

        $data['all_student'] = $all_stuent;
        $data['student_ids'] = $student_id_array;

        return $this->result($data,1,'success',200);

    }

    public function attendUpdate(Request $request, Attendance $attendance)
    {
        $student_ids = $request->input('student_ids');
        $attendance_id = $request->input('attendance_id');

        $attendance_result = $attendance::find($attendance_id);
        $attendance_result->student()->sync($student_ids);

        return $this->result('',1,'保存成功',200);
    }















}
