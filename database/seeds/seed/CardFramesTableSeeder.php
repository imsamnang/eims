<?php

use App\Models\CardFrames;
use Illuminate\Database\Seeder;

class CardFramesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CardFrames::insert([
            [
                'institute_id' => 1,
                'type'         => 'student',
                'name'         => 'Rpitssr Students card blue vertical',
                'foreground'        => 'blue-01.jpg',
                'background'   => 'blue-02.jpg',
                'layout'       => 'vertical',
                'status'       => 1,
            ],
            [
                'institute_id' => 1,
                'type'         => 'student',
                'name'         => 'Rpitssr Students card blue horizontal',
                'foreground'        => 'blue-03.jpg',
                'background'   => 'blue-04.jpg',
                'layout'       => 'horizontal',
                'status'       => 0,
            ],

            [
                'institute_id' => 1,
                'type'         => 'student',
                'name'         => 'Rpitssr Students card red vertical',
                'foreground'        => 'red-01.jpg',
                'background'   => 'red-02.jpg',
                'layout'       => 'vertical',
                'status'       => 0,
            ],
            [
                'institute_id' => 1,
                'type'         => 'student',
                'name'         => 'Rpitssr Students card red horizontal',
                'foreground'        => 'red-03.jpg',
                'background'   => 'red-04.jpg',
                'layout'       => 'horizontal',
                'status'       => 0,
            ],

            [
                'institute_id' => 1,
                'type'         => 'student',
                'name'         => 'Rpitssr Students card green vertical',
                'foreground'        => 'green-01.jpg',
                'background'   => 'green-02.jpg',
                'layout'       => 'vertical',
                'status'       => 0,
            ],
            [
                'institute_id' => 1,
                'type'         => 'student',
                'name'         => 'Rpitssr Students card green horizontal',
                'foreground'        => 'green-03.jpg',
                'background'   => 'green-04.jpg',
                'layout'       => 'horizontal',
                'status'       => 0,
            ],
            [
                'institute_id' => 1,
                'type'         => 'student',
                'name'         => 'Rpitssr Students card orange vertical',
                'foreground'        => 'orange-01.jpg',
                'background'   => 'orange-02.jpg',
                'layout'       => 'vertical',
                'status'       => 0,
            ],
            [
                'institute_id' => 1,
                'type'         => 'student',
                'name'         => 'Rpitssr Students card orange horizontal',
                'foreground'        => 'orange-03.jpg',
                'background'   => 'orange-04.jpg',
                'layout'       => 'horizontal',
                'status'       => 0,
            ],
            [
                'institute_id' => 1,
                'type'         => 'student',
                'name'         => 'Rpitssr Students card indigo vertical',
                'foreground'        => 'indigo-01.jpg',
                'background'   => 'indigo-02.jpg',
                'layout'       => 'vertical',
                'status'       => 0,
            ],
            [
                'institute_id' => 1,
                'type'         => 'student',
                'name'         => 'Rpitssr Students card indigo horizontal',
                'foreground'        => 'indigo-03.jpg',
                'background'   => 'indigo-04.jpg',
                'layout'       => 'horizontal',
                'status'       => 0,
            ],
            [
                'institute_id' => 1,
                'type'         => 'student',
                'name'         => 'Rpitssr Students card gray-dark vertical',
                'foreground'        => 'gray-dark-01.jpg',
                'background'   => 'gray-dark-02.jpg',
                'layout'       => 'vertical',
                'status'       => 0,
            ],
            [
                'institute_id' => 1,
                'type'         => 'student',
                'name'         => 'Rpitssr Students card gray-dark horizontal',
                'foreground'        => 'gray-dark-03.jpg',
                'background'   => 'gray-dark-04.jpg',
                'layout'       => 'horizontal',
                'status'       => 0,
            ],
            [
                'institute_id' => 1,
                'type'         => 'student',
                'name'         => 'Rpitssr Students card pink vertical',
                'foreground'        => 'pink-01.jpg',
                'background'   => 'pink-02.jpg',
                'layout'       => 'vertical',
                'status'       => 0,
            ],
            [
                'institute_id' => 1,
                'type'         => 'student',
                'name'         => 'Rpitssr Students card pink horizontal',
                'foreground'        => 'pink-03.jpg',
                'background'   => 'pink-04.jpg',
                'layout'       => 'horizontal',
                'status'       => 0,
            ],

        ]);
    }
}
