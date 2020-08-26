<?php

namespace App\Http\Controllers\Study;


use Carbon\Carbon;
use App\Models\App as AppModel;
use App\Models\Users;
use App\Models\Gender;
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
use App\Models\StudyGeneration;
use App\Models\StudyAcademicYears;
use Illuminate\Support\Collection;
use App\Models\StudyCourseSchedule;
use App\Http\Controllers\Controller;

class StudyCourseSchedulesController extends Controller
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
        $breadcrumb  = [
            [
                'title' => __('Study'),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . StudyCourseSchedule::path('url')),
            ],
            [
                'title' => __('List Study Course Schedule'),
                'status' => false,
                'link'  => url(Users::role() . '/study/' . StudyCourseSchedule::path('url') . '/list'),
            ]
        ];

        $data['formData']            = [[]];
        $data['formAction']      = '/add';
        $data['formName']        = 'study/' . StudyCourseSchedule::path('url');
        $data['title']           = Users::role(app()->getLocale()) . ' | ' . __('List Request study');
        $data['metaImage']       = asset('assets/img/icons/' . $param1 . '.png');
        $data['metaLink']        = url(Users::role() . '/' . $param1);
        $data['listData']       = array();
        if ($param1 == 'list' || $param1 == null) {
            $breadcrumb[1]['status']  = 'active';
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'add') {
            $breadcrumb[] = [
                'title' => __($param1),
                'status' => 'active',
                'link'  => url(Users::role() . '/study/' . 'study/' . $param1),
            ];
            if (request()->method() == 'POST') {
                return StudyCourseSchedule::addToTable();
            } else {
                $data = $this->show($data, null, $param1);
            }
        } elseif ($param1 == 'view') {
            $id = request('id', $param2);
            $breadcrumb[] = [
                'title' => __($param1),
                'status' => 'active',
                'link'  => url(Users::role() . '/study/' . 'study/' . $param1 . '/' . $id),
            ];
            $data = $this->show($data, $id, $param1);
            $data['view']  = StudyCourseSchedule::path('view') . '.includes.view.index';
        } elseif ($param1 == 'edit') {
            $id = request('id', $param2);
            $breadcrumb[] = [
                'title' => __($param1),
                'status' => 'active',
                'link'  => url(Users::role() . '/study/' . 'study/' . $param1 . '/' . $id),
            ];
            if (request()->method() == 'POST') {
                return StudyCourseSchedule::updateToTable($id);
            }
            $data = $this->show($data, $id, $param1);
        } elseif ($param1 == 'report') {
            return $this->report();
        } elseif ($param1 == 'delete') {
            return StudyCourseSchedule::deleteFromTable(request('id', $param2));
        } else {
            abort(404);
        }

        view()->share('breadcrumb', $breadcrumb);

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
            'parent'     => StudyCourseSchedule::path('view'),
            'view'       => $data['view'],
        );

        $pages['form']['validate'] = StudyCourseSchedule::validate();

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

        $data['study_program']['data']     = StudyPrograms::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ? ImageHelper::site(Institute::path('image'), $row->image) : ImageHelper::prefix();
            return $row;
        });
        $data['study_course'] = [
            'data' => StudyCourse::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = $row->image ? ImageHelper::site(Institute::path('image'), $row->image) : ImageHelper::prefix();
                return $row;
            }),
            'action' => [
                'list'  =>  url(Users::role() . '/study/' . StudyCourse::path('url') . '/list/')
            ]
        ];

        $data['study_generation']['data']  = StudyGeneration::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ? ImageHelper::site(Institute::path('image'), $row->image) : ImageHelper::prefix();
            return $row;
        });
        $data['study_academic_year']['data']  = StudyAcademicYears::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ? ImageHelper::site(Institute::path('image'), $row->image) : ImageHelper::prefix();
            return $row;
        });
        $data['study_semester']['data']       = StudySemesters::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ? ImageHelper::site(Institute::path('image'), $row->image) : ImageHelper::prefix();
            return $row;
        });
        $data['study_session']['data']       = StudySession::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ? ImageHelper::site(Institute::path('image'), $row->image) : ImageHelper::prefix();
            return $row;
        });

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view(StudyCourseSchedule::path('view') . '.index', $data);
    }

    public function list($data, $id = null)
    {
        $table = StudyCourseSchedule::orderBy('id', 'DESC');
        $table->whereHas('institute', function ($query) {
            if (request('instituteId')) {
                $query->where('id', request('instituteId'));
            }
        });
        $count = $table->count();
        if ($id) {
            $table->whereIn('id', explode(',', $id));
        }
        $response = $table->get()->map(function ($row, $nid) use ($count) {
                $row['nid'] = $count - $nid;
                $row['institute']            = Institute::where('id', $row->institute_id)->pluck(app()->getLocale())->first();
                $row['study_program']        = StudyPrograms::where('id', $row->study_program_id)->pluck(app()->getLocale())->first();
                $row['study_course']         = StudyCourse::where('id', $row->study_course_id)->pluck(app()->getLocale())->first();
                $row['study_generation']     = StudyGeneration::where('id', $row->study_generation_id)->pluck(app()->getLocale())->first();
                $row['study_academic_year']  = StudyAcademicYears::where('id', $row->study_academic_year_id)->pluck(app()->getLocale())->first();
                $row['study_semester']       = StudySemesters::where('id', $row->study_semester_id)->pluck(app()->getLocale())->first();

                $row['name'] = $row['study_generation'] . ' ('  . $row['study_program'] . ' - ' . $row['study_course'] . ' - ' . $row['study_academic_year']. ' - ' . $row['study_semester']. ')';


                $row['action']  = [
                    'edit'   => url(Users::role() . '/study/' . StudyCourseSchedule::path('url') . '/edit/' . $row['id']),
                    'view'   => url(Users::role() . '/study/' . StudyCourseSchedule::path('url') . '/view/' . $row['id']),
                    'approve' => url(Users::role() . '/study/' . StudyCourseSchedule::path('url') . '/add?studRequestId=' . $row['id']),
                    'delete' => url(Users::role() . '/study/' . StudyCourseSchedule::path('url') . '/delete/' . $row['id']),
                ];

                return $row;
            });


        if ($id) {
            return $response;
        }
        $data['response']['data'] = $response;




        $data['view']  = StudyCourseSchedule::path('view') . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List');
        return $data;
    }

    public function show($data, $id, $type)
    {
        $data['view']       = StudyCourseSchedule::path('view') . '.includes.form.index';
        if ($id) {

            $response        = StudyCourseSchedule::join((new StudyCourseSchedule)->getTable(), (new StudyCourseSchedule)->getTable() . '.id', (new StudyCourseSchedule)->getTable() . '.student_id')
                ->whereIn((new StudyCourseSchedule)->getTable() . '.id', explode(',', $id))
                ->get([
                    (new StudyCourseSchedule)->getTable() . '.*',
                    (new StudyCourseSchedule())->getTable() . '.first_name_km',
                    (new StudyCourseSchedule())->getTable() . '.last_name_km',
                    (new StudyCourseSchedule())->getTable() . '.first_name_en',
                    (new StudyCourseSchedule())->getTable() . '.last_name_en',
                    (new StudyCourseSchedule())->getTable() . '.gender_id',
                    (new StudyCourseSchedule())->getTable() . '.photo',
                    (new StudyCourseSchedule())->getTable() . '.email',
                    (new StudyCourseSchedule())->getTable() . '.phone',

                ])->map(function ($row) {
                    $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                    $row['photo'] = $row['photo'] ? ImageHelper::site(StudyCourseSchedule::path('image'), $row['photo']) : ImageHelper::site(StudyCourseSchedule::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
                    $row['action']  = [
                        'edit'   => url(Users::role() . '/study/' . StudyCourseSchedule::path('url') . '/edit/' . $row['id']),
                        'view'   => url(Users::role() . '/study/' . StudyCourseSchedule::path('url') . '/view/' . $row['id']),
                        'delete' => url(Users::role() . '/study/' . StudyCourseSchedule::path('url') . '/delete/' . $row['id']),
                    ];

                    return $row;
                });
            $data['listData'] =  $response->map(function ($row) {
                return [
                    'id'  => $row->id,
                    'name'  => $row->name,
                    'image'  => $row->photo,
                    'action'  => [
                        'edit'    => url(Users::role() . '/' . StudyCourseSchedule::path('url') . '/edit/' . $row->id),
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
        config()->set('pages.parent', StudyCourseSchedule::path('view'));



        $data['instituteFilter']['data']           = Institute::whereIn('id', StudyCourseSchedule::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
                return $row;
            });
        $data['programFilter']['data']           = StudyPrograms::whereIn('id', StudyCourseSchedule::groupBy('study_program_id')->pluck('study_program_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });
        $data['courseFilter']['data']        = StudyCourse::whereIn('id', StudyCourseSchedule::groupBy('study_course_id')->pluck('study_course_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });
        $data['generationFilter']['data']           = StudyGeneration::whereIn('id', StudyCourseSchedule::groupBy('study_generation_id')->pluck('study_generation_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });

        $data['academicFilter']['data']           = StudyAcademicYears::whereIn('id', StudyCourseSchedule::groupBy('study_academic_year_id')->pluck('study_academic_year_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });
        $data['semesterFilter']['data']           = StudySemesters::whereIn('id', StudyCourseSchedule::groupBy('study_semester_id')->pluck('study_semester_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });
        $data['sessionFilter']['data']           = StudySession::whereIn('id', StudyCourseSchedule::groupBy('study_session_id')->pluck('study_session_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });


        $table = StudyCourseSchedule::join((new StudyCourseSchedule)->getTable(), (new StudyCourseSchedule)->getTable() . '.id', (new StudyCourseSchedule)->getTable() . '.student_id');
        if (request('instituteId')) {
            $table->where((new StudyCourseSchedule)->getTable() . '.institute_id', request('instituteId'));
        }

        $response = $table->get([
            (new StudyCourseSchedule)->getTable() . '.*',
            (new StudyCourseSchedule)->getTable() . '.first_name_km',
            (new StudyCourseSchedule)->getTable() . '.last_name_km',
            (new StudyCourseSchedule)->getTable() . '.first_name_en',
            (new StudyCourseSchedule)->getTable() . '.last_name_en',
            (new StudyCourseSchedule)->getTable() . '.gender_id',
            (new StudyCourseSchedule)->getTable() . '.email',
            (new StudyCourseSchedule)->getTable() . '.phone',

        ])->map(function ($row) {
            //$row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
            $row['name'] = $row['first_name_' . app()->getLocale()] . ' ' . $row['last_name_' . app()->getLocale()];
            $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
            $row['photo'] = $row['photo'] ? ImageHelper::site(StudyCourseSchedule::path('image'), $row['photo']) : ImageHelper::site(StudyCourseSchedule::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));


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
            'genders' => StudyCourseSchedule::gender($table),
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

        config()->set('pages.title', __('StudyCourseSchedule required'));

        return view(StudyCourseSchedule::path('view') . '.includes.report.index', $data);
    }
}
