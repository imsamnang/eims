<?php

use App\Models\StudySubjects;
use Illuminate\Support\Facades\DB;
use App\Models\StudentsStudyCourse;
use Illuminate\Support\Facades\Schema;
use App\Models\StudentsStudyCourseScore;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsStudyCourseScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students_study_course_scores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('student_study_course_id')->unsigned()->nullable();
            $table->foreign('student_study_course_id')->references('id')->on((new StudentsStudyCourse())->getTable())->onDelete('cascade');
            $table->float('attendance_score')->nullable()->default(0);
            $table->float('other_score')->nullable()->default(0);
            $table->integer('grade')->nullable();
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
        Schema::dropIfExists('students_study_course_scores');
    }
}
