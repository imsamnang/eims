<?php

namespace App\Http\Controllers\Quiz;

use Carbon\Carbon;
use App\Models\App as AppModel;
use App\Models\Quiz;
use App\Models\Staff;
use App\Models\Users;
use App\Models\Gender;
use App\Models\Institute;
use App\Models\Languages;
use App\Models\QuizAnswers;
use App\Helpers\DateHelper;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Helpers\ImageHelper;
use App\Models\QuizQuestions;
use App\Models\SocailsMedia;
use App\Models\QuizAnswerTypes;
use App\Models\QuizQuestionTypes;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;

class QuizQuestionController extends Controller
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
            'image' => asset('/assets/img/icons/image.jpg'),
        );
        $data['formName'] = Quiz::path('url') . '/' . QuizQuestions::path('url');
        $data['formAction'] = '/add';
        $data['listData']       = array();
        if ($param1 == 'list') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return QuizQuestions::getData();
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'add') {

            if (request()->ajax()) {
                if (request()->method() === 'POST') {
                    return QuizQuestions::addToTable();
                }
            }

            $data = $this->show($data, null, $param1);
        } elseif ($param1 == 'edit') {
            $id = request('id', $param2);

            if (request()->ajax()) {
                if (request()->method() === 'POST') {
                    return QuizQuestions::updateToTable($id);
                }
            }
            $data = $this->show($data, $id, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Edit Quiz question');
            $data['view']       = QuizQuestions::path('view') . '.includes.form.index';
        } elseif ($param1 == 'view') {
            $id = request('id', $param2);
            $data = $this->show($data, $id, $param1);
            $data['view']       = QuizQuestions::path('view') . '.includes.view.index';
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('View Quiz question');
        } elseif (strtolower($param1) == 'list-datatable') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return  QuizQuestions::getDataTable();
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'report') {
            return $this->report();
        } elseif ($param1 == 'delete') {

            $id = request('id', $param2);
            return QuizQuestions::deleteFromTable($id);
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
            'parent'     => QuizQuestions::path('view'),
            'view'       => $data['view'],
        );

        $questions_validate =  QuizQuestions::validate();
        $answers_validate =  QuizQuestions::validate(null,'.*');
        $pages['form']['validate'] = [
            'rules'       =>  $questions_validate['rules'] + $answers_validate['rules'],
            'attributes'  =>  $questions_validate['attributes'] + $answers_validate['attributes'],
            'messages'    =>  $questions_validate['messages'] + $answers_validate['messages'],
            'questions'   =>  $questions_validate['questions'] + $answers_validate['questions'],
        ];


        //Select Option

        $data['instituteFilter']['data'] = Institute::whereIn('id', Quiz::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
                return $row;
            });
        $data['staffFilter']['data'] = Staff::whereIn('id', Quiz::groupBy('staff_id')->pluck('staff_id'))
            ->get()->map(function ($row) {
                $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                $row['photo'] = $row['photo'] ? ImageHelper::site(Staff::path('image'), $row['photo']) : ImageHelper::site(Staff::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
                return $row;
            });

        $data['quizFilter']['data'] = Quiz::join((new Staff)->getTable(), (new Staff)->getTable() . '.id', (new Quiz)->getTable() . '.staff_id')
            ->whereIn((new Quiz)->getTable() . '.id', QuizQuestions::groupBy('quiz_id')->pluck('quiz_id'))
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
                $row['image'] = $row['image'] ? ImageHelper::site(Quiz::path('image'), $row['image']) : ImageHelper::prefix();
                return $row;
            });
        $data['questionTypeFilter']['data'] = QuizQuestionTypes::whereIn('id', QuizQuestions::groupBy('quiz_question_type_id')->pluck('quiz_question_type_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])
            ->map(function ($row) {
                $row['image'] = $row['image'] ? ImageHelper::site(Quiz::path('image'), $row['image']) : ImageHelper::prefix();
                return $row;
            });
        $data['answerTypeFilter']['data'] = QuizAnswerTypes::whereIn('id', QuizQuestions::groupBy('quiz_answer_type_id')->pluck('quiz_answer_type_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])
            ->map(function ($row) {
                $row['image'] = $row['image'] ? ImageHelper::site(Quiz::path('image'), $row['image']) : ImageHelper::prefix();
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
                $row['image'] = $row['image'] ? ImageHelper::site(Quiz::path('image'), $row['image']) : ImageHelper::prefix();
                return $row;
            });

        $data['questionType']['data'] = QuizQuestionTypes::get(['id', app()->getLocale() . ' as name', 'image'])
            ->map(function ($row) {
                $row['image'] = $row['image'] ? ImageHelper::site(Quiz::path('image'), $row['image']) : ImageHelper::prefix();
                return $row;
            });
        $data['answerType']['data'] = QuizAnswerTypes::get(['id', app()->getLocale() . ' as name', 'image'])
            ->map(function ($row) {
                $row['image'] = $row['image'] ? ImageHelper::site(Quiz::path('image'), $row['image']) : ImageHelper::prefix();
                return $row;
            });

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view($pages['parent'] . '.index', $data);
    }


    public function list($data, $id = null)
    {
        $table = QuizQuestions::join((new Quiz)->getTable(), (new Quiz)->getTable() . '.id', (new QuizQuestions)->getTable() . '.quiz_id')
            ->orderBy((new QuizQuestions)->getTable() . '.id', 'DESC');
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
        $count = $table->count();
        if ($id) {
            $table->whereIn((new QuizQuestions)->getTable() . '.id', explode(',', $id));
        }
        $data['response']['data'] = $table->get([
            (new Quiz)->getTable() . '.image',
            (new Quiz)->getTable() . '.name as quiz',
            (new QuizQuestions())->getTable() . '.*',
        ])->map(function ($row, $nid) use ($count) {
            $row['nid'] = $count - $nid;
            $row['image']   = $row->image ? ImageHelper::site(Quiz::path('image'), $row->image) : ImageHelper::prefix();
            $row['question_type'] = QuizQuestionTypes::where('id', $row->quiz_question_type_id)->pluck(app()->getLocale())->first();
            $row['answer_type'] = QuizAnswerTypes::where('id', $row->quiz_answer_type_id)->pluck(app()->getLocale())->first();
            $row['action']        = [
                'edit' => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizQuestions::path('url') . '/edit/' . $row['id']),
                'view' => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizQuestions::path('url') . '/view/' . $row['id']),
                'delete' => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizQuestions::path('url') . '/delete/' . $row['id']),
            ];

            return $row;
        });
        if ($id) {
            return $data['response']['data'];
        }
        $data['view']     = QuizQuestions::path('view') . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List Quiz');
        return $data;
    }
    public function show($data, $id, $type)
    {
        $data['view']       = QuizQuestions::path('view') . '.includes.form.index';
        if ($id) {
            $response   = QuizQuestions::join((new Quiz)->getTable(), (new Quiz)->getTable() . '.id', (new QuizQuestions)->getTable() . '.quiz_id')
                ->join((new Staff)->getTable(), (new Staff)->getTable() . '.id', (new Quiz)->getTable() . '.staff_id')
                ->whereIn((new QuizQuestions)->getTable() . '.id', explode(',', $id))->get([
                    (new Quiz)->getTable() . '.' . app()->getLocale() . ' as quiz',
                    (new Quiz)->getTable() . '.image',
                    (new Staff)->getTable() . '.first_name_km',
                    (new Staff)->getTable() . '.last_name_km',
                    (new Staff)->getTable() . '.first_name_en',
                    (new Staff)->getTable() . '.last_name_en',
                    (new QuizQuestions)->getTable() . '.*',

                ])->map(function ($row) {
                    $row['name'] = $row->question . ' ( ' .  $row->quiz . ')';
                    $row['image'] = $row['image'] ? ImageHelper::site(Quiz::path('image'), $row['image']) : ImageHelper::prefix();
                    $row['question_type'] = QuizQuestionTypes::where('id', $row->quiz_question_type_id)->pluck(app()->getLocale())->first();
                    $row['answer_type'] = QuizAnswerTypes::where('id', $row->quiz_answer_type_id)->pluck(app()->getLocale())->first();
                    $row['answers']  = QuizAnswers::where('quiz_question_id', $row->id)->get();
                    $row['action']  = [
                        'edit'   => url(Users::role() . '/' . QuizQuestions::path('url') . '/edit/' . $row['id']),
                        'view'   => url(Users::role() . '/' . QuizQuestions::path('url') . '/view/' . $row['id']),
                        'delete' => url(Users::role() . '/' . QuizQuestions::path('url') . '/delete/' . $row['id']),
                    ];
                    return $row;
                });
            $data['listData'] =  $response->map(function ($row) {
                return [
                    'id'  => $row->id,
                    'name'  => $row->name,
                    'image'  => $row->image,
                    'action'  => [
                        'edit'   => url(Users::role() . '/' . QuizQuestions::path('url') . '/' . QuizQuestions::path('url') . '/edit/' . $row['id']),
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
        config()->set('pages.parent', QuizQuestions::path('view'));


        $data['instituteFilter']['data']           = Institute::whereIn('id', Quiz::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
                return $row;
            });
        $data['staffFilter']['data'] = Staff::whereIn('id', Quiz::groupBy('staff_id')->pluck('staff_id'))
            ->get()->map(function ($row) {
                $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                $row['photo'] = $row['photo'] ? ImageHelper::site(Staff::path('image'), $row['photo']) : ImageHelper::site(Staff::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
                return $row;
            });
        $data['quizFilter']['data'] = Quiz::join((new Staff)->getTable(), (new Staff)->getTable() . '.id', (new Quiz)->getTable() . '.staff_id')
            ->whereIn((new Quiz)->getTable() . '.id', QuizQuestions::groupBy('quiz_id')->pluck('quiz_id'))
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
                $row['image'] = $row['image'] ? ImageHelper::site(Quiz::path('image'), $row['image']) : ImageHelper::prefix();
                return $row;
            });
        $data['questionTypeFilter']['data'] = QuizQuestionTypes::whereIn('id', QuizQuestions::groupBy('quiz_question_type_id')->pluck('quiz_question_type_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])
            ->map(function ($row) {
                $row['image'] = $row['image'] ? ImageHelper::site(Quiz::path('image'), $row['image']) : ImageHelper::prefix();
                return $row;
            });
        $data['answerTypeFilter']['data'] = QuizAnswerTypes::whereIn('id', QuizQuestions::groupBy('quiz_answer_type_id')->pluck('quiz_answer_type_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])
            ->map(function ($row) {
                $row['image'] = $row['image'] ? ImageHelper::site(Quiz::path('image'), $row['image']) : ImageHelper::prefix();
                return $row;
            });

        $table = QuizQuestions::join((new Quiz)->getTable(), (new Quiz)->getTable() . '.id', (new QuizQuestions)->getTable() . '.quiz_id')
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
            (new QuizQuestions)->getTable() . '.*',
            (new Quiz)->getTable() . '.' . app()->getLocale() . ' as name',
            (new Quiz)->getTable() . '.image',
            (new Staff)->getTable() . '.first_name_km',
            (new Staff)->getTable() . '.last_name_km',
            (new Staff)->getTable() . '.first_name_en',
            (new Staff)->getTable() . '.last_name_en',
        ])->map(function ($row) {
            $row['answer_limit']  = QuizAnswers::where('quiz_question_id', $row->id)->where('correct_answer', 1)->count();
            $row['answers']  = QuizAnswers::where('quiz_question_id', $row->id)->get();
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
        config()->set('pages.title', __('Question'));
        return view(QuizQuestions::path('view') . '.includes.report.index', $data);
    }
}
