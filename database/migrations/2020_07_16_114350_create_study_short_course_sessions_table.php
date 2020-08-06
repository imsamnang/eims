<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudyShortCourseSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('study_short_course_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('stu_sh_c_schedule_id')->unsigned()->nullable();
            $table->bigInteger('study_session_id')->unsigned()->nullable();
            $table->date('study_start')->nullable();
            $table->date('study_end')->nullable();
            $table->bigInteger('province_id')->unsigned()->nullable();
            $table->bigInteger('district_id')->unsigned()->nullable();
            $table->bigInteger('commune_id')->unsigned()->nullable();
            $table->bigInteger('village_id')->unsigned()->nullable();
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'))->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('study_short_course_sessions');
    }
}
