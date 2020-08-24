<?php

namespace App\Http\Controllers\General;

use Carbon\Carbon;
use App\Models\App as AppModel;
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
use Illuminate\Support\Collection;
use App\Http\Requests\FormDistrict;
use App\Http\Controllers\Controller;

class VillageController extends Controller
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
        $data['formData'] = array(
            ['image' => asset('/assets/img/icons/image.jpg'),]
        );
        $data['formName'] = 'general/' . Villages::path('url');
        $data['formAction'] = '/add';
        $data['listData']       = array();
        $id = request('id', $param2);
        if ($param1 == 'list') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return [
                    'success' => true,
                    'data' => Villages::whereHas('commune', function ($query) {
                        $query->where('id', request('communeId'));
                    })->orderBy('id', 'DESC')->get(['id', app()->getLocale() . ' as name', 'image'])
                ];
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'add') {

            if (request()->method() === 'POST') {
                return Villages::addToTable();
            }
            $data = $this->show($data, null, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Add District');
        } elseif ($param1 == 'edit') {
            if (request()->method() === 'POST') {
                return Villages::updateToTable($id);
            }
            $data = $this->show($data, $id, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Edit District');
        } elseif ($param1 == 'view') {
            $data = $this->show($data, $id, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('View District');
        } elseif ($param1 == 'delete') {
            return Villages::deleteFromTable($id);
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
            'parent'     => Villages::path('view'),
            'view'       => $data['view'],
        );
        $pages['form']['validate'] = [
            'rules'       =>  FormDistrict::rules(),
            'attributes'  =>  FormDistrict::attributes(),
            'messages'    =>  FormDistrict::messages(),
            'questions'   =>  FormDistrict::questions(),
        ];
        //Select Options
        $data['provinces'] = [
            'data'  => Provinces::get(['id', app()->getLocale() . ' as name']),
            'action' => [
                'list'  => url(Users::role() . '/general/' . Provinces::path('url') . '/list/'),
            ]
        ];

        $data['districts'] = [
            'data'  => [],
            'action' => [
                'list'  => url(Users::role() . '/general/' . Districts::path('url') . '/list/'),
            ]
        ];

        $data['communes'] = [
            'data'  => [],
            'action' => [
                'list'  => url(Users::role() . '/general/' . Communes::path('url') . '/list/'),
            ]
        ];


        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view($pages['parent'] . '.index', $data);
    }

    public function list($data, $id = null)
    {
        $table = Villages::whereHas('commune', function ($query) {
            $query->where('id', request('communeId'));
        })->orderBy('id', 'DESC');

        $response = $table->get()->map(function ($row) {

            $row['name']  = $row->km . ' - ' . $row->name;
            $row['image'] = ImageHelper::site(Villages::path('image'), $row['image']);
            $commune = Communes::where('id', $row->commune_id)->get()->first();
            $row['commune'] = $commune->{app()->getLocale()};

            $district = Districts::where('id', $commune->district_id)->get()->first();

            $row['district'] = $district->{app()->getLocale()};

            $row['province'] = Provinces::where('id', $district->province_id)->pluck(app()->getLocale())->first();
            $row['action']  = [
                'edit'   => url(Users::role() . '/' . 'general/' . Villages::path('url') . '/edit/' . $row['id']),
                'view'   => url(Users::role() . '/' . 'general/' . Villages::path('url') . '/view/' . $row['id']),
                'delete' => url(Users::role() . '/' . 'general/' . Villages::path('url') . '/delete/' . $row['id']),
            ];

            return $row;
        });
        $data['response']['data'] = $response;
        $data['view']     = Villages::path('view') . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List District');
        return $data;
    }

    public function show($data, $id, $type)
    {
        $data['view']       = Villages::path('view') . '.includes.form.village.index';
        if ($id) {

            $response           = Villages::whereIn('id', explode(',', $id))->get()->map(function ($row) {
                $row['image'] = $row['image'] ? ImageHelper::site(Villages::path('image'), $row['image']) : ImageHelper::prefix();
                $row['action']  = [
                    'edit'   => url(Users::role() . '/' . 'general/' . Villages::path('url') . '/edit/' . $row['id']),
                    'view'   => url(Users::role() . '/' . 'general/' . Villages::path('url') . '/view/' . $row['id']),
                    'delete' => url(Users::role() . '/' . 'general/' . Villages::path('url') . '/delete/' . $row['id']),
                ];
                return $row;
            });
            $data['listData'] =  $response->map(function ($row) {
                return [
                    'id'  => $row->id,
                    'name'  => $row->km . '-' . $row->en,
                    'image'  => $row->image,
                    'action'  => [
                        'edit'   => url(Users::role() . '/' . 'general/' . Villages::path('url') . '/edit/' . $row['id']),
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

        config()->set('app.title', __('List District'));
        config()->set('pages.parent', Villages::path('view'));

        $table = new Villages;

        $response = $table->get()->map(function ($row) {
            $row['name']  = $row->km . ' - ' . $row->en;
            $row['image'] = $row['image'] ? ImageHelper::site(Villages::path('image'), $row['image']) : ImageHelper::prefix();
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
        config()->set('pages.title', __('List District'));
        return view(Villages::path('view') . '.includes.report.index', $data);
    }
}
