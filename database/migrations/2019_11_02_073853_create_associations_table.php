<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssociationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('associations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ass_name',20)->comment('社团名称')->default('');
            $table->string('english_name',20)->comment('社团英文名称')->default('');
            $table->integer('number_people')->comment('社团人数')->default(0)->unsigned();
            $table->string('corporate_slogan',50)->comment('社团口号')->default('');
            $table->string('introduce',255)->comment('社团介绍')->default('');
            $table->string('images',50)->nullable()->comment('说明图')->default('');
            $table->string('learning_objectives',255)->comment('学习目标')->default('');
            $table->integer('department_id')->comment('所属系id')->unsigned();

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
        Schema::dropIfExists('societies');
    }
}
