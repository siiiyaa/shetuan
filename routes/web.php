<?php
use Illuminate\Support\Facades\Storage;
use App\Traits\BaseTrait;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
//    $path = 'user/default/22.png';
//    $type = pathinfo($path,PATHINFO_EXTENSION);
//
//    $data = Storage::disk('public')->get($path);
//    $base64String = 'data:image/' . $type . ';base64,' . base64_encode($data);
//    $directoryName = 'user/default';
//    $fileName = '';
//    $base64 = substr(strstr($base64String,','),1);
//    $data = base64_decode($base64);
//
//
//    if(!$fileName){
//        $fileName = time().mt_rand(999, 9999);
//    }
//
//    preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64String, $result);
//
//    $path = $directoryName.'/'.$fileName.'.'.$result['2'];
//
//    $result = Storage::disk('public')->put($path, $data);
//    dd($path);
//    if($result){
//        return $path;
//    }


//    $string = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAeAB4AAD/4RDSRXhpZgAATU0AKgAAAAgABAE7AAIAAAAEWFhYAIdpAAQAAAABAAAISpydAAEAAAAIAAAQwuocAAcAAAgMAAAAPgAAAAAc6gAAAAgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA';


//    $result = \Illuminate\Support\Facades\Redis::hgetall('association_index');
//    $result = \Webpatser\Uuid\Uuid::generate()->string;

//    \Illuminate\Support\Facades\Redis::set('my_car', 1,'ex' , 60, 'nx');
//    \Illuminate\Support\Facades\Redis::expire('my_car',60);

//    $users = \App\Models\Student::get();
//    dd($users->load('classes')->toArray());

    $result = new ReflectionClass(\App\Models\Student::class);

    $data = '';
    $code = -1;
    $msg = '考试已关闭';

    $result = [
        'data' => $data,
        'code' => $code,
        'message'  => $msg,
        'status' => 200
    ];

    return response()->json($result);
    return view('welcome');


});



//超级管理员
Route::group(['namespace' => 'Admin','prefix' => 'admin', 'middleware' =>  ['cors', 'checkAjax']],function (){
    //登录
    Route::post('login','IndexController@login')->name('admin_login');
    //添加系
    Route::post('departmentStore','DepartmentController@store')->middleware('checkAdminToken')->name('admin_departmentStore');
    //删除系
    Route::delete('departmentDelete','DepartmentController@destroy')->middleware('checkAdminToken')->name('admin_departmentDelete');
    //修改系
    Route::patch('departmentUpdate','DepartmentController@update')->middleware('checkAdminToken')->name('admin_departmentUpdate');
    //所有系
    Route::get('departmentIndex','DepartmentController@index')->middleware('checkAdminToken')->name('admin_departmentIndex');
    //搜索系
    Route::get('departmentShow','DepartmentController@show')->middleware('checkAdminToken')->name('admin_departmentShow');
    //添加专业页面系数据
    Route::get('majorCreate','MajorController@create')->middleware('checkAdminToken')->name('admin_majorCreate');
    //添加专业
    Route::post('majorStore','MajorController@store')->middleware('checkAdminToken')->name('admin_majorStore');
    //删除专业
    Route::delete('majorDestroy','MajorController@destroy')->middleware('checkAdminToken')->name('admin_majorDestroy');
    //修改专业
    Route::patch('majorUpdate','MajorController@update')->middleware('checkAdminToken')->name('admin_majorUpdate');
    //所有专业
    Route::get('majorIndex','MajorController@index')->middleware('checkAdminToken')->name('admin_majorIndex');
    //搜索专业
    Route::get('majorShow','MajorController@show')->middleware('checkAdminToken')->name('admin_majorShow');
    //添加班级联动框数据
    Route::get('classCreate','ClassesController@create')->middleware('checkAdminToken')->name('admin_majorCreate');
    //添加班级
    Route::post('classStore','ClassesController@store')->middleware('checkAdminToken')->name('admin_classStore');
    //显示班级修改页面数据
    Route::get('classEdit','ClassesController@edit')->middleware('checkAdminToken')->name('admin_classEdit');
    //修改班级
    Route::patch('classUpdate','ClassesController@update')->middleware('checkAdminToken')->name('admin_classUpdate');
    //显示所有班级
    Route::get('classIndex','ClassesController@index')->middleware('checkAdminToken')->name('admin_classIndex');
    //显示筛选框数据
    Route::get('classScreenData','ClassesController@screenData')->middleware('checkAdminToken')->name('admin_classScreenData');
    //搜索班级
    Route::get('classShow','ClassesController@show')->middleware('checkAdminToken')->name('admin_classShow');
    //删除班级
    Route::delete('classDelete','ClassesController@destroy')->middleware('checkAdminToken')->name('admin_classDelete');
    //显示所有学生
    Route::get('studentIndex','StudentController@index')->middleware('checkAdminToken')->name('admin_studentIndex');
    //学生页面筛选框数据
    Route::get('screenData','StudentController@screenData')->middleware('checkAdminToken')->name('admin_screenData');
    //学生搜索
    Route::get('screenShow','StudentController@show')->middleware('checkAdminToken')->name('admin_screenShow');
    //删除学生
    Route::delete('studentDestroy','StudentController@destroy')->middleware('checkAdminToken')->name('admin_studentDestroy');
    //显示所有老师
    Route::get('teacherIndex','TeacherController@index')->middleware('checkAdminToken')->name('admin_teacherIndex');
    //搜索老师
    Route::get('teacherShow','TeacherController@show')->middleware('checkAdminToken')->name('admin_teacherShow');
    //删除老师
    Route::delete('teacherDelete','TeacherController@destroy')->middleware('checkAdminToken')->name('admin_teacherDelete');
    //添加社团页面系数据
    Route::get('associationCreate','AssociationManageController@create')->middleware('checkAdminToken')->name('admin_associationCreate');
    //添加社团
    Route::post('associationStore','AssociationManageController@store')->middleware('checkAdminToken')->name('admin_associationStore');
    //社团首页
    Route::get('associationIndex','AssociationManageController@index')->middleware('checkAdminToken')->name('admin_associationIndex');
    //社团详情
    Route::get('associationShow','AssociationManageController@show')->middleware('checkAdminToken')->name('admin_associationShow');
    //修改社团详情
    Route::post('associationUpdate','AssociationManageController@update')->middleware('checkAdminToken')->name('admin_associationUpdate');
    //删除社团
    Route::delete('associationDestroy','AssociationManageController@destroy')->middleware('checkAdminToken')->name('admin_associationDestroy');
    //社团搜索
    Route::get('associationSearch','AssociationManageController@searchAssociation')->middleware('checkAdminToken')->name('admin_associationSearch');
    //系统设置首页
    Route::get('systemIndex', 'SystemManageController@index')->middleware('checkAdminToken')->name('admin_systemIndex');
    //修改学生默认头像
    Route::post('editStudentDefaultImages', 'SystemManageController@editStudentDefaultImages')->middleware('checkAdminToken')->name('admin_editStudentDefaultImages');
    //修改老师默认头像
    Route::post('editTeacherDefaultImages', 'SystemManageController@editTeacherDefaultImages')->middleware('checkAdminToken')->name('admin_editTeacherDefaultImages');
    //修改社团默认头像
    Route::post('editAssociationDefaultImages', 'SystemManageController@editAssociationDefaultImages')->middleware('checkAdminToken')->name('admin_editAssociationDefaultImages');
    //修改活动默认头像
    Route::post('editActivityDefaultImages', 'SystemManageController@editActivityDefaultImages')->middleware('checkAdminToken')->name('admin_editActivityDefaultImages');
    //修改新增社团接口
    Route::post('editAssociationStore', 'SystemManageController@editAssociationStore')->middleware('checkAdminToken')->name('admin_editAssociationStore');
    //修改老师注册接口
    Route::post('editTeacherRegister', 'SystemManageController@editTeacherRegister')->middleware('checkAdminToken')->name('admin_editTeacherRegister');
    //修改学生注册接口
    Route::post('editStudentRegister', 'SystemManageController@editStudentRegister')->middleware('checkAdminToken')->name('admin_editStudentRegister');

    //显示所有老师（不是分页）
    Route::get('allTeachers', 'TeacherController@allTeachers')->middleware('checkAdminToken')->name('admin_allTeachers');

});

//学生
Route::group(['namespace' => 'Student', 'prefix' => 'student', 'middleware' =>  ['cors', 'checkAjax']], function (){
    //注册
    Route::post('register','UserController@register')->name('student_register');
    //登录
    Route::post('login','UserController@login')->name('student_login');
    //注册页面班级信息
    Route::get('register','UserController@registerIndex')->name('student_registerIndex');
    //忘记密码首页
    Route::post('forgetIndex', 'UserController@forgetIndex')->name('student_forgetIndex');
    //发送邮箱
    Route::post('sendMail', 'UserController@sendMail')->middleware('checkStudentToken')->name('student_sendMail');
    //验证验证码
    Route::post('validateCode', 'UserController@validateCode')->middleware('checkStudentToken')->name('student_sendMail');
    //重置密码
    Route::post('resetPass', 'UserController@resetPass')->middleware('checkStudentToken')->name('student_resetPass');

    //申请加入社团
    Route::post('addAssociation','IndexController@addAssociation')->middleware(['checkStudentToken'])->name('student_addAssociation');
    //显示所有社团
    Route::get('AssociationIndex', 'IndexController@index')->middleware('checkStudentToken')->name('student_associationIndex');
    //首页的特定社团
    Route::get('AssociationShow', 'IndexController@show')->middleware(['checkStudentToken'])->name('student_AssociationShow');
    //我加入的社团的特定社团
    Route::get('AssociationMyShow', 'IndexController@show')->middleware(['checkStudentToken','checkStudentAssociationBelongTo'])->name('student_AssociationMyShow');
    //显示我已加入的社团
    Route::post('myJoinAssociation', 'IndexController@myJoinAssociation')->middleware('checkStudentToken')->name('student_myJoinAssociation');
    //提交考试
    Route::post('submitAssessment', 'IndexController@submitAssessment')->middleware('checkStudentToken')->name('student_submitAssessment');
    //显示成绩排行
    Route::get('scoreIndex', 'IndexController@scoreIndex')->middleware('checkStudentToken')->name('student_scoreIndex');
    //显示所有成员
    Route::get('showAllStudent', 'IndexController@showAllStudent')->middleware(['checkStudentToken','checkStudentAssociationBelongTo'])->name('student_showAllStudent');
    //修改用户基本信息（姓名，性别）
    Route::patch('updateBaseInfo', 'UserController@updateBaseInfo')->middleware('checkStudentToken')->name('student_updateBaseInfo');
    //修改密码
    Route::patch('updatePassword', 'UserController@updatePassword')->middleware('checkStudentToken')->name('student_updatePassword');
    //修改头像
    Route::post('updateHeadImg', 'UserController@updateHeadImg')->middleware('checkStudentToken')->name('student_updateHeadImg');
    //显示申请记录
    Route::get('showRecord', 'UserController@showRecord')->middleware('checkStudentToken')->name('student_showRecord');
    //显示用户信息
    Route::get('studentInfo', 'UserController@studentInfo')->middleware('checkStudentToken')->name('student_studentInfo');
    //搜索社团
    Route::get('searchAssociation', 'UserController@searchAssociation')->middleware('checkStudentToken')->name('student_searchAssociation');

    //显示所有活动
    Route::get('activityIndex','IndexController@activityIndex')->middleware(['checkStudentToken','checkStudentAssociationBelongTo'])->name('student_activityIndex');

    //是否有前置学习视频
    Route::get('whetherHaveStudyAvi', 'IndexController@whetherHaveStudyAvi')->middleware('checkStudentToken')->name('student_whetherHaveStudyAvi');
    //点击开始考试
    Route::get('firstTopic', 'IndexController@firstTopic')->middleware('checkStudentToken')->name('student_startTest');
    //下一题
    Route::get('nextTopic', 'IndexController@nextTopic')->middleware('checkStudentToken')->name('student_nextTopic');
    //获取考试到期时间
    Route::get('assessmentEndTime', 'IndexController@assessmentEndTime')->middleware('checkStudentToken')->name('student_assessmentEndTime');
});


//老师
Route::group(['namespace' => 'Teacher', 'prefix' => 'teacher','middleware' =>   ['cors', 'checkAjax']], function (){
    //登录
    Route::post('login','IndexController@login')->name('teacher_login');
    //注册
    Route::post('register','IndexController@register')->name('teacher_login');
    //获取用户信息
    Route::get('teacherInfo','IndexController@teacherInfo')->middleware('checkTeacherToken')->name('teacher_login');
    //新增课程
    Route::post('addCourse','CourseManageController@courseStore')->middleware('checkTeacherToken')->name('teacher_addCourse');
    //新增章节
    Route::post('addChapter','CourseManageController@chapterStore')->middleware('checkTeacherToken')->name('teacher_addChapter');
    //新增小节
    Route::post('addSection','CourseManageController@sectionStore')->middleware('checkTeacherToken')->name('teacher_addSection');
    //课程章节管理重命名
    Route::patch('renameCourse','CourseManageController@rename')->middleware('checkTeacherToken')->name('teacher_renameCourse');
    //课程章节管理删除
    Route::delete('deleteCourse','CourseManageController@delete')->middleware('checkTeacherToken')->name('teacher_deleteCourse');
    //显示所有课程
    Route::get('courseIndex','CourseManageController@courseIndex')->middleware(['checkTeacherToken','checkTeacherAssociationBelongTo'])->name('teacher_courseIndex');
    //显示特定章节
    Route::get('showChapter','CourseManageController@showChapter')->middleware('checkTeacherToken')->name('teacher_showChapter');
    //显示特定小节
    Route::get('showSection','CourseManageController@showSection')->middleware('checkTeacherToken')->name('teacher_showSection');
    //添加题目
    Route::post('addTopic','ItemBankController@store')->middleware('checkTeacherToken')->name('teacher_addTopic');
    //显示特定题目
    Route::get('showTopic','TestManageController@show')->middleware(['checkTeacherToken','checkTeacherAssociationBelongTo'])->name('teacher_showTopic');
    //添加考试
    Route::post('addAssessment','TestManageController@store')->middleware(['checkTeacherToken','checkTeacherAssociationBelongTo'])->name('teacher_addAssessment');
    //显示所有活动
    Route::get('activityIndex','ActivityController@index')->middleware(['checkTeacherToken','checkTeacherAssociationBelongTo'])->name('teacher_activityIndex');
    //显示修改考试活动页面
    Route::get('updateAssessment','TestManageController@edit')->middleware('checkTeacherToken')->name('teacher_editAssessment');
    //提交修改考试
    Route::post('updateAssessment','TestManageController@update')->middleware(['checkTeacherToken','checkTeacherAssociationBelongTo'])->name('teacher_updateAssessment');
    //删除考试
    Route::delete('deleteAssessment','TestManageController@destroy')->middleware('checkTeacherToken')->name('teacher_deleteAssessment');
    //修改活动状态(开始考试结束考试都用这个接口)
    Route::patch('updateActivityStatus','ActivityController@updateStatus')->middleware('checkTeacherToken')->name('teacher_updateActivityStatus');
    //学生管理页面显示学生
    Route::get('studentIndex','StudentManageController@studentIndex')->middleware(['checkTeacherToken','checkTeacherAssociationBelongTo'])->name('teacher_studentIndex');
    //是否通过
    Route::patch('whetherToPass','StudentManageController@whetherToPass')->middleware(['checkTeacherToken','checkTeacherAssociationBelongTo'])->name('teacher_whetherToPass');
    //移除学生
    Route::delete('removeStudent','StudentManageController@destroy')->middleware(['checkTeacherToken','checkTeacherAssociationBelongTo'])->name('teacher_removeStudent');
    //搜索学生
    Route::get('searchStudent','StudentManageController@searchStudent')->middleware(['checkTeacherToken','checkTeacherAssociationBelongTo'])->name('teacher_searchStudent');
    //考勤学生页面数据
    Route::get('attendanceIndex','StudentManageController@attendanceIndex')->middleware(['checkTeacherToken','checkTeacherAssociationBelongTo'])->name('teacher_attendanceIndex');
    //添加考勤
    Route::post('attendanceStore','StudentManageController@attendanceStore')->middleware(['checkTeacherToken','checkTeacherAssociationBelongTo'])->name('teacher_attendanceStore');
    //考勤情况初始页面数据
    Route::get('attendanceShow','StudentManageController@attendanceShow')->middleware(['checkTeacherToken','checkTeacherAssociationBelongTo'])->name('teacher_attendanceShow');
    //查看特定考勤日期的考勤情况
    Route::get('attendEdit','StudentManageController@attendEdit')->middleware(['checkTeacherToken','checkTeacherAssociationBelongTo'])->name('teacher_attendanceShow');
    //修改特定考勤日期的考勤情况
    Route::patch('attendUpdate','StudentManageController@attendUpdate')->middleware('checkTeacherToken')->name('teacher_attendUpdate');
    //修改社团信息(基本信息)
    Route::post('associationUpdateBaseInfo','IndexController@associationUpdateBaseInfo')->middleware(['checkTeacherToken','checkTeacherAssociationBelongTo'])->name('teacher_associationUpdateBaseInfo');
    //修改社团信息(社团介绍)
    Route::post('associationUpdateIntroduce','IndexController@associationUpdateIntroduce')->middleware(['checkTeacherToken','checkTeacherAssociationBelongTo'])->name('teacher_associationUpdateIntroduce');
    //修改社团信息(学习目标)
    Route::post('associationUpdateLearn','IndexController@associationUpdateLearn')->middleware(['checkTeacherToken','checkTeacherAssociationBelongTo'])->name('teacher_associationUpdateLearn');
    //修改社团信息(社团口号)
    Route::post('associationUpdateCorporateSlogan','IndexController@associationUpdateCorporateSlogan')->middleware(['checkTeacherToken','checkTeacherAssociationBelongTo'])->name('teacher_associationUpdateCorporateSlogan');
    //创建社团 (没做表单验证)
    Route::post('storeAssociation','IndexController@storeAssociation')->middleware('checkTeacherToken')->name('teacher_storeAssociation');
    //老师申请管理社团
    Route::post('ApplyManageAssociation', 'IndexController@ApplyManageAssociation')->middleware(['checkTeacherToken'])->name('teacher_ApplyManageAssociation');
    //显示老师申请记录
    Route::get('showTeacherApply', 'IndexController@showTeacherApply')->middleware(['checkTeacherToken','checkTeacherAssociationBelongTo'])->name('teacher_showTeacherApply');
    //是否通过老师申请管理的请求
    Route::post('whetherAllowTeacher', 'IndexController@whetherAllowTeacher')->middleware(['checkTeacherToken'])->name('teacher_whetherAllowTeacher');
    //是否是此社团的管理员
    Route::post('isAdmin', 'IndexController@isAdmin')->middleware(['checkTeacherToken','checkTeacherAssociationBelongTo'])->name('teacher_isAdmin');
    //修改密码
    Route::patch('updatePassword', 'IndexController@updatePassword')->middleware('checkTeacherToken')->name('teacher_updatePassword');
    //修改头像
    Route::post('updateHeadImage','IndexController@updateHeadImage')->middleware('checkTeacherToken')->name('teacher_updateHeadImage');
    //特定一个题目
    Route::get('oneTopic','TestManageController@oneTopic')->middleware('checkTeacherToken')->name('teacher_oneTopic');

    //显示所有社团
    Route::get('associationIndex','IndexController@index')->middleware('checkTeacherToken')->name('teacher_updatePassword');
    //-------------------
    //显示我的社团
    Route::get('myJoinAssociation','IndexController@myJoinAssociation')->middleware('checkTeacherToken')->name('teacher_myJoinAssociation');
    //显示特定社团
    Route::post('associationShow','IndexController@show')->middleware(['checkTeacherToken'])->name('teacher_associationShow');

    //-------------
    //显示所有系选择框
    Route::get('associationCreate','IndexController@associationCreate')->middleware('checkTeacherToken')->name('teacher_associationCreate');

    //老师管理页面数据
    Route::get('teacherManageIndex','IndexController@teacherManageIndex')->middleware(['checkTeacherToken','checkTeacherAssociationBelongTo'])->name('teacher_teacherManageIndex');
    //转移管理权
    Route::patch('changePower','IndexController@changePower')->middleware(['checkTeacherToken','checkTeacherAssociationBelongTo'])->name('teacher_changePower');
    //踢出社团管理
    Route::patch('expelTeacher','IndexController@expelTeacher')->middleware(['checkTeacherToken','checkTeacherAssociationBelongTo'])->name('teacher_expelTeacher');

    //显示社团详情
    Route::get('associationDetails','IndexController@associationDetails')->middleware(['checkTeacherToken','checkTeacherAssociationBelongTo'])->name('teacher_associationDetails');

    //显示课程管理树形控件数据
    Route::get('treeIndex','CourseManageController@treeIndex')->middleware(['checkTeacherToken','checkTeacherAssociationBelongTo'])->name('teacher_treeIndex');

    //忘记密码首页
    Route::post('forgetIndex', 'IndexController@forgetIndex')->name('teacher_forgetIndex');
    //发送邮箱
    Route::post('sendMail', 'IndexController@sendMail')->middleware('checkTeacherToken')->name('teacher_sendMail');
    //验证验证码
    Route::post('validateCode', 'IndexController@validateCode')->middleware('checkTeacherToken')->name('teacher_sendMail');
    //重置密码
    Route::post('resetPass', 'IndexController@resetPass')->middleware('checkTeacherToken')->name('teacher_resetPass');

    //显示所有系
    Route::get('allDepartment','IndexController@allDepartment')->name('teacher_allDepartment');

});



