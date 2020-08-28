<?php

use App\Models\AttendanceTypes;
use Illuminate\Support\Facades\DB;
use App\Models\StudentsStudyCourse;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students_attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('student_study_course_id')->unsigned()->nullable();
            $table->foreign('student_study_course_id')->references('id')->on((new StudentsStudyCourse())->getTable())->onDelete('cascade');
            $table->bigInteger('attendance_type_id')->unsigned()->nullable();
            $table->foreign('attendance_type_id')->references('id')->on((new AttendanceTypes())->getTable())->onDelete('cascade');
            $table->integer('year')->nullable();
            $table->integer('month')->nullable();
            $table->integer('date')->nullable();
            $table->text('create_by')->nullable();
            $table->text('from_by')->nullable();
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
        Schema::dropIfExists('students_attendances');
    }
}
