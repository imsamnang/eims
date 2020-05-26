<?php

use App\Models\QuizQuestionType;
use Illuminate\Database\Seeder;

class QuizQuestionTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        QuizQuestionType::insert([
            [
                'name'  => 'health',
                'en'    => 'Health',
                'km'    => 'សុខភាព',
            ],
            [
                'name'  => 'knowlagde',
                'en'    => 'Knowlagde',
                'km'    => 'ចំនេះដឹង',
            ]
        ]);
    }
}
