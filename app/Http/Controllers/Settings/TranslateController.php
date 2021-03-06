<?php

namespace App\Http\Controllers\Settings;

use App\Models\App as AppModel;
use App\Models\Users;
use App\Models\Languages;
use App\Models\Translates;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Models\SocailsMedia;
use App\Http\Controllers\Controller;


class TranslateController extends Controller
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

        // $languages = Translates::orderBy('phrase', 'ASC')->get()->toArray();

        // foreach ($languages as $value) {
        //     echo "[<br>
        //             'phrase' => '" . str_replace("'","\'",$value['phrase']) . "',<br>
        //             'en' => '" . str_replace("'","\'",$value['en']) . "',<br>
        //             'km' => '" . $value['km'] . "'
        //         <br>]," . "<br>";
        // }
        // dd();

        //  foreach ($languages as $value) {
        //     echo "'".$value['phrase']."' => '" . str_replace("'","\'",$value['km']) ."',<br>";
        // }
        // dd();


        $data['formData'] = array(
            'image' => asset('/assets/img/icons/image.jpg'),
        );
        $data['formName'] = AppModel::path('url') . '/' . Translates::path('url');
        $data['formAction'] = '/add';
        $data['listData']       = array();
        if ($param1 == 'list') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return Translates::getData(null, null, 10);
            } else {
                $data = $this->list($data);
            }
        } elseif (strtolower($param1) == 'list-datatable') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return Translates::getDataTable();
            } else {
                $data = $this->list($data, $param1);
            }
        } elseif ($param1 == 'add') {

            if (request()->ajax()) {
                if (request()->method() === 'POST') {
                    return Translates::addToTable();
                }
            }

            $data = $this->add($data);
        } elseif ($param1 == 'edit') {
            $id = request('id', $param2);
            if (request()->ajax()) {
                if (request()->method() === 'POST') {
                    return Translates::updateToTable($id);
                }
            }

            $data = $this->edit($data, $id);
        } elseif ($param1 == 'view') {
            $id = request('id', $param2);

            $data = $this->view($data, $id);
        } elseif ($param1 == 'delete') {
            $id = request('id', $param2);
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
            'parent'     => Translates::path('view'),
            'view'       => $data['view'],
        );
        $pages['form']['validate'] = [
            'rules'       =>  FormTranslates::rules(),
            'attributes'  =>  FormTranslates::attributes(),
            'messages'    =>  FormTranslates::messages(),
            'questions'   =>  FormTranslates::questions(),
        ];

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view($pages['parent'] . '.index', $data);
    }

    public function list($data, $id = null)
    {
        $data['response'] = Translates::getData(null, 10);
        $data['view']     = Translates::path('view') . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List Translates');
        return $data;
    }

    public function add($data)
    {
        $data['view']      = Translates::path('view') . '.includes.form.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Add Translates');
        $data['metaImage'] = asset('assets/img/icons/register.png');
        $data['metaLink']  = url(Users::role() . '/add/');
        return $data;
    }

    public function edit($data, $id)
    {
        $response = Translates::getData($id, true);
        $data['view']       = Translates::path('view') . '.includes.form.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Edit Translates');
        $data['metaImage']  = asset('assets/img/icons/register.png');
        $data['metaLink']   = url(Users::role() . '/edit/' . $id);
        $data['formData']   = $response['data'][0];
        $data['listData']   = $response['pages']['listData'];
        $data['formAction'] = '/edit/' . $response['data'][0]['id'];
        return $data;
    }

    public function view($data, $id)
    {
        $response = Translates::getData($id, true);
        $data['view']       = Translates::path('view') . '.includes.form.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('View Translates');
        $data['metaImage']  = asset('assets/img/icons/register.png');
        $data['metaLink']   = url(Users::role() . '/view/' . $id);
        $data['formData']   = $response['data'][0];
        $data['listData']   = $response['pages']['listData'];
        $data['formAction'] = '/view/' . $response['data'][0]['id'];
        return $data;
    }
}
