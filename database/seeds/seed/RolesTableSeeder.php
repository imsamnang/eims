<?php

use App\Models\Roles;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Roles::insert([
            [
                'name'        => 'administrator',
                'en'          => 'Administrator',
                'km'          => 'អ្នកគ្រប់គ្រងជាន់ខ្ពស់',
                'description' => 'Administrator',
                'view_path'   => 'Administrator',

            ],
            [
                'name'        => 'manager',
                'en'          => 'Manager',
                'km'          => 'អ្នកគ្រប់គ្រង',
                'description' => 'Manager',
                'view_path'   => 'Manager',

            ],
            [
                'name'        => 'account',
                'en'          => 'Accountant',
                'km'          => 'គណនេយ្យករ',
                'description' => 'Accountant',
                'view_path'   => 'Accountant',

            ],
            [
                'name'        => 'library',
                'en'          => 'Librarian',
                'km'          => 'បណ្ណារក្ស',
                'description' => 'Librarian',
                'view_path'   => 'Librarian',

            ],
            [
                'name'        => 'staff',
                'en'          => 'Staff',
                'km'          => 'បុគ្គលិក',
                'description' => 'Staff',
                'view_path'   => 'Staff',

            ],
            [
                'name'        => 'student',
                'en'          => 'Student',
                'km'          => 'និស្សិត',
                'description' => 'Student',
                'view_path'   => 'Student',

            ],
            [
                'name'        => 'guardian',
                'en'          => 'Guardian',
                'km'          => 'អាណាព្យាបាល',
                'description' => 'Guardian',
                'view_path'   => 'Guardian',

            ],
            [
                'name'        => 'teacher',
                'en'          => 'Teacher',
                'km'          => 'គ្រូ',
                'description' => 'Teacher',
                'view_path'   => 'Teacher',

            ],
            [
                'name'        => 'user',
                'en'          => 'User',
                'km'          => 'អ្នកប្រើប្រាស់',
                'description' => 'User',
                'view_path'   => 'Users',

            ],

        ]);
    }
}
