<?php

namespace App\Http\Controllers\Study;

use Carbon\Carbon;
use App\Models\Users;
use App\Models\Communes;
use App\Models\Villages;
use App\Models\Districts;
use App\Models\Institute;
use App\Models\Languages;
use App\Models\Provinces;
use App\Helpers\DateHelper;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Helpers\ImageHelper;
use App\Models\SocailsMedia;
use App\Models\StudySubjects;
use App\Models\App as AppModel;
use App\Models\StudyGeneration;
use Illuminate\Support\Collection;
use App\Models\StudyCourseSchedule;
use App\Http\Controllers\Controller;
use App\Models\StudySession;
use App\Models\StudyShortCourseSchedule;

class StudyShortCourseSchedulesController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
        AppModel::setConfig();
       Languages::setConfig(); AppModel::setConfig();  SocailsMedia::setConfig();
        view()->share('breadcrumb', []);
    }


    public function index($param1 = 'list', $param2 = null, $param3 = null)
    {
      
        $data['formData']       = array(
            ['image' => asset('/assets/img/icons/image.jpg'),]
        );
        $data['formName']       = 'study/' . StudyShortCourseSchedule::path('url');
        $data['formAction']     = '/add';
        $data['listData']       = array();
        if ($param1 == 'list') {

            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                
            } else {
                $data = $this->list($data);
            }
        } elseif (strtolower($param1) == 'list-datatable') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return StudyShortCourseSchedule::getDataTable();
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'add') {

            if (request()->ajax()) {
                if (request()->method() === 'POST') {
                    return StudyShortCourseSchedule::addToTable();
                }
            }

            $data = $this->show($data, null, $param1);
        } elseif ($param1 == 'edit') {
            $id = request('id', $param2);
            if (request()->ajax()) {
                if (request()->method() === 'POST') {
                    return StudyShortCourseSchedule::updateToTable($id);
                }
            }

            $data = $this->show($data, $id, $param1);
        } elseif ($param1 == 'view') {
            $id = request('id', $param2);
            $data = $this->show($data, $id, $param1);
        } elseif ($param1 == 'delete') {
            $id = request('id', $param2);
            return StudyShortCourseSchedule::deleteFromTable($id);
        } elseif ($param1 == 'report') {            
            return $this->report();
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
            'parent'     => StudyShortCourseSchedule::path('view'),
            'view'       => $data['view'],
        );

        $pages['form']['validate'] = StudyShortCourseSchedule::validate();

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
            $data['study_generation']['data']  = StudyGeneration::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = $row->image ? ImageHelper::site(Institute::path('image'), $row->image) : ImageHelper::prefix();
                return $row;
            });
            $data['study_subjects']['data']  = StudySubjects::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = $row->image ? ImageHelper::site(StudySubjects::path('image'), $row->image) : ImageHelper::prefix();
                return $row;
            });

            $data['study_session']['data']  = StudySession::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = $row->image ? ImageHelper::site(StudySession::path('image'), $row->image) : ImageHelper::prefix();
                return $row;
            });

            $data['provinces']['data']           = Provinces::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = $row->image ?  ImageHelper::site(Provinces::path('image'), $row->image) : ImageHelper::prefix();
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

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view($pages['parent'] . '.index', $data);
    }

    public function list($data, $id = null)
    {        
        $table = StudyShortCourseSchedule::orderBy('id', 'DESC');
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
            $row->nid = $count - $nid;
            $row->study_generation = $row->study_generation->{app()->getLocale()};
            $row->study_subjects = $row->study_subjects->{app()->getLocale()};            
            $row->action  = [
                'edit'   => url(Users::role() . '/' . 'study/' . StudyShortCourseSchedule::path('url') . '/edit/' . $row->id),
                'view'   => url(Users::role() . '/' . 'study/' . StudyShortCourseSchedule::path('url') . '/view/' . $row->id),
                'delete' => url(Users::role() . '/' . 'study/' . StudyShortCourseSchedule::path('url') . '/delete/' . $row->id),
            ];
            return $row;
        });
        if ($id) {
            return $response;
        }

        $data['response']['data'] = $response;
        $data['view']     = StudyShortCourseSchedule::path('view') . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List Study Short Course Schedule');
        return $data;
    }

    public function show($data, $id, $type)
    {
        $table = StudyShortCourseSchedule::orderBy('id', 'desc');        
        $table->whereHas('institute', function ($query) {
            if (request('instituteId')) {
                $query->where('id', request('instituteId'));
            }
        });
        if ($id) {
            $table->whereIn('id', explode(',', $id));
            $response =  $table->get()->map(function ($row) {
                $row->study_generation = $row->study_generation->{app()->getLocale()};
                $row->study_subjects = $row->study_subjects->{app()->getLocale()}; 
                $row->name = $row->study_generation . ' - ' . $row->study_subjects;
                $row['action']  = [
                    'edit'   => url(Users::role() . '/' . 'study/' . StudyShortCourseSchedule::path('url') . '/edit/' . $row['id']),
                    'view'   => url(Users::role() . '/' . 'study/' . StudyShortCourseSchedule::path('url') . '/view/' . $row['id']),
                    'delete' => url(Users::role() . '/' . 'study/' . StudyShortCourseSchedule::path('url') . '/delete/' . $row['id']),
                ];
                return $row;
            });
            $data['listData'] =  $response->map(function ($row) {
                return [
                    'id'  => $row->id,
                    'name'  => $row->study_generation . '-' . $row->study_subjects,
                    'image'  => null,
                    'action'  => [
                        'edit'   => url(Users::role() . '/' . 'study/' . StudyShortCourseSchedule::path('url') . '/edit/' . $row['id']),
                    ],
                ];
            });

            $data['response']['data']   = $response;
            $data['formData']   = $response;
            $data['formAction'] = '/' . $type . '/' . $id;
        }

        $data['view']  = StudyShortCourseSchedule::path('view') . '.includes.form.index';
        $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('Study Short Course Schedule') . ' | ' . __($type);
        return $data;
    }

    public function report()
    {
        request()->merge([
            'size'  => request('size', 'A4'),
            'layout'  => request('layout', 'portrait'),
        ]);

        config()->set('app.title', __('List Study Short Course Schedule'));
        config()->set('pages.parent', StudyShortCourseSchedule::path('view'));

        $data['instituteFilter']['data']           = Institute::whereIn('id', StudyShortCourseSchedule::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
                return $row;
            });


            $table = StudyShortCourseSchedule::orderBy('id', 'desc');        
            $table->whereHas('institute', function ($query) {
                if (request('instituteId')) {
                    $query->where('id', request('instituteId'));
                }
            });


        $response = $table->get()->map(function ($row) {
            return [
                'id'    => $row->id,
                'study_generation'    => $row->study_generation->{app()->getLocale()},
                'study_subjects'    => $row->study_subjects->{app()->getLocale()},
            ];
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
        config()->set('pages.title', __('List Study Short Course Schedule'));
        return view(StudyShortCourseSchedule::path('view') . '.includes.report.index', $data);
    }
}
