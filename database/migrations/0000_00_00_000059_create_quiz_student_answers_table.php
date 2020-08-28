<?php

use App\Models\QuizStudents;
use App\Models\QuizQuestions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizStudentAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_student_answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('quiz_student_id')->unsigned()->nullable();
            $table->foreign('quiz_student_id')->references('id')->on((new QuizStudents())->getTable())->onDelete('cascade');
            $table->bigInteger('quiz_question_id')->unsigned()->nullable();
            $table->foreign('quiz_question_id')->references('id')->on((new QuizQuestions())->getTable())->onDelete('cascade');
            $table->string('answered')->nullable();
            $table->float('score')->nullable();
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
        Schema::dropIfExists('quiz_student_answers');
    }
}
