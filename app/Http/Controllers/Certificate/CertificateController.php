<?php

namespace App\Http\Controllers\Certificate;


use App\Models\App;
use App\Models\Users;
use App\Models\Students;
use App\Models\Institute;
use App\Models\Languages;
use App\Models\CertificateFrames;
use App\Helpers\CertificateHelper;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;

use App\Helpers\ImageHelper;
use App\Models\SocailsMedia;
use App\Http\Requests\FormCertificate;
use App\Models\StudentsStudyCourse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class CertificateController extends Controller
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

        $data['formData'] = array(
            [
                'front' => asset('/assets/img/certificate/front.png'),
                'background' => asset('/assets/img/certificate/background.png'),
            ]
        );
        $data['formAction']      = '/add';
        $data['formName']        = Students::$path['url'] . '/' . CertificateFrames::$path['url'];
        $data['title']           = Users::role(app()->getLocale()) . ' | ' .  __('Certificate');
        $data['metaImage']       = asset('assets/img/icons/' . $param1 . '.png');
        $data['metaLink']        = url(Users::role() . '/' . $param1);
        $data['listData']       = array();
        if ($param1 == 'list' || $param1 == null) {
            $data = $this->list($data);
        } elseif (strtolower($param1) == 'list-datatable') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return  CertificateFrames::getDataTable();
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'add') {
            if (request()->method() == 'POST') {
                return CertificateFrames::addToTable();
            }
            $data = $this->show($data, null, $param1);
        } elseif ($param1 == 'view') {
            $id = request('id', $param2);
            if ($param2) {
                $data = $this->show($data, $id, $param1);
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'edit') {
            $id = request('id', $param2);
            if ($id) {
                if (request()->method() == "POST") {
                    return CertificateFrames::updateToTable($id);
                }
                $data = $this->show($data, $id, $param1);
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'delete') {
            return CertificateFrames::deleteFromTable($param2);
        } elseif ($param1 == 'make') {
            if (request()->method() == 'POST') {
                if (request()->all()) {
                    Session::put('certificate', json_decode(request()->post("certificate"), true));
                    if (request()->hasFile('front_certificate')) {
                        $file = request()->front_certificate;
                        $file_tmp  = $file->getPathName();
                        $file_type = $file->getMimeType();
                        $file_str  = file_get_contents($file_tmp);
                        $tob64img  = base64_encode($file_str);
                        $certificate_front = 'data:' . $file_type . ';base64,' . $tob64img;
                        Session::put('certificate_front',  $certificate_front);
                    }

                    if (request()->hasFile('back_certificate')) {
                        $file = request()->back_certificate;
                        $file_tmp  = $file->getPathName();
                        $file_type = $file->getMimeType();
                        $file_str  = file_get_contents($file_tmp);
                        $tob64img  = base64_encode($file_str);
                        $certificate_back = 'data:' . $file_type . ';base64,' . $tob64img;
                        Session::put('certificate_back',  $certificate_back);
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
            $d['title'] = Users::role(app()->getLocale()) . ' | ' . __('Certificate');
            MetaHelper::setConfig(
                [
                    'title'       => $d['title'],
                    'author'      => config('app.name'),
                    'keywords'    => null,
                    'description' => null,
                    'link'        => null,
                    'image'       => null
                ]
            );
            config()->set('app.title', $d['title']);
            request()->merge([
                'size'  => request('size', 'A4'),
                'layout'  => request('layout', 'landscape'),
            ]);


            $d['response'] = CertificateHelper::make($param3);

            return view('Certificate.includes.result.index', $d);
        } elseif ($param1 == 'save') {

            return StudentsStudyCourse::makeCertificateToTable();
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
            'parent'     => CertificateFrames::$path['view'],
            'view'       => $data['view'],
        );

        $pages['form']['validate'] = [
            'rules'       =>  FormCertificate::rulesField(),
            'attributes'  =>  FormCertificate::attributeField(),
            'messages'    =>  FormCertificate::customMessages(),
            'questions'   =>  FormCertificate::questionField(),
        ];


        //Select Option

        $data['institute']['data']  = Institute::get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
            $row['image']   = ImageHelper::site(Institute::$path['image'], $row->logo);
            return $row;
        });
        $data['instituteFilter']['data'] = Institute::whereIn('id', CertificateFrames::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::$path['image'], $row->logo);
                return $row;
            });

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view('Certificate.index', $data);
    }

    public function list($data)
    {
        $table = CertificateFrames::orderBy('id', 'DESC');

        if (request('instituteId')) {
            $table->where('institute_id', request('instituteId'));
        }
        $response = $table->get()->map(function ($row) {
            $row['front'] = ImageHelper::site(CertificateFrames::$path['image'], $row->front, 'original');
            $row['background'] = ImageHelper::site(CertificateFrames::$path['image'], $row->background, 'original');
            $row['layout'] = __($row->layout);
            $row['action']  = [
                'set' => url(Users::role() . '/' . Students::$path['url'] . '/' . CertificateFrames::$path['url'] . '/set/' . $row['id']),
                'edit'   => url(Users::role() . '/' . Students::$path['url'] . '/' . CertificateFrames::$path['url'] . '/edit/' . $row['id']),
                'view'   => url(Users::role() . '/' . Students::$path['url'] . '/' . CertificateFrames::$path['url'] . '/view/' . $row['id']),
                'delete' => url(Users::role() . '/' . Students::$path['url'] . '/' . CertificateFrames::$path['url'] . '/delete/' . $row['id']),
            ];

            return $row;
        });
        $data['response']['data'] = $response;
        $data['view']     = CertificateFrames::$path['view'] . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List Certificate');
        return $data;
    }

    public function show($data, $id, $type)
    {
        $data['view']       = CertificateFrames::$path['view'] . '.includes.form.index';
        if ($id) {

            $response           = CertificateFrames::whereIn('id', explode(',', $id))->get()->map(function ($row) {
                $row['front'] = ImageHelper::site(CertificateFrames::$path['image'], $row->front, 'original');
                $row['background'] = ImageHelper::site(CertificateFrames::$path['image'], $row->background, 'original');

                $row['action']  = [
                    'edit'   => url(Users::role() . '/' . CertificateFrames::$path['url'] . '/' . CertificateFrames::$path['url'] . '/edit/' . $row['id']),
                    'view'   => url(Users::role() . '/' . CertificateFrames::$path['url'] . '/' . CertificateFrames::$path['url'] . '/view/' . $row['id']),
                    'delete' => url(Users::role() . '/' . CertificateFrames::$path['url'] . '/' . CertificateFrames::$path['url'] . '/delete/' . $row['id']),
                ];
                return $row;
            });
            $data['listData'] =  $response->map(function ($row) {
                return [
                    'id'  => $row->id,
                    'name'  => $row->name,
                    'image'  => $row->front,
                    'action'  => [
                        'edit'   => url(Users::role() . '/' . CertificateFrames::$path['url'] . '/' . CertificateFrames::$path['url'] . '/edit/' . $row['id']),
                    ],
                ];
            });

            $data['response']['data']   = $response;
            $data['formData']   = $response;
            $data['formAction'] = '/' . $type . '/' . $id;
        }
        return $data;
    }



    public function make($data, $ts)
    {

        $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('Certificate');
        $data['view']  = CertificateFrames::$path['view'] . '.includes.make.index';
        $data['response']['frame']  = CertificateFrames::getData(CertificateFrames::where('status', 1)->first()->id, 10)['data'][0];
        $data['response']['frame']['front'] = $data['response']['frame']['front_o'];

        $data['formName']   = Students::$path['url'] . '/' . StudentsStudyCourse::$path['url'] . '/' . CertificateFrames::$path['url'];
        $data['formAction'] = '/make/' . request('id');

        $data['response']['all'] = CertificateFrames::frameData('all');
        $data['response']['selected'] = CertificateFrames::frameData('selected');
        if ($ts['success']) {
            $data['response']['data'] = $ts['data'];
        } else {
            $data['response']['data'] =  [];
        }

        $data['certificates']['data'] = CertificateFrames::get()->map(function ($row) {
            $row['front'] = ImageHelper::site(CertificateFrames::$path['image'], $row->front, 'original');
            $row['layout'] = __($row->layout);
            $row['action']  = [
                'set' => url(Users::role() . '/' . Students::$path['url'] . '/' . CertificateFrames::$path['url'] . '/set/' . $row['id']),
            ];

            return $row;
        });

        return $data;
    }

    public function set($id)
    {
        return CertificateFrames::setToTable($id);
    }
}
