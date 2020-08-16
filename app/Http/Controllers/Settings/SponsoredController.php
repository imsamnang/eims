<?php

namespace App\Http\Controllers\Settings;

use App\Models\App;
use App\Models\Users;
use App\Models\Institute;
use App\Models\Languages;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;

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
        SocailsMedia::setConfig();
        Languages::setConfig();
    }
    public function index($param1 = 'list', $param2 = null, $param3 = null)
    {
        $data['institute'] = Institute::getData();
        $data['formData'] = array(
            'image' => asset('/assets/img/icons/image.jpg'),
        );
        $data['formName'] = App::$path['url'] . '/' . Sponsored::$path['url'];
        $data['formAction'] = '/add';
        $data['listData']       = array();
        if ($param1 == 'list') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return Sponsored::getData(null, null, 10);
            } else {
                $data = $this->list($data);
            }
        } elseif (strtolower($param1) == 'list-datatable') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return Sponsored::getDataTable();
            } else {
                $data = $this->list($data, $param1);
            }
        } elseif ($param1 == 'gallery') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return Sponsored::getData(null, null, 10);
            } else {
                $data = $this->gallery($data);
            }
        } elseif ($param1 == 'add') {


            if (request()->method() === 'POST') {
                return Sponsored::addToTable();
            }

            $data = $this->add($data);
        } elseif ($param1 == 'edit') {
            $id = request('id', $param2);

            if (request()->method() === 'POST') {
                return Sponsored::updateToTable($id);
            }


            $data = $this->show($data, $id, $param1);
        } elseif ($param1 == 'set') {
            if ($param2) {
                $locale = $param2;
            } else if (request('locale')) {
                $locale = request('locale');
            }
            return $this->setLocale($locale);
        } elseif ($param1 == 'view') {
            $id = request('id', $param2);

            $data = $this->show($data, $id, $param1);
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
            'parent'     => Sponsored::$path['view'],
            'view'       => $data['view'],
        );
        $pages['form']['validate'] = [
            'rules'       =>  FormSponsored::rulesField(),
            'attributes'  =>  FormSponsored::attributeField(),
            'messages'    =>  FormSponsored::customMessages(),
            'questions'   =>  FormSponsored::questionField(),
        ];

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view($pages['parent'] . '.index', $data);
    }

    public function list($data)
    {
        $data['response'] = Sponsored::getData(null, 10);
        $data['view']     = Sponsored::$path['view'] . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List Sponsored');
        return $data;
    }

    public function gallery($data)
    {
        $data['response'] = Sponsored::getData();
        $data['view']     = Sponsored::$path['view'] . '.includes.gallery.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List Gallery');
        return $data;
    }

    public function add($data)
    {
        $data['view']      = Sponsored::$path['view'] . '.includes.form.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Add Sponsored');
        $data['metaImage'] = asset('assets/img/icons/register.png');
        $data['metaLink']  = url(Users::role() . '/add/');
        return $data;
    }

    public function show($data, $id, $type)
    {
        $response           = Sponsored::getData($id);
        $data['view']       = Sponsored::$path['view'] . '.includes.form.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Sponsored');
        $data['metaImage']  = asset('assets/img/icons/' . $type . '.png');
        $data['metaLink']   = url(Users::role() . '/' . $type . '/' . $id);
        $data['formData']   = $response['data'][0];
        $data['listData']   = $response['pages']['listData'];
        $data['formAction'] = '/' . $type . '/' . $response['data'][0]['id'];
        return $data;
    }
}
