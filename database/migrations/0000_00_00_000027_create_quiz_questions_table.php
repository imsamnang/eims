<?php

use App\Models\Quiz;
use App\Models\QuizAnswerTypes;
use App\Models\QuizQuestionTypes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('quiz_id')->unsigned()->nullable();
            $table->foreign('quiz_id')->references('id')->on((new Quiz())->getTable())->onDelete('cascade');
            $table->bigInteger('quiz_answer_type_id')->unsigned()->nullable();
            $table->foreign('quiz_answer_type_id')->references('id')->on((new QuizAnswerTypes())->getTable())->onDelete('cascade');
            $table->bigInteger('quiz_question_type_id')->unsigned()->nullable();
            $table->foreign('quiz_question_type_id')->references('id')->on((new QuizQuestionTypes())->getTable())->onDelete('cascade');
            $table->string('question')->nullable();
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
        Schema::dropIfExists('quiz_questions');
    }
}
