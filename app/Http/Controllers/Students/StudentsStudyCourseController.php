<?php

namespace App\Http\Controllers\Students;

use Carbon\Carbon;
use App\Models\App;
use App\Models\Staff;
use App\Models\Users;
use App\Models\Years;
use App\Models\Gender;
use App\Models\Months;
use App\Models\Students;
use App\Helpers\QRHelper;
use App\Models\Institute;
use App\Models\Languages;
use App\Models\CardFrames;
use App\Helpers\DateHelper;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Models\StudyCourse;
use App\Models\StudyStatus;
use App\Helpers\ImageHelper;
use App\Helpers\KhmerNumber;
use App\Models\SocailsMedia;
use App\Models\StudySession;
use App\Models\StudyPrograms;
use App\Models\StudySemesters;
use App\Models\StudentsRequest;
use App\Models\StudyGeneration;
use App\Models\CertificateFrames;
use App\Models\StudyAcademicYears;
use App\Models\StudyCourseSession;
use Illuminate\Support\Collection;
use App\Models\StudentsAttendances;
use App\Models\StudentsStudyCourse;
use App\Models\StudyCourseSchedule;
use App\Http\Controllers\Controller;
use App\Models\StudentsStudyCourseScore;
use App\Http\Controllers\CardFrames\CardFramesController;
use App\Http\Requests\FormStudentsStudyCourse;
use App\Http\Controllers\Photo\PhotoController;
use App\Http\Controllers\QrCode\QrCodeController;
use App\Http\Controllers\CertificateFrames\CertificateFramesController;
use App\Http\Controllers\Students\StudentsAttendanceController;
use App\Http\Controllers\Students\StudentsStudyCourseScoreController;

class StudentsStudyCourseController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
        App::setConfig();
        Languages::setConfig();
        App::setConfig();
        SocailsMedia::setConfig();
        view()->share('breadcrumb', []);
    }

    public function index($param1 = 'list', $param2 = null, $param3 = null)
    {
        $breadcrumb  = [
            [
                'title' => __('Students'),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . Students::path('url')),
            ],
            [
                'title' => __('List Student study course'),
                'status' => false,
                'link'  => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/list'),
            ]
        ];

        $data['study_course_session']['data'] = StudyCourseSession::join((new StudyCourseSchedule)->getTable(), (new StudyCourseSchedule)->getTable() . '.id', (new StudyCourseSession)->getTable() . '.study_course_schedule_id')
        ->get([
            (new StudyCourseSchedule)->getTable() . '.*',
            (new StudyCourseSession)->getTable() . '.*',
        ])->map(function ($row) {
            $row['study_generation'] = StudyGeneration::where('id', $row->study_generation_id)->pluck(app()->getLocale())->first();
            $row['study_program'] = StudyPrograms::where('id', $row->study_program_id)->pluck(app()->getLocale())->first();
            $row['study_course'] = StudyCourse::where('id', $row->study_course_id)->pluck(app()->getLocale())->first();
            $row['study_academic_year'] = StudyAcademicYears::where('id', $row->study_academic_year_id)->pluck(app()->getLocale())->first();
            $row['study_semester'] = StudySemesters::where('id', $row->study_semester_id)->pluck(app()->getLocale())->first();
            $row['study_session'] = StudySession::where('id', $row->study_session_id)->pluck(app()->getLocale())->first();

            $row['study'] = $row['study_generation'] . ' ('  . $row['study_program'] . ' - ' . $row['study_course'] . ' - ' . $row['study_academic_year']. ' - ' . $row['study_semester']. ' - ' . $row['study_session'] . ')';

            $row['study_start'] = DateHelper::convert($row->study_start, 'd-M-Y');
            $row['study_end'] = DateHelper::convert($row->study_end, 'd-M-Y');

            $row['study'] .= ' - (' . $row['study_start'] . ' - ' . $row['study_end'] . ')';

            $row['name']   = $row['study'];
            return $row;
        });
        $data['formAction']           = '/add';
        $data['formName']             = Students::path('url') . '/' . StudentsStudyCourse::path('url');
        $data['title']              = Users::role(app()->getLocale()) . ' | ' . __('List Students study course');
        $data['metaImage']            = asset('assets/img/icons/' . $param1 . '.png');
        $data['metaLink']             = url(Users::role() . '/' . $param1);

        $data['formData']       = array(
            'photo' => asset('/assets/img/user/male.jpg'),
        );
        $data['listData']       = array();
        $id = $param2 ? $param2 : request('id');

        if ($param1 == null || $param1 == 'list') {
            $breadcrumb[1]['status']  = 'active';
            request()->merge(['id' => $id]);
            $data  = $this->list($data);

            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'add') {
            $breadcrumb[]  = [
                'title' => __($param1),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/' . $param1),
            ];

            if (request()->method() === 'POST') {
                return StudentsStudyCourse::addToTable();
            }
            $data  = $this->show($data, null, $param1);
        } elseif ($param1 == 'edit') {

            request()->merge(['id' => $id]);

            $breadcrumb[]  = [
                'title' => __($param1),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/' . $param1 . '/' . $id),
            ];

            if (request()->method() === 'POST') {
                return StudentsStudyCourse::updateToTable($id);
            }
            $data  = $this->show($data, $id, $param1);
            $data['study_status']['data']  = StudyStatus::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = $row->image ? ImageHelper::site(StudyStatus::path('image'), $row->image) : ImageHelper::prefix();
                return $row;
            });
        } elseif ($param1 == 'view') {
            $id = request('id', $param2);
            $breadcrumb[]  = [
                'title' => __($param1),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/' . $param1 . '/' . $id),
            ];
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('View Staff');
            $data['response']['data'] = StudentsStudyCourse::join((new StudentsRequest())->getTable(), (new StudentsRequest())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.student_request_id')
                ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsRequest())->getTable() . '.student_id')
                ->join((new StudyCourseSession())->getTable(), (new StudyCourseSession())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.study_course_session_id')
                ->join((new StudyCourseSchedule())->getTable(), (new StudyCourseSchedule())->getTable() . '.id', (new StudyCourseSession())->getTable() . '.study_course_schedule_id')
                ->whereIn((new StudentsStudyCourse())->getTable() . '.id', explode(',', $id))
                ->get([
                    (new StudyCourseSchedule())->getTable() . '.*',
                    (new StudyCourseSession())->getTable() . '.study_session_id',

                    (new StudentsStudyCourse())->getTable() . '.id',
                    (new StudentsRequest())->getTable() . '.student_id',
                    (new Students())->getTable() . '.first_name_km',
                    (new Students())->getTable() . '.last_name_km',
                    (new Students())->getTable() . '.first_name_en',
                    (new Students())->getTable() . '.last_name_en',
                    (new Students())->getTable() . '.gender_id',
                    (new Students())->getTable() . '.photo',
                    (new Students())->getTable() . '.email',
                    (new Students())->getTable() . '.phone',
                    (new StudentsStudyCourse())->getTable() . '.created_at',
                    (new StudentsStudyCourse())->getTable() . '.photo as photo_crop',
                    (new StudentsStudyCourse())->getTable() . '.card',
                    (new StudentsStudyCourse())->getTable() . '.qrcode',
                    (new StudentsStudyCourse())->getTable() . '.certificate',
                    (new StudentsStudyCourse())->getTable() . '.study_status_id',

                ])->map(function ($row) {

                    $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                    $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
                    $row['photo'] = $row['photo'] ? ImageHelper::site(Students::path('image'), $row['photo']) : ImageHelper::site(Students::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
                    $row['photo_crop'] = ImageHelper::site(Students::path('image') . '/' . StudentsStudyCourse::path('image'), $row->photo_crop);
                    $row['card'] = ImageHelper::site(Students::path('image') . '/' . CardFrames::path('image'), $row->card);
                    $row['certificate'] = ImageHelper::site(Students::path('image') . '/' . CertificateFrames::path('image'), $row->certificate);
                    $row['qrcode'] = ImageHelper::site(Students::path('image') . '/' . QRHelper::path('image'), $row->qrcode);


                    $row['account'] = Users::where('email', $row->email)->where('node_id', $row->student_id)->exists();
                    $row['study_program'] = StudyPrograms::where('id', $row->study_program_id)->pluck(app()->getLocale())->first();
                    $row['study_course'] = StudyCourse::where('id', $row->study_course_id)->pluck(app()->getLocale())->first();
                    $row['study_generation'] = StudyGeneration::where('id', $row->study_generation_id)->pluck(app()->getLocale())->first();
                    $row['study_academic_year'] = StudyAcademicYears::where('id', $row->study_academic_year_id)->pluck(app()->getLocale())->first();
                    $row['study_semester'] = StudySemesters::where('id', $row->study_semester_id)->pluck(app()->getLocale())->first();
                    $row['study_session'] = StudySession::where('id', $row->study_session_id)->pluck(app()->getLocale())->first();
                    $row['study_status'] = StudyStatus::where('id', $row->study_status_id)->pluck(app()->getLocale())->first();
                    $row['study'] = $row['study_program'] . ' (' . $row['study_course'] . ' - ' . $row['study_generation'] . ', ' . $row['study_academic_year'] . ', ' . $row['study_semester'] . ', ' . $row['study_session'] . ')';
                    $row['action']  = [
                        'account'   => url(Users::role() . '/' . Students::path('url') . '/account/create/' . $row['id']),
                        'edit'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/edit/' . $row['id']),
                        'view'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/view/' . $row['id']),
                        'photo'  => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/photo/crop/' . $row['id']),
                        'qrcode' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/' . QRHelper::path('url') . '/make/' . $row['id']),
                        'card'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/' . CardFrames::path('url') . '/make/' . $row['id']),
                        'certificate' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/' . CertificateFrames::path('url') . '/make/' . $row['id']),
                        'account' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/account/create/' . $row['id']),
                        'delete' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/delete/' . $row['id']),
                    ];

                    $row['suggest_role']       = Students::path('roleId');
                    return $row;
                });
            $data['formAction']          = '/view/' . $id;
            $data['view']  = StudentsStudyCourse::path('view') . '.includes.view.index';
        } elseif ($param1 == 'photo') {
            $id =  request('id', $param3);

            $view = new PhotoController();
            if (request()->method() === 'POST') {
                return StudentsStudyCourse::makeImageToTable($id);
            }
            $nodes = StudentsStudyCourse::join((new StudentsRequest())->getTable(), (new StudentsRequest())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.student_request_id')
                ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsRequest())->getTable() . '.student_id')
                ->join((new StudyCourseSession())->getTable(), (new StudyCourseSession())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.study_course_session_id')
                ->join((new StudyCourseSchedule())->getTable(), (new StudyCourseSchedule())->getTable() . '.id', (new StudyCourseSession())->getTable() . '.study_course_schedule_id')
                ->whereIn((new StudentsStudyCourse())->getTable() . '.id', explode(',', $id))
                ->get([
                    (new StudyCourseSchedule())->getTable() . '.*',
                    (new StudyCourseSession())->getTable() . '.study_session_id',

                    (new StudentsStudyCourse())->getTable() . '.id',
                    (new StudentsRequest())->getTable() . '.student_id',
                    (new Students())->getTable() . '.first_name_km',
                    (new Students())->getTable() . '.last_name_km',
                    (new Students())->getTable() . '.first_name_en',
                    (new Students())->getTable() . '.last_name_en',
                    (new Students())->getTable() . '.gender_id',
                    (new Students())->getTable() . '.email',
                    (new Students())->getTable() . '.phone',
                    (new Students())->getTable() . '.photo',
                    (new StudentsStudyCourse())->getTable() . '.created_at',
                    (new StudentsStudyCourse())->getTable() . '.photo as photo_crop',
                    (new StudentsStudyCourse())->getTable() . '.study_status_id',

                ])->map(function ($row) {
                    $row['photo'] = $row['photo'] ? ImageHelper::site(Students::path('image'), $row['photo']) : ImageHelper::site(Students::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
                    $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                    $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
                    $row['photo_crop'] = ImageHelper::site(Students::path('image') . '/' . StudentsStudyCourse::path('image'), $row->photo_crop);

                    $row['study_program'] = StudyPrograms::where('id', $row->study_program_id)->pluck(app()->getLocale())->first();
                    $row['study_course'] = StudyCourse::where('id', $row->study_course_id)->pluck(app()->getLocale())->first();
                    $row['study_generation'] = StudyGeneration::where('id', $row->study_generation_id)->pluck(app()->getLocale())->first();
                    $row['study_academic_year'] = StudyAcademicYears::where('id', $row->study_academic_year_id)->pluck(app()->getLocale())->first();
                    $row['study_semester'] = StudySemesters::where('id', $row->study_semester_id)->pluck(app()->getLocale())->first();
                    $row['study_session'] = StudySession::where('id', $row->study_session_id)->pluck(app()->getLocale())->first();
                    $row['study_status'] = StudyStatus::where('id', $row->study_status_id)->pluck(app()->getLocale())->first();
                    $row['study'] = $row['study_program'] . ' (' . $row['study_course'] . ' - ' . $row['study_generation'] . ', ' . $row['study_academic_year'] . ', ' . $row['study_semester'] . ', ' . $row['study_session'] . ')';

                    $row['action']  = [
                        'photo'  => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/photo/crop/' . $row['id']),
                    ];

                    return $row;
                });
            return $view->index($param2, $param3, $nodes);
        } elseif ($param1 == 'qrcode') {
            $id = request('id', $param3);
            request()->merge(['id' => $id, 'qrcode_type' => Students::path('role')]);
            if (request()->method() === 'POST') {
                return StudentsStudyCourse::makeQrcodeToTable($id);
            }
            $view = new QrCodeController();
            $nodes = StudentsStudyCourse::join((new StudentsRequest())->getTable(), (new StudentsRequest())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.student_request_id')
                ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsRequest())->getTable() . '.student_id')
                ->join((new StudyCourseSession())->getTable(), (new StudyCourseSession())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.study_course_session_id')
                ->join((new StudyCourseSchedule())->getTable(), (new StudyCourseSchedule())->getTable() . '.id', (new StudyCourseSession())->getTable() . '.study_course_schedule_id')
                ->whereIn((new StudentsStudyCourse())->getTable() . '.id', explode(',', $id))
                ->get([
                    (new StudyCourseSchedule())->getTable() . '.*',
                    (new StudyCourseSession())->getTable() . '.study_session_id',

                    (new StudentsStudyCourse())->getTable() . '.id',
                    (new StudentsRequest())->getTable() . '.student_id',
                    (new Students())->getTable() . '.first_name_km',
                    (new Students())->getTable() . '.last_name_km',
                    (new Students())->getTable() . '.first_name_en',
                    (new Students())->getTable() . '.last_name_en',
                    (new Students())->getTable() . '.gender_id',
                    (new Students())->getTable() . '.email',
                    (new Students())->getTable() . '.phone',
                    (new Students())->getTable() . '.photo',
                    (new StudentsStudyCourse())->getTable() . '.created_at',
                    (new StudentsStudyCourse())->getTable() . '.qrcode',


                ])->map(function ($row) {
                    $row['photo'] = $row['photo'] ? ImageHelper::site(Students::path('image'), $row['photo']) : ImageHelper::site(Students::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
                    $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                    $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();

                    $row['study_program'] = StudyPrograms::where('id', $row->study_program_id)->pluck(app()->getLocale())->first();
                    $row['study_course'] = StudyCourse::where('id', $row->study_course_id)->pluck(app()->getLocale())->first();
                    $row['study_generation'] = StudyGeneration::where('id', $row->study_generation_id)->pluck(app()->getLocale())->first();
                    $row['study_academic_year'] = StudyAcademicYears::where('id', $row->study_academic_year_id)->pluck(app()->getLocale())->first();
                    $row['study_semester'] = StudySemesters::where('id', $row->study_semester_id)->pluck(app()->getLocale())->first();
                    $row['study_session'] = StudySession::where('id', $row->study_session_id)->pluck(app()->getLocale())->first();
                    $row['study'] = $row['study_program'] . ' (' . $row['study_course'] . ' - ' . $row['study_generation'] . ', ' . $row['study_academic_year'] . ', ' . $row['study_semester'] . ', ' . $row['study_session'] . ')';

                    $date = (new Carbon)->addYear(1);
                    $qrcode  = QRHelper::encrypt([
                        'stuId'  => $row['student_request_id'],
                        'id'     => $row['id'],
                        'type'   => Students::path('role'),
                        'exp'    => $date->format('Y-m-d'),
                    ], '?fc');
                    $row['qrcode_url']  = $qrcode;
                    $row['action']  = [
                        'qrcode'  => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/qrcode/make/' . $row['id']),
                    ];

                    return $row;
                });

            return $view->index($param2, $param3, $nodes);
        } elseif ($param1 == CardFrames::path('url')) {
            $id =  request('id', $param3);
            request()->merge(['id' => $id]);
            if (request()->method() == "POST") {
                if ($param2 == "save") {
                    return StudentsStudyCourse::makeCardToTable();
                }
            }
            $view = new CardFramesController();
            $nodes = StudentsStudyCourse::join((new StudentsRequest())->getTable(), (new StudentsRequest())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.student_request_id')
                ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsRequest())->getTable() . '.student_id')
                ->join((new StudyCourseSession())->getTable(), (new StudyCourseSession())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.study_course_session_id')
                ->join((new StudyCourseSchedule())->getTable(), (new StudyCourseSchedule())->getTable() . '.id', (new StudyCourseSession())->getTable() . '.study_course_schedule_id')
                ->whereIn((new StudentsStudyCourse())->getTable() . '.id', explode(',', $id))
                ->get([
                    (new StudyCourseSchedule())->getTable() . '.study_course_id',
                    (new StudentsStudyCourse())->getTable() . '.id',
                    (new Students())->getTable() . '.first_name_km',
                    (new Students())->getTable() . '.last_name_km',
                    (new Students())->getTable() . '.first_name_en',
                    (new Students())->getTable() . '.last_name_en',
                    (new Students())->getTable() . '.gender_id',
                    (new Students())->getTable() . '.photo',
                    (new StudentsStudyCourse())->getTable() . '.qrcode',
                    (new StudentsStudyCourse())->getTable() . '.photo as photo_crop',


                ])->map(function ($row) {
                    $row['photo'] = $row['photo'] ? ImageHelper::site(Students::path('image'), $row['photo']) : ImageHelper::site(Students::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
                    $row['photo_crop'] = ImageHelper::site(Students::path('image') . '/' . StudentsStudyCourse::path('image'), $row->photo_crop, 'original');
                    $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
                    $row['study_course'] = StudyCourse::where('id', $row->study_course_id)->pluck(app()->getLocale())->first();
                    $row['qrcode'] = ImageHelper::site(Students::path('image') . '/' . QRHelper::path('image'), $row->qrcode, 'original');

                    return  [
                        'realId'       =>  $row->id,
                        'id'           => 'student-' . $row->id,
                        'fullname'     => $row->first_name_km . ' ' . $row->last_name_km,
                        '_fullname'    => $row->first_name_en . ' ' . $row->last_name_en,
                        'photo'        => $row['photo_crop'] ? $row['photo_crop'] : $row['photo'],
                        'qrcode'       => $row['qrcode'],
                        'gender'       => $row['gender'],
                        'course'       => $row['study_course'],
                        'action'  => [
                            'card'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/' . CardFrames::path('url') . '/make/' . $row['id']),
                        ]
                    ];
                });
            return $view->index($param2, $param3, $nodes);
        } elseif ($param1 == StudentsStudyCourseScore::path('url')) {
            $view = new StudentsStudyCourseScoreController();
            return $view->index($param2, $param3);
        } elseif ($param1 == CertificateFrames::path('url')) {
            $id =  request('id', $param3);
            request()->merge(['id' => $id]);
            if (request()->method() == "POST") {
                if ($param2 == "save") {
                    return StudentsStudyCourse::makeCertificateToTable();
                }
            }
            $view = new CertificateFramesController();
            $nodes = StudentsStudyCourse::join((new StudentsRequest())->getTable(), (new StudentsRequest())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.student_request_id')
                ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsRequest())->getTable() . '.student_id')
                ->join((new StudyCourseSession())->getTable(), (new StudyCourseSession())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.study_course_session_id')
                ->join((new StudyCourseSchedule())->getTable(), (new StudyCourseSchedule())->getTable() . '.id', (new StudyCourseSession())->getTable() . '.study_course_schedule_id')
                ->whereIn((new StudentsStudyCourse())->getTable() . '.id', explode(',', $id))
                ->get([
                    (new StudyCourseSchedule())->getTable() . '.study_course_id',
                    (new StudentsStudyCourse())->getTable() . '.id',
                    (new Students())->getTable() . '.first_name_km',
                    (new Students())->getTable() . '.last_name_km',
                    (new Students())->getTable() . '.first_name_en',
                    (new Students())->getTable() . '.last_name_en',
                    (new Students())->getTable() . '.gender_id',
                    (new Students())->getTable() . '.photo',
                    (new Students())->getTable() . '.date_of_birth',
                    (new StudentsStudyCourse())->getTable() . '.qrcode',
                    (new StudentsStudyCourse())->getTable() . '.photo as photo_crop',


                ])->map(function ($row) {
                    $row['photo'] = $row['photo'] ? ImageHelper::site(Students::path('image'), $row['photo']) : ImageHelper::site(Students::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
                    $row['photo_crop'] = ImageHelper::site(Students::path('image') . '/' . StudentsStudyCourse::path('image'), $row->photo_crop, 'original');
                    $row['gender'] = Gender::where('id', $row->gender_id)->get()->first();
                    $row['study_course'] = StudyCourse::where('id', $row->study_course_id)->get()->first();
                    $row['qrcode'] = ImageHelper::site(Students::path('image') . '/' . QRHelper::path('image'), $row->qrcode, 'original');
                    $k = new KhmerNumber;

                    return  [
                        'realId'       =>  $row->id,
                        'id'           => 'student-' . $row->id,
                        'fullname'     => $row->first_name_km . ' ' . $row->last_name_km,
                        '_fullname'    => $row->first_name_en . ' ' . $row->last_name_en,
                        'photo'        => $row['photo_crop'] ? $row['photo_crop'] : $row['photo'],
                        'qrcode'       => $row['qrcode'],
                        'gender'       => $row['gender']['km'],
                        '_gender'      => $row['gender']['en'],
                        'course'       => $row['study_course']['km'],
                        '_course'      => $row['study_course']['en'],
                        'dob'          =>  $k->convert(DateHelper::convert($row->date_of_birth, 'd-M-Y')),
                        '_dob'          => DateHelper::convert($row->date_of_birth, 'd-F-Y', false),
                        'action'  => [
                            'certificate'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/' . CertificateFrames::path('url') . '/make/' . $row['id']),
                        ]
                    ];
                });

            return $view->index($param2, $param3, $nodes);
        } elseif ($param1 == StudentsAttendances::path('url')) {

            $view = new StudentsAttendanceController();
            $monthYear =  request('month_year') ? explode('-', request('month_year')) : null;
            request()->merge([
                'year'  => $monthYear ? $monthYear[1] : Years::now(),
                'month' => $monthYear ? $monthYear[0] : Months::now(),
                'date'  => request('date') ? request('date') : date('d'),
                'type'  => Students::path('role'),
            ]);

            return $view->index($param2, $param3);
        } elseif ($param1 == 'report') {
            return $this->report();
        } elseif ($param1 == 'delete') {
            $id = $param2 ? $param2 : request('id');
            return StudentsStudyCourse::deleteFromTable($id);
        } else {
            abort(404);
        }

        view()->share('breadcrumb', $breadcrumb);

        MetaHelper::setConfig(
            [
                'title'       => $data['title'],
                'author'      => config('app.name'),
                'keywords'    => '',
                'description' => '',
                'link'        => $data['metaLink'],
                'image'       => $data['metaImage']
            ]
        );

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
            'parent'     => StudentsStudyCourse::path('view'),
            'view'       => $data['view'],
        );
        $pages['form']['validate'] = [
            'rules'       => ($param1 == 'account') ? ['password' => 'required'] :  (new FormStudentsStudyCourse)->rules(),
            'attributes'  => ($param1 == 'account') ? ['password' => __('Password')] :  (new FormStudentsStudyCourse)->attributes(),
            'messages'    => ($param1 == 'account') ? [] :  (new FormStudentsStudyCourse)->messages(),
            'questions'   => ($param1 == 'account') ? [] :  (new FormStudentsStudyCourse)->questions(),
        ];

        //Select Option

        $data['institute']['data']  = Institute::get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
            $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
            return $row;
        });
        $data['instituteFilter']['data'] = Institute::whereIn('id', StudyCourseSchedule::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
                return $row;
            });

        $data['programFilter']['data']           = StudyPrograms::whereIn('id', StudentsStudyCourse::join((new StudyCourseSession)->getTable(), (new StudyCourseSession)->getTable() . '.id', (new StudentsStudyCourse)->getTable() . '.study_course_session_id')
            ->join((new StudyCourseSchedule)->getTable(), (new StudyCourseSchedule)->getTable() . '.id', (new StudyCourseSession)->getTable() . '.study_course_schedule_id')
            ->groupBy('study_program_id')->pluck('study_program_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });
        $data['courseFilter']['data']           = StudyCourse::whereIn('id', StudentsStudyCourse::join((new StudyCourseSession)->getTable(), (new StudyCourseSession)->getTable() . '.id', (new StudentsStudyCourse)->getTable() . '.study_course_session_id')
            ->join((new StudyCourseSchedule)->getTable(), (new StudyCourseSchedule)->getTable() . '.id', (new StudyCourseSession)->getTable() . '.study_course_schedule_id')
            ->groupBy('study_course_id')->pluck('study_course_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });
        $data['generationFilter']['data']           = StudyGeneration::whereIn('id', StudentsStudyCourse::join((new StudyCourseSession)->getTable(), (new StudyCourseSession)->getTable() . '.id', (new StudentsStudyCourse)->getTable() . '.study_course_session_id')
            ->join((new StudyCourseSchedule)->getTable(), (new StudyCourseSchedule)->getTable() . '.id', (new StudyCourseSession)->getTable() . '.study_course_schedule_id')
            ->groupBy('study_generation_id')->pluck('study_generation_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });

        $data['academicFilter']['data']           = StudyAcademicYears::whereIn('id', StudentsStudyCourse::join((new StudyCourseSession)->getTable(), (new StudyCourseSession)->getTable() . '.id', (new StudentsStudyCourse)->getTable() . '.study_course_session_id')
            ->join((new StudyCourseSchedule)->getTable(), (new StudyCourseSchedule)->getTable() . '.id', (new StudyCourseSession)->getTable() . '.study_course_schedule_id')
            ->groupBy('study_academic_year_id')->pluck('study_academic_year_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });
        $data['semesterFilter']['data']           = StudySemesters::whereIn('id', StudentsStudyCourse::join((new StudyCourseSession)->getTable(), (new StudyCourseSession)->getTable() . '.id', (new StudentsStudyCourse)->getTable() . '.study_course_session_id')
            ->join((new StudyCourseSchedule)->getTable(), (new StudyCourseSchedule)->getTable() . '.id', (new StudyCourseSession)->getTable() . '.study_course_schedule_id')
            ->groupBy('study_semester_id')->pluck('study_semester_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });
        $data['sessionFilter']['data']           = StudySession::whereIn('id', StudentsStudyCourse::join((new StudyCourseSession)->getTable(), (new StudyCourseSession)->getTable() . '.id', (new StudentsStudyCourse)->getTable() . '.study_course_session_id')
            ->join((new StudyCourseSchedule)->getTable(), (new StudyCourseSchedule)->getTable() . '.id', (new StudyCourseSession)->getTable() . '.study_course_schedule_id')
            ->groupBy('study_session_id')->pluck('study_session_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });


        if ($param1 == 'list' || $param1 == 'add') {
            $data['study_status']['data']  = StudyStatus::whereIn('id', [1, 2, 3])
                ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                    $row['image']   = $row->image ? ImageHelper::site(StudyStatus::path('image'), $row->image) : ImageHelper::prefix();
                    return $row;
                });
            $data['students']['data'] = StudentsRequest::join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsRequest())->getTable() . '.student_id')
                ->where('status', '0')
                ->get([
                    (new StudentsRequest)->getTable() . '.*',
                    (new Students())->getTable() . '.first_name_km',
                    (new Students())->getTable() . '.last_name_km',
                    (new Students())->getTable() . '.first_name_en',
                    (new Students())->getTable() . '.last_name_en',
                    (new Students())->getTable() . '.gender_id',
                    (new Students())->getTable() . '.photo',
                    (new Students())->getTable() . '.email',
                    (new Students())->getTable() . '.phone',

                ])->map(function ($row) {
                    $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                    $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
                    $row['photo'] = $row['photo'] ? ImageHelper::site(Students::path('image'), $row['photo']) : ImageHelper::site(Students::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));

                    $row['study_program'] = StudyPrograms::where('id', $row->study_program_id)->pluck(app()->getLocale())->first();
                    $row['study_course'] = StudyCourse::where('id', $row->study_course_id)->pluck(app()->getLocale())->first();
                    $row['study_generation'] = StudyGeneration::where('id', $row->study_generation_id)->pluck(app()->getLocale())->first();
                    $row['study_academic_year'] = StudyAcademicYears::where('id', $row->study_academic_year_id)->pluck(app()->getLocale())->first();
                    $row['study_semester'] = StudySemesters::where('id', $row->study_semester_id)->pluck(app()->getLocale())->first();
                    $row['study_session'] = StudySession::where('id', $row->study_session_id)->pluck(app()->getLocale())->first();

                    $row['study'] = $row['study_program'] . ' (' . $row['study_course'] . ' - ' . $row['study_generation'] . ', ' . $row['study_academic_year'] . ', ' . $row['study_semester'] . ', ' . $row['study_session'] . ')';
                    $row['name'] .= ' - ' . $row['study'];
                    return $row;
                });
        }


        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view($pages['parent'] . '.index', $data);
    }



    public function list($data, $id = null)
    {
        $table =  StudentsStudyCourse::join((new StudentsRequest())->getTable(), (new StudentsRequest())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.student_request_id')
            ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsRequest())->getTable() . '.student_id')
            ->join((new StudyCourseSession())->getTable(), (new StudyCourseSession())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.study_course_session_id')
            ->join((new StudyCourseSchedule())->getTable(), (new StudyCourseSchedule())->getTable() . '.id', (new StudyCourseSession())->getTable() . '.study_course_schedule_id')
            ->orderBy((new StudentsStudyCourse())->getTable() . '.id', 'DESC');

        if (request('instituteId')) {
            $table->where((new StudyCourseSchedule())->getTable() . '.institute_id', request('instituteId'));
        }
        if (request('programId')) {
            $table->where((new StudyCourseSchedule())->getTable() . '.study_program_id', request('programId'));
        }
        if (request('courseId')) {
            $table->where((new StudyCourseSchedule())->getTable() . '.study_course_id', request('courseId'));
        }
        if (request('generationId')) {
            $table->where((new StudyCourseSchedule())->getTable() . '.study_generation_id', request('generationId'));
        }
        if (request('academicId')) {
            $table->where((new StudyCourseSchedule())->getTable() . '.study_academic_id', request('academicId'));
        }
        if (request('semesterId')) {
            $table->where((new StudyCourseSchedule())->getTable() . '.study_semester_id', request('semesterId'));
        }
        if (request('sessionId')) {
            $table->where((new StudyCourseSession())->getTable() . '.study_session_id', request('sessionId'));
        }

        $count = $table->count();
        if ($id) {
            $table->whereIn((new StudentsRequest)->getTable() . '.id', explode(',', $id));
        }

        $response = $table->get([
            (new StudyCourseSchedule())->getTable() . '.*',
            (new StudyCourseSession())->getTable() . '.study_session_id',

            (new StudentsStudyCourse())->getTable() . '.id',
            (new StudentsRequest())->getTable() . '.student_id',
            (new Students())->getTable() . '.first_name_km',
            (new Students())->getTable() . '.last_name_km',
            (new Students())->getTable() . '.first_name_en',
            (new Students())->getTable() . '.last_name_en',
            (new Students())->getTable() . '.gender_id',
            (new Students())->getTable() . '.photo',
            (new Students())->getTable() . '.email',
            (new Students())->getTable() . '.phone',
            (new StudentsStudyCourse())->getTable() . '.study_status_id',

        ])->map(function ($row, $nid) use ($count) {
            $row['nid'] = $count - $nid;
            $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
            $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
            $row['photo'] = $row['photo'] ? ImageHelper::site(Students::path('image'), $row['photo']) : ImageHelper::site(Students::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
            $row['account'] = Users::where('email', $row->email)->where('node_id', $row->student_id)->exists();

            $row['study_program'] = StudyPrograms::where('id', $row->study_program_id)->pluck(app()->getLocale())->first();
            $row['study_course'] = StudyCourse::where('id', $row->study_course_id)->pluck(app()->getLocale())->first();
            $row['study_generation'] = StudyGeneration::where('id', $row->study_generation_id)->pluck(app()->getLocale())->first();
            $row['study_academic_year'] = StudyAcademicYears::where('id', $row->study_academic_year_id)->pluck(app()->getLocale())->first();
            $row['study_semester'] = StudySemesters::where('id', $row->study_semester_id)->pluck(app()->getLocale())->first();
            $row['study_session'] = StudySession::where('id', $row->study_session_id)->pluck(app()->getLocale())->first();

            $row['study_status'] = StudyStatus::where('id', $row->study_status_id)->pluck(app()->getLocale())->first();

            $row['study'] = $row['study_program'] . ' (' . $row['study_course'] . ' - ' . $row['study_generation'] . ', ' . $row['study_academic_year'] . ', ' . $row['study_semester'] . ', ' . $row['study_session'] . ')';

            $row['action']  = [
                'account'   => url(Users::role() . '/' . Students::path('url') . '/account/create/' . $row['id']),
                'edit'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/edit/' . $row['id']),
                'view'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/view/' . $row['id']),
                'photo'  => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/photo/crop/' . $row['id']),
                'qrcode' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/' . QRHelper::path('url') . '/make/' . $row['id']),
                'card'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/' . CardFrames::path('url') . '/make/' . $row['id']),
                'certificate' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/' . CertificateFrames::path('url') . '/make/' . $row['id']),
                'account' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/account/create/' . $row['id']),
                'delete' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/delete/' . $row['id']),
            ];

            $row['suggest_role']       = Students::path('roleId');
            return $row;
        });

        if ($id) {
            return $response;
        }

        $data['response'] = [
            'data'      => $response,
            'gender'    => Students::gender($table),
            'studyStatus' => StudentsStudyCourse::studyStatus($table)

        ];
        $data['view']     = StudentsStudyCourse::path('view') . '.includes.list.index';
        $data['title']              = Users::role(app()->getLocale()) . ' | ' . __('List Students study course');
        return $data;
    }

    public function show($data, $id, $type)
    {
        $data['view']       = StudentsStudyCourse::path('view') . '.includes.form.index';
        if ($id) {

            $response           = StudentsStudyCourse::join((new StudentsRequest())->getTable(), (new StudentsRequest())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.student_request_id')
                ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsRequest())->getTable() . '.student_id')
                ->whereIn((new StudentsStudyCourse())->getTable() . '.id', explode(',', $id))->get()
                ->map(function ($row) {
                    $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                    $row['photo'] = $row['photo'] ? ImageHelper::site(Students::path('image'), $row['photo']) : ImageHelper::site(Students::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));

                    $row['action']  = [
                        'account'   => url(Users::role() . '/' . Students::path('url') . '/account/create/' . $row['id']),
                        'edit'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/edit/' . $row['id']),
                        'view'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/view/' . $row['id']),
                        'photo'  => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/photo/crop/' . $row['id']),
                        'qrcode' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/' . QRHelper::path('url') . '/make/' . $row['id']),
                        'card'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/' . CardFrames::path('url') . '/make/' . $row['id']),
                        'certificate' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/' . CertificateFrames::path('url') . '/make/' . $row['id']),
                        'account' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/account/create/' . $row['id']),
                        'delete' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/delete/' . $row['id']),
                    ];

                    $row['suggest_role']       = Students::path('roleId');

                    return $row;
                });
            $data['listData'] =  $response->map(function ($row) {
                return [
                    'id'  => $row->id,
                    'name'  => $row->name,
                    'image'  => $row->photo,
                    'action'  => [
                        'edit'    => url(Users::role() . '/' . Staff::path('url') . '/edit/' . $row->id),
                    ],
                ];
            });

            $data['formData']   = $response;
            $data['formAction'] = '/' . $type . '/' . $id;


            $data['students']['data'] = StudentsRequest::join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsRequest())->getTable() . '.student_id')
                ->whereIn((new StudentsRequest())->getTable() . '.id', StudentsStudyCourse::whereIn('id', explode(',', $id))->pluck('student_request_id'))
                ->get()->map(function ($row) {
                    $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                    $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
                    $row['photo'] = $row['photo'] ? ImageHelper::site(Students::path('image'), $row['photo']) : ImageHelper::site(Students::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
                    return $row;
                });
        }


        return $data;
    }

    public function report()
    {
        request()->merge([
            'size'  => request('size', 'A4'),
            'layout'  => request('layout', 'landscape'),
        ]);

        config()->set('app.title', __('List Student study course'));
        config()->set('pages.parent', StudentsStudyCourse::path('view'));



        $data['instituteFilter']['data']           = Institute::whereIn('id', StudentsStudyCourse::join((new StudyCourseSession)->getTable(), (new StudyCourseSession)->getTable() . '.id', (new StudentsStudyCourse)->getTable() . '.study_course_session_id')
            ->join((new StudyCourseSchedule)->getTable(), (new StudyCourseSchedule)->getTable() . '.id', (new StudyCourseSession)->getTable() . '.study_course_schedule_id')
            ->groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
                return $row;
            });
        $data['programFilter']['data']           = StudyPrograms::whereIn('id', StudentsStudyCourse::join((new StudyCourseSession)->getTable(), (new StudyCourseSession)->getTable() . '.id', (new StudentsStudyCourse)->getTable() . '.study_course_session_id')
            ->join((new StudyCourseSchedule)->getTable(), (new StudyCourseSchedule)->getTable() . '.id', (new StudyCourseSession)->getTable() . '.study_course_schedule_id')
            ->groupBy('study_program_id')->pluck('study_program_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });
        $data['courseFilter']['data']           = StudyCourse::whereIn('id', StudentsStudyCourse::join((new StudyCourseSession)->getTable(), (new StudyCourseSession)->getTable() . '.id', (new StudentsStudyCourse)->getTable() . '.study_course_session_id')
            ->join((new StudyCourseSchedule)->getTable(), (new StudyCourseSchedule)->getTable() . '.id', (new StudyCourseSession)->getTable() . '.study_course_schedule_id')
            ->groupBy('study_course_id')->pluck('study_course_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });
        $data['generationFilter']['data']           = StudyGeneration::whereIn('id', StudentsStudyCourse::join((new StudyCourseSession)->getTable(), (new StudyCourseSession)->getTable() . '.id', (new StudentsStudyCourse)->getTable() . '.study_course_session_id')
            ->join((new StudyCourseSchedule)->getTable(), (new StudyCourseSchedule)->getTable() . '.id', (new StudyCourseSession)->getTable() . '.study_course_schedule_id')
            ->groupBy('study_generation_id')->pluck('study_generation_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });

        $data['academicFilter']['data']           = StudyAcademicYears::whereIn('id', StudentsStudyCourse::join((new StudyCourseSession)->getTable(), (new StudyCourseSession)->getTable() . '.id', (new StudentsStudyCourse)->getTable() . '.study_course_session_id')
            ->join((new StudyCourseSchedule)->getTable(), (new StudyCourseSchedule)->getTable() . '.id', (new StudyCourseSession)->getTable() . '.study_course_schedule_id')
            ->groupBy('study_academic_year_id')->pluck('study_academic_year_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });
        $data['semesterFilter']['data']           = StudySemesters::whereIn('id', StudentsStudyCourse::join((new StudyCourseSession)->getTable(), (new StudyCourseSession)->getTable() . '.id', (new StudentsStudyCourse)->getTable() . '.study_course_session_id')
            ->join((new StudyCourseSchedule)->getTable(), (new StudyCourseSchedule)->getTable() . '.id', (new StudyCourseSession)->getTable() . '.study_course_schedule_id')
            ->groupBy('study_semester_id')->pluck('study_semester_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });
        $data['sessionFilter']['data']           = StudySession::whereIn('id', StudentsStudyCourse::join((new StudyCourseSession)->getTable(), (new StudyCourseSession)->getTable() . '.id', (new StudentsStudyCourse)->getTable() . '.study_course_session_id')
            ->join((new StudyCourseSchedule)->getTable(), (new StudyCourseSchedule)->getTable() . '.id', (new StudyCourseSession)->getTable() . '.study_course_schedule_id')
            ->groupBy('study_session_id')->pluck('study_session_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });


        $table = StudentsStudyCourse::join((new StudentsRequest())->getTable(), (new StudentsRequest())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.student_request_id')
            ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsRequest())->getTable() . '.student_id')
            ->join((new StudyCourseSession)->getTable(), (new StudyCourseSession)->getTable() . '.id', (new StudentsStudyCourse)->getTable() . '.study_course_session_id')
            ->join((new StudyCourseSchedule)->getTable(), (new StudyCourseSchedule)->getTable() . '.id', (new StudyCourseSession)->getTable() . '.study_course_schedule_id');

        if (request('instituteId')) {
            $table->where((new StudyCourseSchedule)->getTable() . '.institute_id', request('instituteId'));
        }

        $response = $table->get([
            (new StudyCourseSchedule())->getTable() . '.*',
            (new StudyCourseSession())->getTable() . '.study_session_id',

            (new StudentsStudyCourse())->getTable() . '.id',
            (new StudentsRequest())->getTable() . '.student_id',
            (new Students())->getTable() . '.first_name_km',
            (new Students())->getTable() . '.last_name_km',
            (new Students())->getTable() . '.first_name_en',
            (new Students())->getTable() . '.last_name_en',
            (new Students())->getTable() . '.gender_id',
            (new Students())->getTable() . '.photo',
            (new Students())->getTable() . '.email',
            (new Students())->getTable() . '.phone',
            (new StudentsStudyCourse())->getTable() . '.created_at',
            (new StudentsStudyCourse())->getTable() . '.photo as photo_crop',
            (new StudentsStudyCourse())->getTable() . '.card',
            (new StudentsStudyCourse())->getTable() . '.qrcode',
        ])->map(function ($row) {
            //$row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
            $row['name'] = $row['first_name_' . app()->getLocale()] . ' ' . $row['last_name_' . app()->getLocale()];
            $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
            $row['photo'] = $row['photo'] ? ImageHelper::site(Students::path('image'), $row['photo']) : ImageHelper::site(Students::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
            $row['photo_crop'] = ImageHelper::site(Students::path('image') . '/' . StudentsStudyCourse::path('image'), $row->photo_crop);
            $row['card'] = ImageHelper::site(Students::path('image') . '/' . CardFrames::path('image'), $row->card);
            $row['qrcode'] = ImageHelper::site(Students::path('image') . '/' . QRHelper::path('image'), $row->qrcode);


            $row['account'] = Users::where('email', $row->email)->where('node_id', $row->student_id)->exists();
            $row['study_program'] = StudyPrograms::where('id', $row->study_program_id)->pluck(app()->getLocale())->first();
            $row['study_course'] = StudyCourse::where('id', $row->study_course_id)->pluck(app()->getLocale())->first();
            $row['study_generation'] = StudyGeneration::where('id', $row->study_generation_id)->pluck(app()->getLocale())->first();
            $row['study_academic_year'] = StudyAcademicYears::where('id', $row->study_academic_year_id)->pluck(app()->getLocale())->first();
            $row['study_semester'] = StudySemesters::where('id', $row->study_semester_id)->pluck(app()->getLocale())->first();
            $row['study_session'] = StudySession::where('id', $row->study_session_id)->pluck(app()->getLocale())->first();

            return $row;
        })->toArray();

        $date = Carbon::now();
        $newData = [];
        $items = Collection::make($response);
        $perPage = request('layout') == 'portrait' ? 25 : 15;
        $perPageNoTop = $perPage + 5;
        $offset = ceil($items->count() / $perPage);

        for ($i = 1; $i <= $offset; $i++) {
            if ($i != 1) {
                $perPage = $perPageNoTop;
            }

            $item = $items->forPage($i, $perPage);
            if ($item->count()) {
                array_push($newData, $item);
            }
        }

        $data['response'] = [
            'data'   => $newData,
            'total'  => $items->count(),
            'genders' => Students::gender($table),
            'date'      => [
                'day'   => $date->day,
                '_day'  => $date->getTranslatedDayName(),
                'month' => $date->getTranslatedMonthName(),
                'year'  => $date->year,
                'def'   => DateHelper::convert($date, 'd-M-Y'),
            ]
        ];

        $data['institute'] = Institute::where('id', request('instituteId'))
            ->get(['logo', app()->getLocale() . ' as name'])
            ->map(function ($row) {
                $row['logo'] = ImageHelper::site(Institute::path('image'), $row['logo']);
                return $row;
            })->first();

        config()->set('pages.title', __('List Student study course'));

        return view(StudentsStudyCourse::path('view') . '.includes.report.index', $data);
    }
}
