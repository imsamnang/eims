<?php

namespace App\Http\Controllers\Students;


use App\Models\App as AppModel;
use App\Models\Users;
use App\Models\Students;
use App\Models\Institute;
use App\Models\Languages;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Models\SocailsMedia;
use App\Models\CertificateFrames;
use App\Helpers\CertificateHelper;
use App\Models\StudentsStudyCourse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class StudentsCertificateFramesController extends Controller
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
        $data['institutes']      = Institute::getData();
        $data['formData'] = array(
            'front' => asset('/assets/img/Certificate/front.png'),
            'background' => asset('/assets/img/Certificate/background.png'),
        );
        $data['formAction']      = '/add';
        $data['formName']        = CertificateFrames::path('url');
        $data['title']                = Users::role(app()->getLocale()) . ' | ' . __('Student Certificate');
        $data['metaImage']       = asset('assets/img/icons/' . $param1 . '.png');
        $data['metaLink']        = url(Users::role() . '/' . $param1);
        $data['listData']       = array();
        if ($param1 == 'list' || $param1 == null) {
            $data = $this->list($data);
        } elseif ($param1 == 'add') {
            $data = $this->add($data);
        } elseif ($param1 == 'view') {
            if ($param2) {
                $data = $this->view($data, $param2);
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'edit') {
            if ($param2) {
                $data = $this->edit($data, $param2);
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'delete') {
            return CertificateFrames::deleteFromTable($param2);
        } elseif ($param1 == 'make') {
            if (request()->method() == 'POST') {
                if (request()->all()) {
                    Session::put('Certificate', json_decode(request()->post("Certificate"), true));
                    if (request()->hasFile('front_Certificate')) {
                        $file = request()->front_Certificate;
                        $file_tmp  = $file->getPathName();
                        $file_type = $file->getMimeType();
                        $file_str  = file_get_contents($file_tmp);
                        $tob64img  = base64_encode($file_str);
                        $Certificate_front = 'data:' . $file_type . ';base64,' . $tob64img;
                        Session::put('Certificate_front',  $Certificate_front);
                    }

                    if (request()->hasFile('back_Certificate')) {
                        $file = request()->back_Certificate;
                        $file_tmp  = $file->getPathName();
                        $file_type = $file->getMimeType();
                        $file_str  = file_get_contents($file_tmp);
                        $tob64img  = base64_encode($file_str);
                        $Certificate_back = 'data:' . $file_type . ';base64,' . $tob64img;
                        Session::put('Certificate_back',  $Certificate_back);
                    }

                    return array(
                        'success' => true,
                        'redirect' => str_replace('make', 'result', request()->getUri())
                    );
                }
            }
            $data = $this->make($data, $param3);
        } elseif ($param1 == 'set') {
            return $this->set($param2);
        } elseif ($param1 == 'result') {
            $data['title']                = Users::role(app()->getLocale()) . ' | ' . __('Student Certificate');
            MetaHelper::setConfig(
                [
                    'title'       => $data['title'],
                    'author'      => config('app.name'),
                    'keywords'    => null,
                    'description' => null,
                    'link'        => null,
                    'image'       => null
                ]
            );
            config()->set('app.title', $data['title']);
            $d['certificates'] = CertificateHelper::make($param3);
            return view('Certificate.includes.result.index', $d);
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
            'parent'     => CertificateFrames::path('view'),
            'view'       => $data['view'],
        );

        $pages['form']['validate'] = [
            'rules'       =>  FormCertificateFrames::rules(),
            'attributes'  =>  FormCertificateFrames::attributes(),
            'messages'    =>  FormCertificateFrames::messages(),
            'questions'   =>  FormCertificateFrames::questions(),
        ];

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view('Certificate.index', $data);
    }

    public function list($data, $id = null)
    {
        $data['view']     = CertificateFrames::path('view') . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List Certificate frames');
        $data['response'] =  CertificateFrames::getData(null, null, 10);
        return $data;
    }

    public function add($data)
    {
        $data['view']  = CertificateFrames::path('view') . '.includes.form.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Add Certificate frames');
        $data['metaImage'] = asset('assets/img/icons/register.png');
        $data['metaLink']  = url(Users::role() . '/add/');

        return $data;
    }

    public function view($data, $id)
    {
        $response           = CertificateFrames::getData($id, true);
        $data['view']       = CertificateFrames::path('view') . '.includes.form.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('View Certificate frames');
        $data['metaImage']  = asset('assets/img/icons/register.png');
        $data['metaLink']   = url(Users::role() . '/view/' . $id);
        $data['formData']   = $response['data'][0];
        $data['listData']   = $response['pages']['listData'];
        $data['formAction'] = '/view/' . $response['data'][0]['id'];
        return $data;
    }

    public function edit($data, $id)
    {
        $response = CertificateFrames::getData($id, true);
        $data['view']       = CertificateFrames::path('view') . '.includes.form.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Edit Certificate frames');
        $data['metaImage']  = asset('assets/img/icons/register.png');
        $data['metaLink']   = url(Users::role() . '/edit/' . $id);
        $data['formData']   = $response['data'][0];
        $data['listData']   = $response['pages']['listData'];
        $data['formAction'] = '/edit/' . $response['data'][0]['id'];
        return $data;
    }
    public function make($data, $user)
    {

        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Certificate frames');
        $data['view']  = CertificateFrames::path('view') . '.includes.make.index';
        $data['certificates']['frame']  = CertificateFrames::getData(CertificateFrames::where('status', 1)->first()->id, 10)['data'][0];
        $data['response']        =  CertificateFrames::getData();
        $data['formName']   = Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/' . CertificateFrames::path('url');
        $data['formAction'] = '/make/' . request('id');

        $data['certificates']['all'] = CertificateFrames::frameData('all');
        $data['certificates']['selected'] = CertificateFrames::frameData('selected');
        if ($user['success']) {
            $data['certificates']['user'] = $user['data'][0];
        } else {
            $data['certificates']['user'] =  [];
        }

        return $data;
    }

    public function set($id)
    {
        return CertificateFrames::setToTable($id);
    }
}
