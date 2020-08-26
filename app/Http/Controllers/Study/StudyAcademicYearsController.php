<?php

namespace App\Http\Controllers\Study;

use Carbon\Carbon;
use App\Models\App as AppModel;
use App\Models\Users;
use App\Models\Institute;
use App\Models\Languages;
use App\Helpers\DateHelper;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Helpers\ImageHelper;
use App\Models\SocailsMedia;
use App\Models\StudyAcademicYears;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;

class StudyAcademicYearsController extends Controller
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
                'title' => __('Study'),
                'status' => 'active',
                'link'  => url(Users::role() . '/study/' . StudyAcademicYears::path('url')),
            ],
            [
                'title' => __('List Study Academic Years'),
                'status' => false,
                'link'  => url(Users::role() . '/study/' . StudyAcademicYears::path('url') . '/list'),
            ]
        ];

        $data['formData'] = array(
            ['image' => asset('/assets/img/icons/image.jpg'),]
        );
        $data['formName'] = 'study/' . StudyAcademicYears::path('url');
        $data['formAction'] = '/add';
        $data['listData']       = array();
        $id = request('id', $param2);
        if ($param1 == null || $param1 == 'list') {
            $breadcrumb[1]['status']  = 'active';
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'add') {
            $breadcrumb[]  = [
                'title' => __($param1),
                'status' => 'active',
                'link'  => url(Users::role() . '/study/' . StudyAcademicYears::path('url') . '/' . $param1),
            ];
            if (request()->method() === 'POST') {
                return StudyAcademicYears::addToTable();
            }
            $data = $this->show($data, null, $param1);
        } elseif ($param1 == 'edit') {
            $breadcrumb[]  = [
                'title' => __($param1),
                'status' => 'active',
                'link'  => url(Users::role() . '/study/' . StudyAcademicYears::path('url') . '/' . $param1 . '/' . $id),
            ];
            if (request()->method() === 'POST') {
                return StudyAcademicYears::updateToTable($id);
            }

            $data = $this->show($data, $id, $param1);
        } elseif ($param1 == 'view') {

            $breadcrumb[]  = [
                'title' => __($param1),
                'status' => 'active',
                'link'  => url(Users::role() . '/study/' . StudyAcademicYears::path('url') . '/' . $param1 . '/' . $id),
            ];

            $data = $this->show($data, $id, $param1);
            $data['view']  = StudyAcademicYears::path('view') . '.includes.view.index';
        } elseif ($param1 == 'delete') {
            if (request()->method() === 'POST') {
                return StudyAcademicYears::deleteFromTable($id);
            }
            abort(404);
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
            'parent'     => StudyAcademicYears::path('view'),
            'view'       => $data['view'],
        );
        $pages['form']['validate'] = StudyAcademicYears::validate();

        //Select Option
        $data['institute']['data']           = Institute::get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
            $row->image   = ImageHelper::site(Institute::path('image'), $row->logo);
            return $row;
        });

        $data['instituteFilter']['data']           = Institute::whereIn('id', StudyAcademicYears::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row->image   = ImageHelper::site(Institute::path('image'), $row->logo);
                return $row;
            });

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view($pages['parent'] . '.index', $data);
    }

    public function list($data, $id = null)
    {
        $table = StudyAcademicYears::orderBy('id', 'DESC');
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
            $row->name  = $row->km . ' - ' . $row->en;
            $row->image = ImageHelper::site(StudyAcademicYears::path('image'), $row->image);
            $row->action  = [
                'edit'   => url(Users::role() . '/' . 'study/' . StudyAcademicYears::path('url') . '/edit/' . $row->id),
                'view'   => url(Users::role() . '/' . 'study/' . StudyAcademicYears::path('url') . '/view/' . $row->id),
                'delete' => url(Users::role() . '/' . 'study/' . StudyAcademicYears::path('url') . '/delete/' . $row->id),
            ];
            return $row;
        });
        if ($id) {
            return $response;
        }

        $data['response']['data'] = $response;
        $data['view']     = StudyAcademicYears::path('view') . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List Study Academic Years');
        return $data;
    }

    public function show($data, $id, $type)
    {
        $table = StudyAcademicYears::orderBy('id', 'desc');
        if ($id) {
            $table->whereIn('id', explode(',', $id));
            $response =  $table->get()->map(function ($row) {
                $row['image'] = $row['image'] ? ImageHelper::site(StudyAcademicYears::path('image'), $row['image']) : ImageHelper::prefix();
                $row['action']  = [
                    'edit'   => url(Users::role() . '/' . 'study/' . StudyAcademicYears::path('url') . '/edit/' . $row['id']),
                    'view'   => url(Users::role() . '/' . 'study/' . StudyAcademicYears::path('url') . '/view/' . $row['id']),
                    'delete' => url(Users::role() . '/' . 'study/' . StudyAcademicYears::path('url') . '/delete/' . $row['id']),
                ];
                return $row;
            });
            $data['listData'] =  $response->map(function ($row) {
                return [
                    'id'  => $row->id,
                    'name'  => $row->km . '-' . $row->en,
                    'image'  => $row->image,
                    'action'  => [
                        'edit'   => url(Users::role() . '/' . 'study/' . StudyAcademicYears::path('url') . '/edit/' . $row['id']),
                    ],
                ];
            });

            $data['response']['data']   = $response;
            $data['formData']   = $response;
            $data['formAction'] = '/' . $type . '/' . $id;
        }

        $data['view']  = StudyAcademicYears::path('view') . '.includes.form.index';
        $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('Study Academic Years') . ' | ' . __($type);
        return $data;
    }

    public function report()
    {
        request()->merge([
            'size'  => request('size', 'A4'),
            'layout'  => request('layout', 'portrait'),
        ]);

        config()->set('app.title', __('List Study Academic Years'));
        config()->set('pages.parent', StudyAcademicYears::path('view'));

        $data['instituteFilter']['data']           = Institute::whereIn('id', StudyAcademicYears::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
                return $row;
            });


        $table = new StudyAcademicYears;
        if (request('instituteId')) {
            $table->where('institute_id', request('instituteId'));
        }

        $response = $table->get()->map(function ($row) {
            $row['name']  = $row->km . ' - ' . $row->en;
            $row['image'] = $row['image'] ? ImageHelper::site(StudyAcademicYears::path('image'), $row['image']) : ImageHelper::prefix();
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
        config()->set('pages.title', __('List Study Academic Years'));
        return view(StudyAcademicYears::path('view') . '.includes.report.index', $data);
    }
}
