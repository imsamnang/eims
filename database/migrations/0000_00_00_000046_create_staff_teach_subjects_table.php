<?php

use App\Models\Staff;
use App\Models\StudySubjects;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffTeachSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_teach_subjects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('staff_id')->unsigned();
            $table->foreign('staff_id')->references('id')->on((new Staff())->getTable())->onDelete('cascade');            
            $table->bigInteger('study_subject_id')->unsigned();
            $table->foreign('study_subject_id')->references('id')->on((new StudySubjects())->getTable())->onDelete('cascade');
            $table->year('year')->nullable();
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
        Schema::dropIfExists('staff_teach_subjects');
    }
}
