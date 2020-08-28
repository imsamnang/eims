<?php

use App\Models\Users;
use App\Models\Students;
use App\Models\Institute;
use App\Models\StudySession;
use App\Models\StudySubjects;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsShortCourseRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students_short_course_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('student_id')->unsigned()->nullable();
            $table->foreign('institute_id')->references('id')->on((new Institute())->getTable())->onDelete('cascade');
            $table->bigInteger('institute_id')->unsigned()->nullable();
            $table->foreign('student_id')->references('id')->on((new Students())->getTable())->onDelete('cascade');
            $table->bigInteger('study_subject_id')->unsigned()->nullable();
            $table->foreign('study_subject_id')->references('id')->on((new StudySubjects())->getTable())->onDelete('cascade');
            $table->bigInteger('study_session_id')->unsigned()->nullable();
            $table->foreign('study_session_id')->references('id')->on((new StudySession())->getTable())->onDelete('cascade');
            $table->string('description')->nullable();
            $table->string('photo')->nullable();
            $table->enum('status',['1','0'])->default(0);
            
            $table->bigInteger('added_by')->unsigned()->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
            $table->timestamp('updated_at')->nullable();

            
            
            
            
            $table->foreign('added_by')->references('id')->on((new Users())->getTable())->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on((new Users())->getTable())->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students_short_course_requests');
    }
}
