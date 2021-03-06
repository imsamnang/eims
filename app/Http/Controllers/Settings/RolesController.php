<?php

namespace App\Http\Controllers\Settings;

use App\Models\App as AppModel;
use App\Models\Users;
use App\Models\Languages;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Helpers\ImageHelper;
use App\Models\SocailsMedia;
use App\Models\Roles;
use App\Http\Controllers\Controller;

class RolesController extends Controller
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
        $data['formName'] = AppModel::path('url') . '/' . Roles::path('url');
        $data['formAction'] = '/add';
        $data['listData']       = array();
        $id = request('id', $param2);
        if ($param1 == 'list') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return Roles::getData(null, null, 10);
            } else {
                $data = $this->list($data);
            }
        } elseif (strtolower($param1) == 'list-datatable') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return  Roles::getDataTable();
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'add') {
            if (request()->ajax()) {
                if (request()->method() === 'POST') {
                    return Roles::addToTable();
                }
            }
            $data = $this->show($data, null, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Add Roles');
        } elseif ($param1 == 'edit') {
            if (request()->method() === 'POST') {
                return Roles::updateToTable($id);
            }
            $data = $this->show($data, $id, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Edit Roles');
        } elseif ($param1 == 'view') {
            $data = $this->show($data, $id, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('View Roles');
        } elseif ($param1 == 'delete') {
            return Roles::deleteFromTable($id);
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
            'parent'     => Roles::path('view'),
            'view'       => $data['view'],
        );
        $pages['form']['validate'] = Roles::validate();

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view($pages['parent'] . '.index', $data);
    }

    public function list($data, $id = null)
    {
        $table = Roles::orderBy('id', 'DESC');

        $response = $table->get()->map(function ($row) {
            $row['name'] = $row->km;
            $row['image'] = ImageHelper::site(Roles::path('image'), $row['image']);
            $row['action']  = [
                'edit'   => url(Users::role() . '/' . AppModel::path('url') . '/' . Roles::path('url') . '/edit/' . $row['id']),
                'view'   => url(Users::role() . '/' . AppModel::path('url') . '/' . Roles::path('url') . '/view/' . $row['id']),
                'delete' => url(Users::role() . '/' . AppModel::path('url') . '/' . Roles::path('url') . '/delete/' . $row['id']),
            ];

            return $row;
        });
        $data['response']['data'] = $response;
        $data['view']     = Roles::path('view') . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List Roles');
        return $data;
    }

    public function show($data, $id, $type)
    {
        $data['view']       = Roles::path('view') . '.includes.form.index';
        if ($id) {
            $response           = Roles::whereIn('id', explode(',', $id))->get()->map(function ($row) {

                $row['image'] = $row['image'] ? ImageHelper::site(Roles::path('image'), $row['image']) : ImageHelper::prefix();
                $row['action']  = [
                    'edit'   => url(Users::role() . '/' . AppModel::path('url') . '/' . Roles::path('url') . '/edit/' . $row['id']),
                    'view'   => url(Users::role() . '/' . AppModel::path('url') . '/' . Roles::path('url') . '/view/' . $row['id']),
                    'delete' => url(Users::role() . '/' . AppModel::path('url') . '/' . Roles::path('url') . '/delete/' . $row['id']),
                ];
                return $row;
            });
            $data['listData'] =  $response->map(function ($row) {
                return [
                    'id'  => $row->id,
                    'name'  => $row->{app()->getLocale()},
                    'image'  => $row->image,
                    'action'  => [
                        'edit'   => url(Users::role() . '/' . AppModel::path('url') . '/' . Roles::path('url') . '/edit/' . $row['id']),
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
