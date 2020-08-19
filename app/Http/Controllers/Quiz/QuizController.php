<?php

namespace App\Http\Controllers\Quiz;

use Carbon\Carbon;
use App\Models\App;
use App\Models\Quiz;
use App\Models\Staff;
use App\Models\Users;
use App\Models\Gender;
use App\Models\Institute;

use App\Models\Languages;
use App\Helpers\DateHelper;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Models\QuizStudent;
use App\Helpers\ImageHelper;
use App\Models\QuizQuestion;
use App\Models\SocailsMedia;
use App\Models\QuizAnswerType;
use App\Http\Requests\FormQuiz;
use App\Models\QuizQuestionType;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Quiz\QuizQuestionController;


class QuizController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
        App::setConfig();
       Languages::setConfig(); App::setConfig();  SocailsMedia::setConfig();
        view()->share('breadcrumb', []);
    }


    public function index($param1 = null, $param2 = null, $param3 = null)
    {

        $data['formData'] = array(
            ['image' => asset('/assets/img/icons/image.jpg'),]
        );
        $data['formName'] = Quiz::$path['url'];
        $data['formAction'] = '/add';
        $data['listData']       = array();
        if ($param1 == null) {
            $data['shortcut'] = [
                [
                    'name'  => __('Add Quiz'),
                    'link'  => url(Users::role() . '/' . Quiz::$path['url'] . '/add'),
                    'icon'  => 'fas fa-plus',
                    'image' => null,
                    'color' => 'bg-' . config('app.theme_color.name'),
                ],
                [
                    'name'  => __('List Quiz'),
                    'link'  => url(Users::role() . '/' . Quiz::$path['url'] . '/list'),
                    'icon'  => 'fas fa-question-square',
                    'image' => null,
                    'color' => 'bg-' . config('app.theme_color.name'),
                ],
                [
                    'name'  => __('List Quiz question'),
                    'link'  => url(Users::role() . '/' . Quiz::$path['url'] . '/' . QuizQuestion::$path['url'] . '/list'),
                    'icon'  => 'fas fa-question',
                    'image' => null,
                    'color' => 'bg-' . config('app.theme_color.name'),
                ],
                [
                    'name'  => __('List Quiz student'),
                    'link'  => url(Users::role() . '/' . Quiz::$path['url'] . '/' . QuizStudent::$path['url'] . '/list'),
                    'icon'  => 'fas fa-users-class',
                    'image' => null,
                    'color' => 'bg-' . config('app.theme_color.name'),
                ],
                [
                    'name'  => __('List Quiz answer type'),
                    'link'  => url(Users::role() . '/' . Quiz::$path['url'] . '/' . QuizAnswerType::$path['url'] . '/list'),
                    'icon'  => null,
                    'text'  => __('Quiz answer type'),
                    'image' => null,
                    'color' => 'bg-' . config('app.theme_color.name'),
                ],
                [
                    'name'  => __('List Quiz question type'),
                    'link'  => url(Users::role() . '/' . Quiz::$path['url'] . '/' . QuizQuestionType::$path['url'] . '/list'),
                    'icon'  => null,
                    'text'  => __('Quiz question type'),
                    'image' => null,
                    'color' => 'bg-' . config('app.theme_color.name'),
                ]
            ];
            $data['view']  = 'Quiz.includes.dashboard.index';
            $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('Quiz');
        } elseif ($param1 == 'list') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return Quiz::getData(null, null, 10, request('search'));
            } else {
                $data = $this->list($data);
            }
        } elseif (strtolower($param1) == 'list-datatable') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return  Quiz::getDataTable();
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'add') {

            if (request()->method() === 'POST') {
                return Quiz::addToTable();
            }
            $data = $this->show($data, null, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Add Quiz');
        } elseif ($param1 == 'edit') {
            $id = request('id', $param2);

            if (request()->method() === 'POST') {
                return Quiz::updateToTable($id);
            }
            $data = $this->show($data, $id, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Edit Quiz');
        } elseif ($param1 == 'view') {
            $id = request('id', $param2);
            $data = $this->show($data, $id, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('View Quiz');
            $data['view']       = Quiz::$path['view'] . '.includes.view.index';
        } elseif ($param1 == 'report') {
            return $this->report();
        } elseif ($param1 == 'delete') {

            $id = request('id', $param2);
            return Quiz::deleteFromTable($id);
        } elseif ($param1 == QuizQuestionType::$path['url']) {
            $view = new QuizQuestionTypeController();
            return $view->index($param2, $param3);
        } elseif ($param1 == QuizAnswerType::$path['url']) {
            $view = new QuizAnswerTypeController();
            return $view->index($param2, $param3);
        } elseif ($param1 == QuizQuestion::$path['url']) {
            $view = new QuizQuestionController();
            return $view->index($param2, $param3);
        } elseif ($param1 == QuizStudent::$path['url']) {
            $view = new QuizStudentController();
            return $view->index($param2, $param3);
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
            'parent'     => Quiz::$path['view'],
            'view'       => $data['view'],
        );
        $pages['form']['validate'] = [
            'rules'       =>  FormQuiz::rulesField(),
            'attributes'  =>  FormQuiz::attributeField(),
            'messages'    =>  FormQuiz::customMessages(),
            'questions'   =>  FormQuiz::questionField(),
        ];

        //Select Option

        $data['institute']['data']  = Institute::get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
            $row['image']   = ImageHelper::site(Institute::$path['image'], $row->logo);
            return $row;
        });
        $data['instituteFilter']['data'] = Institute::whereIn('id', Quiz::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::$path['image'], $row->logo);
                return $row;
            });
        $data['staffFilter']['data'] = Staff::whereIn('id', Quiz::groupBy('staff_id')->pluck('staff_id'))
            ->get()->map(function ($row) {
                $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                $row['photo'] = $row['photo'] ? ImageHelper::site(Staff::$path['image'], $row['photo']) : ImageHelper::site(Staff::$path['image'], ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
                return $row;
            });

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view($pages['parent'] . '.index', $data);
    }

    public function list($data)
    {
        $table =  Quiz::orderBy('id', 'asc');
        if (request('instituteId')) {
            $table->where('institute_id', request('instituteId'));
        }

        if (request('staffId')) {
            $table->where('staff_id', request('staffId'));
        }

        $data['response']['data'] = $table->get()->map(function ($row) {
            $row['name']   = $row->{app()->getLocale()};
            $row['image']   = $row->image ? ImageHelper::site(Quiz::$path['image'], $row->image) : ImageHelper::prefix();
            $row['questions'] = [
                'total' =>  QuizQuestion::where('quiz_id', $row->id)->count() . __('Questions'),
                'link_view' => url(Users::role() . '/' . Quiz::$path['url'] . '/' . QuizQuestion::$path['url'] . '/list/?quizId=' . $row['id']),
            ];
            $row['students'] = [
                'total'  => __('Students') . '(' . QuizStudent::where('quiz_id', $row->id)->count() . ((app()->getLocale() == 'km') ? ' នាក់' : ' Poeple') . ')',
                'link_view'  => url(Users::role() . '/' . Quiz::$path['url'] . '/' . QuizStudent::$path['url'] . '/list?quizId=' . $row['id']),
            ];
            $row['action']        = [
                'edit' => url(Users::role() . '/' . Quiz::$path['url'] . '/edit/' . $row['id']),
                'view' => url(Users::role() . '/' . Quiz::$path['url'] . '/view/' . $row['id']),
                'delete' => url(Users::role() . '/' . Quiz::$path['url'] . '/delete/' . $row['id']),
                'question_answer'  => url(Users::role() . '/' . Quiz::$path['url'] . '/' . QuizQuestion::$path['url'] . '/list/?quizId=' . $row['id']),
            ];

            return $row;
        });
        $data['view']     = Quiz::$path['view'] . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List Quiz');
        return $data;
    }
    public function show($data, $id, $type)
    {
        $data['view']       = Quiz::$path['view'] . '.includes.form.index';
        if ($id) {
            $response   = Quiz::whereIn('id', explode(',', $id))->get()->map(function ($row) {
                $row['staff']   = Staff::where('id', $row->staff_id)->get()->map(function ($row) {
                    $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                    $row['photo'] = $row['photo'] ? ImageHelper::site(Staff::$path['image'], $row['photo']) : ImageHelper::site(Staff::$path['image'], ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
                    $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
                    $row['date_of_birth'] = DateHelper::convert($row->date_of_birth, 'd-M-Y');
                    return $row;
                })->first();
                $row['image'] = $row['image'] ? ImageHelper::site(Quiz::$path['image'], $row['image']) : ImageHelper::prefix();
                $row['action']  = [
                    'edit'   => url(Users::role() . '/' . Quiz::$path['url'] . '/edit/' . $row['id']),
                    'view'   => url(Users::role() . '/' . Quiz::$path['url'] . '/view/' . $row['id']),
                    'delete' => url(Users::role() . '/' . Quiz::$path['url'] . '/delete/' . $row['id']),
                ];
                return $row;
            });
            $data['listData'] =  $response->map(function ($row) {
                return [
                    'id'  => $row->id,
                    'name'  => $row->{app()->getLocale()},
                    'image'  => $row->image,
                    'action'  => [
                        'edit'   => url(Users::role() . '/' . Quiz::$path['url'] . '/' . Quiz::$path['url'] . '/edit/' . $row['id']),
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

        config()->set('app.title', __('List Quiz'));
        config()->set('pages.parent', Quiz::$path['view']);


        $data['instituteFilter']['data']           = Institute::whereIn('id', Quiz::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::$path['image'], $row->logo);
                return $row;
            });
        $data['staffFilter']['data'] = Staff::whereIn('id', Quiz::groupBy('staff_id')->pluck('staff_id'))
            ->get()->map(function ($row) {
                $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                $row['photo'] = $row['photo'] ? ImageHelper::site(Staff::$path['image'], $row['photo']) : ImageHelper::site(Staff::$path['image'], ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
                return $row;
            });

        $table = new Quiz;
        if (request('instituteId')) {
            $table->where('institute_id', request('instituteId'));
        }

        if (request('staffId')) {
            $table->where('staff_id', request('staffId'));
        }

        $response = $table->get()->map(function ($row) {
            $row['name']  = $row->km . ' - ' . $row->en;
            $row['image'] = $row['image'] ? ImageHelper::site(Quiz::$path['image'], $row['image']) : ImageHelper::prefix();
            $row['staff']   = Staff::where('id', $row->staff_id)->get()->map(function ($row) {
                $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                $row['photo'] = $row['photo'] ? ImageHelper::site(Staff::$path['image'], $row['photo']) : ImageHelper::site(Staff::$path['image'], ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
                $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
                $row['date_of_birth'] = DateHelper::convert($row->date_of_birth, 'd-M-Y');
                return $row;
            })->first();
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
                $row['logo'] = ImageHelper::site(Institute::$path['image'], $row['logo']);
                return $row;
            })->first();
        config()->set('pages.title', __('List Quiz'));
        return view(Quiz::$path['view'] . '.includes.report.index', $data);
    }
}
