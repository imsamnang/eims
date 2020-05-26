<?php

use App\Models\StudyStatus;
use Illuminate\Database\Seeder;

class StudyStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StudyStatus::insert([
            [
                'name'    => 'New Admission',
                'en'      => 'New Admission',
                'km'      => 'សិស្សថ្មី',
                'color'   => 'bg-blue text-white',

            ],
            [
                'name'    => 'Old Student',
                'en'      => 'Old Student',
                'km'      => 'សិស្សចាស់',
                'color'   => 'bg-secondary',

            ],
            [
                'name'    => 'Continue',
                'en'      => 'Continue',
                'km'      => 'បន្តការសិក្សា',
                'color'   => 'bg-green text-white',

            ],
            [
                'name'    => 'Pass Out',
                'en'      => 'Pass Out',
                'km'      => 'ចេញពីសាលា',
                'color'   => 'bg-gray text-white',

            ],
            [
                'name'    => 'Back Continue',
                'en'      => 'Back Continue',
                'km'      => 'ត្រឡប់មកបន្ត',
                'color'   => 'bg-dark text-white',

            ],
            [
                'name'    => 'Drop Out',
                'en'      => 'Drop Out',
                'km'      => 'ឈប់រៀន',
                'color'   => 'bg-red text-white',

            ],
            [
                'name'    => 'Transfer In',
                'en'      => 'Transfer In',
                'km'      => 'ផ្ទេរចូល',
                'color'   => 'bg-info text-white',

            ],
            [
                'name'    => 'Transfer Out',
                'en'      => 'Transfer Out',
                'km'      => 'ផ្ទេរចេញ',
                'color'   => 'bg-warning text-white',

            ],
        ]);
    }
}
