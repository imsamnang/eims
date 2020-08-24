<?php

namespace App\Http\Controllers\Students;

use Carbon\Carbon;
use App\Models\App;
use App\Models\Staff;
use App\Models\Users;
use App\Models\Gender;
use App\Models\Communes;
use App\Models\Students;
use App\Models\Villages;
use App\Helpers\QRHelper;
use App\Models\Districts;
use App\Models\Institute;
use App\Models\Languages;
use App\Models\Provinces;
use App\Models\CardFrames;
use App\Helpers\DateHelper;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;

use App\Models\StudyStatus;
use App\Helpers\ImageHelper;
use App\Models\SocailsMedia;
use App\Models\StudySession;
use App\Models\StudyPrograms;
use App\Models\StudySubjects;
use App\Models\StudySemesters;
use App\Models\StudyGeneration;
use App\Models\CertificateFrames;
use App\Models\StudyAcademicYears;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Models\StudyShortCourseSession;
use App\Models\StudentsStudyShortCourse;
use App\Models\StudyShortCourseSchedule;
use App\Models\StudentsShortCourseRequest;
use App\Http\Requests\FormStudentsStudyShortCourse;

class StudentsStudyShortCourseController extends Controller
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

        request()->merge(['ref' => request('ref', StudentsStudyShortCourse::path('url'))]);



        $data['formAction']           = '/add';
        $data['formName']             = Students::path('url') . '/' . StudentsStudyShortCourse::path('url');
        $data['title']              = Users::role(app()->getLocale()) . ' | ' . __('List Students study course');
        $data['metaImage']            = asset('assets/img/icons/' . $param1 . '.png');
        $data['metaLink']             = url(Users::role() . '/' . $param1);

        $data['formData']       = array(
            'photo' => asset('/assets/img/user/male.jpg'),
        );
        $data['listData']       = array();
        $id = $param2 ? $param2 : request('id');

        if ($param1 == null || $param1 == 'list') {
            request()->merge(['id' => $id]);
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return StudentsStudyShortCourse::getData(null, null, 10);
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'list-datatable') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return  StudentsStudyShortCourse::getDataTable();
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'add') {

            if (request()->method() === 'POST') {
                return StudentsStudyShortCourse::addToTable();
            }
            $data  = $this->show($data, null, $param1);
        } elseif ($param1 == 'edit') {

            request()->merge(['id' => $id]);
            if (request()->method() === 'POST') {
                return StudentsStudyShortCourse::updateToTable($id);
            }
            $data  = $this->show($data, $id, $param1);
            $data['study_status']['data']  = StudyStatus::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = $row->image ? ImageHelper::site(StudyStatus::path('image'), $row->image) : ImageHelper::prefix();
                return $row;
            });
        } elseif ($param1 == 'view') {
            $id = request('id', $param2);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Students study short course');
            $data['response']['data'] = StudentsStudyShortCourse::join((new StudentsShortCourseRequest())->getTable(), (new StudentsShortCourseRequest())->getTable() . '.id', (new StudentsStudyShortCourse())->getTable() . '.stu_sh_c_request_id')
                ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsShortCourseRequest())->getTable() . '.student_id')
                ->join((new StudyShortCourseSession())->getTable(), (new StudyShortCourseSession())->getTable() . '.id', (new StudentsStudyShortCourse())->getTable() . '.stu_sh_c_session_id')
                ->join((new StudyShortCourseSchedule())->getTable(), (new StudyShortCourseSchedule())->getTable() . '.id', (new StudyShortCourseSession())->getTable() . '.stu_sh_c_schedule_id')
                ->whereIn((new StudentsStudyShortCourse())->getTable() . '.id', explode(',', $id))
                ->get([
                    (new StudyShortCourseSchedule())->getTable() . '.*',
                    (new StudyShortCourseSession())->getTable() . '.study_session_id',

                    (new StudentsStudyShortCourse())->getTable() . '.id',
                    (new StudentsShortCourseRequest())->getTable() . '.student_id',
                    (new Students())->getTable() . '.first_name_km',
                    (new Students())->getTable() . '.last_name_km',
                    (new Students())->getTable() . '.first_name_en',
                    (new Students())->getTable() . '.last_name_en',
                    (new Students())->getTable() . '.gender_id',
                    (new Students())->getTable() . '.photo',
                    (new Students())->getTable() . '.email',
                    (new Students())->getTable() . '.phone',
                    (new StudentsStudyShortCourse())->getTable() . '.created_at',
                    (new StudentsStudyShortCourse())->getTable() . '.photo as photo_crop',

                ])->map(function ($row) {

                    $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                    $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
                    $row['photo'] = $row['photo'] ? ImageHelper::site(Students::path('image'), $row['photo']) : ImageHelper::site(Students::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
                    $row['photo_crop'] = ImageHelper::site(Students::path('image') . '/' . StudentsStudyShortCourse::path('image'), $row->photo_crop);
                    $row['study_generation'] = StudyGeneration::where('id', $row->study_generation_id)->pluck(app()->getLocale())->first();
                    $row['study_subject'] = StudySubjects::where('id', $row->study_subject_id)->pluck(app()->getLocale())->first();
                    $row['study_session'] = StudySession::where('id', $row->study_session_id)->pluck(app()->getLocale())->first();

                    $row['study'] = $row['study_generation'] . ' (' . $row['study_subject'] . ', ' . $row['study_session'] . ')';

                    $row['province'] = Provinces::where('id', $row->province_id)->pluck(app()->getLocale())->first();
                    $row['district'] = Districts::where('id', $row->district_id)->pluck(app()->getLocale())->first();
                    $row['commune']  = Communes::where('id', $row->commune_id)->pluck(app()->getLocale())->first();
                    $row['village']  = Villages::where('id', $row->village_id)->pluck(app()->getLocale())->first();

                    $row['location'] = $row['province'] . ',' . $row['district'] . ',' . $row['commune'] . ',' . $row['village'];

                    $row['action']  = [
                        'account'   => url(Users::role() . '/' . Students::path('url') . '/account/create/' . $row['id']),
                        'edit'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyShortCourse::path('url') . '/edit/' . $row['id']),
                        'view'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyShortCourse::path('url') . '/view/' . $row['id']),
                        'photo'  => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyShortCourse::path('url') . '/photo/make/' . $row['id']),
                        'qrcode' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyShortCourse::path('url') . '/' . QRHelper::path('url') . '/make/' . $row['id']),
                        'card'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyShortCourse::path('url') . '/' . CardFrames::path('url') . '/make/' . $row['id']),
                        'certificate' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyShortCourse::path('url') . '/' . CertificateFrames::path('url') . '/make/' . $row['id']),
                        'account' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyShortCourse::path('url') . '/account/create/' . $row['id']),
                        'delete' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyShortCourse::path('url') . '/delete/' . $row['id']),
                    ];
                    return $row;
                });
            $data['formAction']          = '/view/' . $id;
            $data['view']  = StudentsStudyShortCourse::path('view') . '.includes.view.index';
        } elseif ($param1 == 'report') {
            return $this->report();
        } elseif ($param1 == 'delete') {
            $id = $param2 ? $param2 : request('id');
            return StudentsStudyShortCourse::deleteFromTable($id);
        } else {
            abort(404);
        }

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
            'parent'     => StudentsStudyShortCourse::path('view'),
            'view'       => $data['view'],
        );
        $pages['form']['validate'] = [
            'rules'       => ($param1 == 'account') ? ['password' => 'required'] :  (new FormStudentsStudyShortCourse)->rules(),
            'attributes'  => ($param1 == 'account') ? ['password' => __('Password')] :  (new FormStudentsStudyShortCourse)->attributes(),
            'messages'    => ($param1 == 'account') ? [] :  (new FormStudentsStudyShortCourse)->messages(),
            'questions'   => ($param1 == 'account') ? [] :  (new FormStudentsStudyShortCourse)->questions(),
        ];

        //Select Option

        $data['study_short_course_session']['data'] = StudyShortCourseSession::join((new StudyShortCourseSchedule)->getTable(), (new StudyShortCourseSchedule)->getTable() . '.id', (new StudyShortCourseSession)->getTable() . '.stu_sh_c_schedule_id')
            ->get([
                (new StudyShortCourseSchedule)->getTable() . '.*',
                (new StudyShortCourseSession)->getTable() . '.*',
            ])->map(function ($row) {

                $row['study_generation'] = StudyGeneration::where('id', $row->study_generation_id)->pluck(app()->getLocale())->first();
                $row['study_subject'] = StudySubjects::where('id', $row->study_subject_id)->pluck(app()->getLocale())->first();
                $row['study_session'] = StudySession::where('id', $row->study_session_id)->pluck(app()->getLocale())->first();
                $row['study'] = $row['study_generation'] . ' ('  . $row['study_subject'] . ' - ' . $row['study_session'] . ')';

                $row['study_start'] = DateHelper::convert($row->study_start, 'd-M-Y');
                $row['study_end'] = DateHelper::convert($row->study_end, 'd-M-Y');

                $row['study'] .= ' - (' . $row['study_start'] . ' - ' . $row['study_end'] . ')';

                $row['province'] = Provinces::where('id', $row->province_id)->pluck(app()->getLocale())->first();
                $row['district'] = Districts::where('id', $row->district_id)->pluck(app()->getLocale())->first();
                $row['commune']  = Communes::where('id', $row->commune_id)->pluck(app()->getLocale())->first();
                $row['village']  = Villages::where('id', $row->village_id)->pluck(app()->getLocale())->first();

                $row['location'] = $row['province'] . ',' . $row['district'] . ',' . $row['commune'] . ',' . $row['village'];


                $row['name']   = $row['study'] . ' - ' . $row['location'];
                return $row;
            });



        $data['institute']['data']  = Institute::get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
            $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
            return $row;
        });
        $data['instituteFilter']['data'] = Institute::whereIn('id', StudyShortCourseSchedule::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
                return $row;
            });

        $data['generationFilter']['data']           = StudyGeneration::whereIn('id', StudentsStudyShortCourse::join((new StudyShortCourseSession)->getTable(), (new StudyShortCourseSession)->getTable() . '.id', (new StudentsStudyShortCourse)->getTable() . '.stu_sh_c_session_id')
            ->join((new StudyShortCourseSchedule)->getTable(), (new StudyShortCourseSchedule)->getTable() . '.id', (new StudyShortCourseSession)->getTable() . '.stu_sh_c_schedule_id')
            ->groupBy('study_generation_id')->pluck('study_generation_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });

        $data['subjectsFilter']['data']           = StudySubjects::whereIn('id', StudyShortCourseSchedule::groupBy('study_subject_id')->pluck('study_subject_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });

        $data['sessionFilter']['data']           = StudySession::whereIn('id', StudentsStudyShortCourse::join((new StudyShortCourseSession)->getTable(), (new StudyShortCourseSession)->getTable() . '.id', (new StudentsStudyShortCourse)->getTable() . '.stu_sh_c_session_id')
            ->join((new StudyShortCourseSchedule)->getTable(), (new StudyShortCourseSchedule)->getTable() . '.id', (new StudyShortCourseSession)->getTable() . '.stu_sh_c_schedule_id')
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
            $data['student']['data'] = StudentsShortCourseRequest::join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsShortCourseRequest())->getTable() . '.student_id')
                ->where('status', '0')
                ->get()->map(function ($row) {
                    $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                    $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
                    $row['study_subject'] = StudySubjects::where('id', $row->study_subject_id)->pluck(app()->getLocale())->first();
                    $row['study_session'] = StudySession::where('id', $row->study_session_id)->pluck(app()->getLocale())->first();
                    $row['name'] .= ' ' . $row['gender'] . ' (' . $row['study_subject'] . ' - ' . $row['study_session'] . ')';
                    $row['photo'] = $row['photo'] ? ImageHelper::site(Students::path('image'), $row['photo']) : ImageHelper::site(Students::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
                    return $row;
                });
        }


        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view($pages['parent'] . '.index', $data);
    }



    public function list($data, $id = null)
    {
        $table =  StudentsStudyShortCourse::join((new StudentsShortCourseRequest())->getTable(), (new StudentsShortCourseRequest())->getTable() . '.id', (new StudentsStudyShortCourse())->getTable() . '.stu_sh_c_request_id')
            ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsShortCourseRequest())->getTable() . '.student_id')
            ->join((new StudyShortCourseSession())->getTable(), (new StudyShortCourseSession())->getTable() . '.id', (new StudentsStudyShortCourse())->getTable() . '.stu_sh_c_session_id')
            ->join((new StudyShortCourseSchedule())->getTable(), (new StudyShortCourseSchedule())->getTable() . '.id', (new StudyShortCourseSession())->getTable() . '.stu_sh_c_schedule_id')
            ->orderBy((new StudentsStudyShortCourse())->getTable() . '.id', 'DESC');

        if (request('instituteId')) {
            $table->where((new StudyShortCourseSchedule())->getTable() . '.institute_id', request('instituteId'));
        }

        if (request('generationId')) {
            $table->where((new StudyShortCourseSchedule())->getTable() . '.study_generation_id', request('generationId'));
        }

        if (request('subjectId')) {
            $table->where((new StudyShortCourseSchedule())->getTable() . '.study_subject_id', request('subjectId'));
        }
        if (request('sessionId')) {
            $table->where((new StudyShortCourseSession())->getTable() . '.study_session_id', request('sessionId'));
        }


        $response = $table->get([
            (new StudyShortCourseSchedule())->getTable() . '.*',
            (new StudyShortCourseSession())->getTable() . '.*',

            (new StudentsStudyShortCourse())->getTable() . '.id',
            (new StudentsShortCourseRequest())->getTable() . '.student_id',
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
            $row['account'] = Users::where('email', $row->email)->where('node_id', $row->student_id)->exists();

            $row['study_generation'] = StudyGeneration::where('id', $row->study_generation_id)->pluck(app()->getLocale())->first();
            $row['study_subject'] = StudySubjects::where('id', $row->study_subject_id)->pluck(app()->getLocale())->first();
            $row['study_session'] = StudySession::where('id', $row->study_session_id)->pluck(app()->getLocale())->first();
            $row['study'] = $row['study_generation'] . ' ('  . $row['study_subject'] . ' - ' . $row['study_session'] . ')';

            $row['study_start'] = DateHelper::convert($row->study_start, 'd-M-Y');
            $row['study_end'] = DateHelper::convert($row->study_end, 'd-M-Y');

            $row['study'] .= ' - (' . $row['study_start'] . ' - ' . $row['study_end'] . ')';

            $row['action']  = [
                'account'   => url(Users::role() . '/' . Students::path('url') . '/account/create/' . $row['id']),
                'edit'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyShortCourse::path('url') . '/edit/' . $row['id']),
                'view'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyShortCourse::path('url') . '/view/' . $row['id']),
                'photo'  => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyShortCourse::path('url') . '/photo/make/' . $row['id']),
                'qrcode' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyShortCourse::path('url') . '/' . QRHelper::path('url') . '/make/' . $row['id']),
                'card'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyShortCourse::path('url') . '/' . CardFrames::path('url') . '/make/' . $row['id']),
                'certificate' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyShortCourse::path('url') . '/' . CertificateFrames::path('url') . '/make/' . $row['id']),
                'account' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyShortCourse::path('url') . '/account/create/' . $row['id']),
                'delete' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyShortCourse::path('url') . '/delete/' . $row['id']),
            ];

            $row['suggest_role']       = Students::path('roleId');
            return $row;
        });


        $data['response'] = [
            'data'      => $response,
            'gender'    => Students::gender($table),

        ];
        $data['view']     = StudentsStudyShortCourse::path('view') . '.includes.list.index';
        $data['title']              = Users::role(app()->getLocale()) . ' | ' . __('List Students study course');
        return $data;
    }

    public function show($data, $id, $type)
    {
        $data['view']       = StudentsStudyShortCourse::path('view') . '.includes.form.index';
        if ($id) {

            $response           = StudentsStudyShortCourse::join((new StudentsShortCourseRequest())->getTable(), (new StudentsShortCourseRequest())->getTable() . '.id', (new StudentsStudyShortCourse())->getTable() . '.stu_sh_c_request_id')
                ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsShortCourseRequest())->getTable() . '.student_id')
                ->whereIn((new StudentsStudyShortCourse())->getTable() . '.id', explode(',', $id))->get()
                ->map(function ($row) {
                    $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                    $row['photo'] = $row['photo'] ? ImageHelper::site(Students::path('image'), $row['photo']) : ImageHelper::site(Students::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));

                    $row['action']  = [
                        'account'   => url(Users::role() . '/' . Students::path('url') . '/account/create/' . $row['id']),
                        'edit'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyShortCourse::path('url') . '/edit/' . $row['id']),
                        'view'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyShortCourse::path('url') . '/view/' . $row['id']),
                        'photo'  => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyShortCourse::path('url') . '/photo/make/' . $row['id']),
                        'qrcode' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyShortCourse::path('url') . '/' . QRHelper::path('url') . '/make/' . $row['id']),
                        'card'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyShortCourse::path('url') . '/' . CardFrames::path('url') . '/make/' . $row['id']),
                        'certificate' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyShortCourse::path('url') . '/' . CertificateFrames::path('url') . '/make/' . $row['id']),
                        'account' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyShortCourse::path('url') . '/account/create/' . $row['id']),
                        'delete' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyShortCourse::path('url') . '/delete/' . $row['id']),
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


            $data['student']['data'] = StudentsShortCourseRequest::join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsShortCourseRequest())->getTable() . '.student_id')
                ->whereIn((new StudentsShortCourseRequest())->getTable() . '.id', StudentsStudyShortCourse::whereIn('id', explode(',', $id))->pluck('stu_sh_c_request_id'))
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
            'layout'  => request('layout', 'portrait'),
        ]);

        config()->set('app.title', __('List Students study short course'));
        config()->set('pages.parent', StudentsStudyShortCourse::path('view'));



        $data['instituteFilter']['data']           = Institute::whereIn('id', StudentsStudyShortCourse::join((new StudyShortCourseSession)->getTable(), (new StudyShortCourseSession)->getTable() . '.id', (new StudentsStudyShortCourse)->getTable() . '.stu_sh_c_session_id')
            ->join((new StudyShortCourseSchedule)->getTable(), (new StudyShortCourseSchedule)->getTable() . '.id', (new StudyShortCourseSession)->getTable() . '.stu_sh_c_schedule_id')
            ->groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
                return $row;
            });

        $data['subjectFilter']['data']           = StudySubjects::whereIn('id', StudentsStudyShortCourse::join((new StudyShortCourseSession)->getTable(), (new StudyShortCourseSession)->getTable() . '.id', (new StudentsStudyShortCourse)->getTable() . '.stu_sh_c_session_id')
            ->join((new StudyShortCourseSchedule)->getTable(), (new StudyShortCourseSchedule)->getTable() . '.id', (new StudyShortCourseSession)->getTable() . '.stu_sh_c_schedule_id')
            ->groupBy('study_subject_id')->pluck('study_subject_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });
        $data['sessionFilter']['data']           = StudySession::whereIn('id', StudentsStudyShortCourse::join((new StudyShortCourseSession)->getTable(), (new StudyShortCourseSession)->getTable() . '.id', (new StudentsStudyShortCourse)->getTable() . '.stu_sh_c_session_id')
            ->join((new StudyShortCourseSchedule)->getTable(), (new StudyShortCourseSchedule)->getTable() . '.id', (new StudyShortCourseSession)->getTable() . '.stu_sh_c_schedule_id')
            ->groupBy('study_session_id')->pluck('study_session_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });


        $table = StudentsStudyShortCourse::join((new StudentsShortCourseRequest())->getTable(), (new StudentsShortCourseRequest())->getTable() . '.id', (new StudentsStudyShortCourse())->getTable() . '.stu_sh_c_request_id')
            ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsShortCourseRequest())->getTable() . '.student_id')
            ->join((new StudyShortCourseSession)->getTable(), (new StudyShortCourseSession)->getTable() . '.id', (new StudentsStudyShortCourse)->getTable() . '.stu_sh_c_session_id')
            ->join((new StudyShortCourseSchedule)->getTable(), (new StudyShortCourseSchedule)->getTable() . '.id', (new StudyShortCourseSession)->getTable() . '.stu_sh_c_schedule_id');

        if (request('instituteId')) {
            $table->where((new StudyShortCourseSchedule)->getTable() . '.institute_id', request('instituteId'));
        }

        $response = $table->get([
            (new StudyShortCourseSchedule())->getTable() . '.*',
            (new StudyShortCourseSession())->getTable() . '.study_session_id',

            (new StudentsStudyShortCourse())->getTable() . '.id',
            (new StudentsShortCourseRequest())->getTable() . '.student_id',
            (new Students())->getTable() . '.first_name_km',
            (new Students())->getTable() . '.last_name_km',
            (new Students())->getTable() . '.first_name_en',
            (new Students())->getTable() . '.last_name_en',
            (new Students())->getTable() . '.gender_id',
            (new Students())->getTable() . '.photo',
            (new Students())->getTable() . '.email',
            (new Students())->getTable() . '.phone',
            (new StudentsStudyShortCourse())->getTable() . '.created_at',
            (new StudentsStudyShortCourse())->getTable() . '.photo as photo_crop',
        ])->map(function ($row) {
            //$row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
            $row['name'] = $row['first_name_' . app()->getLocale()] . ' ' . $row['last_name_' . app()->getLocale()];
            $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
            $row['photo'] = $row['photo'] ? ImageHelper::site(Students::path('image'), $row['photo']) : ImageHelper::site(Students::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
            $row['photo_crop'] = ImageHelper::site(Students::path('image') . '/' . StudentsStudyShortCourse::path('image'), $row->photo_crop);
            $row['study_generation'] = StudyGeneration::where('id', $row->study_generation_id)->pluck(app()->getLocale())->first();
            $row['study_subject'] = StudySubjects::where('id', $row->study_subject_id)->pluck(app()->getLocale())->first();
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

        config()->set('pages.title', __('List Students study short course'));

        return view(StudentsStudyShortCourse::path('view') . '.includes.report.index', $data);
    }
}
