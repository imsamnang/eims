<?php

use App\Models\StudySemesters;
use Illuminate\Database\Seeder;

class StudySemestersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StudySemesters::insert([
            [
                'name'       => 'Semester I',
                'en'         => 'Semester I',
                'km'         => 'ឆមាសទី ១',

            ],
            [
                'name'       => 'Semester II',
                'en'         => 'Semester II',
                'km'         => 'ឆមាសទី ២',

            ],
            [
                'name'       => 'Semester III',
                'en'         => 'Semester III',
                'km'         => 'ឆមាសទី ៣',

            ],
            [
                'name'       => 'Semester IV',
                'en'         => 'Semester IV',
                'km'         => 'ឆមាសទី ៤',

            ],

        ]);
    }
}
