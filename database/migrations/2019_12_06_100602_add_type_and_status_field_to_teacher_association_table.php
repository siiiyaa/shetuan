<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeAndStatusFieldToTeacherAssociationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teacher_association', function (Blueprint $table) {
            $table->tinyInteger('status')->unsigned()->comment('0=>等待验证 1=>已通过 2=>未通过');
            $table->tinyInteger('is_admin')->unsigned()->comment('1=>是管理员 0=>不是管理员');
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
        Schema::table('teacher_association', function (Blueprint $table) {
            //
        });
    }
}
