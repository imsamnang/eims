<?php

namespace App\Http\Controllers\General;

use Carbon\Carbon;
use App\Models\App;
use App\Models\Users;
use App\Models\Institute;
use App\Models\Languages;
use App\Helpers\DateHelper;

use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Helpers\ImageHelper;
use App\Models\SocailsMedia;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Http\Requests\FormBloodGroup;
use App\Models\BloodGroup;


class BloodGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        App::setConfig();
        Languages::setConfig();
        App::setConfig();
        SocailsMedia::setConfig();
        view()->share('breadcrumb', []);
    }


    public function index($param1 = 'list', $param2 = null, $param3 = null)
    {
        $data['formData'] = array(
            ['image' => asset('/assets/img/icons/image.jpg'),]
        );
        $data['formName'] = 'general/' . BloodGroup::path('url');
        $data['formAction'] = '/add';
        $data['listData']       = array();
        $id = request('id', $param2);
        if ($param1 == 'list') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return BloodGroup::getData(null, null, 10);
            } else {
                $data = $this->list($data);
            }
        } elseif (strtolower($param1) == 'list-datatable') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return  BloodGroup::getDataTable();
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'add') {
            if (request()->ajax()) {
                if (request()->method() === 'POST') {
                    return BloodGroup::addToTable();
                }
            }
            $data = $this->show($data, null, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Add Blood group');
        } elseif ($param1 == 'edit') {
            if (request()->method() === 'POST') {
                return BloodGroup::updateToTable($id);
            }
            $data = $this->show($data, $id, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Edit Blood group');
        } elseif ($param1 == 'view') {
            $data = $this->show($data, $id, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('View Blood group');
        } elseif ($param1 == 'delete') {
            return BloodGroup::deleteFromTable($id);
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
            'parent'     => BloodGroup::path('view'),
            'view'       => $data['view'],
        );
        $pages['form']['validate'] = [
            'rules'       =>  (new FormBloodGroup)->rules(),
            'attributes'  =>  (new FormBloodGroup)->attributes(),
            'messages'    =>  (new FormBloodGroup)->messages(),
            'questions'   =>  (new FormBloodGroup)->questions(),
        ];

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view($pages['parent'] . '.index', $data);
    }

    public function list($data, $id = null)
    {
        $table = BloodGroup::orderBy('id', 'DESC');

        $response = $table->get()->map(function ($row) {
            $row['name']  = $row->{app()->getLocale()};
            $row['image'] = ImageHelper::site(BloodGroup::path('image'), $row['image']);
            $row['action']  = [
                'edit'   => url(Users::role() . '/' . 'general/' . BloodGroup::path('url') . '/edit/' . $row['id']),
                'view'   => url(Users::role() . '/' . 'general/' . BloodGroup::path('url') . '/view/' . $row['id']),
                'delete' => url(Users::role() . '/' . 'general/' . BloodGroup::path('url') . '/delete/' . $row['id']),
            ];

            return $row;
        });
        $data['response']['data'] = $response;
        $data['view']     = BloodGroup::path('view') . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List Blood group');
        return $data;
    }

    public function show($data, $id, $type)
    {
        $data['view']       = BloodGroup::path('view') . '.includes.form.index';
        if ($id) {
            $response           = BloodGroup::whereIn('id', explode(',', $id))->get()->map(function ($row) {
                $row['image'] = $row['image'] ? ImageHelper::site(BloodGroup::path('image'), $row['image']) : ImageHelper::prefix();
                $row['action']  = [
                    'edit'   => url(Users::role() . '/' . 'general/' . BloodGroup::path('url') . '/edit/' . $row['id']),
                    'view'   => url(Users::role() . '/' . 'general/' . BloodGroup::path('url') . '/view/' . $row['id']),
                    'delete' => url(Users::role() . '/' . 'general/' . BloodGroup::path('url') . '/delete/' . $row['id']),
                ];
                return $row;
            });
            $data['listData'] =  $response->map(function ($row) {
                return [
                    'id'  => $row->id,
                    'name'  => $row->name,
                    'image'  => $row->image,
                    'action'  => [
                        'edit'   => url(Users::role() . '/' . 'general/' . BloodGroup::path('url') . '/edit/' . $row['id']),
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

        config()->set('app.title', __('List Blood group'));
        config()->set('pages.parent', BloodGroup::path('view'));


        $table = new BloodGroup;


        $response = $table->get()->map(function ($row) {
            $row['name']  = $row->{app()->getLocale()};
            $row['image'] = $row['image'] ? ImageHelper::site(BloodGroup::path('image'), $row['image']) : ImageHelper::prefix();
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
        config()->set('pages.title', __('List Blood group'));
        return view(BloodGroup::path('view') . '.includes.report.index', $data);
    }
}
