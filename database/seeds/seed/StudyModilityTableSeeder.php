<?php

use App\Models\StudyModality;
use Illuminate\Database\Seeder;

class StudyModilityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StudyModality::insert([
            [
                'institute_id' => 1,
                'name'  => 'Center Base',
                'en'    => 'Center Base',
                'km'    => 'តាមសហគ្រាស',
            ],
            [
                'institute_id' => 1,
                'name'  => 'Interprise Base',
                'en'    => 'Interprise Base',
                'km'    => 'តាមសហគមន៍',
            ],
            [
                'institute_id' => 1,
                'name'  => 'Institute',
                'en'    => 'Institute',
                'km'    => 'វិទ្យាស្ថាន',
            ],
        ]);
    }
}
