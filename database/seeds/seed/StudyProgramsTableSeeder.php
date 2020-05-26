<?php


use App\Models\StudyPrograms;
use Illuminate\Database\Seeder;

class StudyProgramsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StudyPrograms::insert([
            [
                "name"  =>"C1",
                "en"    =>"C1",
                "km"    =>"ស.ប.វិ ១",
            ],
            [
                "name"  =>"C2",
                "en"    =>"C2",
                "km"    =>"ស.ប.វិ ២",
            ],
            [
                "name"  =>"C3",
                "en"    =>"C3",
                "km"    =>"ស.ប.វិ ៣",
            ],


            [
                "name"  =>"Associate Degree",
                "en"    =>"Associate Degree",
                "km"    =>"បរិញ្ញាបត្ររង",
            ],
            [
                "name"  =>"Bachelor Degress",
                "en"    =>"Bachelor Degress",
                "km"    =>"បរិញ្ញាបត្រ / វិស្វករ",
            ],

            [
                "name"  =>"Master Degree",
                "en"    =>"Master Degree",
                "km"    =>"អនុបណ្ឌិត",
            ],

            [
                "name"  =>"PhD Degree",
                "en"    =>"PhD Degree",
                "km"    =>"បណ្ឌិត",
            ],

            [
                "name"  =>"Teacher Training (Basic)",
                "en"    =>"Teacher Training (Basic)",
                "km"    =>"បណ្តុះបណ្តាលគ្រូមធ្យម",
            ],

            [
                "name"  =>"Teacher Training (Advanced)",
                "en"    =>"Teacher Training (Advanced)",
                "km"    =>"បណ្តុះបណ្តាលគ្រូឧត្តម",
            ],
        ]);
    }
}
