<?php

use App\Models\Days;
use App\Models\Staff;
use App\Models\StudyClass;
use Illuminate\Support\FacadesDB;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\StudyShortCourseSchedule;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudyShortCourseRoutinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('study_short_course_routines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('stu_sh_c_schedule_id')->unsigned()->nullable();
            $table->foreign('stu_sh_c_schedule_id')->references('id')->on((new StudyShortCourseSchedule())->getTable())->onDelete('cascade');        
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->bigInteger('day_id')->unsigned()->nullable();
            $table->foreign('day_id')->references('id')->on((new Days())->getTable())->onDelete('cascade');
            $table->bigInteger('teacher_id')->unsigned()->nullable();
            $table->foreign('teacher_id')->references('id')->on((new Staff())->getTable())->onDelete('cascade');            
            $table->bigInteger('study_class_id')->unsigned()->nullable();
            $table->foreign('study_class_id')->references('id')->on((new StudyClass())->getTable())->onDelete('cascade');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
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
        Schema::dropIfExists('study_short_course_routines');
    }
}
