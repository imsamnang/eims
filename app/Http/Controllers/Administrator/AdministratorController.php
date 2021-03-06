<?php

namespace App\Http\Controllers\Administrator;

use App\Models\App as AppModel;
use App\Models\Staff;
use App\Models\Users;
use App\Models\Students;
use App\Models\Institute;
use App\Models\Languages;
use App\Helpers\FormHelper;
use App\Helpers\ImageHelper;
use App\Helpers\MetaHelper;
use App\Models\ActivityFeed;
use App\Models\SocailsMedia;
use App\Models\StudyPrograms;
use App\Models\StaffInstitutes;
use App\Models\StudentsStudyCourse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\Study\StudyController;
use App\Http\Controllers\users\usersController;
use App\Http\Controllers\General\GeneralController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Students\StudentsController;
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\ActivityFeed\ActivityFeedController;
use App\Http\Controllers\Mailbox\MailboxController;
use App\Http\Controllers\Quiz\QuizController;
use App\Models\Mailbox;
use App\Models\Quiz;
use App\Models\StudentsRequest;
use App\Models\StudyCourseSchedule;
use App\Models\StudyCourseSession;


class AdministratorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Languages::setConfig();
        AppModel::setConfig();
        SocailsMedia::setConfig();
        view()->share('breadcrumb', []);
    }

    public function index($param1 = null, $param2 = null, $param3 = null, $param4 = null, $param5 = null, $param6 = null)
    {
        if (strtolower($param1) == null || strtolower($param1) == 'dashboard') {
            return $this->dashboard();
        } else {
            $controller = null;
            if (strtolower($param1) == ActivityFeed::path('url')) {
                $controller = ActivityFeed::path('controller');
            } elseif (strtolower($param1) == Mailbox::path('url')) {
                $view = new MailboxController();
                return $view->index($param2, $param3, $param4, $param5, $param6);
            } elseif (strtolower($param1) == Staff::path('url')) {
                $view = new StaffController();
                return $view->index($param2, $param3, $param4, $param5, $param6);
            } elseif (strtolower($param1) == Students::path('url')) {
                $view = new StudentsController();
                return $view->index($param2, $param3, $param4, $param5, $param6);
            } elseif (strtolower($param1) == 'study') {
                $view = new StudyController();
                return $view->index($param2, $param3, $param4, $param5, $param6);
            } elseif (strtolower($param1) == 'general') {
                $view = new GeneralController();
                return $view->index($param2, $param3, $param4, $param5, $param6);
            } elseif (strtolower($param1) == Users::path('url')) {
                $view = new UsersController();
                return $view->index($param2, $param3, $param4, $param5, $param6);
            } elseif (strtolower($param1) == AppModel::path('url')) {
                $view = new SettingsController();
                return $view->index($param2, $param3, $param4, $param5, $param6);
            } elseif (strtolower($param1) == 'profile') {
                $view = new ProfileController();
                return $view->index($param2, $param3, $param4, $param5, $param6);
            } elseif (strtolower($param1) == Quiz::path('url')) {
                $view = new QuizController();
                return $view->index($param2, $param3, $param4, $param5, $param6);
            }
        }


        abort(404);

    }

    public function dashboard()
    {

        view()->share('breadcrumb', [
            [
                'title' => __("Dashboard"),
                'status' => 'active',
                'icon'  => 'fas fa-home'
            ]
        ]);

        $data['title'] = __("Dashboard");
        $data['formData'] = null;
        $data['formName'] = null;
        $data['formAction'] = 'feed/post';
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
                'param1' => null,
                'param2' => null,
                'param3' => null,
            ),
            'search'     => parse_url(request()->getUri(), PHP_URL_QUERY) ? '?' . parse_url(request()->getUri(), PHP_URL_QUERY) : '',
            'form'       => FormHelper::form($data['formData'], $data['formName'], $data['formAction']),
            'parent'     => "Administrator",
            'view'       => Users::role('view_path') . ".includes.dashboard.index",
        );

        $institutes = Institute::get();

        $data['staff'] = array(
            [
                'title'   => __('Staff & Teacher'),
                'link'    => url(Users::role() . '/' . Staff::path('url') . '/list'),
                'icon'    => 'fas fa-chalkboard-teacher',
                'image'   => null,
                'gender'  => Staff::gender(Staff::join((new StaffInstitutes())->getTable(), (new Staff())->getTable() . '.id', (new StaffInstitutes())->getTable() . '.staff_id')->whereNotIn('staff_status_id', [1, 4])),
                'status'  => [], //Staff::staffStatus(Staff::join((new StaffInstitutes())->getTable(), (new Staff())->getTable().'.id', (new StaffInstitutes())->getTable().'.staff_id')),
                'color'   => 'blue',
            ],
        );

        if ($institutes) {
            foreach ($institutes as $row) {
                $data['staff'][] = [
                    'title'   => $row->{app()->getLocale()},
                    'link'    => url(Users::role() . '/' . Staff::path('url') . '/list?instituteId=' . $row->id),
                    'text'    => __('Staff & Teacher'),
                    'icon'    => 'fas fa-chalkboard-teacher',
                    'image'   => ImageHelper::site(Institute::path('url'), $row->image),
                    'gender'  => Staff::gender(Staff::join((new StaffInstitutes())->getTable(), (new Staff())->getTable() . '.id', (new StaffInstitutes())->getTable() . '.staff_id')->whereNotIn('staff_status_id', [1, 4])->where('institute_id', $row->id)),
                    'status'  => [], //Staff::staffStatus(Staff::join((new StaffInstitutes())->getTable(), (new Staff())->getTable().'.id', (new StaffInstitutes())->getTable().'.staff_id')->where('institute_id',$row['id'])),
                    'color'   => 'blue',
                ];
            }
        }

        $data['student'] = array(
            [
                'title'       => __('Students'),
                'link'        => url(Users::role() . '/' . Students::path('url') . '/list'),
                'icon'        => 'fas fa-user-graduate',
                'image'       => null,
                'gender'      => Students::gender(new Students),
                'status'      => [],
                'color'       => 'green',
            ],
            [
                'title'       => __('Student study course'),
                'link'        => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/list'),
                'icon'        => 'fas fa-user-graduate',
                'image'       => null,
                'gender'      => Students::gender(StudentsStudyCourse::join((new StudentsRequest())->getTable(), (new StudentsRequest())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.student_request_id')
                    ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsRequest())->getTable() . '.student_id')->whereNotIn('study_status_id', [7])),
                'status'      => [], //StudentsStudyCourse::studyStatus(StudentsStudyCourse::join((new Students())->getTable(), (new Students())->getTable().'.id', (new StudentsStudyCourse())->getTable().'.student_id')),
                'color'       => 'green',
            ],
        );

        if ($institutes) {
            foreach ($institutes as $row) {
                $data['student'][] = [
                    'title'   => $row->{app()->getLocale()},
                    'link'    => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/list?instituteId=' . $row->id),
                    'text'    => __('Student study course'),
                    'icon'    => 'fas fa-user-graduate',
                    'image'   => $row->image ? ImageHelper::site(Institute::path('url'), $row->image) : ImageHelper::prefix(),
                    'gender'  => Students::gender(
                        StudentsStudyCourse::join((new StudyCourseSession())->getTable(), (new StudyCourseSession())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.study_course_session_id')
                            ->join((new StudyCourseSchedule())->getTable(), (new StudyCourseSchedule())->getTable() . '.id', (new StudyCourseSession())->getTable() . '.study_course_schedule_id')
                            ->join((new StudentsRequest())->getTable(), (new StudentsRequest())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.student_request_id')
                            ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsRequest())->getTable() . '.student_id')
                            ->whereNotIn('study_status_id', [7])
                            ->where((new StudyCourseSchedule())->getTable() . '.institute_id', $row->id)
                    ),
                    'status'  => [], //StudentsStudyCourse::studyStatus(StudentsStudyCourse::join((new Students())->getTable(), (new Students())->getTable().'.id', (new StudentsStudyCourse())->getTable().'.student_id')->where('institute_id',$row['id'])),
                    'color'   => 'green',
                ];
            }
        }

        $studyPrograms = StudyPrograms::get();
        if ($studyPrograms) {
            foreach ($studyPrograms as $row) {
                $data['studyProgram'][] = [
                    'title'   => $row->{app()->getLocale()},
                    'link'    => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/list?programId=' . $row->id),
                    'icon'    => null,
                    'image'   => $row->image ? ImageHelper::site(StudyPrograms::path('url'), $row->image) : ImageHelper::prefix(),
                    'gender'  => Students::gender(
                        StudentsStudyCourse::join((new StudyCourseSession())->getTable(), (new StudyCourseSession())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.study_course_session_id')
                            ->join((new StudyCourseSchedule())->getTable(), (new StudyCourseSchedule())->getTable() . '.id', (new StudyCourseSession())->getTable() . '.study_course_schedule_id')
                            ->join((new StudentsRequest())->getTable(), (new StudentsRequest())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.student_request_id')
                            ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsRequest())->getTable() . '.student_id')
                            ->whereNotIn('study_status_id', [7])
                            ->where((new StudyCourseSchedule())->getTable() . '.study_program_id', $row->id)
                    ),
                    'status'  => [], //StudentsStudyCourse::studyStatus(StudentsStudyCourse::join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.student_id')->where('study_program_id', $row['id'])),
                    'color'   => config('app.theme_color.name'),
                ];
            }
        }

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view($pages['parent'] . '.index', $data);
    }
}
