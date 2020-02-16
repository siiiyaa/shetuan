<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\TeacherShowRequest;
use App\models\Teacher;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TeacherController extends Controller
{
    use BaseTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Teacher $teacher)
    {
        $result = $teacher::select('id','te_name')->paginate(2);

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

    public function allTeachers(Teacher $teacher)
    {
        $result = $teacher::all(['id', 'te_name']);

        return $this->result($result,1,'success',200);
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
    public function show(TeacherShowRequest $request, Teacher $teacher)
    {
        $search_name = $request->input('search_name');

        $result = $teacher->where('te_name','like','%'.$search_name.'%')->select('id','te_name')->paginate(2);

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
    public function destroy(Request $request, Teacher $teacher)
    {
        $teacher_id = $request->input('teacher_id');
        if($teacher_id){
            $result = $teacher::where('id', $teacher_id)->delete();
            if($result){
                return $this->result('',1,'删除成功',200);
            }else{
                return $this->result('',0,'删除失败',200);
            }
        }
    }
}
