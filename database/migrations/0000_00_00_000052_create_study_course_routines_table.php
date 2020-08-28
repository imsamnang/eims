<?php

use App\Models\Days;
use App\Models\Staff;
use App\Models\StudyClass;
use App\Models\StudySubjects;
use Illuminate\Support\FacadesDB;
use App\Models\StudyCourseSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudyCourseRoutinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('study_course_routines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('study_course_session_id')->unsigned()->nullable();
            $table->foreign('study_course_session_id')->references('id')->on((new StudyCourseSession())->getTable())->onDelete('cascade');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->bigInteger('day_id')->unsigned()->nullable();
            $table->foreign('day_id')->references('id')->on((new Days())->getTable())->onDelete('cascade');
            $table->bigInteger('study_subject_id')->unsigned()->nullable();
            $table->foreign('teacher_id')->references('id')->on((new Staff())->getTable())->onDelete('cascade');
            $table->bigInteger('teacher_id')->unsigned()->nullable();
            $table->foreign('study_subject_id')->references('id')->on((new StudySubjects())->getTable())->onDelete('cascade');
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
        Schema::dropIfExists('study_course_routines');
    }
}
