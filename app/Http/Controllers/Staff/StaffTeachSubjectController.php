<?php

namespace App\Http\Controllers\Staff;

use Carbon\Carbon;
use App\Models\App as AppModel;
use App\Models\Staff;
use App\Models\Users;
use App\Models\Years;
use App\Models\Gender;
use App\Models\Institute;
use App\Models\Languages;

use App\Helpers\DateHelper;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Helpers\ImageHelper;
use App\Models\SocailsMedia;
use App\Models\StudySubjects;
use App\Models\StaffInstitutes;
use App\Models\StaffTeachSubject;
use App\Models\StudySubjectLesson;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Http\Requests\FormStaffTeachSubject;

class StaffTeachSubjectController extends Controller
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


    public function index($param1 = 'list', $param2 = null, $param3 = null)
    {
        $breadcrumb  = [
            [
                'title' => __('Staff & Teacher'),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . Staff::path('url')),
            ],
            [
                'title' => __('List Staff teach subject'),
                'status' => false,
                'link'  => url(Users::role() . '/' . Staff::path('url') . '/' . StaffTeachSubject::path('url') . '/list'),
            ]
        ];

        $data['formData'] = array(
            ['year' => Years::now(),]
        );
        $data['formName'] = Staff::path('url') . '/' . StaffTeachSubject::path('url');
        $data['formAction'] = '/add';
        $data['listData']       = array();
        if ($param1 == 'list') {
            $breadcrumb[1]['status']  = 'active';
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                request()->merge([
                    'ref'   => StudySubjectLesson::path('url')
                ]);
                return StaffTeachSubject::getData();
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'grid') {
            $data = $this->grid($data);
        } elseif ($param1 == 'add') {
            $breadcrumb[]  = [
                'title' => __($param1),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . Staff::path('url') . '/' . StaffTeachSubject::path('url') . '/' . $param1),
            ];
            if (request()->method() === 'POST') {
                return StaffTeachSubject::addToTable();
            }
            $data = $this->show($data, null, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Staff teach subjects') . __($param1);
        } elseif ($param1 == 'edit') {
            $id = request('id', $param2);
            $breadcrumb[]  = [
                'title' => __('Edit Staff teach subject'),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . Staff::path('url') . '/' . StaffTeachSubject::path('url') . '/' . $param1 . '/' . $id),
            ];
            if (request()->method() === 'POST') {
                return StaffTeachSubject::updateToTable($id);
            }
            $data = $this->show($data, $id, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | '  . __('Staff teach subjects') . __($param1);
        } elseif ($param1 == 'view') {
            $id = request('id', $param2);
            $breadcrumb[]  = [
                'title' => __($param1),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . Staff::path('url') . '/' . StaffTeachSubject::path('url') . '/' . $param1 . '/' . $id),
            ];
            $data = $this->show($data, $id, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Staff teach subjects') . __($param1);
        } elseif ($param1 == 'delete') {
            $id = request('id', $param2);
            return StaffTeachSubject::deleteFromTable($id);
        } elseif ($param1 == 'report') {
            return $this->report();
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
            'parent'     => StaffTeachSubject::path('view'),
            'view'       => $data['view'],
        );
        $pages['form']['validate'] = StaffTeachSubject::validate();

        //Select Option
        $data['institute']['data']           = Institute::get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
            $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
            return $row;
        });
        $data['instituteFilter']['data']           = Institute::whereIn('id', StaffInstitutes::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
                return $row;
            });
        $data['subjectsFilter']['data']           = StudySubjects::whereIn('id', StaffTeachSubject::groupBy('study_subject_id')->pluck('study_subject_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = $row->image ?  ImageHelper::site(Institute::path('image'), $row->image) : ImageHelper::prefix();
                return $row;
            });
        $data['yearFilter']['data']           = StaffTeachSubject::groupBy('year')->pluck('year');

        $data['staff']['data'] = Staff::all()->map(function ($row) {
            return [
                'id'    => $row['id'],
                'name'   => $row['first_name_km'] . ' ' . $row['last_name_km'] . ' - '
                    . $row['first_name_en'] . ' ' . $row['last_name_en'] . ' - '
                    . $row['phone'],
                'photo' => ImageHelper::site(Staff::path('image'), $row['photo'])

            ];
        })->toArray();

        $data['study_subject']['data'] = StudySubjects::all()->map(function ($row) {
            return [
                'id'    => $row['id'],
                'name'   => $row[app()->getLocale()],
                'image' => $row['image'] ? ImageHelper::site(StudySubjects::path('image'), $row['image']) : ImageHelper::prefix()
            ];
        })->toArray();

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view($pages['parent'] . '.index', $data);
    }

    public function list($data, $id = null)
    {
        $table = StaffTeachSubject::join((new Staff)->getTable(), (new Staff)->getTable() . '.id', (new StaffTeachSubject)->getTable() . '.staff_id')
            ->join((new StaffInstitutes)->getTable(), (new StaffInstitutes)->getTable() . '.staff_id', (new Staff)->getTable() . '.id')
            ->orderBy((new StaffTeachSubject)->getTable() . '.id', 'DESC');

        if (request('instituteId')) {
            $table->where('institute_id', request('instituteId'));
        }
        if (request('subjectsId')) {
            $table->where('study_subject_id', request('subjectsId'));
        }
        if (request('year')) {
            $table->where('year', request('year'));
        }

        $count = $table->count();

        if ($id) {
            $table->whereIn((new StaffTeachSubject)->getTable() . '.id', explode(',', $id));
        }

        $response = $table->get([
            (new StaffTeachSubject)->getTable() . '.id',
            (new Staff)->getTable() . '.first_name_' . app()->getLocale(),
            (new Staff)->getTable() . '.last_name_' . app()->getLocale(),
            (new Staff)->getTable() . '.gender_id',
            (new Staff)->getTable() . '.phone',
            (new Staff)->getTable() . '.email',
            (new Staff)->getTable() . '.photo',
            (new StaffTeachSubject)->getTable() . '.study_subject_id',
            (new StaffTeachSubject)->getTable() . '.year',
            (new StaffTeachSubject)->getTable() . '.created_at',
            (new StaffTeachSubject)->getTable() . '.updated_at',
        ])->map(function ($row, $nid) use ($count) {
            $row['nid'] = $count - $nid;

            $row['name'] = $row['first_name_' . app()->getLocale()] . ' ' . $row['last_name_' . app()->getLocale()];
            $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
            $row['subjects'] = StudySubjects::where('id', $row->study_subject_id)->pluck(app()->getLocale())->first();
            $row['action']  = [
                'edit'   => url(Users::role() . '/' . Staff::path('url') . '/' . StaffTeachSubject::path('url') . '/edit/' . $row['id']),
                'view'   => url(Users::role() . '/' . Staff::path('url') . '/' . StaffTeachSubject::path('url') . '/view/' . $row['id']),
                'delete' => url(Users::role() . '/' . Staff::path('url') . '/' . StaffTeachSubject::path('url') . '/delete/' . $row['id']),
            ];
            return $row;
        });
        if ($id) {
            return $response;
        }
        $data['response']['data'] = $response;
        $data['view']     = StaffTeachSubject::path('view') . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List Staff teach subject');
        return $data;
    }
    public function grid($data)
    {
        $data['response'] = StaffTeachSubject::getTeachSubjects(null, null, null, 10);

        $data['view']     = StaffTeachSubject::path('view') . '.includes.grid.index';
        $data['title']               = Users::role(app()->getLocale()) . ' | ' . __('Grid Staff teach subject');
        return $data;
    }

    public function show($data, $id, $type)
    {
        $data['view'] = StaffTeachSubject::path('view') . '.includes.form.index';
        if ($id) {

            $response = StaffTeachSubject::join((new Staff)->getTable(), (new Staff)->getTable() . '.id', (new StaffTeachSubject)->getTable() . '.staff_id')
                ->join((new StaffInstitutes)->getTable(), (new StaffInstitutes)->getTable() . '.staff_id', (new Staff)->getTable() . '.id')
                ->whereIn((new StaffTeachSubject)->getTable() . '.id', explode(',', $id))
                ->get([
                    (new StaffTeachSubject)->getTable() . '.id',
                    (new Staff)->getTable() . '.first_name_' . app()->getLocale(),
                    (new Staff)->getTable() . '.last_name_' . app()->getLocale(),
                    (new Staff)->getTable() . '.gender_id',
                    (new Staff)->getTable() . '.phone',
                    (new Staff)->getTable() . '.email',
                    (new Staff)->getTable() . '.photo',
                    (new StaffTeachSubject)->getTable() . '.study_subject_id',
                    (new StaffTeachSubject)->getTable() . '.year',
                    (new StaffTeachSubject)->getTable() . '.created_at',
                    (new StaffTeachSubject)->getTable() . '.updated_at',
                ])->map(function ($row) {
                    $row['name'] = $row['first_name_' . app()->getLocale()] . ' ' . $row['last_name_' . app()->getLocale()];
                    $row['photo'] = $row['photo'] ? ImageHelper::site(Staff::path('image'), $row['photo']) : ImageHelper::site(Staff::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
                    $row['subjects'] = StudySubjects::where('id', $row->study_subject_id)->pluck(app()->getLocale())->first();
                    $row['action']  = [
                        'edit'   => url(Users::role() . '/' . Staff::path('url') . '/' . StaffTeachSubject::path('url') . '/edit/' . $row['id']),
                        'view'   => url(Users::role() . '/' . Staff::path('url') . '/' . StaffTeachSubject::path('url') . '/view/' . $row['id']),
                        'delete' => url(Users::role() . '/' . Staff::path('url') . '/' . StaffTeachSubject::path('url') . '/delete/' . $row['id']),
                    ];
                    return $row;
                });
            $data['listData'] =  $response->map(function ($row) {
                return [
                    'id'  => $row->id,
                    'name'  => $row->name . ' - ' . $row->subjects . ' (' . $row->year . ')',
                    'image'  => $row->photo,
                    'action'  => [
                        'edit'   => url(Users::role() . '/' . Staff::path('url') . '/' . StaffTeachSubject::path('url') . '/edit/' . $row['id']),
                    ],
                ];
            });

            $data['response']['data']   = $response;
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

        config()->set('app.title', __('List Staff teach subject'));
        config()->set('pages.parent', StaffTeachSubject::path('view'));

        $data['instituteFilter']['data']           = Institute::whereIn('id', StaffInstitutes::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
                return $row;
            });
        $data['subjectsFilter']['data']           = StudySubjects::whereIn('id', StaffTeachSubject::groupBy('study_subject_id')->pluck('study_subject_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = $row->image ?  ImageHelper::site(Institute::path('image'), $row->image) : ImageHelper::prefix();
                return $row;
            });
        $data['yearFilter']['data']           = StaffTeachSubject::groupBy('year')->pluck('year');


        $table = StaffTeachSubject::join((new Staff)->getTable(), (new Staff)->getTable() . '.id', (new StaffTeachSubject)->getTable() . '.staff_id')
            ->join((new StaffInstitutes)->getTable(), (new StaffInstitutes)->getTable() . '.staff_id', (new Staff)->getTable() . '.id');

        if (request('instituteId')) {
            $table->where('institute_id', request('instituteId'));
        }
        if (request('subjectsId')) {
            $table->where('study_subject_id', request('subjectsId'));
        }
        if (request('year')) {
            $table->where('year', request('year'));
        }
        $response = $table->get()->map(function ($row) {
            // $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
            $row['name'] = $row['first_name_' . app()->getLocale()] . ' ' . $row['last_name_' . app()->getLocale()];
            $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
            $row['subjects'] = StudySubjects::where('id', $row->study_subject_id)->pluck(app()->getLocale())->first();
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
            'genders' => Staff::gender($table),
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

        $data['subjects']  = StudySubjects::where('id', request('subjectsId'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = $row->image ?  ImageHelper::site(Institute::path('image'), $row->image) : ImageHelper::prefix();
                return $row;
            })->first();

        $pTitle = __('List Staff teach subject');
        if ($data['subjects']) {
            $pTitle  .= ' "' . $data['subjects']['name'] . '"';
        }
        if (request('year')) {
            $pTitle  .=  ' ' . request('year');
        }
        config()->set('pages.title', $pTitle);

        return view(StaffTeachSubject::path('view') . '.includes.report.index', $data);
    }
}
