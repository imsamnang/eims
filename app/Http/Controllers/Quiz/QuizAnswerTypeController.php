<?php

namespace App\Http\Controllers\Quiz;

use Carbon\Carbon;
use App\Models\App as AppModel;
use App\Models\Users;
use App\Models\Institute;
use App\Models\Languages;
use App\Helpers\DateHelper;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Helpers\ImageHelper;
use App\Models\SocailsMedia;
use App\Models\QuizAnswerTypes;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Http\Requests\FormQuizAnswerTypes;
use App\Models\Quiz;

class QuizAnswerTypesController extends Controller
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
        $breadcrumb  = [
            [
                'title' => __('Quiz'),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . Quiz::path('url')),
            ],
            [
                'title' => __('List Quiz answer type'),
                'status' => false,
                'link'  => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizAnswerTypes::path('url') . '/list'),
            ]
        ];

        $data['formData'] = array(
            ['image' => asset('/assets/img/icons/image.jpg'),]
        );
        $data['formName'] = Quiz::path('url') . '/' . QuizAnswerTypes::path('url');
        $data['formAction'] = '/add';
        $data['listData']       = array();
        $id = request('id', $param2);
        if ($param1 == 'list') {
            $breadcrumb[1]['status']  = 'active';
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return QuizAnswerTypes::getData(null, null, 10);
            } else {
                $data = $this->list($data);
            }
        } elseif (strtolower($param1) == 'list-datatable') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return  QuizAnswerTypes::getDataTable();
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'add') {
            $breadcrumb[] = [
                'title' => __($param1),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizAnswerTypes::path('url') . '/' . $param1),
            ];
            if (request()->method() === 'POST') {
                return QuizAnswerTypes::addToTable();
            }
            $data = $this->show($data, null, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Quiz Question type') . ' | ' . __('Add');
        } elseif ($param1 == 'edit') {
            $breadcrumb[] = [
                'title' => __($param1),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizAnswerTypes::path('url') . '/' . $param1 . '/' . $id),
            ];
            if (request()->method() === 'POST') {
                return QuizAnswerTypes::updateToTable($id);
            }
            $data = $this->show($data, $id, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Quiz Question type') . ' | '  . __('Edit');
        } elseif ($param1 == 'view') {
            $breadcrumb[] = [
                'title' => __($param1),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizAnswerTypes::path('url') . '/' . $param1 . '/' . $id),
            ];
            $data = $this->show($data, $id, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Quiz Question type') . ' | '  . __('View');
            $data['view']     = QuizAnswerTypes::path('view') . '.includes.view.index';
        } elseif ($param1 == 'delete') {
            return QuizAnswerTypes::deleteFromTable($id);
        } elseif ($param1 == 'report') {
            return $this->report();
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
            'parent'     => QuizAnswerTypes::path('view'),
            'view'       => $data['view'],
        );
        $pages['form']['validate'] = QuizAnswerTypes::validate();

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view($pages['parent'] . '.index', $data);
    }

    public function list($data, $id = null)
    {
        $table = QuizAnswerTypes::orderBy('id', 'DESC');
        $count = $table->count();
        if ($id) {
            $table->whereIn('id', explode(',', $id));
        }
        $response = $table->get()->map(function ($row, $nid) use ($count) {
            $row['nid'] = $count - $nid;
            $row['name']  = $row->km . ' - ' . $row->en;
            $row['image'] = ImageHelper::site(QuizAnswerTypes::path('image'), $row['image']);
            $row['action']  = [
                'edit'   => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizAnswerTypes::path('url') . '/edit/' . $row['id']),
                'view'   => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizAnswerTypes::path('url') . '/view/' . $row['id']),
                'delete' => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizAnswerTypes::path('url') . '/delete/' . $row['id']),
            ];

            return $row;
        });
        if ($id) {
            return  $response;
        }
        $data['response']['data'] = $response;
        $data['view']     = QuizAnswerTypes::path('view') . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Quiz Question type') . ' | '  . __('List');
        return $data;
    }

    public function show($data, $id, $type)
    {
        $data['view']       = QuizAnswerTypes::path('view') . '.includes.form.index';
        if ($id) {

            $response           = QuizAnswerTypes::whereIn('id', explode(',', $id))->get()->map(function ($row) {
                $row['image'] = $row['image'] ? ImageHelper::site(QuizAnswerTypes::path('image'), $row['image']) : ImageHelper::prefix();
                $row['action']  = [
                    'edit'   => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizAnswerTypes::path('url') . '/edit/' . $row['id']),
                    'view'   => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizAnswerTypes::path('url') . '/view/' . $row['id']),
                    'delete' => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizAnswerTypes::path('url') . '/delete/' . $row['id']),
                ];
                return $row;
            });
            $data['listData'] =  $response->map(function ($row) {
                return [
                    'id'  => $row->id,
                    'name'  => $row->km . '-' . $row->en,
                    'image'  => $row->image,
                    'action'  => [
                        'edit'   => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizAnswerTypes::path('url') . '/edit/' . $row['id']),
                    ],
                ];
            });

            $data['response']['data']   = $response;
            $data['formData']   = $response;
            $data['formAction'] = '/' . $type . '/' . $id;
        }
        return $data;
    }

    public function report()
    {
        request()->merge([
            'size'  => request('size', 'A4'),
            'layout'  => request('layout', 'portrait'),
        ]);

        config()->set('app.title', __('Report') . ' | ' . __('Quiz Question type'));
        config()->set('pages.parent', QuizAnswerTypes::path('view'));

        $table = new QuizAnswerTypes;
        $response = $table->get()->map(function ($row) {
            $row['name']  = $row->km . ' - ' . $row->en;
            $row['image'] = $row['image'] ? ImageHelper::site(QuizAnswerTypes::path('image'), $row['image']) : ImageHelper::prefix();
            return $row;
        })->toArray();

        $date = Carbon::now();
        $newData = [];
        $items = Collection::make($response);
        $perPage = request('layout') == 'portrait' ? 25 : 15;
        $perPageNoTop = $perPage + 5;
        $offset = ceil($items->count() / $perPage);

        for ($i = 1; $i <= $offset; $i++) {
            if ($i != 1) {
                $perPage = $perPageNoTop;
            }

            $item = $items->forPage($i, $perPage);
            if ($item->count()) {
                array_push($newData, $item);
            }
        }
        $data['response'] = [
            'data'   => $newData,
            'total'  => $items->count(),
            'date'      => [
                'day'   => $date->day,
                '_day'  => $date->getTranslatedDayName(),
                'month' => $date->getTranslatedMonthName(),
                'year'  => $date->year,
                'def'   => DateHelper::convert($date, 'd-M-Y'),
            ]
        ];

        config()->set('pages.title', __('List Quiz answer type'));
        return view(QuizAnswerTypes::path('view') . '.includes.report.index', $data);
    }
}
