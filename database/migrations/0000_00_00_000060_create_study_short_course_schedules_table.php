<?php

use App\Models\Communes;
use App\Models\Villages;
use App\Models\Districts;
use App\Models\Institute;
use App\Models\Provinces;
use App\Models\StudySession;
use App\Models\StudySubjects;
use App\Models\StudyGeneration;
use Illuminate\Support\FacadesDB;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudyShortCourseSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('study_short_course_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('institute_id')->unsigned()->nullable();
            $table->foreign('institute_id')->references('id')->on((new Institute())->getTable())->onDelete('cascade');
            $table->bigInteger('study_generation_id')->unsigned()->nullable();
            $table->foreign('study_generation_id')->references('id')->on((new StudyGeneration())->getTable())->onDelete('cascade');
            $table->bigInteger('study_subject_id')->unsigned()->nullable();
            $table->foreign('study_subject_id')->references('id')->on((new StudySubjects())->getTable())->onDelete('cascade');
            $table->bigInteger('study_session_id')->unsigned()->nullable();
            $table->foreign('study_session_id')->references('id')->on((new StudySession())->getTable())->onDelete('cascade');
            $table->date('study_start')->nullable();
            $table->date('study_end')->nullable();
            $table->bigInteger('province_id')->unsigned()->nullable();
            $table->foreign('province_id')->references('id')->on((new Provinces)->getTable())->onDelete('cascade');
            $table->bigInteger('district_id')->unsigned()->nullable();
            $table->foreign('district_id')->references('id')->on((new Districts)->getTable())->onDelete('cascade');
            $table->bigInteger('commune_id')->unsigned()->nullable();
            $table->foreign('commune_id')->references('id')->on((new Communes)->getTable())->onDelete('cascade');
            $table->bigInteger('village_id')->unsigned()->nullable();
            $table->foreign('village_id')->references('id')->on((new Villages)->getTable())->onDelete('cascade');
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
        Schema::dropIfExists('study_short_course_schedules');
    }
}
