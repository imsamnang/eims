<?php

use App\Models\StudyGrade;
use Illuminate\Database\Seeder;

class StudyGradeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StudyGrade::insert([
            [
                'name'          => 'A',
                'en'            => 'A',
                'km'            => 'A',
                'marks'         => 90
            ],
            [
                'name'          => 'B',
                'en'            => 'B',
                'km'            => 'B',
                'marks'         => 80
            ],
            [
                'name'          => 'C',
                'en'            => 'C',
                'km'            => 'C',
                'marks'         => 70
            ],
            [
                'name'          => 'D',
                'en'            => 'D',
                'km'            => 'D',
                'marks'         => 60
            ],
            [
                'name'          => 'E',
                'en'            => 'E',
                'km'            => 'E',
                'marks'         => 50
            ],

        ]);
    }
}
