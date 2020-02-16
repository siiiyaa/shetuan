<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssessmentTopicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assessment_topic', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('asses_id')->unsigned()->comment('考试id');
            $table->integer('topic_id')->unsigned()->comment('题目id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assessment_topic');
    }
}
