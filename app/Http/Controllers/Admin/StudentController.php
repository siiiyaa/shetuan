<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StudentDestroyRequest;
use App\Http\Requests\Admin\StudentScreenShowRequest;
use App\Models\Department;
use App\Models\Student;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentController extends Controller
{
    use BaseTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Student $student)
    {
        //加一个多选框选中的数据
        $class_id = $request->input('class_id');

        $query = $student->query();
        if($class_id){
            $query->where('class_id', $class_id);
        }

        $result = $query->with(['association:associations.id,ass_name'])->select('id','stu_name','sex','experience')->paginate(10)->appends(['class_id' => $class_id]);


        $result->getCollection()->transform(function ($item){
            $item->association->transform(function ($value){
               $value->setAppends([]);
               unset($value->pivot);
               return $value;
            });
            return $item;
        });

        return $this->result($result,1,'success',200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(StudentScreenShowRequest $request, Student $student)
    {
        $search_name = $request->input('search_name');
        $class_id = $request->input('class_id');

        $query = $student->query();
        if($class_id){
            $query->where('class_id', $class_id);
        }

        $result = $query->where('stu_name','like','%'.$search_name.'%')
            ->with(['association:associations.id,ass_name'])
            ->select('id','stu_name','sex','experience')
            ->paginate(10)->appends(['class_id' => $class_id]);

        foreach ($result as $value){
            unset($value->sex);
            foreach ($value['association'] as $value){
                unset($value->status_value);
                unset($value->distance_time);
                unset($value->pivot);
            }
        }

        return $this->result($result,1,'success',200);

    }



    public function screenData(Student $student, Department $department)
    {
        $result = $student->selectData($department);

        return $this->result($result,1,'success',200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentDestroyRequest $request, Student $student)
    {
        $student_id_arr = $request->input('student_id_arr');

        $result = $student->whereIn('id', $student_id_arr)->delete();

        if($result){
            return $this->result('',1,'删除成功',200);
        }else{
            return $this->result('',0,'删除失败',200);
        }
    }
}
