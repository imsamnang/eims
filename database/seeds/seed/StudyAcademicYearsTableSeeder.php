<?php

use App\Models\StudyAcademicYears;
use Illuminate\Database\Seeder;

class StudyAcademicYearsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StudyAcademicYears::insert([
            [
                'name' => 'Year I',
                'en'   => 'Year I',
                'km'   => 'ឆ្នាំទី ១',
            ],
            [
                'name' => 'Year II',
                'en'   => 'Year II',
                'km'   => 'ឆ្នាំទី ២',
            ],
            [
                'name' => 'Year III',
                'en'   => 'Year III',
                'km'   => 'ឆ្នាំទី ៣',
            ],
            [
                'name' => 'Year IV',
                'en'   => 'Year IV',
                'km'   => 'ឆ្នាំទី ៤',
            ]
        ]);
    }
}
