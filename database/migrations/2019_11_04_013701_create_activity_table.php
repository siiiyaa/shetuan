<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('type')->unsigned()->comment('标识活动类型 1=>考试 2=>投票');
            $table->integer('activity_id')->unsigned()->comment('与考试表或者投票表等关联');
            $table->tinyInteger('status')->default(2)->unsigned()->comment('活动状态 1=>进行中 2=>未开始 3=>已结束');
            $table->integer('assoc_id')->unsigned()->comment('所属社团id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity');
    }
}
