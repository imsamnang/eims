<?php

namespace App\Http\Controllers\CardFrames;


use App\Models\App as AppModel;
use App\Models\Users;
use App\Models\Students;
use App\Models\Institute;
use App\Models\Languages;
use App\Models\CardFrames;
use App\Helpers\CardHelper;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Helpers\ImageHelper;
use App\Models\SocailsMedia;
use App\Http\Requests\FormCard;
use App\Models\StudentsStudyCourse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class CardFramesController extends Controller
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
                'title' => __('List Card frames'),
                'status' => false,
                'link'  => url(Users::role() . '/' . Students::path('url') . '/' . CardFrames::path('url') . '/list'),
            ]
        ];


        $data['formData'] = array(
            [
                'foreground' => asset('/assets/img/card/foreground.png'),
                'background' => asset('/assets/img/card/background.png'),
            ]
        );
        $data['formAction']      = '/add';
        $data['formName']        = Students::path('url') . '/' . CardFrames::path('url');
        $data['title']           = Users::role(app()->getLocale()) . ' | ' .  __('Card');
        $data['metaImage']       = asset('assets/img/icons/' . $param1 . '.png');
        $data['metaLink']        = url(Users::role() . '/' . $param1);
        $data['listData']       = array();
        if ($param1 == 'list' || $param1 == null) {
            $breadcrumb[1]['status']  = 'active';
            $data = $this->list($data);
        } elseif ($param1 == 'add') {
            $breadcrumb[]  = [
                'title' => __('Add Card'),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . Students::path('url') . '/add'),
            ];
            if (request()->method() == 'POST') {
                return CardFrames::addToTable();
            }
            $data = $this->show($data, null, $param1);
        } elseif ($param1 == 'view') {
            $id = request('id', $param2);

            $breadcrumb[]  = [
                'title' => __('View Card'),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . Students::path('url') . '/view/' . $id),
            ];
            if ($param2) {
                $data = $this->show($data, $id, $param1);
                $data['view']       = CardFrames::path('view') . '.includes.view.index';
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'edit') {
            $id = request('id', $param2);
            $breadcrumb[]  = [
                'title' => __('Edit Card'),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . Students::path('url') . '/edit/' . $id),
            ];
            if ($id) {
                if (request()->method() == "POST") {
                    return CardFrames::updateToTable($id);
                }
                $data = $this->show($data, $id, $param1);
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'delete') {
            return CardFrames::deleteFromTable($param2);
        } elseif ($param1 == 'make') {
            $breadcrumb[1]  =  [
                'title' => __('Student study course'),
                'status' => false,
                'link'  => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/list'),
            ];
            $breadcrumb[]  = [
                'title' => __('Make Card'),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/' . CardFrames::path('url') . '/make/' . $param2),
            ];
            if (request()->method() == 'POST') {
                if (request()->all()) {
                    Session::put('card', json_decode(request()->post("card"), true));
                    if (request()->hasFile('foreground_card')) {
                        $file = request()->foreground_card;
                        $file_tmp  = $file->getPathName();
                        $file_type = $file->getMimeType();
                        $file_str  = file_get_contents($file_tmp);
                        $tob64img  = base64_encode($file_str);
                        $card_foreground = 'data:' . $file_type . ';base64,' . $tob64img;
                        Session::put('card_foreground',  $card_foreground);
                    }else{
                        Session::forget('card_foreground');
                    }

                    if (request()->hasFile('back_card')) {
                        $file = request()->back_card;
                        $file_tmp  = $file->getPathName();
                        $file_type = $file->getMimeType();
                        $file_str  = file_get_contents($file_tmp);
                        $tob64img  = base64_encode($file_str);
                        $card_background = 'data:' . $file_type . ';base64,' . $tob64img;
                        Session::put('card_background',  $card_background);
                    }else{
                        Session::forget('card_background');
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
            $d['title'] = Users::role(app()->getLocale()) . ' | ' . __('Card');
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



            $d['response'] = CardHelper::make($param3);
            config()->set('app.title', $d['title']);
            request()->merge([
                'size'  => request('size', 'A4'),
                'layout'  => request('layout', $d['response']['frame']['layout'] == 'vertical' ? 'landscape' : 'portrait')
            ]);
            return view(CardFrames::path('view').'.includes.result.index', $d);
        } elseif ($param1 == 'save') {
            return StudentsStudyCourse::makeCardToTable();
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
            'parent'     => CardFrames::path('view'),
            'view'       => $data['view'],
        );

        $pages['form']['validate'] =CardFrames::validate();


        //Select Option

        $data['institute']['data']  = Institute::get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
            $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
            return $row;
        });
        $data['instituteFilter']['data'] = Institute::whereIn('id', CardFrames::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
                return $row;
            });

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view(CardFrames::path('view').'.index', $data);
    }

    public function list($data, $id = null)
    {
        $table = CardFrames::orderBy('id', 'DESC');

        if (request('instituteId')) {
            $table->where('institute_id', request('instituteId'));
        }
        $count = $table->count();
        if ($id) {
            $table->whereIn('id', explode(',', $id));
        }
        $response = $table->get()->map(function ($row, $nid) use ($count) {
            $row['nid'] = $count - $nid;
            $row['foreground'] = ImageHelper::site(CardFrames::path('image'), $row->foreground);
            $row['background'] = ImageHelper::site(CardFrames::path('image'), $row->background);
            $row['layout'] = __($row->layout);
            $row['action']  = [
                'set' => url(Users::role() . '/' . Students::path('url') . '/' . CardFrames::path('url') . '/set/' . $row['id']),
                'edit'   => url(Users::role() . '/' . Students::path('url') . '/' . CardFrames::path('url') . '/edit/' . $row['id']),
                'view'   => url(Users::role() . '/' . Students::path('url') . '/' . CardFrames::path('url') . '/view/' . $row['id']),
                'delete' => url(Users::role() . '/' . Students::path('url') . '/' . CardFrames::path('url') . '/delete/' . $row['id']),
            ];

            return $row;
        });
        if ($id) {
            return $response;
        }
        $data['response']['data'] = $response;
        $data['view']     = CardFrames::path('view') . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List Card');
        return $data;
    }

    public function show($data, $id, $type)
    {
        $data['view']       = CardFrames::path('view') . '.includes.form.index';
        if ($id) {

            $response           = CardFrames::whereIn('id', explode(',', $id))->get()->map(function ($row) {
                $row['foreground'] = ImageHelper::site(CardFrames::path('image'), $row->foreground, 'large');
                $row['background'] = ImageHelper::site(CardFrames::path('image'), $row->background, 'large');

                $row['action']  = [
                    'edit'   => url(Users::role() . '/' . CardFrames::path('url') . '/' . CardFrames::path('url') . '/edit/' . $row['id']),
                    'view'   => url(Users::role() . '/' . CardFrames::path('url') . '/' . CardFrames::path('url') . '/view/' . $row['id']),
                    'delete' => url(Users::role() . '/' . CardFrames::path('url') . '/' . CardFrames::path('url') . '/delete/' . $row['id']),
                ];
                return $row;
            });
            $data['listData'] =  $response->map(function ($row) {
                return [
                    'id'  => $row->id,
                    'name'  => $row->name,
                    'image'  => $row->foreground,
                    'action'  => [
                        'edit'   => url(Users::role() . '/' . CardFrames::path('url') . '/' . CardFrames::path('url') . '/edit/' . $row['id']),
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

        $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('Card');
        $data['view']  = CardFrames::path('view') . '.includes.make.index';
        $data['response']['frame']  = CardFrames::where('status', 1)->get()->map(function ($row) {
            $row['foreground'] = ImageHelper::site(CardFrames::path('image'), $row->foreground, 'large');
            $row['background'] = ImageHelper::site(CardFrames::path('image'), $row->background, 'large');
            return $row;
        })->first();

        $data['formName']   = Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/' . CardFrames::path('url');
        $data['formAction'] = '/make/' . request('id');

        $data['response']['all'] = CardFrames::frameData('all');
        $data['response']['selected'] = CardFrames::frameData('selected');
        if ($nodes) {
            $data['response']['data'] = $nodes;
        } else {
            $data['response']['data'] =  [];
        }

        $data['cards']['data'] = CardFrames::get()->map(function ($row) {
            $row['foreground'] = ImageHelper::site(CardFrames::path('image'), $row->foreground, 'large');
            $row['background'] = ImageHelper::site(CardFrames::path('image'), $row->background, 'large');
            $row['layout'] = __($row->layout);
            $row['action']  = [
                'set' => url(Users::role() . '/' . Students::path('url') . '/' . CardFrames::path('url') . '/set/' . $row['id']),
            ];

            return $row;
        });

        return $data;
    }

    public function set($id)
    {
        return CardFrames::setToTable($id);
    }
}
