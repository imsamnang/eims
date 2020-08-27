<?php

namespace App\Http\Controllers\Study;


use Carbon\Carbon;
use App\Models\Days;
use App\Models\Staff;
use App\Models\Users;
use App\Models\Gender;
use App\Models\Institute;
use App\Models\Languages;
use App\Models\StudyClass;
use App\Helpers\DateHelper;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Models\StudyCourse;
use App\Helpers\ImageHelper;
use App\Models\SocailsMedia;
use App\Models\StudySession;
use App\Models\StudyPrograms;
use App\Models\StudySubjects;
use App\Models\StudySemesters;
use App\Models\App as AppModel;
use App\Models\StudyGeneration;
use App\Models\StudyAcademicYears;
use App\Models\StudyCourseRoutine;
use App\Models\StudyCourseSession;
use Illuminate\Support\Collection;
use App\Models\StudentsStudyCourse;
use App\Models\StudyCourseSchedule;
use App\Http\Controllers\Controller;

class StudyCourseRoutinesController extends Controller
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
                'link'  => url(Users::role() . '/' . StudyCourseRoutine::path('url')),
            ],
            [
                'title' => __('List Study Course Routine'),
                'status' => false,
                'link'  => url(Users::role() . '/study/' . StudyCourseRoutine::path('url') . '/list'),
            ]
        ];

        $data['formData']             = [[]];
        $data['formAction']      = '/add';
        $data['formName']        = 'study/' . StudyCourseRoutine::path('url');
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
                'link'  => url(Users::role() . '/study/' . StudyCourseRoutine::path('url') . '/' . $param1),
            ];
            if (request()->method() == 'POST') {
                return StudyCourseRoutine::addToTable();
            } else {
                $data = $this->show($data, null, $param1);
            }
        } elseif ($param1 == 'view') {
            $id = request('id', $param2);
            $breadcrumb[] = [
                'title' => __($param1),
                'status' => 'active',
                'link'  => url(Users::role() . '/study/' . StudyCourseRoutine::path('url') . '/' . $param1 . '/' . $id),
            ];
            $data = $this->show($data, $id, $param1);
            $data['view']  = StudyCourseRoutine::path('view') . '.includes.view.index';
        } elseif ($param1 == 'edit') {
            $id = request('id', $param2);
            $breadcrumb[] = [
                'title' => __($param1),
                'status' => 'active',
                'link'  => url(Users::role() . '/study/' . StudyCourseRoutine::path('url') . '/' . $param1 . '/' . $id),
            ];
            if (request()->method() == 'POST') {
                return StudyCourseRoutine::updateToTable($id);
            }
            $data = $this->show($data, $id, $param1);
        } elseif ($param1 == 'report') {
            return $this->report();
        } elseif ($param1 == 'delete') {
            return StudyCourseRoutine::deleteFromTable(request('id', $param2));
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
            'parent'     => StudyCourseRoutine::path('view'),
            'view'       => $data['view'],
        );

        $pages['form']['validate'] = StudyCourseRoutine::validate();

        //Select Option

        $data['instituteFilter']['data'] = Institute::whereIn('id', StudyCourseSchedule::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
                return $row;
            });
        $data['programFilter']['data']            = StudyPrograms::whereIn('id', StudentsStudyCourse::join((new StudyCourseSession)->getTable(), (new StudyCourseSession)->getTable() . '.id', (new StudentsStudyCourse)->getTable() . '.study_course_session_id')
            ->join((new StudyCourseSchedule)->getTable(), (new StudyCourseSchedule)->getTable() . '.id', (new StudyCourseSession)->getTable() . '.study_course_schedule_id')
            ->groupBy('study_program_id')->pluck('study_program_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });
        $data['courseFilter']['data']            = StudyCourse::whereIn('id', StudentsStudyCourse::join((new StudyCourseSession)->getTable(), (new StudyCourseSession)->getTable() . '.id', (new StudentsStudyCourse)->getTable() . '.study_course_session_id')
            ->join((new StudyCourseSchedule)->getTable(), (new StudyCourseSchedule)->getTable() . '.id', (new StudyCourseSession)->getTable() . '.study_course_schedule_id')
            ->groupBy('study_course_id')->pluck('study_course_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });
        $data['generationFilter']['data']            = StudyGeneration::whereIn('id', StudentsStudyCourse::join((new StudyCourseSession)->getTable(), (new StudyCourseSession)->getTable() . '.id', (new StudentsStudyCourse)->getTable() . '.study_course_session_id')
            ->join((new StudyCourseSchedule)->getTable(), (new StudyCourseSchedule)->getTable() . '.id', (new StudyCourseSession)->getTable() . '.study_course_schedule_id')
            ->groupBy('study_generation_id')->pluck('study_generation_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });

        $data['academicFilter']['data']          = StudyAcademicYears::whereIn('id', StudentsStudyCourse::join((new StudyCourseSession)->getTable(), (new StudyCourseSession)->getTable() . '.id', (new StudentsStudyCourse)->getTable() . '.study_course_session_id')
            ->join((new StudyCourseSchedule)->getTable(), (new StudyCourseSchedule)->getTable() . '.id', (new StudyCourseSession)->getTable() . '.study_course_schedule_id')
            ->groupBy('study_academic_year_id')->pluck('study_academic_year_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });
        $data['semesterFilter']['data']          = StudySemesters::whereIn('id', StudentsStudyCourse::join((new StudyCourseSession)->getTable(), (new StudyCourseSession)->getTable() . '.id', (new StudentsStudyCourse)->getTable() . '.study_course_session_id')
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

        $data['study_course_session']['data'] = StudyCourseSession::whereHas('study_course_schedule', function ($query) {
            if (request('instituteId')) {
                $query->where('institute_id', request('instituteId'));
            }
        })->get()->map(function ($row) {
            $row->study_program = $row->study_course_schedule->study_program->{app()->getLocale()};
            $row->study_course = $row->study_course_schedule->study_course->{app()->getLocale()};
            $row->study_generation = $row->study_course_schedule->study_generation->{app()->getLocale()};
            $row->study_academic_year = $row->study_course_schedule->study_academic_year->{app()->getLocale()};
            $row->study_semester = $row->study_course_schedule->study_semester->{app()->getLocale()};

            $row->study_session = $row->study_session->{app()->getLocale()};
            $row->study_start = DateHelper::convert($row->study_start, 'd-F-Y');
            $row->study_end = DateHelper::convert($row->study_end, 'd-F-Y');
            $row->name = $row->study_program . ' (' . $row->study_course . ')';
            $row->name .= ' - ' . $row->study_generation . ',' . $row->study_academic_year . ',' . $row->study_semester;
            $row->name .= ' - [' . $row->study_session . '] [' . $row->study_start . ' - ' . $row->study_end . ']';
            return $row;
        });

        $data['days']['data']       = Days::get(['id', app()->getLocale() . ' as name']);
        $data['study_class']['data'] = StudyClass::get(['id', app()->getLocale() . ' as name']);
        $data['study_subjects']['data'] = StudySubjects::get(['id', app()->getLocale() . ' as name']);
        $data['teachers']['data'] = Staff::whereHas('staff_institute', function ($query) {
            $query->where('institute_id', request('instituteId'));
        })->get(['id', 'first_name_km', 'last_name_km', 'first_name_en', 'last_name_en', 'photo'])->map(function ($row) {
            $row->first_name = $row->{'first_name_' . app()->getLocale()};
            $row->last_name = $row->{'last_name_' . app()->getLocale()};
            $row->photo   = ImageHelper::site(Staff::path('image'), $row->photo);
            return $row;
        });

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view(StudyCourseRoutine::path('view') . '.index', $data);
    }

    public function list($data, $study_course_session_id = null)
    {
        $table = StudyCourseRoutine::orderBy('study_course_session_id', 'DESC')->groupBy('study_course_session_id');
        $table->whereHas('study_course_schedule', function ($query) {
            if (request('instituteId')) {
                $query->where('institute_id', request('instituteId'));
            }
            if (request('scheduleId')) {
                $query->where('study_course_schedule_id', request('scheduleId'));
            }
            if (request('sessionId')) {
                $query->where('study_course_session_id', request('sessionId'));
            }
        });

        $count = count($table->get());

        if ($study_course_session_id) {
            $table->whereIn('study_course_session_id', explode(',', $study_course_session_id));
        }
        $response = $table->get()->map(function ($row, $nid) use ($count) {
            $row->nid  = $count - $nid;
            $row->id  = $row->study_course_session_id;

            $row->study_program = $row->study_course_schedule->first()->study_program->{app()->getLocale()};
            $row->study_course = $row->study_course_schedule->first()->study_course->{app()->getLocale()};
            $row->study_generation = $row->study_course_schedule->first()->study_generation->{app()->getLocale()};
            $row->study_academic_year = $row->study_course_schedule->first()->study_academic_year->{app()->getLocale()};
            $row->study_semester = $row->study_course_schedule->first()->study_semester->{app()->getLocale()};
            $row->study_session = $row->study_course_session->first()->study_session->{app()->getLocale()};
            $row->study_start = DateHelper::convert($row->study_course_session->study_start, 'd-F-Y');
            $row->study_end = DateHelper::convert($row->study_course_session->study_end, 'd-F-Y');

            $row->name = $row->study_program . ' (' . $row->study_course . ')';
            $row->name .= ' - ' . $row->study_generation . ',' . $row->study_academic_year . ',' . $row->study_semester;
            $row->name .= ' - [' . $row->study_session . '] [' . $row->study_start . ' - ' . $row->study_end . ']';

            $row->action = [
                'edit'   => url(Users::role() . '/study/' . StudyCourseRoutine::path('url') . '/edit/' . $row->id),
                'view'   => url(Users::role() . '/study/' . StudyCourseRoutine::path('url') . '/view/' . $row->id),
                'delete' => url(Users::role() . '/study/' . StudyCourseRoutine::path('url') . '/delete/' . $row->id),
            ];
            return $row;
        });

        if ($study_course_session_id) {
            return $response;
        }
        $data['response']['data'] = $response;

        $data['view']  = StudyCourseRoutine::path('view') . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List Study Course Routine');
        return $data;
    }

    public function show($data, $study_course_session_id, $type)
    {
        $data['title']   = Users::role(app()->getLocale()) . ' | ' . __('Study Course Routine') . ' | ' . __($type);
        $data['view']    = StudyCourseRoutine::path('view') . '.includes.form.index';
        if ($study_course_session_id) {

            $table = StudyCourseSchedule::orderBy('id', 'DESC');
            $table->whereHas('study_course_routine', function ($query) use ($study_course_session_id) {
                $query->whereHas('study_course_session',function($q) use ($study_course_session_id){
                    $q->whereIn('id',explode(',',$study_course_session_id));
                });
            })->with('study_course_routine','study_course_session');


            $response = $table->get()->map(function ($row) use ($type) {
                $row->study_course_session_id = $row->study_course_session->first()->id;

                $row->id  = $row->study_course_session_id;

                $row->study_program = $row->study_program->{app()->getLocale()};
                $row->study_course = $row->study_course->{app()->getLocale()};
                $row->study_generation = $row->study_generation->{app()->getLocale()};

                $row->study_academic_year = $row->study_academic_year->{app()->getLocale()};
                $row->study_semester = $row->study_semester->{app()->getLocale()};
                $row->study_session = $row->study_course_session->first()->study_session->{app()->getLocale()};
                $row->study_start = DateHelper::convert($row->study_course_session->first()->study_start, 'd-F-Y');
                $row->study_end = DateHelper::convert($row->study_course_session->first()->study_end, 'd-F-Y');

                $row->name = $row->study_program . ' (' . $row->study_course . ')';
                $row->name .= ' - ' . $row->study_generation . ',' . $row->study_academic_year . ',' . $row->study_semester;
                $row->name .= ' - [' . $row->study_session . '] [' . $row->study_start . ' ⚊ ' . $row->study_end . ']';

                if ($type == 'edit') {
                    $row->routines = StudyCourseRoutine::getGroupTimesEdit($row->study_course_routine);

                } else {
                    $row->routines = StudyCourseRoutine::getGroupTimes($row->study_course_routine);
                }

                $row->action = [
                    'edit'   => url(Users::role() . '/study/' . StudyCourseRoutine::path('url') . '/edit/' . $row->id),
                    'view'   => url(Users::role() . '/study/' . StudyCourseRoutine::path('url') . '/view/' . $row->id),
                    'delete' => url(Users::role() . '/study/' . StudyCourseRoutine::path('url') . '/delete/' . $row->id),
                ];
                return $row;
            });

            $data['response']['data'] = $response;
            $data['listData'] =  $response->map(function ($row) {
                return [
                    'id'  => $row->id,
                    'name'  => $row->name,
                    'action'  => [
                        'edit'    => url(Users::role() . '/' . StudyCourseRoutine::path('url') . '/edit/' . $row->id),
                    ],
                ];
            });

            $data['formData']   = $response;
            $data['formAction'] = '/' . $type . '/' . $study_course_session_id;
        }

        return $data;
    }
    public function report()
    {
        request()->merge([
            'size'  => request('size', 'A4'),
            'layout'  => request('layout', 'landscape'),
        ]);

        config()->set('app.title', __('List Study Course Routine'));
        config()->set('pages.parent', StudyCourseRoutine::path('view'));



        $data['instituteFilter']['data']             = Institute::whereIn('id', StudyCourseRoutine::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
                return $row;
            });
        $data['programFilter']['data']           = StudyPrograms::whereIn('id', StudyCourseRoutine::groupBy('study_program_id')->pluck('study_program_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });
        $data['courseFilter']['data']        = StudyCourse::whereIn('id', StudyCourseRoutine::groupBy('study_course_id')->pluck('study_course_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });
        $data['generationFilter']['data']            = StudyGeneration::whereIn('id', StudyCourseRoutine::groupBy('study_generation_id')->pluck('study_generation_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });

        $data['academicFilter']['data']          = StudyAcademicYears::whereIn('id', StudyCourseRoutine::groupBy('study_academic_year_id')->pluck('study_academic_year_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });
        $data['semesterFilter']['data']          = StudySemesters::whereIn('id', StudyCourseRoutine::groupBy('study_semester_id')->pluck('study_semester_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });
        $data['sessionFilter']['data']           = StudySession::whereIn('id', StudyCourseRoutine::groupBy('study_session_id')->pluck('study_session_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->image);
                return $row;
            });


        $table = StudyCourseRoutine::join((new StudyCourseRoutine)->getTable(), (new StudyCourseRoutine)->getTable() . '.id', (new StudyCourseRoutine)->getTable() . '.student_id');
        if (request('instituteId')) {
            $table->where((new StudyCourseRoutine)->getTable() . '.institute_id', request('instituteId'));
        }

        $response = $table->get([
            (new StudyCourseRoutine)->getTable() . '.*',
            (new StudyCourseRoutine)->getTable() . '.first_name_km',
            (new StudyCourseRoutine)->getTable() . '.last_name_km',
            (new StudyCourseRoutine)->getTable() . '.first_name_en',
            (new StudyCourseRoutine)->getTable() . '.last_name_en',
            (new StudyCourseRoutine)->getTable() . '.gender_id',
            (new StudyCourseRoutine)->getTable() . '.email',
            (new StudyCourseRoutine)->getTable() . '.phone',

        ])->map(function ($row) {
            //$row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
            $row['name'] = $row['first_name_' . app()->getLocale()] . ' ' . $row['last_name_' . app()->getLocale()];
            $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
            $row['photo'] = $row['photo'] ? ImageHelper::site(StudyCourseRoutine::path('image'), $row['photo']) : ImageHelper::site(StudyCourseRoutine::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));


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
            'genders' => StudyCourseRoutine::gender($table),
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

        config()->set('pages.title', __('List Study Course Routine'));

        return view(StudyCourseRoutine::path('view') . '.includes.report.index', $data);
    }
}
