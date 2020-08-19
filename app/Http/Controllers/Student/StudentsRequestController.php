<?php

namespace App\Http\Controllers\Student;


use Carbon\Carbon;
use App\Models\App;
use App\Models\Users;
use App\Models\Gender;
use App\Models\Students;
use App\Models\Institute;
use App\Models\Languages;

use App\Helpers\DateHelper;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Models\StudyCourse;
use App\Helpers\ImageHelper;
use App\Models\SocailsMedia;
use App\Models\StudySession;
use App\Models\StudyPrograms;
use App\Models\StudySemesters;
use App\Models\StudentsRequest;
use App\Models\StudyGeneration;
use App\Models\StudyAcademicYears;
use Illuminate\Support\Collection;
use App\Models\StudentsStudyCourse;
use App\Models\StudyCourseSchedule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\FormStudentsRequest;

class StudentsRequestController extends Controller
{


    public function __construct()
    {
        App::setConfig();
       Languages::setConfig(); App::setConfig();  SocailsMedia::setConfig();
        view()->share('breadcrumb', []);
    }

    public function index($param1 = 'list', $param2 = null, $param3 = null)
    {


        $data['formData']            = array(
            'photo'                  => asset('/assets/img/user/male.jpg'),
        );


        $data['formAction']      = '/add';
        $data['formName']        = Students::$path['url'] . '/' . StudentsRequest::$path['url'];
        $data['title']           = Users::role(app()->getLocale()) . ' | ' . __('List Request study');
        $data['metaImage']       = asset('assets/img/icons/' . $param1 . '.png');
        $data['metaLink']        = url(Users::role() . '/' . $param1);
        $data['listData']       = array();
        if ($param1 == 'list' || $param1 == null) {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return StudentsRequest::getData(null, null, 10, request('search'));
            } else {
                $data = $this->list($data);
            }
        } elseif (strtolower($param1) == 'list-datatable') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return  StudentsRequest::getDataTable();
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'add') {
            $data = $this->show($data, null, $param1);
        } elseif ($param1 == 'view') {
            $id = request('id', $param2);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('View Students required');
            $data['response']['data'] = StudentsRequest::join((new Students)->getTable(), (new Students)->getTable() . '.id', (new StudentsRequest)->getTable() . '.student_id')
                ->whereIn((new StudentsRequest)->getTable() . '.id', explode(',', $id))
                ->get([
                    (new StudentsRequest())->getTable() . '.*',
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
                    $row['photo'] = $row['photo'] ? ImageHelper::site(Students::$path['image'], $row['photo']) : ImageHelper::site(Students::$path['image'], ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));

                    $row['study_program'] = StudyPrograms::where('id', $row->study_program_id)->pluck(app()->getLocale())->first();
                    $row['study_course'] = StudyCourse::where('id', $row->study_course_id)->pluck(app()->getLocale())->first();
                    $row['study_generation'] = StudyGeneration::where('id', $row->study_generation_id)->pluck(app()->getLocale())->first();
                    $row['study_academic_year'] = StudyAcademicYears::where('id', $row->study_academic_year_id)->pluck(app()->getLocale())->first();
                    $row['study_semester'] = StudySemesters::where('id', $row->study_semester_id)->pluck(app()->getLocale())->first();
                    $row['study_session'] = StudySession::where('id', $row->study_session_id)->pluck(app()->getLocale())->first();
                    $row['action']  = [
                        'edit'   => url(Users::role() . '/' . Students::$path['url'] . '/' . StudentsRequest::$path['url'] . '/edit/' . $row['id']),
                        'view'   => url(Users::role() . '/' . Students::$path['url'] . '/' . StudentsRequest::$path['url'] . '/view/' . $row['id']),
                        'delete' => url(Users::role() . '/' . Students::$path['url'] . '/' . StudentsRequest::$path['url'] . '/delete/' . $row['id']),
                    ];

                    return $row;
                });
            $data['formAction']          = '/view/' . $id;
            $data['view']  = StudentsRequest::$path['view'] . '.includes.view.index';
        } elseif ($param1 == 'edit') {
            $id = request('id', $param2);
            if (request()->method() == 'POST') {
                return StudentsRequest::updateToTable(request('id', $param2));
            }
            $data = $this->show($data, $id, $param1);
        } elseif ($param1 == 'report') {
            return $this->report();
        } elseif ($param1 == 'delete') {
            return StudentsRequest::deleteFromTable(request('id', $param2));
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
            'parent'     => StudentsRequest::$path['view'],
            'view'       => $data['view'],
        );

        $pages['form']['validate'] = [
            'rules'       =>  FormStudentsRequest::rulesField(),
            'attributes'  =>  FormStudentsRequest::attributeField(),
            'messages'    =>  FormStudentsRequest::customMessages(),
            'questions'   =>  FormStudentsRequest::questionField(),
        ];

        //Select Option

        $data['institute']['data']  = Institute::get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
            $row['image']   = ImageHelper::site(Institute::$path['image'], $row->logo);
            return $row;
        });
        $data['instituteFilter']['data'] = Institute::whereIn('id', StudentsRequest::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::$path['image'], $row->logo);
                return $row;
            });

        $data['study_program']['data']     = StudyPrograms::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ? ImageHelper::site(Institute::$path['image'], $row->image) : ImageHelper::prefix();
            return $row;
        });
        $data['study_course'] = [
            'data' => StudyCourse::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = $row->image ? ImageHelper::site(Institute::$path['image'], $row->image) : ImageHelper::prefix();
                return $row;
            }),
            'action' => [
                'list'  =>  url(Users::role() . '/study/' . StudyCourse::$path['url'] . '/list/')
            ]
        ];

        $data['study_generation']['data']  = StudyGeneration::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ? ImageHelper::site(Institute::$path['image'], $row->image) : ImageHelper::prefix();
            return $row;
        });
        $data['study_academic_year']['data']  = StudyAcademicYears::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ? ImageHelper::site(Institute::$path['image'], $row->image) : ImageHelper::prefix();
            return $row;
        });
        $data['study_semester']['data']       = StudySemesters::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ? ImageHelper::site(Institute::$path['image'], $row->image) : ImageHelper::prefix();
            return $row;
        });
        $data['study_session']['data']       = StudySession::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ? ImageHelper::site(Institute::$path['image'], $row->image) : ImageHelper::prefix();
            return $row;
        });
        $data['student']['data']       = Students::get()->map(function ($row) {
            $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
            $row['photo']   = $row->photo ? ImageHelper::site(Students::$path['image'], $row->photo) : ImageHelper::prefix();
            return $row;
        });


        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view(StudentsRequest::$path['view'] . '.index', $data);
    }

    public function list($data)
    {
        $table = StudentsRequest::join((new Students)->getTable(), (new Students)->getTable() . '.id', (new StudentsRequest)->getTable() . '.student_id');
        if (request('instituteId')) {
            $table->where('institute_id', request('instituteId'));
        }

        $response = $table->orderBy((new StudentsRequest)->getTable() . '.id', 'DESC')
            ->get([
                (new Students)->getTable() . '.first_name_km',
                (new Students)->getTable() . '.last_name_km',
                (new Students)->getTable() . '.first_name_en',
                (new Students)->getTable() . '.last_name_en',
                (new Students)->getTable() . '.gender_id',
                (new Students)->getTable() . '.email',
                (new Students)->getTable() . '.phone',
                (new StudentsRequest)->getTable() . '.*',
            ])->map(function ($row) {
                $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
                $row['institute']            = Institute::where('id', $row->institute_id)->pluck(app()->getLocale())->first();
                $row['study_program']        = StudyPrograms::where('id', $row->study_program_id)->pluck(app()->getLocale())->first();
                $row['study_course']         = StudyCourse::where('id', $row->study_course_id)->pluck(app()->getLocale())->first();
                $row['study_generation']     = StudyGeneration::where('id', $row->study_generation_id)->pluck(app()->getLocale())->first();
                $row['study_academic_year']  = StudyAcademicYears::where('id', $row->study_academic_year_id)->pluck(app()->getLocale())->first();
                $row['study_semester']       = StudySemesters::where('id', $row->study_semester_id)->pluck(app()->getLocale())->first();
                $row['study_session']       = StudySession::where('id', $row->study_session_id)->pluck(app()->getLocale())->first();
                $row['action']  = [
                    'edit'   => url(Users::role() . '/' . Students::$path['url'] . '/' . StudentsRequest::$path['url'] . '/edit/' . $row['id']),
                    'view'   => url(Users::role() . '/' . Students::$path['url'] . '/' . StudentsRequest::$path['url'] . '/view/' . $row['id']),
                    'approve' => url(Users::role() . '/' . Students::$path['url'] . '/' . StudentsStudyCourse::$path['url'] . '/add?studRequestId=' . $row['id']),
                    'delete' => url(Users::role() . '/' . Students::$path['url'] . '/' . StudentsRequest::$path['url'] . '/delete/' . $row['id']),
                ];

                return $row;
            })->toArray();


        $data['response'] = [
            'data'      => $response,
            'gender'    => Students::gender($table),
        ];


        $data['view']  = Students::$path['view'] . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List Student request study');
        return $data;
    }

    public function show($data, $id, $type)
    {
        $data['view']       = StudentsRequest::$path['view'] . '.includes.form.index';
        if ($id) {

            $response        = StudentsRequest::join((new Students)->getTable(), (new Students)->getTable() . '.id', (new StudentsRequest)->getTable() . '.student_id')
                ->whereIn((new StudentsRequest)->getTable() . '.id', explode(',', $id))
                ->get()->map(function ($row) {
                    $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                    $row['photo'] = $row['photo'] ? ImageHelper::site(Students::$path['image'], $row['photo']) : ImageHelper::site(Students::$path['image'], ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
                    $row['action']  = [
                        'edit'   => url(Users::role() . '/' . Students::$path['url'] . '/' . StudentsRequest::$path['url'] . '/edit/' . $row['id']),
                        'view'   => url(Users::role() . '/' . Students::$path['url'] . '/' . StudentsRequest::$path['url'] . '/view/' . $row['id']),
                        'delete' => url(Users::role() . '/' . Students::$path['url'] . '/' . StudentsRequest::$path['url'] . '/delete/' . $row['id']),
                    ];

                    return $row;
                });
            $data['listData'] =  $response->map(function ($row) {
                return [
                    'id'  => $row->id,
                    'name'  => $row->name,
                    'image'  => $row->photo,
                    'action'  => [
                        'edit'    => url(Users::role() . '/' . Students::$path['url'] . '/edit/' . $row->id),
                    ],
                ];
            });

            $data['formData']   = $response;
            $data['formAction'] = '/' . $type . '/' . $id;
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
        config()->set('pages.parent', StudentsStudyCourse::$path['view']);



        $data['instituteFilter']['data']           = Institute::whereIn('id', StudentsRequest::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::$path['image'], $row->logo);
                return $row;
            });
        $data['programFilter']['data']           = StudyPrograms::whereIn('id', StudentsRequest::groupBy('study_program_id')->pluck('study_program_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::$path['image'], $row->image);
                return $row;
            });
        $data['courseFilter']['data']        = StudyCourse::whereIn('id', StudentsRequest::groupBy('study_course_id')->pluck('study_course_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::$path['image'], $row->image);
                return $row;
            });
        $data['generationFilter']['data']           = StudyGeneration::whereIn('id', StudentsRequest::groupBy('study_generation_id')->pluck('study_generation_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::$path['image'], $row->image);
                return $row;
            });

        $data['academicFilter']['data']           = StudyAcademicYears::whereIn('id', StudentsRequest::groupBy('study_academic_year_id')->pluck('study_academic_year_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::$path['image'], $row->image);
                return $row;
            });
        $data['semesterFilter']['data']           = StudySemesters::whereIn('id', StudentsRequest::groupBy('study_semester_id')->pluck('study_semester_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::$path['image'], $row->image);
                return $row;
            });
        $data['sessionFilter']['data']           = StudySession::whereIn('id', StudentsRequest::groupBy('study_session_id')->pluck('study_session_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::$path['image'], $row->image);
                return $row;
            });


        $table = StudentsRequest::join((new Students)->getTable(), (new Students)->getTable() . '.id', (new StudentsRequest)->getTable() . '.student_id');
        if (request('instituteId')) {
            $table->where((new StudentsRequest)->getTable() . '.institute_id', request('instituteId'));
        }

        $response = $table->get([
            (new Students)->getTable() . '.first_name_km',
            (new Students)->getTable() . '.last_name_km',
            (new Students)->getTable() . '.first_name_en',
            (new Students)->getTable() . '.last_name_en',
            (new Students)->getTable() . '.gender_id',
            (new Students)->getTable() . '.email',
            (new Students)->getTable() . '.phone',
            (new StudentsRequest)->getTable() . '.*',
        ])->map(function ($row) {
            //$row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
            $row['name'] = $row['first_name_' . app()->getLocale()] . ' ' . $row['last_name_' . app()->getLocale()];
            $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
            $row['photo'] = $row['photo'] ? ImageHelper::site(Students::$path['image'], $row['photo']) : ImageHelper::site(Students::$path['image'], ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));


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
                $row['logo'] = ImageHelper::site(Institute::$path['image'], $row['logo']);
                return $row;
            })->first();

        config()->set('pages.title', __('Students required'));

        return view(StudentsStudyCourse::$path['view'] . '.includes.report.index', $data);
    }
}
