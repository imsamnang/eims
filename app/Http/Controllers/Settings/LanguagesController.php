<?php

namespace App\Http\Controllers\Settings;

use App\Models\App as AppModel;
use App\Models\Users;
use App\Models\Languages;

use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Helpers\ImageHelper;
use App\Models\SocailsMedia;
use App\Http\Controllers\Controller;
use App\Http\Requests\FormLanguages;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;


class LanguagesController extends Controller
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
        $data['formName'] = App::path('url') . '/' . Languages::path('url');
        $data['formAction'] = '/add';
        $data['listData']       = array();
        $id = request('id', $param2);
        if ($param1 == 'list') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return Languages::getData(null, null, 10);
            } else {
                $data = $this->list($data);
            }
        } elseif (strtolower($param1) == 'list-datatable') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return  Languages::getDataTable();
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'add') {
            if (request()->ajax()) {
                if (request()->method() === 'POST') {
                    return Languages::addToTable();
                }
            }
            $data = $this->show($data, null, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Add Language');
        } elseif ($param1 == 'edit') {
            if (request()->method() === 'POST') {
                return Languages::updateToTable($id);
            }
            $data = $this->show($data, $id, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Edit Language');
        } elseif ($param1 == 'view') {
            $data = $this->show($data, $id, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('View Language');
        } elseif ($param1 == 'delete') {
            return Languages::deleteFromTable($id);
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
            'parent'     => Languages::path('view'),
            'view'       => $data['view'],
        );
        $pages['form']['validate'] = [
            'rules'       =>  (new FormLanguages)->rules(),
            'attributes'  =>  (new FormLanguages)->attributes(),
            'messages'    =>  (new FormLanguages)->messages(),
            'questions'   =>  (new FormLanguages)->questions(),
        ];

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view($pages['parent'] . '.index', $data);
    }

    public function list($data, $id = null)
    {
        $table = Languages::orderBy('id', 'DESC');

        $response = $table->get()->map(function ($row) {
            $row['name']  = $row->km . ' - ' . $row->en;
            $row['image'] = ImageHelper::site(Languages::path('image'), $row['image']);
            $row['action']  = [
                'edit'   => url(Users::role() . '/' . App::path('url') . '/' . Languages::path('url') . '/edit/' . $row['id']),
                'view'   => url(Users::role() . '/' . App::path('url') . '/' . Languages::path('url') . '/view/' . $row['id']),
                'delete' => url(Users::role() . '/' . App::path('url') . '/' . Languages::path('url') . '/delete/' . $row['id']),
            ];

            return $row;
        });
        $data['response']['data'] = $response;
        $data['view']     = Languages::path('view') . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List Language');
        return $data;
    }

    public function show($data, $id, $type)
    {
        $data['view']       = Languages::path('view') . '.includes.form.index';
        if ($id) {
            $response           = Languages::whereIn('id', explode(',', $id))->get()->map(function ($row) {
                $row['image'] = $row['image'] ? ImageHelper::site(Languages::path('image'), $row['image']) : ImageHelper::prefix();
                $row['action']  = [
                    'edit'   => url(Users::role() . '/' . App::path('url') . '/' . Languages::path('url') . '/edit/' . $row['id']),
                    'view'   => url(Users::role() . '/' . App::path('url') . '/' . Languages::path('url') . '/view/' . $row['id']),
                    'delete' => url(Users::role() . '/' . App::path('url') . '/' . Languages::path('url') . '/delete/' . $row['id']),
                ];
                return $row;
            });
            $data['listData'] =  $response->map(function ($row) {
                return [
                    'id'  => $row->id,
                    'name'  => $row->km . '-' . $row->en,
                    'image'  => $row->image,
                    'action'  => [
                        'edit'   => url(Users::role() . '/' . App::path('url') . '/' . Languages::path('url') . '/edit/' . $row['id']),
                    ],
                ];
            });

            $data['response']['data']   = $response;
            $data['formData']   = $response;
            $data['formAction'] = '/' . $type . '/' . $id;
        }
        return $data;
    }

    public function setLocale($locale = null)
    {

        $locale = strtolower($locale);
        if ($locale) {
            if (Schema::hasColumn('languages', $locale)) {
                Session::put('locale', $locale);
                if (request()->method() == 'POST') {
                    $this->response = array(
                        'success' => true,
                        'redirect'  => request()->header('referer')
                    );
                } else {
                    $this->response = redirect()->back();
                }
            } else {
                $this->response =  array(
                    'success' => false,
                    'errors'  => __('Language') . __($locale) . __('Not in list.'),
                );
            }
        }
        return $this->response;
    }
}
