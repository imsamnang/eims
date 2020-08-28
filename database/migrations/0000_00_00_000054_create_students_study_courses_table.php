<?php

use App\Models\StudyStatus;
use App\Models\StudentsRequest;
use App\Models\StudyCourseSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsStudyCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students_study_courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('nid')->nullable();
            $table->bigInteger('student_request_id')->unsigned()->nullable();
            $table->foreign('student_request_id')->references('id')->on((new StudentsRequest())->getTable())->onDelete('cascade');
            $table->bigInteger('study_course_session_id')->unsigned()->nullable();
            $table->foreign('study_course_session_id')->references('id')->on((new StudyCourseSession())->getTable())->onDelete('cascade');
            $table->bigInteger('study_status_id')->unsigned()->nullable();
            $table->foreign('study_status_id')->references('id')->on((new StudyStatus())->getTable())->onDelete('cascade');
            $table->text('description')->nullable();
            $table->text('photo')->nullable();
            $table->text('qrcode')->nullable();
            $table->text('card')->nullable();
            $table->text('certificate')->nullable();
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
        Schema::dropIfExists('students_study_courses');
    }
}
