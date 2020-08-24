<?php

namespace App\Http\Controllers\Quiz;

use Carbon\Carbon;
use App\Models\App as AppModel;
use App\Models\Quiz;
use App\Models\Staff;
use App\Models\Users;
use App\Models\Gender;
use App\Models\Students;
use App\Models\Institute;
use App\Models\Languages;

use App\Models\QuizAnswer;
use App\Helpers\DateHelper;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Models\QuizStudent;
use App\Models\StudyCourse;
use App\Helpers\ImageHelper;
use App\Models\QuizQuestion;
use App\Models\SocailsMedia;
use App\Models\StudySession;
use App\Models\StudyPrograms;
use App\Models\StudySemesters;
use App\Models\StudentsRequest;
use App\Models\StudyGeneration;
use App\Models\QuizStudentAnswer;
use App\Models\StudyAcademicYears;
use App\Models\StudyCourseSession;
use Illuminate\Support\Collection;
use App\Models\StudentsStudyCourse;
use App\Models\StudyCourseSchedule;
use App\Http\Controllers\Controller;
use App\Http\Requests\FormQuizStudent;
use App\Http\Requests\FormQuizStudentAnswerMarks;

class QuizStudentsController extends Controller
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
        $data['formName'] = Quiz::path('url') . '/' . QuizStudent::path('url');
        $data['formAction'] = '/add';
        $data['listData']       = array();
        if ($param1 == 'list') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return QuizStudent::getData(null, null, 10);
            } else {
                $data = $this->list($data);
            }
        } elseif (strtolower($param1) == 'list-datatable') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return  QuizStudent::getDataTable();
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'report') {
            return $this->report();
        } elseif ($param1 == 'auto-score') {
            $id = request('id', $param2);
            QuizStudent::join((new QuizStudentAnswer)->getTable(), (new QuizStudentAnswer)->getTable() . '.quiz_student_id', (new QuizStudent)->getTable() . '.id')
                ->whereIn((new QuizStudent)->getTable() . '.id', explode(',', $id))
                ->get([
                    (new QuizStudentAnswer)->getTable() . '.*',
                ])->map(function ($r) {
                    $r['questions'] = QuizQuestion::where('id', $r->quiz_question_id)->get()->first();
                    $r['answers'] = QuizAnswer::where('quiz_question_id', $r->quiz_question_id)->get();

                    $correct_marks = 0;


                    if ($r['questions']['quiz_answer_type_id'] == 1) {
                        $quizAnswer = QuizAnswer::where('id', $r->answered)->get()->first();
                        if ($quizAnswer->correct_answer) {
                            $correct_marks = $r['questions']['score'];
                        };
                    } elseif ($r['questions']['quiz_answer_type_id'] == 2) {
                        $correct = [];
                        foreach (explode(',', $r->answered) as $answerId) {

                            $quizAnswer = QuizAnswer::where('id', $answerId)->get()->first();
                            if ($quizAnswer->correct_answer) {
                                $correct[] = $quizAnswer->correct_answer;
                                $correct_marks += $r['questions']['score'] / $r['answer_limit'];
                            }
                        }

                        if ($r['answer_limit'] == count($correct)) {
                            $correct_marks = $r['questions']['score'];
                        }
                    } elseif ($r['questions']['quiz_answer_type']['id'] == 3) {
                        if ($r['answers'][0]['answer'] == $r->answered) {
                            $correct_marks = $r['questions']['score'];
                        }
                    }
                    QuizStudentAnswer::where('id', $r->id)->update([
                        'score' => $correct_marks
                    ]);
                });

            return back();
        } elseif ($param1 == 'add') {
            if (request()->method() === 'POST') {
                return QuizStudent::addToTable();
            }


            $data = $this->show($data, null, $param1);
        } elseif ($param1 == 'edit') {
            $id = request('id', $param2);

            if (request()->method() === 'POST') {
                return QuizStudent::updateToTable($id);
            }


            $data = $this->show($data, $id, $param1);
            $data['view']       = QuizStudent::path('view') . '.includes.form.index';
        } elseif ($param1 == 'view') {
            $id = request('id', $param2);
            $data = $this->show($data, $id, $param1);
            $data['view']    = QuizStudent::path('view') . '.includes.view.index';
        } elseif ($param1 == 'delete') {
            $id = request('id', $param2);
            return QuizStudent::deleteFromTable($id);
        } elseif ($param1 == 'answer_again') {
            $id = $param2 ? $param2 : request('id');
            return QuizStudentAnswer::updateAnswerAgainToTable($id);
        } elseif ($param1 == 'score') {
            if ($param2 == 'update') {
                $id = $param3 ? $param3 : request('id');
                return QuizStudentAnswer::updateMarksToTable($id);
            }
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
            'parent'     => QuizStudent::path('view'),
            'view'       => $data['view'],
        );
        $pages['form']['validate'] = [
            'rules'       =>  FormQuizStudent::rules(),
            'attributes'  =>  FormQuizStudent::attributes(),
            'messages'    =>  FormQuizStudent::messages(),
            'questions'   =>  FormQuizStudent::questions(),
        ];
        $pages['form2']['validate'] = [
            'rules'       =>  FormQuizStudentAnswerMarks::rules(),
            'attributes'  =>  FormQuizStudentAnswerMarks::attributes(),
            'messages'    =>  FormQuizStudentAnswerMarks::messages(),
            'questions'   =>  FormQuizStudentAnswerMarks::questions(),
        ];

        //Select Option
        $data['instituteFilter']['data'] = Institute::whereIn('id', Quiz::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
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
        $data['study_course_session']['data'] = StudyCourseSession::join((new StudyCourseSchedule())->getTable(), (new StudyCourseSchedule())->getTable() . '.id', (new StudyCourseSession())->getTable() . '.study_course_schedule_id')
            ->orderBy((new StudyCourseSession())->getTable() . '.id', 'DESC')
            ->get([
                (new StudyCourseSchedule)->getTable() . '.*',
                (new StudyCourseSession)->getTable() . '.*',
            ])
            ->map(function ($row) {
                $row['study_program'] = StudyPrograms::where('id', $row->study_program_id)->pluck(app()->getLocale())->first();
                $row['study_course'] = StudyCourse::where('id', $row->study_course_id)->pluck(app()->getLocale())->first();
                $row['study_generation'] = StudyGeneration::where('id', $row->study_generation_id)->pluck(app()->getLocale())->first();
                $row['study_academic_year'] = StudyAcademicYears::where('id', $row->study_academic_year_id)->pluck(app()->getLocale())->first();
                $row['study_semester'] = StudySemesters::where('id', $row->study_semester_id)->pluck(app()->getLocale())->first();
                $row['study_session'] = StudySession::where('id', $row->study_session_id)->pluck(app()->getLocale())->first();

                $row['study'] = $row['study_program'] . ' (' . $row['study_course'] . ' - ' . $row['study_generation'] . ', ' . $row['study_academic_year'] . ', ' . $row['study_semester'] . ', ' . $row['study_session'] . ')';
                $row['name']    =  $row['study'] . ' - ' . DateHelper::convert($row->study_start, 'd-M-Y') . ' - ' . DateHelper::convert($row->study_end, 'd-M-Y');
                return $row;
            });

        $data['student']['data'] = StudentsStudyCourse::join((new StudentsRequest())->getTable(), (new StudentsRequest())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.student_request_id')
            ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsRequest())->getTable() . '.student_id')
            ->join((new StudyCourseSession())->getTable(), (new StudyCourseSession())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.study_course_session_id')
            ->join((new StudyCourseSchedule())->getTable(), (new StudyCourseSchedule())->getTable() . '.id', (new StudyCourseSession())->getTable() . '.study_course_schedule_id')
            ->get([
                (new StudyCourseSchedule())->getTable() . '.*',
                (new StudyCourseSession())->getTable() . '.study_session_id',

                (new StudentsStudyCourse())->getTable() . '.id',
                (new StudentsRequest())->getTable() . '.student_id',
                (new Students())->getTable() . '.first_name_km',
                (new Students())->getTable() . '.last_name_km',
                (new Students())->getTable() . '.first_name_en',
                (new Students())->getTable() . '.last_name_en',
                (new Students())->getTable() . '.gender_id',
                (new Students())->getTable() . '.photo',
                (new Students())->getTable() . '.email',
                (new Students())->getTable() . '.phone',
                (new StudentsStudyCourse())->getTable() . '.created_at',
                (new StudentsStudyCourse())->getTable() . '.photo as photo_crop',
                (new StudentsStudyCourse())->getTable() . '.card',
                (new StudentsStudyCourse())->getTable() . '.qrcode',
                (new StudentsStudyCourse())->getTable() . '.certificate',

            ])->map(function ($row) {

                $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
                $row['photo'] = $row['photo'] ? ImageHelper::site(Students::path('image'), $row['photo']) : ImageHelper::site(Students::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));

                $row['account'] = Users::where('email', $row->email)->where('node_id', $row->student_id)->exists();
                $row['study_program'] = StudyPrograms::where('id', $row->study_program_id)->pluck(app()->getLocale())->first();
                $row['study_course'] = StudyCourse::where('id', $row->study_course_id)->pluck(app()->getLocale())->first();
                $row['study_generation'] = StudyGeneration::where('id', $row->study_generation_id)->pluck(app()->getLocale())->first();
                $row['study_academic_year'] = StudyAcademicYears::where('id', $row->study_academic_year_id)->pluck(app()->getLocale())->first();
                $row['study_semester'] = StudySemesters::where('id', $row->study_semester_id)->pluck(app()->getLocale())->first();
                $row['study_session'] = StudySession::where('id', $row->study_session_id)->pluck(app()->getLocale())->first();

                $row['study'] = $row['study_program'] . ' (' . $row['study_course'] . ' - ' . $row['study_generation'] . ', ' . $row['study_academic_year'] . ', ' . $row['study_semester'] . ', ' . $row['study_session'] . ')';
                $row['name'] .= ' ' . $row['study'];


                return $row;
            });

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view($pages['parent'] . '.index', $data);
    }

    public function list($data, $id = null)
    {
        $table = QuizStudent::join((new Quiz())->getTable(), (new Quiz())->getTable() . '.id', (new QuizStudent())->getTable() . '.quiz_id')
            ->join((new StudentsStudyCourse())->getTable(), (new StudentsStudyCourse())->getTable() . '.id', (new QuizStudent())->getTable() . '.student_study_course_id')
            ->join((new StudentsRequest())->getTable(), (new StudentsRequest())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.student_request_id')
            ->join((new StudyCourseSession())->getTable(), (new StudyCourseSession())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.study_course_session_id')
            ->join((new StudyCourseSchedule())->getTable(), (new StudyCourseSchedule())->getTable() . '.id', (new StudyCourseSession())->getTable() . '.study_course_schedule_id')
            ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsRequest())->getTable() . '.student_id')
            ->orderBy((new QuizStudent())->getTable() . '.id', 'DESC');


        if (request('instituteId')) {
            $table->where((new Quiz())->getTable() . '.institute_id', request('instituteId'));
        }
        if (request('quizId')) {
            $table->where((new QuizStudent())->getTable() . '.quiz_id', request('quizId'));
        }

        $response = $table->get([
            (new StudyCourseSchedule())->getTable() . '.*',
            (new StudyCourseSession())->getTable() . '.study_session_id',
            (new Quiz)->getTable() . '.' . app()->getLocale() . ' as quiz',
            (new Students)->getTable() . '.first_name_km',
            (new Students)->getTable() . '.last_name_km',
            (new Students)->getTable() . '.first_name_en',
            (new Students)->getTable() . '.last_name_en',
            (new Students)->getTable() . '.gender_id',
            (new QuizStudent)->getTable() . '.*',
        ])->map(function ($row) {
            $row['name'] =  $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
            $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
            $row['image'] = $row['image'] ? ImageHelper::site(Quiz::path('image'), $row['image']) : ImageHelper::prefix();
            $row['total_score'] = QuizStudentAnswer::where('quiz_student_id', $row->id)->sum('score') . ' ' . __('score');
            $row['study_program'] = StudyPrograms::where('id', $row->study_program_id)->pluck(app()->getLocale())->first();
            $row['study_course'] = StudyCourse::where('id', $row->study_course_id)->pluck(app()->getLocale())->first();
            $row['study_generation'] = StudyGeneration::where('id', $row->study_generation_id)->pluck(app()->getLocale())->first();
            $row['study_academic_year'] = StudyAcademicYears::where('id', $row->study_academic_year_id)->pluck(app()->getLocale())->first();
            $row['study_semester'] = StudySemesters::where('id', $row->study_semester_id)->pluck(app()->getLocale())->first();
            $row['study_session'] = StudySession::where('id', $row->study_session_id)->pluck(app()->getLocale())->first();

            $row['study'] = $row['study_program'] . ' (' . $row['study_course'] . ' - ' . $row['study_generation'] . ', ' . $row['study_academic_year'] . ', ' . $row['study_semester'] . ', ' . $row['study_session'] . ')';

            $row['action']   = [
                'edit' => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizStudent::path('url') . '/edit/' . $row['id']),
                'view' => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizStudent::path('url') . '/view/' . $row['id']),
                'auto-score' => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizStudent::path('url') . '/auto-score/' . $row['id']),
                'delete' => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizStudent::path('url') . '/delete/' . $row['id']),
            ];
            return $row;
        });

        $data['response'] = [
            'data'      => $response,
            'gender'    => Students::gender($table),
        ];

        $data['view']     = QuizStudent::path('view') . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Quiz Student');
        return $data;
    }

    public function show($data, $id, $type)
    {

        $response = QuizStudent::join((new Quiz())->getTable(), (new Quiz())->getTable() . '.id', (new QuizStudent())->getTable() . '.quiz_id')
            ->join((new StudentsStudyCourse())->getTable(), (new StudentsStudyCourse())->getTable() . '.id', (new QuizStudent())->getTable() . '.student_study_course_id')
            ->join((new StudentsRequest())->getTable(), (new StudentsRequest())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.student_request_id')
            ->join((new StudyCourseSession())->getTable(), (new StudyCourseSession())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.study_course_session_id')
            ->join((new StudyCourseSchedule())->getTable(), (new StudyCourseSchedule())->getTable() . '.id', (new StudyCourseSession())->getTable() . '.study_course_schedule_id')
            ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsRequest())->getTable() . '.student_id')
            ->whereIn((new QuizStudent)->getTable() . '.id', explode(',', $id))
            ->get([
                (new StudyCourseSchedule())->getTable() . '.*',
                (new StudyCourseSession())->getTable() . '.study_session_id',
                (new Quiz)->getTable() . '.' . app()->getLocale() . ' as quiz',
                (new Students)->getTable() . '.first_name_km',
                (new Students)->getTable() . '.last_name_km',
                (new Students)->getTable() . '.first_name_en',
                (new Students)->getTable() . '.last_name_en',
                (new Students)->getTable() . '.gender_id',
                (new Students)->getTable() . '.photo',
                (new QuizStudent)->getTable() . '.*',
            ])->map(function ($row) {
                $row['name'] =  $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
                $row['photo'] = $row['photo'] ? ImageHelper::site(Students::path('image'), $row['photo']) : ImageHelper::site(Students::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));

                $row['image'] = $row['image'] ? ImageHelper::site(Quiz::path('image'), $row['image']) : ImageHelper::prefix();
                $row['total_score'] = QuizStudentAnswer::where('quiz_student_id', $row->id)->sum('score');
                $row['study_program'] = StudyPrograms::where('id', $row->study_program_id)->pluck(app()->getLocale())->first();
                $row['study_course'] = StudyCourse::where('id', $row->study_course_id)->pluck(app()->getLocale())->first();
                $row['study_generation'] = StudyGeneration::where('id', $row->study_generation_id)->pluck(app()->getLocale())->first();
                $row['study_academic_year'] = StudyAcademicYears::where('id', $row->study_academic_year_id)->pluck(app()->getLocale())->first();
                $row['study_semester'] = StudySemesters::where('id', $row->study_semester_id)->pluck(app()->getLocale())->first();
                $row['study_session'] = StudySession::where('id', $row->study_session_id)->pluck(app()->getLocale())->first();

                $row['study'] = $row['study_program'] . ' (' . $row['study_course'] . ' - ' . $row['study_generation'] . ', ' . $row['study_academic_year'] . ', ' . $row['study_semester'] . ', ' . $row['study_session'] . ')';

                $row['quiz_answered'] = QuizStudentAnswer::where('quiz_student_id', $row->id)
                    ->get()->map(function ($row) {
                        $row['answer_limit'] = QuizAnswer::where('quiz_question_id', $row->quiz_question_id)
                            ->where('correct_answer', 1)->count();
                        $row['questions'] = QuizQuestion::where('id', $row->quiz_question_id)->get()->first();
                        $row['answers'] = QuizAnswer::where('quiz_question_id', $row->quiz_question_id)->get();
                        $row['correct'] = false;
                        $row['correct_marks'] = 0;

                        if ($row['questions']['quiz_answer_type_id'] == 1) {
                            $quizAnswer = QuizAnswer::where('id', $row->answered)->get()->first();

                            if ($quizAnswer->correct_answer) {
                                $row['correct'] = true;
                                $row['correct_marks'] = $row['questions']['score'];
                            };
                        } elseif ($row['questions']['quiz_answer_type_id'] == 2) {
                            $correct = [];
                            $correct_marks = 0;
                            foreach (explode(',', $row->answered) as $answer) {

                                $quizAnswer = QuizAnswer::where('id', $answer)->get()->first();
                                if ($quizAnswer->correct_answer) {
                                    $correct[] = $quizAnswer->correct_answer;
                                    $correct_marks += $row['questions']['score'] / $row['answer_limit'];
                                }
                            }

                            if ($row['answer_limit'] == count($correct)) {
                                $row['correct'] = true;
                                $row['correct_marks'] = $row['questions']['score'];
                            } else {
                                $row['correct'] = false;
                                $row['correct_marks'] = $correct_marks;
                            }
                        } elseif ($row['questions']['quiz_answer_type']['id'] == 3) {
                            if ($row['questions']['answer'][0]['answer'] == $row->answered) {
                                $row['correct'] = true;
                                $row['correct_marks'] = $row['questions']['score'];
                            }
                        }
                        return $row;
                    });


                $row['action']   = [
                    'edit' => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizStudent::path('url') . '/edit/' . $row['id']),
                    'view' => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizStudent::path('url') . '/view/' . $row['id']),
                    'auto-score' => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizStudent::path('url') . '/auto-score/' . $row['id']),
                    'delete' => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizStudent::path('url') . '/delete/' . $row['id']),
                ];
                return $row;
            });
        $data['response'] = [
            'data'      => $response,
        ];
        $data['formData'] = $response;
        $data['listData'] =  $response->map(function ($row) {
            return [
                'id'  => $row->id,
                'name'  => $row->name,
                'image'  => $row->photo,
                'action'  => [
                    'edit'   => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizStudent::path('url') . '/edit/' . $row['id']),
                ],
            ];
        });
        $data['formAction'] = '/' . $type . '/' . $id;
        $data['title']    = Users::role(app()->getLocale()) . '|' . __('Quiz Student');
        return $data;
    }


    public function report()
    {
        request()->merge([
            'size'  => request('size', 'A4'),
            'layout'  => request('layout', 'portrait'),
        ]);

        config()->set('app.title', __('List Quiz'));
        config()->set('pages.parent', QuizStudent::path('view'));


        $data['instituteFilter']['data']           = Institute::whereIn('id', Quiz::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
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
                $row['image'] = $row['image'] ? ImageHelper::site(Quiz::path('image'), $row['image']) : ImageHelper::prefix();
                return $row;
            });

        $table = QuizStudent::join((new Quiz())->getTable(), (new Quiz())->getTable() . '.id', (new QuizStudent())->getTable() . '.quiz_id')
            ->join((new StudentsStudyCourse())->getTable(), (new StudentsStudyCourse())->getTable() . '.id', (new QuizStudent())->getTable() . '.student_study_course_id')
            ->join((new StudentsRequest())->getTable(), (new StudentsRequest())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.student_request_id')
            ->join((new StudyCourseSession())->getTable(), (new StudyCourseSession())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.study_course_session_id')
            ->join((new StudyCourseSchedule())->getTable(), (new StudyCourseSchedule())->getTable() . '.id', (new StudyCourseSession())->getTable() . '.study_course_schedule_id')
            ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsRequest())->getTable() . '.student_id');

        $questions = QuizQuestion::join((new Quiz)->getTable(), (new Quiz)->getTable() . '.id', (new QuizQuestion)->getTable() . '.quiz_id');


        if (request('instituteId')) {
            $table->where((new Quiz())->getTable() . '.institute_id', request('instituteId'));
        }

        if (request('quizId')) {
            $table->where((new QuizStudent())->getTable() . '.quiz_id', request('quizId'));
            $questions->where('quiz_id', request('quizId'));
        }

        $response = $table->get([
            (new StudyCourseSchedule())->getTable() . '.*',
            (new StudyCourseSession())->getTable() . '.study_session_id',
            (new Quiz)->getTable() . '.' . app()->getLocale() . ' as quiz',
            (new Students)->getTable() . '.first_name_' . app()->getLocale() . ' as first_name',
            (new Students)->getTable() . '.last_name_' . app()->getLocale() . ' as last_name',
            (new Students)->getTable() . '.gender_id',
            (new Students)->getTable() . '.photo',
            (new QuizStudent)->getTable() . '.*',
        ])->map(function ($row) use ($questions) {
            $row['name'] =  $row->first_name . ' ' . $row->last_name;
            $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
            $row['photo'] = $row['photo'] ? ImageHelper::site(Students::path('image'), $row['photo']) : ImageHelper::site(Students::path('image'), ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));

            $row['image'] = $row['image'] ? ImageHelper::site(Quiz::path('image'), $row['image']) : ImageHelper::prefix();
            $row['total_score'] = QuizStudentAnswer::where('quiz_student_id', $row->id)->sum('score');
            $row['study_program'] = StudyPrograms::where('id', $row->study_program_id)->pluck(app()->getLocale())->first();
            $row['study_course'] = StudyCourse::where('id', $row->study_course_id)->pluck(app()->getLocale())->first();
            $row['study_generation'] = StudyGeneration::where('id', $row->study_generation_id)->pluck(app()->getLocale())->first();
            $row['study_academic_year'] = StudyAcademicYears::where('id', $row->study_academic_year_id)->pluck(app()->getLocale())->first();
            $row['study_semester'] = StudySemesters::where('id', $row->study_semester_id)->pluck(app()->getLocale())->first();
            $row['study_session'] = StudySession::where('id', $row->study_session_id)->pluck(app()->getLocale())->first();

            $row['study'] = $row['study_program'] . ' (' . $row['study_course'] . ' - ' . $row['study_generation'] . ', ' . $row['study_academic_year'] . ', ' . $row['study_semester'] . ', ' . $row['study_session'] . ')';

            $row['questions'] = $questions->get([
                (new QuizQuestion)->getTable() . '.*',
            ])->map(function ($r) use ($row) {
                $r['points'] = QuizStudentAnswer::where('quiz_student_id', $row->id)
                    ->where('quiz_question_id', $r->id)->pluck('score')->first();

                if ($r['points']) {
                    $r['points'] .= ' ' . __('score');
                } else {
                    $r['points'] = '0 ' . __('score');
                }
                return $r;
            });

            $row['total_score'] = QuizStudentAnswer::join((new QuizStudent())->getTable(), (new QuizStudent())->getTable() . '.id', (new QuizStudentAnswer())->getTable() . '.quiz_student_id')
                ->where('quiz_student_id', $row->id)
                ->where('quiz_id', request('quizId'))
                ->sum('score') . ' ' . __('score');

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
            'genders'    => Students::gender($table),
            'questions' => $questions->get(),
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
        config()->set('pages.title', __('List Quiz'));
        return view(QuizStudent::path('view') . '.includes.report.index', $data);
    }
}
