<?php

namespace App\Http\Controllers\Study;

use App\Models\App;
use App\Models\Users;
use App\Models\Institute;
use App\Models\Languages;
use App\Helpers\FormHelper;
use App\Helpers\ImageHelper;
use App\Helpers\MetaHelper;

use App\Models\SocailsMedia;
use App\Http\Controllers\Controller;
use App\Http\Requests\FormInstitute;


class InstituteController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
        App::setConfig();
       Languages::setConfig(); App::setConfig();  SocailsMedia::setConfig();
        view()->share('breadcrumb', []);
    }


    public function index($param1 = 'list', $param2 = null, $param3 = null)
    {
        $data['formData'] = array(
            'image' => asset('/assets/img/icons/image.jpg'),
        );
        $data['formName'] = 'study/' . Institute::$path['url'];
        $data['formAction'] = '/add';
        $data['listData']       = array();
        if ($param1 == 'list') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return Institute::getData(null, null, 10, request('search'));
            } else {
                $data = $this->list($data);
            }
        } elseif (strtolower($param1) == 'list-datatable') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return Institute::getDataTable();
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'add') {
            if (request()->method() === 'POST') {
                return Institute::addToTable();
            }

            $data = $this->show($data, null, $param1);
        } elseif ($param1 == 'edit') {
            $id = request('id', $param2);

            if (request()->method() === 'POST') {
                return Institute::updateToTable($id);
            }

            $data = $this->show($data, $id, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Edit Institute');
        } elseif ($param1 == 'view') {
            $id = request('id', $param2);
            $data = $this->show($data, $id, $param1);
            $data['view']       = Institute::$path['view'] . '.includes.view.index';
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('View Institute');
        } elseif ($param1 == 'delete') {
            $id = request('id', $param2);
            return Institute::deleteFromTable($id);
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
            'parent'     => Institute::$path['view'],
            'view'       => $data['view'],
        );
        $pages['form']['validate'] = [
            'rules'       =>  FormInstitute::rulesField(),
            'attributes'  =>  FormInstitute::attributeField(),
            'messages'    =>  FormInstitute::customMessages(),
            'questions'   =>  FormInstitute::questionField(),
        ];

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view($pages['parent'] . '.index', $data);
    }

    public function list($data)
    {
        $data['view']     = Institute::$path['view'] . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List Institute');
        $data['response']['data'] = Institute::get()->map(function ($row) {
            $row['logo'] = ImageHelper::site(Institute::$path['image'], $row->logo);
            $row['name'] = $row->{app()->getLocale()};
            $row['action']        = [
                'edit' => url(Users::role() . '/study/' . Institute::$path['url'] . '/edit/' . $row['id']),
                'view' => url(Users::role() . '/study/' . Institute::$path['url'] . '/view/' . $row['id']),
                'delete' => url(Users::role() . '/study/' . Institute::$path['url'] . '/delete/' . $row['id']),
            ];
            return $row;
        });
        return $data;
    }
    public function show($data, $id, $type)
    {
        $data['view']       = Institute::$path['view'] . '.includes.form.index';
        if ($id) {

            $response           = Institute::whereIn('id', explode(',', $id))->get()->map(function ($row) {
                $row['logo'] = $row['logo'] ? ImageHelper::site(Institute::$path['image'], $row->logo) : ImageHelper::prefix();
                $row['action']  = [
                    'edit'   => url(Users::role() . '/study/' . Institute::$path['url'] . '/edit/' . $row['id']),
                    'view'   => url(Users::role() . '/study/' . Institute::$path['url'] . '/view/' . $row['id']),
                    'delete' => url(Users::role() . '/study/' . Institute::$path['url'] . '/delete/' . $row['id']),
                ];
                return $row;
            });
            $data['listData'] =  $response->map(function ($row) {
                return [
                    'id'  => $row->id,
                    'name'  => $row->km . '-' . $row->short_name,
                    'image'  => $row->logo,
                    'action'  => $row->action,
                ];
            });

            $data['response']['data']   = $response;
            $data['formData']   = $response;
            $data['formAction'] = '/' . $type . '/' . $id;
        }
        return $data;
    }
}
