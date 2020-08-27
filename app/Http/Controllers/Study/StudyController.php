<?php

namespace App\Http\Controllers\Study;

use App\Models\App as AppModel;
use App\Models\Users;
use App\Models\Institute;
use App\Models\Languages;
use App\Models\StudyClass;
use App\Models\StudyGrade;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Models\CourseTypes;
use App\Models\StudyCourse;
use App\Models\StudyStatus;
use App\Models\SocailsMedia;
use App\Models\StudyFaculty;
use App\Models\StudySession;
use App\Models\StudyModality;
use App\Models\StudyPrograms;
use App\Models\StudySubjects;
use App\Models\StudySemesters;
use App\Models\AttendanceTypes;
use App\Models\StudyGeneration;
use App\Models\CurriculumAuthor;
use App\Models\StudyOverallFund;
use App\Models\StudyAcademicYears;
use App\Models\StudyCourseRoutine;
use App\Models\StudyCourseSession;
use App\Models\StudySubjectLesson;
use App\Models\StudyCourseSchedule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\CurriculumEndorsement;
use App\Models\StudyShortCourseRoutine;
use App\Models\StudyShortCourseSession;
use App\Models\StudyShortCourseSchedule;
use App\Http\Controllers\Study\InstitutesController;
use App\Http\Controllers\Study\CourseTypesController;
use App\Http\Controllers\Study\StudyCoursesController;
use App\Http\Controllers\Study\StudyStatusesController;
use App\Http\Controllers\Study\StudyFacultiesController;
use App\Http\Controllers\Study\StudyProgramsController;
use App\Http\Controllers\Study\StudySessionsController;
use App\Http\Controllers\Study\StudyModalitiesController;
use App\Http\Controllers\Study\StudySemestersController;
use App\Http\Controllers\Study\StudyGenerationsController;
use App\Http\Controllers\Study\CurriculumAuthorsController;
use App\Http\Controllers\Study\StudyOverallFundsController;
use App\Http\Controllers\Study\StudyAcademicYearsController;
use App\Http\Controllers\Study\StudyCourseSchedulesController;
use App\Http\Controllers\Study\CurriculumEndorsementsController;

class StudyController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
        AppModel::setConfig();
        Languages::setConfig();
        AppModel::setConfig();
        SocailsMedia::setConfig();
        view()->share('breadcrumb', []);
    }


    public function index($param1 = null, $param2 = null, $param3 = null)
    {

        $data['formData'] = array(
            'image' => asset('/assets/img/icons/image.jpg'),
        );
        $data['formName']   = 'Study';
        $data['formAction'] = '/add';
        $data['listData']   = array();


        if ($param1 == null) {

            $data['shortcuts'] = [
                [
                    'title' => null,
                    'children' => [
                        [
                            'name'  => __('List Institute'),
                            'link'  => url(Users::role() . '/study/' . Institute::path('url') . '/list'),
                            'icon'  => 'fas fa-school',
                            'image' => null,
                            'color' => 'bg-' . config('app.theme_color.name'),
                        ],
                        [
                            'name'  => __('List Study Programs'),
                            'link'  => url(Users::role() . '/study/' . StudyPrograms::path('url') . '/list'),
                            'icon'  => 'fas fa-graduation-cap',
                            'image' => null,
                            'color' => 'bg-' . config('app.theme_color.name'),
                        ], [
                            'name'  => __('List Study Course'),
                            'link'  => url(Users::role() . '/study/' . StudyCourse::path('url') . '/list'),
                            'icon'  => 'fas fa-user-hard-hat',
                            'image' => null,
                            'color' => 'bg-' . config('app.theme_color.name'),
                        ], [
                            'name'  => __('List Course Type'),
                            'link'  => url(Users::role() . '/study/' . CourseTypes::path('url') . '/list'),
                            'icon'  => 'fas fa-hard-hat',
                            'image' => null,
                            'color' => 'bg-' . config('app.theme_color.name'),
                        ],
                        [
                            'name'  => __('List Study Generation'),
                            'link'  => url(Users::role() . '/study/' . StudyGeneration::path('url') . '/list'),
                            'icon'  => null,
                            'text'  => __('Study Generation'),
                            'image' => null,
                            'color' => 'bg-' . config('app.theme_color.name'),
                        ], [
                            'name'  => __('List Study Academic Years'),
                            'link'  => url(Users::role() . '/study/' . StudyAcademicYears::path('url') . '/list'),
                            'icon'  => null,
                            'text'  => __('Study Academic Years'),
                            'image' => null,
                            'color' => 'bg-' . config('app.theme_color.name'),
                        ], [
                            'name'  => __('List Study Semesters'),
                            'link'  => url(Users::role() . '/study/' . StudySemesters::path('url') . '/list'),
                            'icon'  => null,
                            'text'  => __('Study Semesters'),
                            'image' => null,
                            'color' => 'bg-' . config('app.theme_color.name'),
                        ]
                    ]
                ],
                [
                    'title' => null,
                    'children'  => [
                        [
                            'name'  => __('List Study Session'),
                            'link'  => url(Users::role() . '/study/' . StudySession::path('url') . '/list'),
                            'icon'  => 'fas fa-hourglass-start',
                            'image' => null,
                            'color' => 'bg-' . config('app.theme_color.name'),
                        ],
                        [
                            'name'  => __('List Study Class'),
                            'link'  => url(Users::role() . '/study/' . StudyClass::path('url') . '/list'),
                            'icon'  => null,
                            'text'  => __('Study Class'),
                            'image' => null,
                            'color' => 'bg-' . config('app.theme_color.name'),
                        ],
                    ]
                ],

                [
                    'title' => __('Study Course Schedule'),
                    'children'  => [
                        [
                            'name'  => __('List Study Course Schedule'),
                            'link'  => url(Users::role() . '/study/' . StudyCourseSchedule::path('url') . '/list'),
                            'icon'  => 'fas fa-calendar-alt',
                            'image' => null,
                            'color' => 'bg-' . config('app.theme_color.name'),
                        ],
                        [
                            'name'  => __('List Study Course Session'),
                            'link'  => url(Users::role() . '/study/' . StudyCourseSession::path('url') . '/list'),
                            'icon'  => 'fas fa-table',
                            'image' => null,
                            'color' => 'bg-' . config('app.theme_color.name'),
                        ], [
                            'name'  => __('List Study Course Routine'),
                            'link'  => url(Users::role() . '/study/' . StudyCourseRoutine::path('url') . '/list'),
                            'icon'  => 'fal fa-table',
                            'image' => null,
                            'color' => 'bg-' . config('app.theme_color.name'),
                        ]
                    ]
                ],
                [
                    'title' => __('Short Course Schedule'),
                    'children'  => [
                        [
                            'name'  => __('List Short Course Schedule'),
                            'link'  => url(Users::role() . '/study/' . StudyShortCourseSchedule::path('url') . '/list'),
                            'icon'  => 'fas fa-calendar-alt',
                            'image' => null,
                            'color' => 'bg-' . config('app.theme_color.name'),
                        ],
                        [
                            'name'  => __('List Short Course Session'),
                            'link'  => url(Users::role() . '/study/' . StudyShortCourseSession::path('url') . '/list'),
                            'icon'  => 'fas fa-table',
                            'image' => null,
                            'color' => 'bg-' . config('app.theme_color.name'),
                        ],
                        [
                            'name'  => __('List Short Course Routine'),
                            'link'  => url(Users::role() . '/study/' . StudyShortCourseRoutine::path('url') . '/list'),
                            'icon'  => 'fal fa-table',
                            'image' => null,
                            'color' => 'bg-' . config('app.theme_color.name'),
                        ]
                    ]
                ],
                [
                    'title' => __('Study Subjects'),
                    'children'  => [
                        [
                            'name'  => __('List Study Subjects'),
                            'link'  => url(Users::role() . '/study/' . StudySubjects::path('url') . '/list'),
                            'icon'  => 'fas fa-books',
                            'image' => null,
                            'color' => 'bg-' . config('app.theme_color.name'),
                        ],
                        [
                            'name'  => __('List Study Subjects Lesson'),
                            'link'  => url(Users::role() . '/study/' . StudySubjectLesson::path('url') . '/list'),
                            'icon'  => 'fas fa-book-alt',
                            'image' => null,
                            'color' => 'bg-' . config('app.theme_color.name'),
                        ],
                    ]
                ],
                [
                    'title' => null,
                    'children'  => [
                        [
                            'name'  => __('List Study Modality'),
                            'link'  => url(Users::role() . '/study/' . StudyModality::path('url') . '/list'),
                            'icon'  => 'fas fa-chalkboard',
                            'image' => null,
                            'color' => 'bg-' . config('app.theme_color.name'),
                        ], [
                            'name'  => __('List Study Overall Fund'),
                            'link'  => url(Users::role() . '/study/' . StudyOverallFund::path('url') . '/list'),
                            'icon'  => 'fas fa-donate',
                            'image' => null,
                            'color' => 'bg-' . config('app.theme_color.name'),
                        ], [
                            'name'  => __('List Study Faculty'),
                            'link'  => url(Users::role() . '/study/' . StudyFaculty::path('url') . '/list'),
                            'icon'  => 'fas fa-industry-alt',
                            'image' => null,
                            'color' => 'bg-' . config('app.theme_color.name'),
                        ], [
                            'name'  => __('List Study Status'),
                            'link'  => url(Users::role() . '/study/' . StudyStatus::path('url') . '/list'),
                            'icon'  => 'fas fa-question-square',
                            'image' => null,
                            'color' => 'bg-' . config('app.theme_color.name'),
                        ],  [
                            'name'  => __('List Curriculum Authors'),
                            'link'  => url(Users::role() . '/study/' . CurriculumAuthor::path('url') . '/list'),
                            'icon'  => 'fas fa-book-user',
                            'image' => null,
                            'color' => 'bg-' . config('app.theme_color.name'),
                        ], [
                            'name'  => __('List Curriculum Endorsements'),
                            'link'  => url(Users::role() . '/study/' . CurriculumEndorsement::path('url') . '/list'),
                            'icon'  => 'fas fa-people-carry',
                            'image' => null,
                            'color' => 'bg-' . config('app.theme_color.name'),
                        ],
                        [
                            'name'  => __('List Study Grade'),
                            'link'  => url(Users::role() . '/study/' . StudyGrade::path('url') . '/list'),
                            'icon'  => null,
                            'text'  => __('Study Grade'),
                            'image' => null,
                            'color' => 'bg-' . config('app.theme_color.name'),
                        ],  [
                            'name'  => __('List Attendance Types'),
                            'link'  => url(Users::role() . '/study/' . AttendanceTypes::path('url') . '/list'),
                            'icon'  => null,
                            'text'  => __('Attendance Types'),
                            'image' => null,
                            'color' => 'bg-' . config('app.theme_color.name'),
                        ]
                    ]
                ]

            ];

            if (Auth::user()->role_id != 1) {
                unset($data['shortcuts'][0]['children'][0]);
            }


            $data['view']  = 'Study.includes.dashboard.index';
            $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('Study');
        } elseif (strtolower($param1) == StudyPrograms::path('url')) {
            $view = new StudyProgramsController();
            return $view->index($param2, $param3);
        } elseif (strtolower($param1) == Institute::path('url') && Auth::user()->role_id == 1) {
            $view = new InstitutesController();
            return $view->index($param2, $param3);
        } elseif (strtolower($param1) == StudyCourse::path('url')) {
            $view = new StudyCoursesController();
            return $view->index($param2, $param3);
        } elseif (strtolower($param1) == CourseTypes::path('url')) {
            $view = new CourseTypesController();
            return $view->index($param2, $param3);
        } elseif (strtolower($param1) == StudyCourseSchedule::path('url')) {
            $view = new StudyCourseSchedulesController();
            return $view->index($param2, $param3);
        } elseif (strtolower($param1) == StudyCourseSession::path('url')) {
            $view = new StudyCourseSessionsController();
            return $view->index($param2, $param3);
        } elseif (strtolower($param1) == StudyCourseRoutine::path('url')) {
            $view = new StudyCourseRoutinesController();
            return $view->index($param2, $param3);
        } elseif (strtolower($param1) == StudyModality::path('url')) {
            $view = new StudyModalitiesController();
            return $view->index($param2, $param3);
        } elseif (strtolower($param1) == StudyOverallFund::path('url')) {
            $view = new StudyOverallFundsController();
            return $view->index($param2, $param3);
        } elseif (strtolower($param1) == StudyFaculty::path('url')) {
            $view = new StudyFacultiesController();
            return $view->index($param2, $param3);
        } elseif (strtolower($param1) == StudyGeneration::path('url')) {
            $view = new StudyGenerationsController();
            return $view->index($param2, $param3);
        } elseif (strtolower($param1) == StudyAcademicYears::path('url')) {
            $view = new StudyAcademicYearsController();
            return $view->index($param2, $param3);
        } elseif (strtolower($param1) == StudySemesters::path('url')) {
            $view = new StudySemestersController();
            return $view->index($param2, $param3);
        } elseif (strtolower($param1) == StudyStatus::path('url')) {
            $view = new StudyStatusesController();
            return $view->index($param2, $param3);
        } elseif (strtolower($param1) == StudySession::path('url')) {
            $view = new StudySessionsController();
            return $view->index($param2, $param3);
        } elseif (strtolower($param1) == CurriculumAuthor::path('url')) {
            $view = new CurriculumAuthorsController();
            return $view->index($param2, $param3);
        } elseif (strtolower($param1) == CurriculumEndorsement::path('url')) {
            $view = new CurriculumEndorsementsController();
            return $view->index($param2, $param3);
        } elseif (strtolower($param1) == StudySubjects::path('url')) {
            $view = new StudySubjectsController();
            return $view->index($param2, $param3);
        } elseif (strtolower($param1) == StudySubjectLesson::path('url')) {
            $view = new StudySubjectLessonsController();
            return $view->index($param2, $param3);
        } elseif (strtolower($param1) == StudyClass::path('url')) {
            $view = new StudyClassesController();
            return $view->index($param2, $param3);
        } elseif (strtolower($param1) == AttendanceTypes::path('url')) {
            $view = new AttendanceTypesController();
            return $view->index($param2, $param3);
        } elseif (strtolower($param1) == StudyGrade::path('url')) {
            $view = new StudyGradesController();
            return $view->index($param2, $param3);
        } elseif (strtolower($param1) == StudyShortCourseSchedule::path('url')) {
            $view = new StudyShortCourseScheduleController;
            return $view->index($param2, $param3);
        } elseif (strtolower($param1) == StudyShortCourseSession::path('url')) {
            $view = new StudyShortCourseSessionController;
            return $view->index($param2, $param3);
        } elseif (strtolower($param1) == StudyShortCourseRoutine::path('url')) {
            $view = new StudyShortCourseRoutineController;
            return $view->index($param2, $param3);
        } else {
            abort(404);
        }

        MetaHelper::setConfig([
            'title'       => $data['title'],
            'author'      => config('app.name'),
            'keywords'    => '',
            'description' => '',
            'link'        => null,
            'image'       => null
        ]);
        $pages = array(
            'host'       => url('/'),
            'path'       => '/' . Users::role(),
            'pathview'   => '/' . $data['formName'] . '/',
            'parameters' => array(
                'param1' => $param1,
                'param2' => $param2,
                'param3' => $param3,
            ),
            'search'     => parse_url(request()->getUri(), PHP_URL_QUERY) ? '?' . parse_url(request()->getUri(), PHP_URL_QUERY) : '',
            'form'       => FormHelper::form($data['formData'], $data['formName'], $data['formAction']),
            'parent'     => 'Study',
            'view'       => $data['view'],
        );
        $pages['form']['validate'] = [];

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view($pages['parent'] . '.index', $data);
    }
}
