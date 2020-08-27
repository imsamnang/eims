<?php

use App\Models\QuizStudents;
use Illuminate\Database\Seeder;

class QuizStudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        QuizStudents::insert([
            [
                'quiz_id'                => 1,
                'student_study_course_id' => 1,
            ],
            [
                'quiz_id'                => 1,
                'student_study_course_id' => 3,
            ]
        ]);
    }
}
