<?php

namespace App\Http\Controllers\Students;

use Carbon\Carbon;
use App\Models\App as AppModel;
use App\Models\Days;
use App\Models\Quiz;
use App\Models\Roles;
use App\Models\Users;
use App\Models\Years;
use App\Models\Gender;
use App\Models\Months;
use App\Models\Mailbox;
use App\Models\Marital;
use App\Models\Communes;
use App\Models\Holidays;
use App\Models\Students;
use App\Models\Villages;
use App\Models\Districts;
use App\Models\Institute;
use App\Models\Languages;
use App\Models\Provinces;
use App\Models\BloodGroup;
use App\Models\CardFrames;
use App\Models\MotherTong;
use App\Models\StudyClass;
use App\Helpers\DateHelper;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Models\Nationality;
use App\Models\QuizStudents;
use App\Models\StudyCourse;
use App\Helpers\ImageHelper;
use App\Models\ActivityFeed;
use App\Models\SocailsMedia;
use App\Models\StudySession;
use App\Models\StudyPrograms;
use App\Models\StudySubjects;
use App\Models\StudySemesters;
use App\Models\AttendanceTypes;
use App\Models\StudentsRequest;
use App\Models\StudyGeneration;
use App\Models\CertificateFrames;
use App\Models\QuizStudentAnswer;
use App\Models\StudentsGuardians;
use App\Models\StudyAcademicYears;
use App\Models\StudyCourseRoutine;
use App\Models\StudyCourseSession;
use Illuminate\Support\Collection;
use App\Models\StudentsAttendances;
use App\Models\StudentsStudyCourse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentsStudyCourseScore;
use App\Models\StudentsStudyShortCourse;
use App\Models\StudentsShortCourseRequest;
use App\Http\Controllers\CardFrames\CardFramesController;
use App\Http\Controllers\General\GeneralController;
use App\Http\Controllers\Mailbox\MailboxController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Quiz\QuizStudentAnswerController;
use App\Http\Controllers\CertificateFrames\CertificateFramesController;
use App\Http\Controllers\ActivityFeed\ActivityFeedController;
use App\Http\Controllers\Students\StudentsStudyCourseController;
use App\Http\Controllers\Students\StudentsShortCourseRequestController;

class StudentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        AppModel::setConfig();
        Languages::setConfig();
        SocailsMedia::setConfig();
        view()->share('breadcrumb', []);
    }

    public function index($param1 = null, $param2 = null, $param3 = null, $param4 = null, $param5 = null, $param6 = null)
    {
        $breadcrumb  = [
            [
                'title' => __('Students'),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . Students::path('url')),
            ],
            [
                'title' => __('List Students'),
                'status' => false,
                'link'  => url(Users::role() . '/' . Students::path('url') . '/list'),
            ]
        ];

        $data['formAction']          = '/add';
        $data['formName']            = Students::path('url');
        $data['title']               = Users::role(app()->getLocale()) . ' | ' . __('Students');
        $data['metaImage']           = asset('assets/img/icons/' . $param1 . '.png');
        $data['metaLink']            = url(Users::role() . '/' . $param1);
        $data['formData']            = array(
            ['photo'                  => asset('/assets/img/user/male.jpg'),]
        );
        $data['listData']            = array();

        if (Auth::user()->role_id == 6) {
            if ($param1  == null) {
                $data = $this->dashboard($data);
            } elseif ($param1  == 'dashboard') {
                $data = $this->dashboard($data);
            } elseif ($param1  == 'study') {
                return $this->study($param2, $param3, $param4, $param5, $param6);
            } elseif ($param1  == 'general') {
                if (request()->method() == 'GET') {
                    return $this->general($param2, $param3, $param4, $param5);
                } else {
                    abort(404);
                }
            } elseif ($param1  == 'profile') {
                $view = new ProfileController();
                return $view->index($param2, $param3, $param4);
            } elseif ($param1  == Users::path('url')) {
                return Users::getData(null, null, 10, request('search'));
            } elseif ($param1  == ActivityFeed::path('url')) {
                $view = new ActivityFeedController();
                return $view->index($param2, $param3, $param4);
            } elseif ($param1  == Mailbox::path('url')) {
                $view = new MailboxController();
                return $view->index($param2, $param3, $param4);
            } else {
                abort(404);
            }
        } else {

            if ($param1  == null) {
                unset($breadcrumb[1]);
                $data['shortcuts'] = [
                    [
                        'title' => null,
                        'children'  => [
                            [
                                'name'  => __('Add Student'),
                                'link'  => url(Users::role() . '/' . Students::path('url') . '/add'),
                                'icon'  => 'fas fa-user-plus',
                                'image' => null,
                                'color' => 'bg-' . config('app.theme_color.name'),
                            ],
                            [
                                'name'  => __('Add Student short form'),
                                'link'  => url('student-register'),
                                'target' => '_blank',
                                'icon'  => 'fas fa-user-plus',
                                'image' => null,
                                'color' => 'bg-' . config('app.theme_color.name'),
                            ], [
                                'name'  => __('List all Student'),
                                'link'  => url(Users::role() . '/' . Students::path('url') . '/list'),
                                'icon'  => 'fas fa-users-class',
                                'image' => null,
                                'color' => 'bg-' . config('app.theme_color.name'),
                            ], [
                                'name'  => __('List Card frames'),
                                'link'  => url(Users::role() . '/' . Students::path('url') . '/' . CardFrames::path('url') . '/list'),
                                'icon'  => 'fas fa-id-card',
                                'image' => null,
                                'color' => 'bg-' . config('app.theme_color.name'),
                            ], [
                                'name'  => __('List Certificate frames'),
                                'link'  => url(Users::role() . '/' . Students::path('url') . '/' . CertificateFrames::path('url') . '/list'),
                                'icon'  => 'fas fa-file-certificate',
                                'image' => null,
                                'color' => 'bg-' . config('app.theme_color.name'),
                            ],
                        ]
                    ],
                    [
                        'title' => __('Long course'),
                        'children' => [
                            [
                                'name'  => __('List Students study course'),
                                'link'  => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/list'),
                                'icon'  => 'fas fa-user-graduate',
                                'image' => null,
                                'color' => 'bg-' . config('app.theme_color.name'),
                            ], [
                                'name'  => __('List request study'),
                                'link'  => url(Users::role() . '/' . Students::path('url') . '/' . StudentsRequest::path('url') . '/list'),
                                'icon'  => 'fas fa-users-medical',
                                'image' => null,
                                'color' => 'bg-' . config('app.theme_color.name'),
                            ],
                            // [
                            //     'name'  => __('List Students attendance'),
                            //     'link'  => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/' . StudentsAttendances::path('url') . '/list'),
                            //     'icon'  => 'fas fa-calendar-edit',
                            //     'image' => null,
                            //     'color' => 'bg-' . config('app.theme_color.name'),
                            // ], [
                            //     'name'  => __('List Students study course score'),
                            //     'link'  => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/' . StudentsStudyCourseScore::path('url') . '/list'),
                            //     'icon'  => 'fas fa-trophy-alt',
                            //     'image' => null,
                            //     'color' => 'bg-' . config('app.theme_color.name'),
                            // ],
                        ]
                    ],
                    [
                        'title' => __('Short course'),
                        'children' => [
                            [
                                'name'  => __('List Students'),
                                'link'  => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyShortCourse::path('url') . '/list'),
                                'icon'  => 'fas fa-user-graduate',
                                'image' => null,
                                'color' => 'bg-' . config('app.theme_color.name'),
                            ], [
                                'name'  => __('List Student request study'),
                                'link'  => url(Users::role() . '/' . Students::path('url') . '/' . StudentsShortCourseRequest::path('url') . '/list'),
                                'icon'  => 'fas fa-users-medical',
                                'image' => null,
                                'color' => 'bg-' . config('app.theme_color.name'),
                            ],
                        ]
                    ]
                ];


                $data['view']  = Students::path('view') . '.includes.dashboardAdmin.index';
                $data['title']   = Users::role(app()->getLocale()) . ' | ' . __('Students');
            } elseif ($param1  == 'list') {
                $breadcrumb[1]['status']  = 'active';
                if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {

                    return  Students::getData(null, null, 10, request('search'));
                } else {
                    $data = $this->list($data);
                }
            } elseif ($param1 == 'list-datatable') {
                if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                    return  Students::getDataTable();
                } else {
                    $data = $this->list($data);
                }
            } elseif ($param1  == 'add') {
                $breadcrumb[]  = [
                    'title' => __('Add Student'),
                    'status' => 'active',
                    'link'  => url(Users::role() . '/' . Students::path('url') . '/add'),
                ];
                if (request()->method() === 'POST') {
                    return Students::addToTable();
                }
                $data = $this->show($data, null, $param1);
            } elseif ($param1  == 'view') {
                $id = request('id', $param2);
                $breadcrumb[]  = [
                    'title' => __('View Student'),
                    'status' => 'active',
                    'link'  => url(Users::role() . '/' . Students::path('url') . '/view/' . $id),
                ];
                $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('View Students');
                $data['response']['data'] = Students::whereIn('id', explode(',', $id))->get()->map(function ($row) {
                    $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                    $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
                    $row['nationality'] = Nationality::where('id', $row->nationality_id)->pluck(app()->getLocale())->first();
                    $row['mother_tong'] = MotherTong::where('id', $row->mother_tong_id)->pluck(app()->getLocale())->first();
                    $row['marital'] = Marital::where('id', $row->marital_id)->pluck(app()->getLocale())->first();
                    $row['blood_group'] = BloodGroup::where('id', $row->blood_group_id)->pluck(app()->getLocale())->first();
                    $row['student_guardian'] = StudentsGuardians::getData($row->id)['data'][0];
                    $row['photo'] = $row['photo'] ? ImageHelper::site(Students::path('image'), $row['photo']) : ImageHelper::site(Students::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
                    $row['action']  = [
                        'edit'   => url(Users::role() . '/' . Students::path('url') . '/edit/' . $row['id']),
                        'view'   => url(Users::role() . '/' . Students::path('url') . '/view/' . $row['id']),
                        'account' => url(Users::role() . '/' . Students::path('url') . '/account/create/' . $row['id']),
                        'print' => url(Users::role() . '/' . Students::path('url') . '/print/' . $row['id']),
                        'delete' => url(Users::role() . '/' . Students::path('url') . '/delete/' . $row['id']),
                    ];
                    return $row;
                });
                $data['formAction']          = '/view/' . $id;
                $data['view']  = Students::path('view') . '.includes.view.index';
            } elseif ($param1  == 'print') {
                $id = request('id', $param2);
                return $this->print($id);
            } elseif ($param1  == 'edit') {

                $id = request('id', $param2);
                $breadcrumb[]  = [
                    'title' => __('Edit Student'),
                    'status' => 'active',
                    'link'  => url(Users::role() . '/' . Students::path('url') . '/edit/' . $id),
                ];

                if (request()->method() === 'POST') {
                    return Students::updateToTable($id);
                }
                $data = $this->show($data, $id, $param1);
            } elseif ($param1  == 'delete') {
                $id = request('id', $param2);
                return Students::deleteFromTable($id);
            } elseif (($param1) == 'account') {

                $id = request('id', $param3);

                $breadcrumb[]  = [
                    'title' => __('Create account'),
                    'status' => 'active',
                    'link'  => url(Users::role() . '/' . Students::path('url') . '/account/create/' . $id),
                ];
                if ($param2 == 'create') {
                    if (request()->method() == "POST") {
                        return Students::createAccountToTable($id);
                    }

                    $data = $this->show($data, $id, $param1);
                    $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Create account');
                    $data['view']       = Students::path('view') . '.includes.account.index';

                    $data['roles']['data']  = Roles::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                        $row['image']   = $row->image ?  ImageHelper::site(Roles::path('image'), $row->image) : ImageHelper::prefix();
                        return $row;
                    });
                }
            } elseif (($param1) == 'report') {
                return $this->report();
            } elseif ($param1  == StudentsStudyCourse::path('url')) {
                $student = new StudentsStudyCourseController();
                return $student->index($param2, $param3, $param4);
            } elseif ($param1  == StudentsRequest::path('url')) {
                $student = new StudentsRequestController();
                return $student->index($param2, $param3, $param4);
            } elseif ($param1 == CardFrames::path('url')) {
                $view = new CardFramesController();
                return $view->index($param2, $param3, $param4, $param5, $param6);
            } elseif ($param1 == CertificateFrames::path('url')) {
                $view = new CertificateFramesController();
                return $view->index($param2, $param3, $param4, $param5, $param6);
            } elseif ($param1 == StudentsStudyShortCourse::path('url')) {
                $view = new StudentsStudyShortCourseController();
                return $view->index($param2, $param3, $param4, $param5, $param6);
            } elseif ($param1 == StudentsShortCourseRequest::path('url')) {
                $view = new StudentsShortCourseRequestController();
                return $view->index($param2, $param3, $param4, $param5, $param6);
            } else {
                abort(404);
            }
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
            'parent'     => Students::path('view'),
            'modal'      => Students::path('view') . '.includes.modal.index',
            'view'       => $data['view'],
        );

        $pages['form']['validate'] = Students::validate();

        if (Auth::user()->role_id && $param1 == 'dashboard' || $param1 == null) {
            $pages['form']['validate'] = QuizStudentAnswer::validate();
        }

        if (Auth::user()->role_id != 6) {
            $data['institute']['data']  = Institute::get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
                return $row;
            });
            $data['instituteFilter']['data'] = Institute::whereIn('id', Students::groupBy('institute_id')->pluck('institute_id'))
                ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                    $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
                    return $row;
                });
            $data['mother_tong']['data']         = MotherTong::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = $row->image ?  ImageHelper::site(Institute::path('image'), $row->image) : ImageHelper::prefix();
                return $row;
            });

            $data['nationality']['data']         = Nationality::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = $row->image ?  ImageHelper::site(Institute::path('image'), $row->image) : ImageHelper::prefix();
                return $row;
            });
            $data['marital']['data']             = Marital::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = $row->image ?  ImageHelper::site(Institute::path('image'), $row->image) : ImageHelper::prefix();
                return $row;
            });
            $data['blood_group']['data']         = BloodGroup::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = $row->image ?  ImageHelper::site(Institute::path('image'), $row->image) : ImageHelper::prefix();
                return $row;
            });
            $data['provinces']['data']           = Provinces::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = $row->image ?  ImageHelper::site(Institute::path('image'), $row->image) : ImageHelper::prefix();
                return $row;
            });
            $data['districts']           =  [
                'data'  => [],
                'action' => [
                    'list'  =>  url(Users::role() . '/general/' . Districts::path('url') . '/list'),
                ]
            ];
            $data['communes']            = [
                'data'  => [],
                'action' => [
                    'list'  =>  url(Users::role() . '/general/' . Communes::path('url') . '/list'),
                ]
            ];
            $data['villages']            = [
                'data'  => [],
                'action' => [
                    'list'  =>  url(Users::role() . '/general/' . Villages::path('url') . '/list'),
                ]
            ];

            $data['curr_districts']      = $data['districts'];
            $data['curr_communes']       = $data['communes'];
            $data['curr_villages']       = $data['villages'];
        }

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);

        return view($pages['parent'] . '.index', $data);
    }

    public function list($data, $id = null)
    {
        $table = Students::whereHas('institute', function ($query) {
            $query->where('institute_id', request('instituteId'));
        });
        $count = $table->count();
        if ($id) {
            $table->whereIn('id', explode(',', $id));
        }

        $response = $table->orderBy('id', 'DESC')
            ->get()->map(function ($row, $nid) use ($count) {
                $row['nid'] = $count - $nid;
                $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
                $row['date_of_birth'] = DateHelper::convert($row->date_of_birth, 'd-M-Y');
                $row['student_guardian'] = StudentsGuardians::getData($row->id)['data'][0];
                $row['photo'] = $row['photo'] ? ImageHelper::site(Students::path('image'), $row['photo']) : ImageHelper::site(Students::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
                $row['account'] = Users::where('node_id', $row->id)->where('email', $row->email)->exists();
                $row['action']  = [
                    'edit'   => url(Users::role() . '/' . Students::path('url') . '/edit/' . $row['id']),
                    'view'   => url(Users::role() . '/' . Students::path('url') . '/view/' . $row['id']),
                    'account' => url(Users::role() . '/' . Students::path('url') . '/account/create/' . $row['id']),
                    'delete' => url(Users::role() . '/' . Students::path('url') . '/delete/' . $row['id']),
                ];

                return $row;
            });

        if ($id) {
            return $response;
        }
        $data['response'] = [
            'data'      => $response,
            'gender'    => Students::gender($table),
        ];


        $data['view']  = Students::path('view') . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List Students');
        return $data;
    }



    public function account($data, $id, $type)
    {
        $response           = Students::getData($id);
        $data['formData']   = $response['data'][0];
        $data['listData']   = $response['pages']['listData'];
        $data['formAction'] = '/account/' . $type . '/' . $response['data'][0]['id'];
        $data['view']       = Students::path('view') . '.includes.account.index';

        return $data;
    }

    public function show($data, $id, $type)
    {
        $data['view']       = Students::path('view') . '.includes.form.index';
        if ($id) {

            $response           = Students::whereIn('id', explode(',', $id))->get()->map(function ($row) {
                $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                $row['date_of_birth'] = DateHelper::convert($row->date_of_birth, 'd-m-Y');
                $row['institute'] = Institute::where('id', $row->institute_id)->pluck(app()->getLocale())->first();
                $row['student_guardian'] = StudentsGuardians::where('student_id', $row->id)->first();
                $row['photo'] = $row['photo'] ? ImageHelper::site(Students::path('image'), $row['photo']) : ImageHelper::site(Students::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
                $row['account'] = Users::where('email', $row->email)->where('node_id', $row->id)->exists();
                $row['action']  = [
                    'edit'   => url(Users::role() . '/' . Students::path('url') . '/edit/' . $row['id']),
                    'view'   => url(Users::role() . '/' . Students::path('url') . '/view/' . $row['id']),
                    'account' => url(Users::role() . '/' . Students::path('url') . '/account/create/' . $row['id']),
                    'print' => url(Users::role() . '/' . Students::path('url') . '/print/' . $row['id']),
                    'delete' => url(Users::role() . '/' . Students::path('url') . '/delete/' . $row['id']),
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
                        'edit'    => url(Users::role() . '/' . Students::path('url') . '/edit/' . $row->id),
                    ],
                ];
            });

            $data['formData']   = $response;
            $data['formAction'] = '/' . $type . '/' . $id;
        }


        return $data;
    }

    public function print($id)
    {

        request()->merge([
            'size'  => request('size', 'A4'),
            'layout'  => request('layout', 'portrait'),
        ]);

        config()->set('app.title', __('List all Students'));
        config()->set('pages.parent', Students::path('view'));

        $data['response']['data'] = Students::whereIn('id', explode(',', $id))->get()->map(function ($row) {
            $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
            $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
            $row['nationality'] = Nationality::where('id', $row->nationality_id)->pluck(app()->getLocale())->first();
            $row['mother_tong'] = MotherTong::where('id', $row->mother_tong_id)->pluck(app()->getLocale())->first();
            $row['marital'] = Marital::where('id', $row->marital_id)->pluck(app()->getLocale())->first();
            $row['blood_group'] = BloodGroup::where('id', $row->blood_group_id)->pluck(app()->getLocale())->first();
            $row['student_guardian'] = StudentsGuardians::getData($row->id)['data'][0];
            $row['photo'] = $row['photo'] ? ImageHelper::site(Students::path('image'), $row['photo']) : ImageHelper::site(Students::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
            return $row;
        });

        config()->set('pages.title', __('List all Students'));

        return view(Students::path('view') . '.includes.print.index', $data);
    }

    public function report()
    {

        request()->merge([
            'size'  => request('size', 'A4'),
            'layout'  => request('layout', 'portrait'),
        ]);

        config()->set('app.title', __('List all Students'));
        config()->set('pages.parent', Students::path('view'));

        $data['instituteFilter']['data']           = Institute::whereIn('id', Students::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
                return $row;
            });

        $table = Students::orderBy('first_name_km')
        ->orderBy('last_name_km')
        ->orderBy('first_name_en')
        ->orderBy('last_name_en');

        if (request('instituteId')) {
            $table->where('institute_id', request('instituteId'));
        }

        $response = $table->get()->map(function ($row) {
            // $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
            $row['name'] = $row['first_name_' . app()->getLocale()] . ' ' . $row['last_name_' . app()->getLocale()];
            $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
            $row['date_of_birth'] = DateHelper::convert($row->date_of_birth, 'd-M-Y');
            $row['photo'] = $row['photo'] ? ImageHelper::site(Students::path('image'), $row['photo']) : ImageHelper::site(Students::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));

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

        config()->set('pages.title', __('List all Students'));

        return view(Students::path('view') . '.includes.report.index', $data);
    }

    public function dashboard($data)
    {
        $data['days']                  = Days::getData();
        $data['institute']            = Institute::getData(request('instituteId', 'null'));
        $data['study_program']        = StudyPrograms::getData(request('programId', 'null'));
        $data['study_course']         = StudyCourse::getData(request('courseId', 'null'));
        $data['study_generation']     = StudyGeneration::getData(request('generationId', 'null'));
        $data['study_academic_year']  = StudyAcademicYears::getData(request('yearId', 'null'));
        $data['study_semester']       = StudySemesters::getData(request('semesterId', 'null'));
        $data['study_session']        = StudySession::getData(request('sessionId', 'null'));
        $data['study_class']          = StudyClass::getData(request('classId', 'null'));

        $data['months']               = Months::getData();
        $data['attendances_type']     = AttendanceTypes::getData();
        $data['formAction']          = '/add';
        $data['formName']            = Students::path('url');
        $data['metaImage']           = asset('assets/img/icons/add.png');
        $data['metaLink']            = url(Users::role() . '/add');
        $data['formData']            = array(
            'photo'                  => asset('/assets/img/user/male.jpg'),
        );
        $data['listData']            = array();
        request()->merge([
            'year'  => request('year', Years::now()),
            'month' => request('month', Months::now()),
        ]);

        $data['study_course_session'] = null;
        if (Auth::user()->node_id) {
            $student_study_course = StudentsStudyCourse::select((new StudentsStudyCourse())->getTable() . '.*')->join((new StudentsRequest())->getTable(), (new StudentsRequest())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.student_request_id')
                ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsRequest())->getTable() . '.student_id')
                ->where((new StudentsRequest())->getTable() . '.student_id', Auth::user()->node_id)
                ->latest((new StudentsStudyCourse())->getTable() . '.id')
                ->first();
            if ($student_study_course) {
                $data['study_course_session'] = StudyCourseSession::getData($student_study_course->study_course_session_id);
                $data['study_course_session']['data'][0]['schedules'] = StudyCourseRoutine::getData($student_study_course->study_course_session_id)['data'];
                $data['study_course_session']['data'][0]['score'] = StudentsStudyCourseScore::getData(null, null, null, $student_study_course->id);
                $data['study_course_session']['data'][0]['attendances'] = StudentsAttendances::getData(null, null, null, $student_study_course->id);
                $data['study_course_session']['data'][0]['holiday'] = Holidays::getHoliday(request('year'), request('month'), $student_study_course->study_course_session_id)['data'];
            }
        }
        $quiz = new QuizStudentAnswerController;
        $quiz = $quiz->list(['formName' => null])['response'];
        $data['quiz'] = $quiz;
        $data['formName'] = 'study/' . Quiz::path('url') . '/' . QuizStudentAnswer::path('url');

        $data['title']   = Users::role(app()->getLocale()) . ' | ' . __('Dashboard');
        $data['view']    = Students::path('view') . '.includes.dashboard.index';

        return  $data;
    } // End dashboard

    public function study($param1 = null, $param2 = null, $param3 = null, $param4 = null)
    {


        $data['study_course_session'] = StudentsStudyCourse::getStudy(Auth::user()->node_id);
        $data['course_routine'] = StudentsStudyCourse::join((new StudentsRequest())->getTable(), (new StudentsRequest())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.student_request_id')
            ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsRequest())->getTable() . '.student_id')
            ->where((new StudentsRequest())->getTable() . '.student_id', Auth::user()->node_id)
            ->latest('study_course_session_id')
            ->first();

        if ($data['course_routine']) {
            request()->merge([
                'course-sessionId' => request('course-sessionId', $data['course_routine']->study_course_session_id),
            ]);
        }

        $data['formAction']          = '/add';
        $data['formName']            = 'study';
        $data['metaImage']           = asset('assets/img/icons/add.png');
        $data['metaLink']            = url(Users::role() . '/add');
        $data['formData']            = array(
            'photo'                  => asset('/assets/img/user/male.jpg'),
        );
        $data['institute']         = Institute::getData();
        $data['study_program']     = StudyPrograms::getData();
        $data['study_course']      = StudyCourse::getData();
        $data['study_generation']  = StudyGeneration::getData();
        $data['study_academic_year']  = StudyAcademicYears::getData();
        $data['study_semester']       = StudySemesters::getData();
        $data['study_session']       = StudySession::getData();

        $data['listData']            = array();

        if ($param1  == null) {
            if (Auth::user()->node_id) {
                $data['shortcuts'] = [
                    [
                        'title' => null,
                        'children'  => [
                            [
                                'name'  => __('Edit Register'),
                                'link'  => url(Users::role() . '/study/edit'),
                                'icon'  => 'fas fa-user-edit',
                                'image' => null,
                                'color' => 'bg-' . config('app.theme_color.name'),
                            ],
                            [
                                'name'  => __('List Quiz'),
                                'link'  => url(Users::role() . '/study/' . Quiz::path('url') . '/list'),
                                'icon'  => 'fas fa-question-circle',
                                'image' => null,
                                'color' => 'bg-' . config('app.theme_color.name'),
                            ],
                        ]
                    ],
                    [
                        'title' => __('Long course'),
                        'children'  => [

                            [
                                'name'  => __('Study course'),
                                'link'  => url(Users::role() . '/study/' . str_replace('request', 'approved', StudentsRequest::path('url')) . '/list'),
                                'icon'  => 'fas fa-user-graduate',
                                'image' => null,
                                'color' => 'bg-' . config('app.theme_color.name'),
                            ],
                            [
                                'name'  => __('Request study'),
                                'link'  => url(Users::role() . '/study/' . StudentsRequest::path('url') . '/list'),
                                'icon'  => 'fas fa-layer-plus',
                                'image' => null,
                                'color' => 'bg-' . config('app.theme_color.name'),
                            ], [
                                'name'  => __('List Schedule'),
                                'link'  => url(Users::role() . '/study/schedule/list'),
                                'icon'  => 'fas fa-calendar-alt',
                                'image' => null,
                                'color' => 'bg-' . config('app.theme_color.name'),
                            ], [
                                'name'  => __('List Attendance'),
                                'link'  => url(Users::role() . '/study/' . StudentsAttendances::path('url') . '/list'),
                                'icon'  => 'fas fa-calendar-edit',
                                'image' => null,
                                'color' => 'bg-' . config('app.theme_color.name'),
                            ], [
                                'name'  => __('List Score'),
                                'link'  => url(Users::role() . '/study/' . StudentsStudyCourseScore::path('url') . '/list'),
                                'icon'  => 'fas fa-trophy-alt',
                                'image' => null,
                                'color' => 'bg-' . config('app.theme_color.name'),
                            ],
                        ]
                    ],
                    [
                        'title' => __('Short course'),
                        'children'  => [

                            [
                                'name'  => __('Study course'),
                                'link'  => url(Users::role() . '/study/' . str_replace('request', 'approved', StudentsShortCourseRequest::path('url')) . '/list'),
                                'icon'  => 'fas fa-user-graduate',
                                'image' => null,
                                'color' => 'bg-' . config('app.theme_color.name'),
                            ],
                            [
                                'name'  => __('Request study'),
                                'link'  => url(Users::role() . '/study/' . StudentsShortCourseRequest::path('url') . '/list'),
                                'icon'  => 'fas fa-layer-plus',
                                'image' => null,
                                'color' => 'bg-' . config('app.theme_color.name'),
                            ],
                        ]
                    ]
                ];
            } else {
                $data['shortcuts'] = [
                    [
                        'title' => null,
                        'children'  => [
                            [
                                'name'  => __('Register'),
                                'link'  => url(Users::role() . '/study/register'),
                                'icon'  => 'fas fa-user-plus',
                                'image' => null,
                                'color' => 'bg-' . config('app.theme_color.name'),
                            ],
                        ]
                    ]

                ];
            }
            $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('Study');
            $data['view']  = Students::path('view') . '.includes.study.includes.dashboard.index';
        } elseif ($param1 == 'register') {
            $data['mother_tong']         = MotherTong::getData();
            $data['blood_group']         = BloodGroup::getData();
            $data['gender']              = Gender::getData();
            $data['nationality']         = Nationality::getData();
            $data['marital']             = Marital::getData();
            $data['provinces']           = Provinces::getData();
            $data['districts']           = Districts::getData('null', 'null');
            $data['communes']            = Communes::getData('null', 'null');
            $data['villages']            = Villages::getData('null', 'null');
            $data['curr_districts']      = Districts::getData('null', 'null');
            $data['curr_communes']       = Communes::getData('null', 'null');
            $data['curr_villages']       = Villages::getData('null', 'null');
            $data = $this->add($data);
            $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('Register');
        } elseif ($param1 == 'edit') {
            $data['provinces']           = Provinces::getData();
            $data['districts']           = Districts::getData('null', 'null');
            $data['communes']            = Communes::getData('null', 'null');
            $data['villages']            = Villages::getData('null', 'null');
            $data['curr_districts']      = Districts::getData('null', 'null');
            $data['curr_communes']       = Communes::getData('null', 'null');
            $data['curr_villages']       = Villages::getData('null', 'null');
            if (request()->method() == 'POST') {
                $id = $param2 ? $param2 : request('id');
                return Students::updateToTable($id);
            } else {
                $data = $this->show($data, Auth::user()->node_id, 'edit');
                $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('Edit');
            }
        } elseif ($param1 == str_replace('request', 'approved', StudentsRequest::path('url'))) {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return  StudentsStudyCourse::getStudy(Auth::user()->node_id);
            } else {
                $data['formName']            = 'study/' . StudentsRequest::path('url');

                if (strtolower($param2) == 'list' || strtolower($param2) == null) {
                    $data['studys'] = StudentsStudyCourse::getStudy(Auth::user()->node_id);
                    $data['response']  = StudentsRequest::getData(null, Auth::user()->node_id, 10);

                    $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('List Approved and request study');
                    $data['view']    = Students::path('view') . '.includes.study.includes.course.list.index';
                } else {
                    abort(404);
                }
            }
        } elseif ($param1 == StudentsRequest::path('url')) {
            $data['formName'] .= '/' . StudentsRequest::path('url');

            if (strtolower($param2) == 'list' || strtolower($param2) == null) {
                $data['response']  = StudentsRequest::getData(null, Auth::user()->node_id, 10);
                $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('Request');
                $data['view']    = Students::path('view') . '.includes.study.includes.requesting.list.index';
            } elseif (strtolower($param2) == 'add') {
                if (request()->method() == 'POST') {
                    request()->merge([
                        'student' => [Auth::user()->node_id]
                    ]);
                    return StudentsRequest::addToTable();
                } else {
                    $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('Course');
                    $data['view']    = Students::path('view') . '.includes.study.includes.requesting.form.index';
                }
            } elseif (strtolower($param2) == 'edit') {
                if (request()->method() == 'POST') {
                    return StudentsRequest::updateToTable(request('id', $param3));
                } else {
                    $data['formAction']          = '/edit';
                    request()->merge([
                        'ref'   => Students::path('url') . '-' . StudentsRequest::path('url')
                    ]);
                    $data['title']   = __(Users::role(app()->getLocale()) . '. | .course');
                    $data['view']    = Students::path('view') . '.includes.study.includes.requesting.form.index';
                    $response  = StudentsRequest::getData(request('id', $param3));

                    $data['formData'] = $response['data'][0];
                    $data['listData'] = $response['pages']['listData'];
                    $data['institute']         = Institute::getData();
                    $data['study_program']     = StudyPrograms::getData();
                    $data['study_course']      = StudyCourse::getData();
                    $data['study_generation']  = StudyGeneration::getData();
                    $data['study_academic_year']  = StudyAcademicYears::getData();
                    $data['study_semester']       = StudySemesters::getData();
                    $data['study_session']       = StudySession::getData();
                }
            } elseif (strtolower($param2) == 'delete') {
                return StudentsRequest::deleteFromTable(request('id', $param3));
            } else {
                abort(404);
            }
        } elseif ($param1 == str_replace('request', 'approved', StudentsShortCourseRequest::path('url'))) {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return  StudentsStudyShortCourse::getStudy(Auth::user()->node_id);
            } else {
                $data['formName']            = 'study/' . StudentsShortCourseRequest::path('url');


                if (strtolower($param2) == 'list' || strtolower($param2) == null) {
                    $data['studys'] = StudentsStudyShortCourse::getStudy(Auth::user()->node_id);
                    $data['response']  = StudentsShortCourseRequest::getData(null, Auth::user()->node_id, 10);



                    $data['title'] = __(Users::role(app()->getLocale()) . '. | .list.short_course.approved.and.request_study');
                    $data['view']    = Students::path('view') . '.includes.study.includes.short_course.list.index';
                } else {
                    abort(404);
                }
            }
        } elseif ($param1 == StudentsShortCourseRequest::path('url')) {
            $data['formName'] .= '/' . StudentsShortCourseRequest::path('url');

            if (strtolower($param2) == 'list' || strtolower($param2) == null) {
                $data['response']  = StudentsShortCourseRequest::getData(null, Auth::user()->node_id, 10);

                $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('Short course request');
                $data['view']    = Students::path('view') . '.includes.study.includes.short_course_requesting.list.index';
            } elseif (strtolower($param2) == 'list-datatable') {
                if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                    request()->merge([
                        'ref'   => Students::path('url') . '-' . StudentsShortCourseRequest::path('url'),
                    ]);
                    return  StudentsShortCourseRequest::getDataTable(Auth::user()->node_id);
                }
            } elseif (strtolower($param2) == 'add') {
                request()->merge([
                    'ref'   => Students::path('url') . '-' . StudentsShortCourseRequest::path('url'),
                    'courseTId' => 1,
                ]);
                if (request()->method() == 'POST') {
                    request()->merge([
                        'student' => [Auth::user()->node_id]
                    ]);
                    return StudentsShortCourseRequest::addToTable();
                } else {
                    $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('Course');
                    $data['view']    = Students::path('view') . '.includes.study.includes.short_course_requesting.form.index';
                    $data['study_subject']       = StudySubjects::getData();
                }
            } elseif (strtolower($param2) == 'edit') {
                if (request()->method() == 'POST') {
                    return StudentsShortCourseRequest::updateToTable(request('id', $param3));
                } else {
                    $data['formAction']          = '/edit';

                    $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('Course');
                    $data['view']    = Students::path('view') . '.includes.study.includes.short_course_requesting.form.index';
                    $response  = StudentsShortCourseRequest::getData(request('id', $param3));

                    $data['formData'] = $response['data'][0];
                    $data['listData'] = $response['pages']['listData'];
                    $data['institute']         = Institute::getData();
                    $data['study_subject']       = StudySubjects::getData();
                    $data['study_session']       = StudySession::getData();
                }
            } elseif (strtolower($param2) == 'delete') {
                return StudentsShortCourseRequest::deleteFromTable(request('id', $param3));
            } else {
                abort(404);
            }
        } elseif ($param1 == 'schedule') {
            if ($data['course_routine']) {
                $data['days']                  = Days::getData();
                $data['response'] = StudyCourseRoutine::getData(request('course-sessionId', $data['course_routine']->study_course_session_id));
                $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('Schedule');
                $data['view']  = Students::path('view') . '.includes.study.includes.schedule.index';
            } else {
                abort(404);
            }
        } elseif ($param1 == StudentsAttendances::path('url')) {
            $data['months']               = Months::getData();
            $data['attendances_type']     = AttendanceTypes::getData();
            $data = $this->attendance($data);
            $data['view']    = Students::path('view') . '.includes.study.includes.attendance.index';
        } elseif ($param1 == Quiz::path('url')) {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                $course_routine = StudentsStudyCourse::select((new QuizStudents())->getTable() . '.*')
                    ->join((new QuizStudents())->getTable(), (new QuizStudents())->getTable() . '.student_study_course_id', (new StudentsStudyCourse())->getTable() . '.id')
                    ->join((new StudentsRequest())->getTable(), (new StudentsRequest())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.student_request_id')
                    ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsRequest())->getTable() . '.student_id')
                    ->where('student_id', Auth::user()->node_id)
                    ->get()->toArray();
                if ($course_routine) {
                    $quiz_id = [];
                    foreach ($course_routine as $key => $row) {
                        $quiz_id[] = $row['quiz_id'];
                    }
                    return Quiz::getData(request('quizId', $quiz_id));
                }
            } else {
                $view = new QuizStudentAnswerController();
                return $view->index($param2, $param3, $param4);
            }
        } elseif ($param1 == StudentsStudyCourseScore::path('url')) {
            $data = $this->score($data);
            $data['view']    = Students::path('view') . '.includes.study.includes.score.index';
        } elseif ($param1 == Institute::path('url')) {
            if (request()->ajax() && request()->method() == 'GET') {
                return Institute::getData(null, null, 10);
            }
        } elseif ($param1 == StudyPrograms::path('url')) {
            if (request()->ajax() && request()->method() == 'GET') {
                return StudyPrograms::getData(null, null, 10);
            }
        } elseif ($param1 == StudyCourse::path('url')) {
            if (request()->ajax() && request()->method() == 'GET') {
                return StudyCourse::getData(null, null, 10);
            }
        } elseif ($param1 == StudyGeneration::path('url')) {
            if (request()->ajax() && request()->method() == 'GET') {
                return StudyGeneration::getData(null, null, 10);
            }
        } elseif ($param1 == StudyAcademicYears::path('url')) {
            if (request()->ajax() && request()->method() == 'GET') {
                return StudyAcademicYears::getData(null, null, 10);
            }
        } elseif ($param1 == StudySemesters::path('url')) {
            if (request()->ajax() && request()->method() == 'GET') {
                return StudySemesters::getData(null, null, 10);
            }
        } elseif ($param1 == StudySession::path('url')) {
            if (request()->ajax() && request()->method() == 'GET') {
                return StudySession::getData(null, null, 10);
            }
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
            'parent'     => Students::path('view'),
            'modal'      => Students::path('view') . '.includes.modal.index',
            'view'       => $data['view'],
        );

        if ($param1 == StudentsRequest::path('url')) {
            $pages['form']['validate'] = StudentsRequest::validate();
        } elseif ($param1 == StudentsShortCourseRequest::path('url')) {
            $pages['form']['validate'] = StudentsShortCourseRequest::validate();
        } else {
            $pages['form']['validate'] = Students::validate();
        }

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view($pages['parent'] . '.index', $data);
    }

    public function attendance($data)
    {

        if ($data['course_routine']) {
            $monthYear =  request('month_year') ? explode('-', request('month_year')) : null;
            request()->merge([
                'course-sessionId' => request('course-sessionId', $data['course_routine']->study_course_session_id),
                'year'             => $monthYear ? $monthYear[1] : Years::now(),
                'month'            => $monthYear ? $monthYear[0] : Months::now(),
                'date'             => request('date') ? request('date') : date('d'),
                'type'             => Students::path('role'),
            ]);
            $data['study_course_session'] = StudyCourseSession::getData(request('course-sessionId', $data['course_routine']->study_course_session_id), null, 10);
        }

        $view = new StudentsAttendanceController();
        return $view->list($data);
    }
    public function score($data)
    {

        if ($data['course_routine']) {
            request()->merge([
                'course-sessionId' => request('course-sessionId', $data['course_routine']->study_course_session_id),
                'type'           => Students::path('role'),
            ]);
        }
        $view = new StudentsStudyCourseScoreController();
        return $view->list($data);
    }

    public function general($param1 = null, $param2 = null, $param3 = null, $param4 = null)
    {
        $view = new GeneralController();
        if ($param1 != null) {
            return $view->index($param1, $param2, $param3, $param4);
        } else {
            abort(404);
        }
    }
}
