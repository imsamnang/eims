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
use App\Models\StudyCourseSession;
use App\Http\Controllers\Controller;
use App\Models\StudyCourseSchedule;

class StudyCourseSessionsController extends Controller
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
                'link'  => url(Users::role() . '/' . StudyCourseSession::path('url')),
            ],
            [
                'title' => __('List Study Course Session'),
                'status' => false,
                'link'  => url(Users::role() . '/study/' . StudyCourseSession::path('url') . '/list'),
            ]
        ];

        $data['formData']            = [[]];
        $data['formAction']      = '/add';
        $data['formName']        = 'study/' . StudyCourseSession::path('url');
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
                'link'  => url(Users::role() . '/study/' .StudyCourseSession::path('url'). '/' . $param1),
            ];
            if (request()->method() == 'POST') {
                return StudyCourseSession::addToTable();
            } else {
                $data = $this->show($data, null, $param1);
            }
        } elseif ($param1 == 'view') {
            $id = request('id', $param2);
            $breadcrumb[] = [
                'title' => __($param1),
                'status' => 'active',
                'link'  => url(Users::role() . '/study/' .StudyCourseSession::path('url'). '/' . $param1 . '/' . $id),
            ];
            $data = $this->show($data, $id, $param1);
            $data['view']  = StudyCourseSession::path('view') . '.includes.view.index';
        } elseif ($param1 == 'edit') {
            $id = request('id', $param2);
            $breadcrumb[] = [
                'title' => __($param1),
                'status' => 'active',
                'link'  => url(Users::role() . '/study/' .StudyCourseSession::path('url'). '/' . $param1 . '/' . $id),
            ];
            if (request()->method() == 'POST') {
                return StudyCourseSession::updateToTable($id);
            }
            $data = $this->show($data, $id, $param1);
        } elseif ($param1 == 'report') {
            return $this->report();
        } elseif ($param1 == 'delete') {
            return StudyCourseSession::deleteFromTable(request('id', $param2));
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
            'parent'     => StudyCourseSession::path('view'),
            'view'       => $data['view'],
        );

        $pages['form']['validate'] = StudyCourseSession::validate();

        //Select Option

        $data['instituteFilter']['data'] = Institute::whereIn('id', StudyCourseSchedule::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
                return $row;
            });

            $data['study_course_schedule']['data']       = StudyCourseSchedule::whereHas('institute', function ($query) {
                if (request('instituteId')) {
                    $query->where('id', request('instituteId'));
                }
            })->get()->map(function ($row) {
                $row['study_program']        = StudyPrograms::where('id', $row->study_program_id)->pluck(app()->getLocale())->first();
                $row['study_course']         = StudyCourse::where('id', $row->study_course_id)->pluck(app()->getLocale())->first();
                $row['study_generation']     = StudyGeneration::where('id', $row->study_generation_id)->pluck(app()->getLocale())->first();
                $row['study_academic_year']  = StudyAcademicYears::where('id', $row->study_academic_year_id)->pluck(app()->getLocale())->first();
                $row['study_semester']       = StudySemesters::where('id', $row->study_semester_id)->pluck(app()->getLocale())->first();
                $row['name'] = $row['study_program'] . ' (' . $row['study_course'] . ')';
                $row['name'].= ' - ' .$row['study_generation'].','.$row['study_academic_year'].','.$row['study_semester'];

                return $row;
            });

        $data['study_session']['data']       = StudySession::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ? ImageHelper::site(Institute::path('image'), $row->image) : ImageHelper::prefix();
            return $row;
        });

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view(StudyCourseSession::path('view') . '.index', $data);
    }

    public function list($data, $id = null)
    {
        $table = StudyCourseSession::orderBy('id', 'DESC');

        $table->whereHas('study_course_schedule', function ($query) {
            if (request('scheduleId')) {
                $query->where('id', request('scheduleId'));
            }else{
                if (request('instituteId')) {
                    $query->where('institute_id', request('instituteId'));
                }
            }
        });


        $count = $table->count();

        if ($id) {
            $table->whereIn('id', explode(',', $id));
        }
        $response = $table->get()->map(function ($row, $nid) use ($count) {
                $row['nid'] = $count - $nid;
                $row['institute']            = Institute::where('id', $row->study_course_schedule->first()->institute_id)->pluck(app()->getLocale())->first();
                $row['study_program']        = StudyPrograms::where('id', $row->study_course_schedule->first()->study_program_id)->pluck(app()->getLocale())->first();
                $row['study_course']         = StudyCourse::where('id', $row->study_course_schedule->first()->study_course_id)->pluck(app()->getLocale())->first();
                $row['study_generation']     = StudyGeneration::where('id', $row->study_course_schedule->first()->study_generation_id)->pluck(app()->getLocale())->first();
                $row['study_academic_year']  = StudyAcademicYears::where('id', $row->study_course_schedule->first()->study_academic_year_id)->pluck(app()->getLocale())->first();
                $row['study_semester']       = StudySemesters::where('id', $row->study_course_schedule->first()->study_semester_id)->pluck(app()->getLocale())->first();
                $row['study_session']       = StudySession::where('id', $row->study_session_id)->pluck(app()->getLocale())->first();

                $row['name'] = $row['study_program'] . ' (' . $row['study_course'] . ')';
                $row['name'].= ' - ' .$row['study_generation'].','.$row['study_academic_year'].','.$row['study_semester'];


                $row->study_start = DateHelper::convert($row->study_start,'d-F-Y');
                $row->study_end = DateHelper::convert($row->study_end,'d-F-Y');

                $row['action']  = [
                    'edit'   => url(Users::role() . '/study/' . StudyCourseSession::path('url') . '/edit/' . $row['id']),
                    'view'   => url(Users::role() . '/study/' . StudyCourseSession::path('url') . '/view/' . $row['id']),
                    'delete' => url(Users::role() . '/study/' . StudyCourseSession::path('url') . '/delete/' . $row['id']),
                ];

                return $row;
            });


        if ($id) {
            return $response;
        }
        $data['response']['data'] = $response;

        $data['view']  = StudyCourseSession::path('view') . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List Study Course Session');
        return $data;
    }

    public function show($data, $id, $type)
    {
        $data['title']           = Users::role(app()->getLocale()) . ' | ' . __('Study Course Session'). ' | ' . __($type);
        $data['view']       = StudyCourseSession::path('view') . '.includes.form.index';
        if ($id) {
            $response        = StudyCourseSession::whereIn('id', explode(',', $id))
                ->get()->map(function ($row) {
                    $row['institute']            = Institute::where('id', $row->schedule->institute_id)->pluck(app()->getLocale())->first();
                    $row['study_program']        = StudyPrograms::where('id', $row->schedule->study_program_id)->pluck(app()->getLocale())->first();
                    $row['study_course']         = StudyCourse::where('id', $row->schedule->study_course_id)->pluck(app()->getLocale())->first();
                    $row['study_generation']     = StudyGeneration::where('id', $row->schedule->study_generation_id)->pluck(app()->getLocale())->first();
                    $row['study_academic_year']  = StudyAcademicYears::where('id', $row->schedule->study_academic_year_id)->pluck(app()->getLocale())->first();
                    $row['study_semester']       = StudySemesters::where('id', $row->schedule->study_semester_id)->pluck(app()->getLocale())->first();

                    $row['study_session']       = StudySession::where('id', $row->study_session_id)->pluck(app()->getLocale())->first();

                    $row['name'] = $row['study_program'] . ' (' . $row['study_course'] . ')';
                    $row->study_start = DateHelper::convert($row->study_start,'d-m-Y');
                    $row->study_end = DateHelper::convert($row->study_end,'d-m-Y');
                    $row['action']  = [
                        'edit'   => url(Users::role() . '/study/' . StudyCourseSession::path('url') . '/edit/' . $row['id']),
                        'view'   => url(Users::role() . '/study/' . StudyCourseSession::path('url') . '/view/' . $row['id']),
                        'delete' => url(Users::role() . '/study/' . StudyCourseSession::path('url') . '/delete/' . $row['id']),
                    ];

                    return $row;
                });
            $data['response']['data'] = $response;
            $data['listData'] =  $response->map(function ($row) {
                return [
                    'id'  => $row->id,
                    'name'  => $row->name,
                    'image'  => $row->photo,
                    'action'  => [
                        'edit'    => url(Users::role() . '/' . StudyCourseSession::path('url') . '/edit/' . $row->id),
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

        config()->set('app.title', __('List Study Course Session'));
        config()->set('pages.parent', StudyCourseSession::path('view'));



        $data['instituteFilter']['data']           = Institute::whereIn('id', StudyCourseSession::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
                return $row;
            });
        $data['programFilter']['data']           = StudyPrograms::whereIn('id', StudyCourseSession::groupBy('study_program_id')->pluck('study_program_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });
        $data['courseFilter']['data']        = StudyCourse::whereIn('id', StudyCourseSession::groupBy('study_course_id')->pluck('study_course_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });
        $data['generationFilter']['data']           = StudyGeneration::whereIn('id', StudyCourseSession::groupBy('study_generation_id')->pluck('study_generation_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });

        $data['academicFilter']['data']           = StudyAcademicYears::whereIn('id', StudyCourseSession::groupBy('study_academic_year_id')->pluck('study_academic_year_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });
        $data['semesterFilter']['data']           = StudySemesters::whereIn('id', StudyCourseSession::groupBy('study_semester_id')->pluck('study_semester_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });
        $data['sessionFilter']['data']           = StudySession::whereIn('id', StudyCourseSession::groupBy('study_session_id')->pluck('study_session_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });


        $table = StudyCourseSession::join((new StudyCourseSession)->getTable(), (new StudyCourseSession)->getTable() . '.id', (new StudyCourseSession)->getTable() . '.student_id');
        if (request('instituteId')) {
            $table->where((new StudyCourseSession)->getTable() . '.institute_id', request('instituteId'));
        }

        $response = $table->get([
            (new StudyCourseSession)->getTable() . '.*',
            (new StudyCourseSession)->getTable() . '.first_name_km',
            (new StudyCourseSession)->getTable() . '.last_name_km',
            (new StudyCourseSession)->getTable() . '.first_name_en',
            (new StudyCourseSession)->getTable() . '.last_name_en',
            (new StudyCourseSession)->getTable() . '.gender_id',
            (new StudyCourseSession)->getTable() . '.email',
            (new StudyCourseSession)->getTable() . '.phone',

        ])->map(function ($row) {
            //$row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
            $row['name'] = $row['first_name_' . app()->getLocale()] . ' ' . $row['last_name_' . app()->getLocale()];
            $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
            $row['photo'] = $row['photo'] ? ImageHelper::site(StudyCourseSession::path('image'), $row['photo']) : ImageHelper::site(StudyCourseSession::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));


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
            'genders' => StudyCourseSession::gender($table),
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

        config()->set('pages.title', __('List Study Course Session'));

        return view(StudyCourseSession::path('view') . '.includes.report.index', $data);
    }
}
