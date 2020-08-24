<?php

namespace App\Http\Controllers\Settings;

use App\Models\App;
use App\Models\Users;
use App\Models\Languages;

use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Helpers\ImageHelper;
use App\Models\SocailsMedia;
use App\Models\Sponsored;
use App\Http\Controllers\Controller;
use App\Http\Requests\FormSponsored;


class SponsoredController extends Controller
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
        $data['formName'] = App::path('url') . '/' . Sponsored::path('url');
        $data['formAction'] = '/add';
        $data['listData']       = array();
        $id = request('id', $param2);
        if ($param1 == 'list') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return Sponsored::getData(null, null, 10);
            } else {
                $data = $this->list($data);
            }
        } elseif (strtolower($param1) == 'list-datatable') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return  Sponsored::getDataTable();
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'add') {
            if (request()->ajax()) {
                if (request()->method() === 'POST') {
                    return Sponsored::addToTable();
                }
            }
            $data = $this->show($data, null, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Add Sponsored');
        } elseif ($param1 == 'edit') {
            if (request()->method() === 'POST') {
                return Sponsored::updateToTable($id);
            }
            $data = $this->show($data, $id, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Edit Sponsored');
        } elseif ($param1 == 'view') {
            $data = $this->show($data, $id, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('View Sponsored');
        } elseif ($param1 == 'delete') {
            return Sponsored::deleteFromTable($id);
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
            'parent'     => Sponsored::path('view'),
            'view'       => $data['view'],
        );
        $pages['form']['validate'] = [
            'rules'       =>  FormSponsored::rules(),
            'attributes'  =>  FormSponsored::attributes(),
            'messages'    =>  FormSponsored::messages(),
            'questions'   =>  FormSponsored::questions(),
        ];

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view($pages['parent'] . '.index', $data);
    }

    public function list($data, $id = null)
    {
        $table = Sponsored::orderBy('id', 'DESC');

        $response = $table->get()->map(function ($row) {
            $row['image'] = ImageHelper::site(Sponsored::path('image'), $row['image']);
            $row['action']  = [
                'edit'   => url(Users::role() . '/' . App::path('url') . '/' . Sponsored::path('url') . '/edit/' . $row['id']),
                'view'   => url(Users::role() . '/' . App::path('url') . '/' . Sponsored::path('url') . '/view/' . $row['id']),
                'delete' => url(Users::role() . '/' . App::path('url') . '/' . Sponsored::path('url') . '/delete/' . $row['id']),
            ];

            return $row;
        });
        $data['response']['data'] = $response;
        $data['view']     = Sponsored::path('view') . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List Sponsored');
        return $data;
    }

    public function show($data, $id, $type)
    {
        $data['view']       = Sponsored::path('view') . '.includes.form.index';
        if ($id) {
            $response           = Sponsored::whereIn('id', explode(',', $id))->get()->map(function ($row) {

                $row['image'] = $row['image'] ? ImageHelper::site(Sponsored::path('image'), $row['image']) : ImageHelper::prefix();
                $row['action']  = [
                    'edit'   => url(Users::role() . '/' . App::path('url') . '/' . Sponsored::path('url') . '/edit/' . $row['id']),
                    'view'   => url(Users::role() . '/' . App::path('url') . '/' . Sponsored::path('url') . '/view/' . $row['id']),
                    'delete' => url(Users::role() . '/' . App::path('url') . '/' . Sponsored::path('url') . '/delete/' . $row['id']),
                ];
                return $row;
            });
            $data['listData'] =  $response->map(function ($row) {
                return [
                    'id'  => $row->id,
                    'name'  => $row->name,
                    'image'  => $row->image,
                    'action'  => [
                        'edit'   => url(Users::role() . '/' . App::path('url') . '/' . Sponsored::path('url') . '/edit/' . $row['id']),
                    ],
                ];
            });

            $data['response']['data']   = $response;
            $data['formData']   = $response;
            $data['formAction'] = '/' . $type . '/' . $id;
        }
        return $data;
    }
}
