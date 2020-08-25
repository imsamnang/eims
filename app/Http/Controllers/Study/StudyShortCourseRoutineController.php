<?php

namespace App\Http\Controllers\Study;

use App\Helpers\Encryption;
use App\Models\App as AppModel;
use App\Models\Users;
use App\Models\Languages;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Models\SocailsMedia;
use App\Models\StudyShortCourseRoutine;
use App\Http\Controllers\Controller;
use App\Models\Days;
use App\Models\Staff;
use App\Models\StudyClass;
use App\Models\StudyShortCourseSession;

class StudyShortCourseRoutineController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
        AppModel::setConfig();
       Languages::setConfig(); AppModel::setConfig();  SocailsMedia::setConfig();
        view()->share('breadcrumb', []);
    }


    public function index($param1 = 'list', $param2 = null, $param3 = null)
    {
        $data['study_course_session'] = StudyShortCourseSession::getData();

        $data['teacher'] = Staff::getData();
        $data['study_class'] = StudyClass::getData();
        $data['days'] = Days::getData();

        $data['formData']       = [];
        $data['formName']       = 'study/' . StudyShortCourseRoutine::path('url');
        $data['formAction']     = '/add';
        $data['listData']       = array();
        if ($param1 == 'list') {

            $data = $this->list($data, $param1);
        } elseif (strtolower($param1) == 'list-datatable') {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                return StudyShortCourseRoutine::getDataTable();
            } else {
                $data = $this->list($data, $param1);
            }
        } elseif ($param1 == 'add') {

            if (request()->ajax()) {
                if (request()->method() === 'POST') {

                    return StudyShortCourseRoutine::addToTable();
                }
            }

            $data = $this->add($data);
        } elseif ($param1 == 'edit') {
            $id = request('id', $param2);
            if (request()->method() === 'POST') {
                return StudyShortCourseRoutine::updateToTable($id);
            }

            $data = $this->edit($data, $id);
        } elseif ($param1 == 'view') {
            $id = request('id', $param2);

            $data = $this->view($data, $id);
        } elseif ($param1 == 'delete') {
            $id = request('id', $param2);
            return StudyShortCourseRoutine::deleteFromTable($id);
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
            'parent'     => StudyShortCourseRoutine::path('view'),
            'view'       => $data['view'],
        );

        $pages['form']['validate'] = StudyShortCourseRoutine::validate();

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view($pages['parent'] . '.index', $data);
    }

    public function list($data, $param1)
    {
        $data['response'] = StudyShortCourseRoutine::getData(null, 10);
        $data['view']     = StudyShortCourseRoutine::path('view') . '.includes.list.index';
        $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('List Study Short Course Routine');
        return $data;
    }

    public function add($data)
    {
        $data['view']      = StudyShortCourseRoutine::path('view') . '.includes.form.index';
        $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('Add Study Short Course Routine');
        $data['metaImage'] = asset('assets/img/icons/register.png');
        $data['metaLink']  = url(Users::role() . '/add/');
        return $data;
    }

    public function edit($data, $id)
    {

        $response = StudyShortCourseRoutine::getData(Encryption::decode($id)['stu_sh_c_session_id']);
        $data['view']       = StudyShortCourseRoutine::path('view') . '.includes.form.index';
        $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('Edit Study Short Course Routine');
        $data['metaImage']  = asset('assets/img/icons/register.png');
        $data['metaLink']   = url(Users::role() . '/edit/' . $id);
        $data['formData']   = $response['data'][0];
        $data['listData']   = $response['pages']['listData'];
        $data['formAction'] = '/edit/' . $response['data'][0]['id'];
        return $data;
    }

    public function view($data, $id)
    {
        $response = StudyShortCourseRoutine::getData(Encryption::decode($id)['stu_sh_c_session_id'], true);
        $data['view']       = StudyShortCourseRoutine::path('view') . '.includes.view.index';
        $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('View Study Short Course Routine');
        $data['metaImage']  = asset('assets/img/icons/register.png');
        $data['metaLink']   = url(Users::role() . '/view/' . $id);
        $data['formData']   = $response['data'][0];
        $data['listData']   = $response['pages']['listData'];
        $data['formAction'] = '/view/' . $response['data'][0]['id'];
        return $data;
    }
}
