<?php

namespace App\Http\Controllers\CertificateFrames;


use App\Models\App as AppModel;
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
use App\Http\Requests\FormCertificateFrames;
use App\Models\StudentsStudyCourse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class CertificateFramesController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
        AppModel::setConfig();
        Languages::setConfig();
        SocailsMedia::setConfig();
        view()->share('breadcrumb', []);
    }

    public function index($param1 = 'list', $param2 = null, $param3 = null)
    {
        $breadcrumb  = [
            [
                'title' => __('Students'),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . Students::path('url')),
            ],
            [
                'title' => __('List Certificate frames'),
                'status' => false,
                'link'  => url(Users::role() . '/' . Students::path('url') . '/' . CertificateFrames::path('url') . '/list'),
            ]
        ];


        $data['formData'] = array(
            [
                'foreground' => asset('/assets/img/certificate/foreground.png'),

            ]
        );
        $data['formAction']      = '/add';
        $data['formName']        = Students::path('url') . '/' . CertificateFrames::path('url');
        $data['title']           = Users::role(app()->getLocale()) . ' | ' .  __('Certificate');
        $data['metaImage']       = asset('assets/img/icons/' . $param1 . '.png');
        $data['metaLink']        = url(Users::role() . '/' . $param1);
        $data['listData']       = array();
        if ($param1 == 'list' || $param1 == null) {
            $breadcrumb[1]['status']  = 'active';
            $data = $this->list($data);
        } elseif (strtolower($param1) == 'list-datatable') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return  CertificateFrames::getDataTable();
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'add') {
            $breadcrumb[]  = [
                'title' => __('Add Certificate'),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . Students::path('url') . '/add'),
            ];
            if (request()->method() == 'POST') {
                return CertificateFrames::addToTable();
            }
            $data = $this->show($data, null, $param1);
        } elseif ($param1 == 'view') {
            $id = request('id', $param2);

            $breadcrumb[]  = [
                'title' => __('View Certificate'),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . Students::path('url') . '/view/' . $id),
            ];
            if ($param2) {
                $data = $this->show($data, $id, $param1);
                $data['view']       = CertificateFrames::path('view') . '.includes.view.index';
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'edit') {
            $id = request('id', $param2);
            $breadcrumb[]  = [
                'title' => __('Edit Certificate'),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . Students::path('url') . '/edit/' . $id),
            ];
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
            $breadcrumb[1]  =  [
                'title' => __('Student study course'),
                'status' => false,
                'link'  => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/list'),
            ];
            $breadcrumb[]  = [
                'title' => __('Make Certificate'),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/' . CertificateFrames::path('url') . '/make/' . $param2),
            ];
            if (request()->method() == 'POST') {
                if (request()->all()) {
                    Session::put('Certificate', json_decode(request()->post("Certificate"), true));
                    if (request()->hasFile('foreground_Certificate')) {
                        $file = request()->foreground_Certificate;
                        $file_tmp  = $file->getPathName();
                        $file_type = $file->getMimeType();
                        $file_str  = file_get_contents($file_tmp);
                        $tob64img  = base64_encode($file_str);
                        $certificate_foreground = 'data:' . $file_type . ';base64,' . $tob64img;
                        Session::put('certificate_foreground',  $certificate_foreground);
                    }else{
                        Session::forget('certificate_foreground');
                    }

                    if (request()->hasFile('certificate_background')) {
                        $file = request()->certificate_background;
                        $file_tmp  = $file->getPathName();
                        $file_type = $file->getMimeType();
                        $file_str  = file_get_contents($file_tmp);
                        $tob64img  = base64_encode($file_str);
                        $certificate_background = 'data:' . $file_type . ';base64,' . $tob64img;
                        Session::put('certificate_background',  $certificate_background);
                    }else{
                        Session::forget('certificate_background');
                    }

                    return array(
                        'success' => true,
                        'message' => __('Adjusted Successfully'),
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


            $d['response'] = CertificateHelper::make($param3);
            config()->set('app.title', $d['title']);
            request()->merge([
                'size'  => request('size', 'A4'),
                'layout'  => request('layout', $d['response']['frame']['layout'] == 'vertical' ? 'landscape' : 'portrait')
            ]);


            return view(CertificateFrames::path('view').'.includes.result.index', $d);
        } elseif ($param1 == 'save') {
            return StudentsStudyCourse::makeCertificateToTable();
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
            'parent'     => CertificateFrames::path('view'),
            'view'       => $data['view'],
        );

        $pages['form']['validate'] = CertificateFrames::validate();


        //Select Option

        $data['institute']['data']  = Institute::get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
            $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
            return $row;
        });
        $data['instituteFilter']['data'] = Institute::whereIn('id', CertificateFrames::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
                return $row;
            });

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view(CertificateFrames::path('view').'.index', $data);
    }

    public function list($data, $id = null)
    {
        $table = CertificateFrames::orderBy('id', 'DESC');

        if (request('instituteId')) {
            $table->where('institute_id', request('instituteId'));
        }
        $count = $table->count();
        if ($id) {
            $table->whereIn('id', explode(',', $id));
        }
        $response = $table->get()->map(function ($row, $nid) use ($count) {
            $row['nid'] = $count - $nid;
            $row['foreground'] = ImageHelper::site(CertificateFrames::path('image'), $row->foreground, 'original');

            $row['layout'] = __($row->layout);
            $row['action']  = [
                'set' => url(Users::role() . '/' . Students::path('url') . '/' . CertificateFrames::path('url') . '/set/' . $row['id']),
                'edit'   => url(Users::role() . '/' . Students::path('url') . '/' . CertificateFrames::path('url') . '/edit/' . $row['id']),
                'view'   => url(Users::role() . '/' . Students::path('url') . '/' . CertificateFrames::path('url') . '/view/' . $row['id']),
                'delete' => url(Users::role() . '/' . Students::path('url') . '/' . CertificateFrames::path('url') . '/delete/' . $row['id']),
            ];

            return $row;
        });
        if ($id) {
            return $response;
        }
        $data['response']['data'] = $response;
        $data['view']     = CertificateFrames::path('view') . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List Certificate');
        return $data;
    }

    public function show($data, $id, $type)
    {
        $data['view']       = CertificateFrames::path('view') . '.includes.form.index';
        if ($id) {

            $response           = CertificateFrames::whereIn('id', explode(',', $id))->get()->map(function ($row) {
                $row['foreground'] = ImageHelper::site(CertificateFrames::path('image'), $row->foreground, 'large');


                $row['action']  = [
                    'edit'   => url(Users::role() . '/' . CertificateFrames::path('url') . '/' . CertificateFrames::path('url') . '/edit/' . $row['id']),
                    'view'   => url(Users::role() . '/' . CertificateFrames::path('url') . '/' . CertificateFrames::path('url') . '/view/' . $row['id']),
                    'delete' => url(Users::role() . '/' . CertificateFrames::path('url') . '/' . CertificateFrames::path('url') . '/delete/' . $row['id']),
                ];
                return $row;
            });
            $data['listData'] =  $response->map(function ($row) {
                return [
                    'id'  => $row->id,
                    'name'  => $row->name,
                    'image'  => $row->foreground,
                    'action'  => [
                        'edit'   => url(Users::role() . '/' . CertificateFrames::path('url') . '/' . CertificateFrames::path('url') . '/edit/' . $row['id']),
                    ],
                ];
            });

            $data['response']['data']   = $response;
            $data['formData']   = $response;
            $data['formAction'] = '/' . $type . '/' . $id;
        }
        return $data;
    }



    public function make($data, $nodes)
    {

        $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('Certificate');
        $data['view']  = CertificateFrames::path('view') . '.includes.make.index';
        $data['response']['frame']  = CertificateFrames::where('status', 1)->get()->map(function ($row) {
            $row['foreground'] = ImageHelper::site(CertificateFrames::path('image'), $row->foreground, 'original');
            $row['background'] = ImageHelper::site(CertificateFrames::path('image'), $row->background, 'original');
            return $row;
        })->first();

        $data['formName']   = Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/' . CertificateFrames::path('url');
        $data['formAction'] = '/make/' . request('id');

        $data['response']['all'] = CertificateFrames::frameData('all');
        $data['response']['selected'] = CertificateFrames::frameData('selected');
        if ($nodes) {
            $data['response']['data'] = $nodes;
        } else {
            $data['response']['data'] =  [];
        }

        $data['certificates']['data'] = CertificateFrames::get()->map(function ($row) {
            $row['foreground'] = ImageHelper::site(CertificateFrames::path('image'), $row->foreground, 'large');
            $row['layout'] = __($row->layout);
            $row['action']  = [
                'set' => url(Users::role() . '/' . Students::path('url') . '/' . CertificateFrames::path('url') . '/set/' . $row['id']),
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
