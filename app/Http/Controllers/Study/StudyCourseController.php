<?php

namespace App\Http\Controllers\Study;

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
use App\Models\StudyCourse;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Http\Requests\FormStudyCourse;
use App\Models\StudyPrograms;

class StudyCourseController extends Controller
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
        $data['formName'] = 'study/' . StudyCourse::path('url');
        $data['formAction'] = '/add';
        $data['listData']       = array();
        $id = request('id', $param2);
        if ($param1 == 'list') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return StudyCourse::getData(null, null, 10);
            } else {
                $data = $this->list($data);
            }
        } elseif (strtolower($param1) == 'list-datatable') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return  StudyCourse::getDataTable();
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'add') {

            if (request()->method() === 'POST') {
                return StudyCourse::addToTable();
            }
            $data = $this->show($data, null, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Add Study course');
        } elseif ($param1 == 'edit') {
            if (request()->method() === 'POST') {
                return StudyCourse::updateToTable($id);
            }
            $data = $this->show($data, $id, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Edit Study course');
        } elseif ($param1 == 'view') {
            $data = $this->show($data, $id, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('View Study course');
        } elseif ($param1 == 'delete') {
            return StudyCourse::deleteFromTable($id);
        } elseif ($param1 == 'report') {
            return $this->report();
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
            'parent'     => StudyCourse::path('view'),
            'view'       => $data['view'],
        );
        $pages['form']['validate'] = StudyCourse::validate();
        //Select Option
        $data['institute']['data']           = Institute::get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
            $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
            return $row;
        });

        $data['instituteFilter']['data']           = Institute::whereIn('id', StudyCourse::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
                return $row;
            });

        $data['study_program']['data']           = StudyPrograms::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = ImageHelper::site(StudyPrograms::path('image'), $row->image);
            return $row;
        });
        $data['programFilter']['data']           = StudyPrograms::whereIn('id', StudyCourse::groupBy('study_program_id')->pluck('study_program_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(StudyPrograms::path('image'), $row->image);
                return $row;
            });
        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view($pages['parent'] . '.index', $data);
    }

    public function list($data, $id = null)
    {
        $table = StudyCourse::orderBy('id', 'DESC');

        if (request('instituteId')) {
            $table->where('institute_id', request('instituteId'));
        }
        if (request('programId')) {
            $table->where('study_program_id', request('programId'));
        }

        $response = $table->get()->map(function ($row) {
            $row['name']  = $row->km . ' - ' . $row->en;
            $row['image'] = ImageHelper::site(StudyCourse::path('image'), $row['image']);
            $row['study_program'] = StudyPrograms::where('id', $row->study_program_id)->pluck(app()->getLocale())->first();
            $row['action']  = [
                'edit'   => url(Users::role() . '/' . 'study/' . StudyCourse::path('url') . '/edit/' . $row['id']),
                'view'   => url(Users::role() . '/' . 'study/' . StudyCourse::path('url') . '/view/' . $row['id']),
                'delete' => url(Users::role() . '/' . 'study/' . StudyCourse::path('url') . '/delete/' . $row['id']),
            ];

            return $row;
        });
        $data['response']['data'] = $response;
        $data['view']     = StudyCourse::path('view') . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List Study course');
        return $data;
    }

    public function show($data, $id, $type)
    {
        $data['view']       = StudyCourse::path('view') . '.includes.form.index';
        if ($id) {

            $response           = StudyCourse::whereIn('id', explode(',', $id))->get()->map(function ($row) {
                $row['image'] = $row['image'] ? ImageHelper::site(StudyCourse::path('image'), $row['image']) : ImageHelper::prefix();
                $row['study_program'] = StudyPrograms::where('id', $row->study_program_id)->pluck(app()->getLocale())->first();
                $row['action']  = [
                    'edit'   => url(Users::role() . '/' . 'study/' . StudyCourse::path('url') . '/edit/' . $row['id']),
                    'view'   => url(Users::role() . '/' . 'study/' . StudyCourse::path('url') . '/view/' . $row['id']),
                    'delete' => url(Users::role() . '/' . 'study/' . StudyCourse::path('url') . '/delete/' . $row['id']),
                ];
                return $row;
            });
            $data['listData'] =  $response->map(function ($row) {
                return [
                    'id'  => $row->id,
                    'name'  => $row->km . '-' . $row->en,
                    'image'  => $row->image,
                    'action'  => [
                        'edit'   => url(Users::role() . '/' . 'study/' . StudyCourse::path('url') . '/edit/' . $row['id']),
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

        config()->set('app.title', __('List Study course'));
        config()->set('pages.parent', StudyCourse::path('view'));

        $data['instituteFilter']['data']           = Institute::whereIn('id', StudyCourse::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
                return $row;
            });
        $data['programFilter']['data']           = StudyPrograms::whereIn('id', StudyCourse::groupBy('study_program_id')->pluck('study_program_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(StudyPrograms::path('image'), $row->image);
                return $row;
            });


        $table = StudyCourse::orderBy('id', 'desc');
        if (request('instituteId')) {
            $table->where('institute_id', request('instituteId'));
        }
        if (request('programId')) {
            $table->where('study_program_id', request('programId'));
        }
        $response = $table->get()->map(function ($row) {
            $row['name']  = $row->{app()->getLocale()};
            $row['image'] = $row['image'] ? ImageHelper::site(StudyCourse::path('image'), $row['image']) : ImageHelper::prefix();
            $row['study_program'] = StudyPrograms::where('id', $row->study_program_id)->pluck(app()->getLocale())->first();
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

        $data['institute'] = Institute::where('id', request('instituteId'))
            ->get(['logo', app()->getLocale() . ' as name'])
            ->map(function ($row) {
                $row['logo'] = ImageHelper::site(Institute::path('image'), $row['logo']);
                return $row;
            })->first();
        config()->set('pages.title', __('List Study course'));
        return view(StudyCourse::path('view') . '.includes.report.index', $data);
    }
}
