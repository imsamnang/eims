<?php

namespace App\Http\Controllers\Study;

use Carbon\Carbon;
use App\Models\Users;
use App\Models\Institute;
use App\Models\Languages;
use App\Helpers\DateHelper;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Helpers\ImageHelper;
use App\Models\SocailsMedia;
use App\Models\App as AppModel;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;


class InstitutesController extends Controller
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
                'link'  => url(Users::role() . '/study/' . Institute::path('url')),
            ],
            [
                'title' => __('List Institute'),
                'status' => false,
                'link'  => url(Users::role() . '/study/' . Institute::path('url') . '/list'),
            ]
        ];

        $data['formData'] = array(
            ['logo' => asset('/assets/img/icons/image.jpg'),]
        );
        $data['formName'] = 'study/' . Institute::path('url');
        $data['formAction'] = '/add';
        $data['listData']       = array();
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
                'link'  => url(Users::role() . '/study/' . Institute::path('url') . '/' . $param1),
            ];
            if (request()->method() === 'POST') {
                return Institute::addToTable();
            }

            $data = $this->show($data, null, $param1);
        } elseif ($param1 == 'edit') {
            $id = request('id', $param2);

            $breadcrumb[]  = [
                'title' => __($param1),
                'status' => 'active',
                'link'  => url(Users::role() . '/study/' . Institute::path('url') . '/' . $param1.'/'.$id),
            ];

            if (request()->method() === 'POST') {
                return Institute::updateToTable($id);
            }
            $data = $this->show($data, $id, $param1);

        } elseif ($param1 == 'view') {
            $id = request('id', $param2);
            $breadcrumb[]  = [
                'title' => __($param1),
                'status' => 'active',
                'link'  => url(Users::role() . '/study/' . Institute::path('url') . '/' . $param1.'/'.$id),
            ];
            $data = $this->show($data, $id, $param1);
            $data['view']       = Institute::path('view') . '.includes.view.index';
        } elseif ($param1 == 'delete') {
            if (request()->method() === 'POST') {
                $id = request('id', $param2);
                return Institute::deleteFromTable($id);
            }
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
            'parent'     => Institute::path('view'),
            'view'       => $data['view'],
        );
        $pages['form']['validate'] = Institute::validate();

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view($pages['parent'] . '.index', $data);
    }

    public function list($data, $id = null)
    {
        $table = Institute::orderBy('id', 'DESC');
        $count = $table->count();
        if ($id) {
            $table->whereIn('id', explode(',', $id));
        }
        $data['response']['data'] = $table->get()->map(function ($row ,$nid) use($count) {
            $row->nid = $count - $nid;
            $row->logo = ImageHelper::site(Institute::path('image'), $row->logo);
            $row->name = $row->{app()->getLocale()};
            $row->action        = [
                'edit' => url(Users::role() . '/study/' . Institute::path('url') . '/edit/' . $row->id),
                'view' => url(Users::role() . '/study/' . Institute::path('url') . '/view/' . $row->id),
                'delete' => url(Users::role() . '/study/' . Institute::path('url') . '/delete/' . $row->id),
            ];
            return $row;
        });
        if ($id) {
            return $data['response']['data'];
        }
        $data['view']     = Institute::path('view') . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List Institute');
        return $data;

    }


    public function show($data, $id, $type)
    {
        $table = Institute::orderBy('id', 'desc');
        if ($id) {
            $table->whereIn('id', explode(',', $id));
            $response =  $table->get()->map(function ($row) {
                $row->logo = $row->logo ? ImageHelper::site(Institute::path('image'), $row->logo) : ImageHelper::prefix();
                $row->action  = [
                    'edit'   => url(Users::role() . '/' . 'study/' . Institute::path('url') . '/edit/' . $row->id),
                    'view'   => url(Users::role() . '/' . 'study/' . Institute::path('url') . '/view/' . $row->id),
                    'delete' => url(Users::role() . '/' . 'study/' . Institute::path('url') . '/delete/' . $row->id),
                ];
                return $row;
            });
            $data['listData'] =  $response->map(function ($row) {
                return [
                    'id'  => $row->id,
                    'name'  => $row->km . '-' . $row->en,
                    'image'  => $row->image,
                    'action'  => [
                        'edit'   => url(Users::role() . '/' . 'study/' . Institute::path('url') . '/edit/' . $row['id']),
                    ],
                ];
            });
        }

        $data['response']['data']   = $response;
        $data['formData']   = $response;
        $data['formAction'] = '/' . $type . '/' . $id;
        $data['view']  = Institute::path('view') . '.includes.form.index';
        $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('Study Session') . ' | ' . __($type);
        return $data;
    }

    public function report()
    {
        request()->merge([
            'size'  => request('size', 'A4'),
            'layout'  => request('layout', 'portrait'),
        ]);

        config()->set('app.title', __('List Study Session'));
        config()->set('pages.parent', Institute::path('view'));


        $table = new Institute;

        $response = $table->get()->map(function ($row) {
            $row->name  = $row->km . ' - ' . $row->en;
            $row->logo = $row->logo ? ImageHelper::site(Institute::path('image'), $row->logo) : ImageHelper::prefix();
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
        config()->set('pages.title', __('List Study Session'));
        return view(Institute::path('view') . '.includes.report.index', $data);
    }
}
