<?php

use App\Models\StaffTeachSubject;
use Illuminate\Support\FacadesDB;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudySubjectLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('study_subject_lessons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('staff_teach_subject_id')->unsigned();
            $table->foreign('staff_teach_subject_id')->references('id')->on((new StaffTeachSubject())->getTable())->onDelete('cascade');
            $table->text('title')->nullable();
            $table->text('source_file')->nullable();
            $table->text('source_link')->nullable();
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
        Schema::dropIfExists('study_subject_lessons');
    }
}
