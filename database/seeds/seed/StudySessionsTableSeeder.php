<?php

use App\Models\StudySession;
use Illuminate\Database\Seeder;

class StudySessionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StudySession::insert([
            [
                'name'  => 'Morning',
                'en'    => 'Morning',
                'km'    => 'ពេលព្រឹក',
            ],
            [
                'name'  => 'Evening',
                'en'    => 'Evening',
                'km'    => 'ពេលល្ងាច',
            ],

            [
                'name'  => 'Night',
                'en'    => 'Night',
                'km'    => 'ពេលយប់',
            ],
        ]);
    }
}
