<?php

use App\Models\CourseTypes;
use Illuminate\Database\Seeder;

class CourseTyesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CourseTypes::insert([
            [
                'institute_id' => 1,
                'name'        => 'Short Course',
                'en'          => 'Short Course',
                'km'          => 'វគ្គខ្លី',

            ],
            [
                'institute_id' => 1,
                'name'        => 'Long Course',
                'en'          => 'Long Course',
                'km'          => 'វគ្គវែង',

            ],
        ]);
    }
}
