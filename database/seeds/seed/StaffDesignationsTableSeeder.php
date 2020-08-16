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
                'id'            => 1,
                'institute_id'  => 1,
                'name'        => 'principal',
                'en'          => 'Principal',
                'km'          => 'នាយក/នាយិកា',

            ],
            [
                'id'            => 2,
                'institute_id'  => 1,
                'name'        => 'teacher',
                'en'          => 'Teacher',
                'km'          => 'គ្រូបច្ចេកទេស',

            ],
            [
                'id'            => 3,
                'institute_id'  => 1,
                'name'        => 'teacher_learning_support',
                'en'          => 'Teacher Learning Support',
                'km'          => 'គ្រូបង្រៀនស្មគ្រចិត្ត',

            ],
            [
                'id'            => 4,
                'institute_id'  => 1,
                'name'        => 'Security Guard',
                'en'          => 'Security Guard',
                'km'          => 'អ្នកយាម',

            ],
            [
                'id'            => 5,
                'institute_id'  => 1,
                'name'        => 'deputy director',
                'en'          => 'Deputy Director',
                'km'          => 'នាយករង/នាយិការង',

            ],
            [
                'id'            => 6,
                'institute_id'  => 1,
                'name'        => 'Head Office',
                'en'          => 'Head Office',
                'km'          => 'ប្រធានការិយាល័យ',

            ],
            [
                'id'            => 7,
                'institute_id'  => 1,
                'name'        => 'Head Department',
                'en'          => 'Head Department',
                'km'          => 'ប្រធានដេប៉ាតឺម៉ង់',
            ],
            [
                'id'            => 8,
                'institute_id'  => 1,
                'name'        => 'Deputy Head Office',
                'en'          => 'Deputy Head Office',
                'km'          => 'អនុ-ការិយាល័យ',
            ],
            [
                'id'            => 9,
                'institute_id'  => 1,
                'name'        => 'Deputy Head Department',
                'en'          => 'Deputy Head Department',
                'km'          => 'អនុ-ដេប៉ាតឺម៉ង់',
            ],
        ]);
    }
}
