<?php

use App\Models\StudyClass;
use Illuminate\Database\Seeder;

class StudyClassTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StudyClass::insert([
            [
                'name' => 'A1',
                'en'   => 'A1',
                'km'   => 'ថ្នាក់ ក១',
            ],
            [
                'name' => 'A2',
                'en'   => 'A2',
                'km'   => 'ថ្នាក់ ក២',
            ],
            [
                'name' => 'A3',
                'en'   => 'A3',
                'km'   => 'ថ្នាក់ ក៣',
            ],
            [
                'name' => 'A4',
                'en'   => 'A4',
                'km'   => 'ថ្នាក់ ក៤',
            ],
            [
                'name' => 'A5',
                'en'   => 'A5',
                'km'   => 'ថ្នាក់ ក៥',
            ],
        ]);
    }
}
