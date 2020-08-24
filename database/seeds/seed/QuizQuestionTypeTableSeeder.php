<?php

use App\Models\QuizQuestionTypes;
use Illuminate\Database\Seeder;

class QuizQuestionTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        QuizQuestionTypes::insert([
            [

                'institute_id'  => 1,
                'name'  => 'health',
                'en'    => 'Health',
                'km'    => 'សុខភាព',
            ],
            [
                'institute_id'  => 1,
                'name'  => 'knowlagde',
                'en'    => 'Knowlagde',
                'km'    => 'ចំនេះដឹង',
            ]
        ]);
    }
}
