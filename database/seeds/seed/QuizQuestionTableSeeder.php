<?php

use App\Models\QuizAnswers;
use App\Models\QuizQuestions;
use Illuminate\Database\Seeder;

class QuizQuestionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        QuizQuestions::insert([
            [
                'id'                    => 1,
                'quiz_id'               => 1,
                'quiz_answer_type_id'   => 1,
                'quiz_question_type_id' => 1,
                'question'              => '1 + 1 = ?',
                'score'                 => 10,
            ],
            [
                'id'                    => 2,
                'quiz_id'               => 1,
                'quiz_answer_type_id'   => 2,
                'quiz_question_type_id' => 2,
                'question'              => '1 + 1 = ?',
                'score'                 => 10,
            ],
            [
                'id'                    => 3,
                'quiz_id'               => 1,
                'quiz_answer_type_id'   => 3,
                'quiz_question_type_id' => 2,
                'question'              => '1 + 5 = ?',
                'score'                 => 10,
            ]
        ]);

        QuizAnswers::insert([
            [
                'quiz_question_id'      => 1,
                'answer'                => 1,
                'correct_answer'        => 0,
            ],
            [
                'quiz_question_id'      => 1,
                'answer'                => 2,
                'correct_answer'        => 1,
            ],
            [
                'quiz_question_id'      => 1,
                'answer'                => 3,
                'correct_answer'        => 0,
            ],


            [
                'quiz_question_id'      => 2,
                'answer'                => 2,
                'correct_answer'        => 1,
            ],
            [
                'quiz_question_id'      => 2,
                'answer'                => 3,
                'correct_answer'        => 0,
            ],
            [
                'quiz_question_id'      => 2,
                'answer'                => '10 (Binary)',
                'correct_answer'        => 1,
            ],


            [
                'quiz_question_id'      => 3,
                'answer'                => 5,
                'correct_answer'        => 1,
            ],

        ]);
    }
}
