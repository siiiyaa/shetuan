<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('type')->default(1)->comment('1=>单选2=>多选')->unsigned();
            $table->string('top_name')->default('')->comment('题干');
            $table->string('options')->default('')->comment('选项');
            $table->string('correct',50)->default('')->comment('正确答案');
            $table->integer('top_score')->default(0)->comment('分值');

            $table->integer('course_id')->unsigned()->comment('所属课程id');
            $table->integer('chapter_id')->unsigned()->comment('所属章节id');
            $table->integer('association_id')->unsigned()->comment('所属社团id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topics');
    }
}
