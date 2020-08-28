<?php

use App\Models\Institute;
use App\Models\CourseTypes;
use App\Models\StudyFaculty;
use App\Models\StudyModality;
use App\Models\StudyPrograms;
use App\Models\StudyGeneration;
use App\Models\CurriculumAuthor;
use App\Models\StudyOverallFund;
use Illuminate\Support\Facades\DB;
use App\Models\CurriculumEndorsement;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudyCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('study_courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name')->nullable();
            $table->text('en')->nullable();
            $table->text('km')->nullable();
            $table->bigInteger('institute_id')->unsigned()->nullable();
            $table->foreign('institute_id')->references('id')->on((new Institute())->getTable())->onDelete('cascade');
            $table->bigInteger('study_faculty_id')->unsigned()->nullable();
            $table->foreign('study_faculty_id')->references('id')->on((new StudyFaculty())->getTable())->onDelete('cascade');
            $table->bigInteger('course_type_id')->unsigned()->nullable();
            $table->foreign('course_type_id')->references('id')->on((new CourseTypes())->getTable())->onDelete('cascade');
            $table->bigInteger('study_modality_id')->unsigned()->nullable();
            $table->foreign('study_modality_id')->references('id')->on((new StudyModality())->getTable())->onDelete('cascade');
            $table->bigInteger('study_program_id')->unsigned()->nullable();
            $table->foreign('study_program_id')->references('id')->on((new StudyPrograms())->getTable())->onDelete('cascade');
            $table->bigInteger('study_overall_fund_id')->unsigned()->nullable();
            $table->foreign('study_overall_fund_id')->references('id')->on((new StudyOverallFund())->getTable())->onDelete('cascade');
            $table->bigInteger('curriculum_endorsement_id')->unsigned()->nullable();
            $table->foreign('curriculum_endorsement_id')->references('id')->on((new CurriculumEndorsement())->getTable())->onDelete('cascade');
            $table->bigInteger('curriculum_author_id')->unsigned()->nullable();
            $table->foreign('curriculum_author_id')->references('id')->on((new CurriculumAuthor())->getTable())->onDelete('cascade');
            $table->bigInteger('study_generation_id')->unsigned()->nullable();
            $table->foreign('study_generation_id')->references('id')->on((new StudyGeneration())->getTable())->onDelete('cascade');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('description')->nullable();
            $table->text('image')->nullable();
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
        Schema::dropIfExists('study_courses');
    }
}
