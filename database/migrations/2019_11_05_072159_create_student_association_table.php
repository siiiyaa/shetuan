<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentAssociationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_association', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id')->unsigned()->comment('学生id');
            $table->integer('association_id')->unsigned()->comment('社团id');
            $table->tinyInteger('status')->unsigned()->comment('状态 0=>等待验证 1=>已通过 2=>未通过');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_association');
    }
}
