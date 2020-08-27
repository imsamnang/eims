<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ProvincesTableSeeder::class,
            DistrictsTableSeeder::class,
            CommunesTableSeeder::class,
            VillagesTableSeeder::class,

            NationalityTableSeeder::class,
            MotherTongTableSeeder::class,
            GenderTableSeeder::class,
            MaritalTableSeeder::class,
            BloodGroupTableSeeder::class,
            InstituteTableSeeder::class,

            StudentTableSeeder::class,
            FeatureSliderTableSeeder::class,
            SponsoredSeeder::class,
            StudyProgramsTableSeeder::class,
            StudyFacultyTableSeeder::class,
            CourseTyesTableSeeder::class,
            StudyModilityTableSeeder::class,
            StudyOverallFundTableSeeder::class,
            CurriculumAuthorTableSeeder::class,
            CurriculumEndorsementTableSeeder::class,
            StudyGenerationsTableSeeder::class,
            StudyCoursesTableSeeder::class,

            StudyAcademicYearsTableSeeder::class,
            StudySemestersTableSeeder::class,
            StudySessionsTableSeeder::class,
            StudyStatusTableSeeder::class,
            MonthsTableSeeder::class,
            DaysTableSeeder::class,
            StudyClassTableSeeder::class,

            StaffCertificateTableSeeder::class,
            StaffStatusTableSeeder::class,
            StaffDesignationsTableSeeder::class,
            StaffTableSeeder::class,

            StudyGradeTableSeeder::class,
            StudySubjectsTableSeeder::class,
            StudyCoursesScheduleTableSeeder::class,
            StudentsRequestTableSeeder::class,
            StudentStudyCourseTableSeeder::class,
            AttendancesTypeTableSeeder::class,
            CardFramesTableSeeder::class,
            CertificateFramesTableSeeder::class,
            ThemesColorsTableSeeder::class,
            ThemeBackgroundTableSeeder::class,
            AppTableSeeder::class,
            SocailMediaTableSeeder::class,
            HolidaysTableSeeder::class,
            LanguagesTableSeeder::class,
            //TranslatesTableSeeder::class,
            RolesTableSeeder::class,
            UsersTableSeeder::class,

            //ActivityFeedTableSeeder::class,
            QuizQuestionTypesTableSeeder::class,
            QuizTableSeeder::class,
            QuizAnswerTypesTableSeeder::class,
            QuizQuestionTableSeeder::class,
            QuizStudentsTableSeeder::class,
        ]);
    }
}
