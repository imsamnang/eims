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
use App\Models\QuizAnswer;
use App\Helpers\DateHelper;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Helpers\ImageHelper;
use App\Models\QuizQuestion;
use App\Models\SocailsMedia;
use App\Models\QuizAnswerType;
use App\Models\QuizQuestionType;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Http\Requests\FormQuizAnswer;
use App\Http\Requests\FormQuizQuestion;

class QuizQuestionController extends Controller
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
            'image' => asset('/assets/img/icons/image.jpg'),
        );
        $data['formName'] = Quiz::$path['url'] . '/' . QuizQuestion::$path['url'];
        $data['formAction'] = '/add';
        $data['listData']       = array();
        if ($param1 == 'list') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return QuizQuestion::getData();
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'add') {

            if (request()->ajax()) {
                if (request()->method() === 'POST') {
                    return QuizQuestion::addToTable();
                }
            }

            $data = $this->show($data, null, $param1);
        } elseif ($param1 == 'edit') {
            $id = request('id', $param2);

            if (request()->ajax()) {
                if (request()->method() === 'POST') {
                    return QuizQuestion::updateToTable($id);
                }
            }
            $data = $this->show($data, $id, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Edit Quiz question');
            $data['view']       = QuizQuestion::$path['view'] . '.includes.form.index';
        } elseif ($param1 == 'view') {
            $id = request('id', $param2);
            $data = $this->show($data, $id, $param1);
            $data['view']       = QuizQuestion::$path['view'] . '.includes.view.index';
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('View Quiz question');
        } elseif (strtolower($param1) == 'list-datatable') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return  QuizQuestion::getDataTable();
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'report') {
            return $this->report();
        } elseif ($param1 == 'delete') {

            $id = request('id', $param2);
            return QuizQuestion::deleteFromTable($id);
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
            'parent'     => QuizQuestion::$path['view'],
            'view'       => $data['view'],
        );
        $pages['form']['validate'] = [
            'rules'       =>  FormQuizQuestion::rulesField() + FormQuizAnswer::rulesField(),
            'attributes'  =>  FormQuizQuestion::attributeField() + FormQuizAnswer::attributeField(),
            'messages'    =>  FormQuizQuestion::customMessages() + FormQuizAnswer::customMessages(),
            'questions'   =>  FormQuizQuestion::questionField() + FormQuizAnswer::questionField(),
        ];

        //Select Option

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

        $data['quizFilter']['data'] = Quiz::join((new Staff)->getTable(), (new Staff)->getTable() . '.id', (new Quiz)->getTable() . '.staff_id')
            ->whereIn((new Quiz)->getTable() . '.id', QuizQuestion::groupBy('quiz_id')->pluck('quiz_id'))
            ->get([
                (new Quiz)->getTable() . '.id',
                (new Quiz)->getTable() . '.' . app()->getLocale() . ' as name',
                (new Quiz)->getTable() . '.image',
                (new Staff)->getTable() . '.first_name_km',
                (new Staff)->getTable() . '.last_name_km',
                (new Staff)->getTable() . '.first_name_en',
                (new Staff)->getTable() . '.last_name_en',
            ])
            ->map(function ($row) {
                $row['name'] = $row->name . ' ( ' .  $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en . ')';
                $row['image'] = $row['image'] ? ImageHelper::site(Quiz::$path['image'], $row['image']) : ImageHelper::prefix();
                return $row;
            });
        $data['questionTypeFilter']['data'] = QuizQuestionType::whereIn('id', QuizQuestion::groupBy('quiz_question_type_id')->pluck('quiz_question_type_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])
            ->map(function ($row) {
                $row['image'] = $row['image'] ? ImageHelper::site(Quiz::$path['image'], $row['image']) : ImageHelper::prefix();
                return $row;
            });
        $data['answerTypeFilter']['data'] = QuizAnswerType::whereIn('id', QuizQuestion::groupBy('quiz_answer_type_id')->pluck('quiz_answer_type_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])
            ->map(function ($row) {
                $row['image'] = $row['image'] ? ImageHelper::site(Quiz::$path['image'], $row['image']) : ImageHelper::prefix();
                return $row;
            });

        $data['quiz']['data'] = Quiz::join((new Staff)->getTable(), (new Staff)->getTable() . '.id', (new Quiz)->getTable() . '.staff_id')

            ->get([
                (new Quiz)->getTable() . '.id',
                (new Quiz)->getTable() . '.' . app()->getLocale() . ' as quiz',
                (new Quiz)->getTable() . '.image',
                (new Staff)->getTable() . '.first_name_km',
                (new Staff)->getTable() . '.last_name_km',
                (new Staff)->getTable() . '.first_name_en',
                (new Staff)->getTable() . '.last_name_en',
            ])
            ->map(function ($row) {
                $row['name'] = $row->quiz . ' ( ' .  $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en . ')';
                $row['image'] = $row['image'] ? ImageHelper::site(Quiz::$path['image'], $row['image']) : ImageHelper::prefix();
                return $row;
            });

        $data['questionType']['data'] = QuizQuestionType::get(['id', app()->getLocale() . ' as name', 'image'])
            ->map(function ($row) {
                $row['image'] = $row['image'] ? ImageHelper::site(Quiz::$path['image'], $row['image']) : ImageHelper::prefix();
                return $row;
            });
        $data['answerType']['data'] = QuizAnswerType::get(['id', app()->getLocale() . ' as name', 'image'])
            ->map(function ($row) {
                $row['image'] = $row['image'] ? ImageHelper::site(Quiz::$path['image'], $row['image']) : ImageHelper::prefix();
                return $row;
            });

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view($pages['parent'] . '.index', $data);
    }


    public function list($data)
    {
        $table = QuizQuestion::join((new Quiz)->getTable(), (new Quiz)->getTable() . '.id', (new QuizQuestion)->getTable() . '.quiz_id')
            ->orderBy((new Quiz)->getTable() . '.id', 'DESC');
        if (request('instituteId')) {
            $table->where('institute_id', request('instituteId'));
        }

        if (request('staffId')) {
            $table->where('staff_id', request('staffId'));
        }
        if (request('quizId')) {
            $table->where('quiz_id', request('quizId'));
        }
        if (request('questionTypeId')) {
            $table->where('quiz_question_type_id', request('questionTypeId'));
        }
        if (request('answerTypeId')) {
            $table->where('quiz_answer_type_id', request('answerTypeId'));
        }

        $data['response']['data'] = $table->get([
            (new Quiz)->getTable() . '.image',
            (new Quiz)->getTable() . '.name as quiz',
            (new QuizQuestion())->getTable() . '.*',
        ])->map(function ($row) {
            $row['image']   = $row->image ? ImageHelper::site(Quiz::$path['image'], $row->image) : ImageHelper::prefix();
            $row['question_type'] = QuizQuestionType::where('id', $row->quiz_question_type_id)->pluck(app()->getLocale())->first();
            $row['answer_type'] = QuizAnswerType::where('id', $row->quiz_answer_type_id)->pluck(app()->getLocale())->first();
            $row['action']        = [
                'edit' => url(Users::role() . '/' . Quiz::$path['url'] . '/' . QuizQuestion::$path['url'] . '/edit/' . $row['id']),
                'view' => url(Users::role() . '/' . Quiz::$path['url'] . '/' . QuizQuestion::$path['url'] . '/view/' . $row['id']),
                'delete' => url(Users::role() . '/' . Quiz::$path['url'] . '/' . QuizQuestion::$path['url'] . '/delete/' . $row['id']),
            ];

            return $row;
        });
        $data['view']     = QuizQuestion::$path['view'] . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List Quiz');
        return $data;
    }
    public function show($data, $id, $type)
    {
        $data['view']       = QuizQuestion::$path['view'] . '.includes.form.index';
        if ($id) {
            $response   = QuizQuestion::join((new Quiz)->getTable(), (new Quiz)->getTable() . '.id', (new QuizQuestion)->getTable() . '.quiz_id')
                ->join((new Staff)->getTable(), (new Staff)->getTable() . '.id', (new Quiz)->getTable() . '.staff_id')
                ->whereIn((new QuizQuestion)->getTable() . '.id', explode(',', $id))->get([
                    (new Quiz)->getTable() . '.' . app()->getLocale() . ' as quiz',
                    (new Quiz)->getTable() . '.image',
                    (new Staff)->getTable() . '.first_name_km',
                    (new Staff)->getTable() . '.last_name_km',
                    (new Staff)->getTable() . '.first_name_en',
                    (new Staff)->getTable() . '.last_name_en',
                    (new QuizQuestion)->getTable() . '.*',

                ])->map(function ($row) {
                    $row['name'] = $row->question . ' ( ' .  $row->quiz . ')';
                    $row['image'] = $row['image'] ? ImageHelper::site(Quiz::$path['image'], $row['image']) : ImageHelper::prefix();
                    $row['question_type'] = QuizQuestionType::where('id', $row->quiz_question_type_id)->pluck(app()->getLocale())->first();
                    $row['answer_type'] = QuizAnswerType::where('id', $row->quiz_answer_type_id)->pluck(app()->getLocale())->first();
                    $row['answers']  = QuizAnswer::where('quiz_question_id', $row->id)->get();
                    $row['action']  = [
                        'edit'   => url(Users::role() . '/' . QuizQuestion::$path['url'] . '/edit/' . $row['id']),
                        'view'   => url(Users::role() . '/' . QuizQuestion::$path['url'] . '/view/' . $row['id']),
                        'delete' => url(Users::role() . '/' . QuizQuestion::$path['url'] . '/delete/' . $row['id']),
                    ];
                    return $row;
                });
            $data['listData'] =  $response->map(function ($row) {
                return [
                    'id'  => $row->id,
                    'name'  => $row->name,
                    'image'  => $row->image,
                    'action'  => [
                        'edit'   => url(Users::role() . '/' . QuizQuestion::$path['url'] . '/' . QuizQuestion::$path['url'] . '/edit/' . $row['id']),
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

        config()->set('app.title', __('List Quiz question'));
        config()->set('pages.parent', QuizQuestion::$path['view']);


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
        $data['quizFilter']['data'] = Quiz::join((new Staff)->getTable(), (new Staff)->getTable() . '.id', (new Quiz)->getTable() . '.staff_id')
            ->whereIn((new Quiz)->getTable() . '.id', QuizQuestion::groupBy('quiz_id')->pluck('quiz_id'))
            ->get([
                (new Quiz)->getTable() . '.id',
                (new Quiz)->getTable() . '.' . app()->getLocale() . ' as name',
                (new Quiz)->getTable() . '.image',
                (new Staff)->getTable() . '.first_name_km',
                (new Staff)->getTable() . '.last_name_km',
                (new Staff)->getTable() . '.first_name_en',
                (new Staff)->getTable() . '.last_name_en',
            ])
            ->map(function ($row) {
                $row['name'] = $row->name . ' ( ' .  $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en . ')';
                $row['image'] = $row['image'] ? ImageHelper::site(Quiz::$path['image'], $row['image']) : ImageHelper::prefix();
                return $row;
            });
        $data['questionTypeFilter']['data'] = QuizQuestionType::whereIn('id', QuizQuestion::groupBy('quiz_question_type_id')->pluck('quiz_question_type_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])
            ->map(function ($row) {
                $row['image'] = $row['image'] ? ImageHelper::site(Quiz::$path['image'], $row['image']) : ImageHelper::prefix();
                return $row;
            });
        $data['answerTypeFilter']['data'] = QuizAnswerType::whereIn('id', QuizQuestion::groupBy('quiz_answer_type_id')->pluck('quiz_answer_type_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])
            ->map(function ($row) {
                $row['image'] = $row['image'] ? ImageHelper::site(Quiz::$path['image'], $row['image']) : ImageHelper::prefix();
                return $row;
            });

        $table = QuizQuestion::join((new Quiz)->getTable(), (new Quiz)->getTable() . '.id', (new QuizQuestion)->getTable() . '.quiz_id')
            ->join((new Staff)->getTable(), (new Staff)->getTable() . '.id', (new Quiz)->getTable() . '.staff_id');
        if (request('instituteId')) {
            $table->where('institute_id', request('instituteId'));
        }

        if (request('staffId')) {
            $table->where('staff_id', request('staffId'));
        }

        if (request('quizId')) {
            $table->where('quiz_id', request('quizId'));
        }
        if (request('questionTypeId')) {
            $table->where('quiz_question_type_id', request('questionTypeId'));
        }
        if (request('answerTypeId')) {
            $table->where('quiz_answer_type_id', request('answerTypeId'));
        }


        $response = $table->get([
            (new QuizQuestion)->getTable() . '.*',
            (new Quiz)->getTable() . '.' . app()->getLocale() . ' as name',
            (new Quiz)->getTable() . '.image',
            (new Staff)->getTable() . '.first_name_km',
            (new Staff)->getTable() . '.last_name_km',
            (new Staff)->getTable() . '.first_name_en',
            (new Staff)->getTable() . '.last_name_en',
        ])->map(function ($row) {
            $row['answer_limit']  = QuizAnswer::where('quiz_question_id', $row->id)->where('correct_answer', 1)->count();
            $row['answers']  = QuizAnswer::where('quiz_question_id', $row->id)->get();
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
        config()->set('pages.title', __('Question'));
        return view(QuizQuestion::$path['view'] . '.includes.report.index', $data);
    }
}
