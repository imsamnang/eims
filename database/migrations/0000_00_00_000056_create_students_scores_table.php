<?php

use App\Models\StudySubjects;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\StudentsStudyCourseScore;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students_scores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('student_study_course_score_id')->unsigned()->nullable();
            $table->foreign('student_study_course_score_id')->references('id')->on((new StudentsStudyCourseScore())->getTable())->onDelete('cascade');
            $table->bigInteger('study_subject_id')->unsigned()->nullable();
            $table->foreign('study_subject_id')->references('id')->on((new StudySubjects())->getTable())->onDelete('cascade');
            $table->float('subject_score')->nullable();
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
        Schema::dropIfExists('students_scores');
    }
}
