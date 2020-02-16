<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ass_name',20)->default('')->comment('考试标题');
            $table->string('study_avi',50)->nullable()->default('')->comment('前置学习视频地址');
            $table->string('clipping_time',20)->default('')->comment('限制做题时间');
            $table->integer('course_id')->unsigned()->comment('所属课程id');
            $table->integer('chapter_id')->unsigned()->comment('所属章节id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assessments');
    }
}
