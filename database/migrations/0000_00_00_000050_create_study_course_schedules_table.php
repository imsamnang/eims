<?php

use App\Models\Institute;
use App\Models\StudyCourse;
use App\Models\StudyPrograms;
use App\Models\StudySemesters;
use App\Models\StudyGeneration;
use Illuminate\Support\FacadesDB;
use App\Models\StudyAcademicYears;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudyCourseSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('study_course_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('institute_id')->unsigned()->nullable();
            $table->foreign('institute_id')->references('id')->on((new Institute())->getTable())->onDelete('cascade');
            $table->bigInteger('study_program_id')->unsigned()->nullable();
            $table->foreign('study_program_id')->references('id')->on((new StudyPrograms())->getTable())->onDelete('cascade');
            $table->bigInteger('study_course_id')->unsigned()->nullable();
            $table->foreign('study_course_id')->references('id')->on((new StudyCourse())->getTable())->onDelete('cascade');
            $table->bigInteger('study_generation_id')->unsigned()->nullable();
            $table->foreign('study_generation_id')->references('id')->on((new StudyGeneration())->getTable())->onDelete('cascade');
            $table->bigInteger('study_academic_year_id')->unsigned()->nullable();
            $table->foreign('study_academic_year_id')->references('id')->on((new StudyAcademicYears())->getTable())->onDelete('cascade');
            $table->bigInteger('study_semester_id')->unsigned()->nullable();
            $table->foreign('study_semester_id')->references('id')->on((new StudySemesters())->getTable())->onDelete('cascade');
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
        Schema::dropIfExists('study_course_schedules');
    }
}
