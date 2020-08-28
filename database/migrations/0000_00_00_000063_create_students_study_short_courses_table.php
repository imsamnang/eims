<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\StudyShortCourseSchedule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\StudentsShortCourseRequest;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsStudyShortCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students_study_short_courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('stu_sh_c_request_id')->unsigned()->nullable();
            $table->foreign('stu_sh_c_request_id')->references('id')->on((new StudentsShortCourseRequest())->getTable())->onDelete('cascade');
            $table->bigInteger('stu_sh_c_schedule_id')->unsigned()->nullable();
            $table->foreign('stu_sh_c_schedule_id')->references('id')->on((new StudyShortCourseSchedule())->getTable())->onDelete('cascade');
            $table->string('photo')->nullable();
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
        Schema::dropIfExists('students_study_short_courses');
    }
}
