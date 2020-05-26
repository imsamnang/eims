<?php

use App\Models\StaffDesignations;
use Illuminate\Database\Seeder;

class StaffDesignationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StaffDesignations::insert([
            [
                'name'        => 'principal',
                'en'          => 'Principal',
                'km'          => 'នាយក',

            ],
            [
                'name'        => 'teacher',
                'en'          => 'Teacher',
                'km'          => 'គ្រូបច្ចេកទេស',

            ],
            [
                'name'        => 'teacher_learning_support',
                'en'          => 'Teacher Learning Support',
                'km'          => 'គ្រូបង្រៀនស្មគ្រចិត្ត',

            ],
            [
                'name'        => 'security_guard',
                'en'          => 'Security Guard',
                'km'          => 'អ្នកយាម',

            ],
            [
                'name'        => 'accountant',
                'en'          => 'Accountant',
                'km'          => 'គណនេយ្យករ',

            ],
            [
                'name'        => 'driver',
                'en'          => 'Driver',
                'km'          => 'អ្នកបើកបរ',

            ],
        ]);
    }
}
