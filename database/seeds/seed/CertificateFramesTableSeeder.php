<?php

use App\Models\CertificateFrames;
use Illuminate\Database\Seeder;

class CertificateFramesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CertificateFrames::insert([
            [
                'institute_id' => 1,
                'type'         => 'student',
                'name'         => 'Rpitssr Certificate blue vertical',
                'foreground'        => 'blue.png',
                'layout'       => 'vertical',
                'status'       => 1,
            ],
            [
                'institute_id' => 1,
                'type'         => 'student',
                'name'         => 'Rpitssr Certificate green vertical',
                'foreground'        => 'green.png',
                'layout'       => 'vertical',
                'status'       => 0,
            ],
            [
                'institute_id' => 1,
                'type'         => 'student',
                'name'         => 'Rpitssr Certificate red vertical',
                'foreground'        => 'red.png',
                'layout'       => 'vertical',
                'status'       => 0,
            ],

        ]);
    }
}
