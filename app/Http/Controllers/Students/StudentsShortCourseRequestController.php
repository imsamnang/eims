<?php

namespace App\Http\Controllers\Students;
use Carbon\Carbon;
use App\Models\App as AppModel;
use App\Models\Users;
use App\Models\Gender;
use App\Models\Students;
use App\Models\Institute;
use App\Models\Languages;
use App\Helpers\DateHelper;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Helpers\ImageHelper;
use App\Models\SocailsMedia;
use App\Models\StudySession;
use App\Models\StudySubjects;
use App\Models\StudentsShortCourseRequest;
use App\Models\StudyGeneration;
use Illuminate\Support\Collection;
use App\Models\StudentsStudyCourse;
use App\Http\Controllers\Controller;

class StudentsShortCourseRequestController extends Controller
{


    public function __construct()
    {
        AppModel::setConfig();
        Languages::setConfig();
        AppModel::setConfig();
        SocailsMedia::setConfig();
        view()->share('breadcrumb', []);
    }

    public function index($param1 = 'list', $param2 = null, $param3 = null)
    {

        $data['formData']            = array(
            'photo'                  => asset('/assets/img/user/male.jpg'),
        );

        $data['formAction']      = '/add';
        $data['formName']        = Students::path('url') . '/' . StudentsShortCourseRequest::path('url');
        $data['title']           = Users::role(app()->getLocale()) . ' | ' . __('List Request study');
        $data['metaImage']       = asset('assets/img/icons/' . $param1 . '.png');
        $data['metaLink']        = url(Users::role() . '/' . $param1);
        $data['listData']       = array();
        if ($param1 == 'list' || $param1 == null) {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return StudentsShortCourseRequest::getData(null, null, 10, request('search'));
            } else {
                $data = $this->list($data);
            }
        } elseif (strtolower($param1) == 'list-datatable') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return  StudentsShortCourseRequest::getDataTable();
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'add') {
            if (request()->method() === 'POST') {
                return StudentsShortCourseRequest::addToTable();
            }
            $data = $this->show($data, null, $param1);
        } elseif ($param1 == 'view') {
            $id = request('id', $param2);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('View Students required');
            $data['response']['data'] = StudentsShortCourseRequest::join((new Students)->getTable(), (new Students)->getTable() . '.id', (new StudentsShortCourseRequest)->getTable() . '.student_id')
                ->whereIn((new StudentsShortCourseRequest)->getTable() . '.id', explode(',', $id))
                ->get([
                    (new StudentsShortCourseRequest())->getTable() . '.*',
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
                    $row['study_generation'] = StudyGeneration::where('id', $row->study_generation_id)->pluck(app()->getLocale())->first();
                    $row['study_subject'] = StudySubjects::where('id', $row->study_subject_id)->pluck(app()->getLocale())->first();
                    $row['study_session'] = StudySession::where('id', $row->study_session_id)->pluck(app()->getLocale())->first();
                    $row['action']  = [
                        'edit'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsShortCourseRequest::path('url') . '/edit/' . $row['id']),
                        'view'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsShortCourseRequest::path('url') . '/view/' . $row['id']),
                        'delete' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsShortCourseRequest::path('url') . '/delete/' . $row['id']),
                    ];

                    return $row;
                });
            $data['formAction']          = '/view/' . $id;
            $data['view']  = StudentsShortCourseRequest::path('view') . '.includes.view.index';
        } elseif ($param1 == 'edit') {
            $id = request('id', $param2);
            if (request()->method() == 'POST') {
                return StudentsShortCourseRequest::updateToTable(request('id', $param2));
            }
            $data = $this->show($data, $id, $param1);
        } elseif ($param1 == 'report') {
            return $this->report();
        } elseif ($param1 == 'delete') {
            return StudentsShortCourseRequest::deleteFromTable(request('id', $param2));
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
            'parent'     => StudentsShortCourseRequest::path('view'),
            'view'       => $data['view'],
        );

        $pages['form']['validate'] = [
            'rules'       =>  (new FormStudentsShortCourseRequest)->rules(),
            'attributes'  =>  (new FormStudentsShortCourseRequest)->attributes(),
            'messages'    =>  (new FormStudentsShortCourseRequest)->messages(),
            'questions'   =>  (new FormStudentsShortCourseRequest)->questions(),
        ];

        //Select Option

        $data['institute']['data']  = Institute::get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
            $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
            return $row;
        });
        $data['instituteFilter']['data'] = Institute::whereIn('id', StudentsShortCourseRequest::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
                return $row;
            });


        $data['study_generation']['data']  = StudyGeneration::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ? ImageHelper::site(Institute::path('image'), $row->image) : ImageHelper::prefix();
            return $row;
        });

        $data['study_subjects']['data']       = StudySubjects::where('course_type_id', 1)->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ? ImageHelper::site(Institute::path('image'), $row->image) : ImageHelper::prefix();
            return $row;
        });

        $data['study_session']['data']       = StudySession::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ? ImageHelper::site(Institute::path('image'), $row->image) : ImageHelper::prefix();
            return $row;
        });

        $data['students']['data']       = Students::get()->map(function ($row) {
            $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
            $row['photo']   = $row->photo ? ImageHelper::site(Students::path('image'), $row->photo) : ImageHelper::prefix();
            return $row;
        });


        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view(StudentsShortCourseRequest::path('view') . '.index', $data);
    }

    public function list($data, $id = null)
    {
        $table = StudentsShortCourseRequest::join((new Students)->getTable(), (new Students)->getTable() . '.id', (new StudentsShortCourseRequest)->getTable() . '.student_id');
        if (request('instituteId')) {
            $table->where((new StudentsShortCourseRequest)->getTable() . '.institute_id', request('instituteId'));
        }

        $response = $table->orderBy((new StudentsShortCourseRequest)->getTable() . '.id', 'DESC')
            ->get([
                (new Students)->getTable() . '.first_name_km',
                (new Students)->getTable() . '.last_name_km',
                (new Students)->getTable() . '.first_name_en',
                (new Students)->getTable() . '.last_name_en',
                (new Students)->getTable() . '.gender_id',
                (new Students)->getTable() . '.email',
                (new Students)->getTable() . '.phone',
                (new StudentsShortCourseRequest)->getTable() . '.*',
            ])->map(function ($row) {
                $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
                $row['institute']            = Institute::where('id', $row->institute_id)->pluck(app()->getLocale())->first();
                $row['study_semester']       = StudySubjects::where('id', $row->study_semester_id)->pluck(app()->getLocale())->first();
                $row['study_session']       = StudySession::where('id', $row->study_session_id)->pluck(app()->getLocale())->first();
                $row['study_subject']       = StudySubjects::where('id', $row->study_subject_id)->pluck(app()->getLocale())->first();
                $row['action']  = [
                    'edit'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsShortCourseRequest::path('url') . '/edit/' . $row['id']),
                    'view'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsShortCourseRequest::path('url') . '/view/' . $row['id']),
                    'approve' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/add?studRequestId=' . $row['id']),
                    'delete' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsShortCourseRequest::path('url') . '/delete/' . $row['id']),
                ];

                return $row;
            })->toArray();


        $data['response'] = [
            'data'      => $response,
            'gender'    => Students::gender($table),
        ];


        $data['view']  = Students::path('view') . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List Student request study');
        return $data;
    }

    public function show($data, $id, $type)
    {
        $data['view']       = StudentsShortCourseRequest::path('view') . '.includes.form.index';
        if ($id) {

            $response        = StudentsShortCourseRequest::join((new Students)->getTable(), (new Students)->getTable() . '.id', (new StudentsShortCourseRequest)->getTable() . '.student_id')
                ->whereIn((new StudentsShortCourseRequest)->getTable() . '.id', explode(',', $id))
                ->get()->map(function ($row) {
                    $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                    $row['photo'] = $row['photo'] ? ImageHelper::site(Students::path('image'), $row['photo']) : ImageHelper::site(Students::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
                    $row['action']  = [
                        'edit'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsShortCourseRequest::path('url') . '/edit/' . $row['id']),
                        'view'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsShortCourseRequest::path('url') . '/view/' . $row['id']),
                        'delete' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsShortCourseRequest::path('url') . '/delete/' . $row['id']),
                    ];

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
    public function report()
    {
        request()->merge([
            'size'  => request('size', 'A4'),
            'layout'  => request('layout', 'portrait'),
        ]);

        config()->set('app.title', __('List Students short course request'));
        config()->set('pages.parent', StudentsShortCourseRequest::path('view'));



        $data['instituteFilter']['data']           = Institute::whereIn('id', StudentsShortCourseRequest::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
                return $row;
            });

        $data['subjectFilter']['data']           = StudySubjects::whereIn('id', StudentsShortCourseRequest::groupBy('study_subject_id')->pluck('study_subject_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(StudySubjects::path('image'), $row->image);
                return $row;
            });
        $data['sessionFilter']['data']           = StudySession::whereIn('id', StudentsShortCourseRequest::groupBy('study_session_id')->pluck('study_session_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(StudySession::path('image'), $row->image);
                return $row;
            });


        $table = StudentsShortCourseRequest::join((new Students)->getTable(), (new Students)->getTable() . '.id', (new StudentsShortCourseRequest)->getTable() . '.student_id');
        if (request('instituteId')) {
            $table->where((new StudentsShortCourseRequest)->getTable() . '.institute_id', request('instituteId'));
        }


        $response = $table->get([
            (new Students)->getTable() . '.first_name_km',
            (new Students)->getTable() . '.last_name_km',
            (new Students)->getTable() . '.first_name_en',
            (new Students)->getTable() . '.last_name_en',
            (new Students)->getTable() . '.gender_id',
            (new Students)->getTable() . '.email',
            (new Students)->getTable() . '.phone',
            (new StudentsShortCourseRequest)->getTable() . '.*',
        ])->map(function ($row) {
            //$row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
            $row['name'] = $row['first_name_' . app()->getLocale()] . ' ' . $row['last_name_' . app()->getLocale()];
            $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
            $row['photo'] = $row['photo'] ? ImageHelper::site(Students::path('image'), $row['photo']) : ImageHelper::site(Students::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));

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

        config()->set('pages.title', __('List Students short course request'));

        return view(StudentsShortCourseRequest::path('view') . '.includes.report.index', $data);
    }
}
