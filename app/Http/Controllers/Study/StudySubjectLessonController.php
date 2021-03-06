<?php

namespace App\Http\Controllers\Study;

use App\Models\App as AppModel;
use App\Models\Users;
use App\Models\Languages;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Models\SocailsMedia;
use App\Models\StudySubjectLesson;
use App\Http\Controllers\Controller;
use App\Models\StaffTeachSubject;


class StudySubjectLessonsController extends Controller
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



        request()->merge([
            'ref'   => StudySubjectLesson::path('url')
        ]);

        $data['staff_teach_subject']['data'] = StaffTeachSubject::get();
        $data['formData'] = array(
            ['image' => asset('/assets/img/icons/pdf.png'),]
        );
        $data['formName'] = 'study/' . StudySubjectLesson::path('url');
        $data['formAction'] = '/add';
        $data['listData']       = array();
        if ($param1 == 'list') {
            $data = $this->list($data);

        } elseif ($param1 == 'grid') {
            $data = $this->grid($data);
        } elseif ($param1 == 'add') {

            if (request()->method() === 'POST') {
                return StudySubjectLesson::addToTable();
            }

            $data = $this->add($data);
        } elseif ($param1 == 'edit') {

            $id = request('id', $param2);
            if (request()->method() === 'POST') {
                return StudySubjectLesson::updateToTable($id);
            }

            $data = $this->show($data, $id, $param1);
        } elseif ($param1 == 'view') {
            $id = request('id', $param2);
            $data = $this->show($data, $id, $param1);
        } elseif ($param1 == 'delete') {

            $id = request('id', $param2);

            return StudySubjectLesson::deleteFromTable($id);
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
            'parent'     => StudySubjectLesson::path('view'),
            'view'       => $data['view'],
        );
        $pages['form']['validate'] = StudySubjectLesson::validate();

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);

        return view($pages['parent'] . '.index', $data);
    }

    public function list($data, $id = null)
    {
        $data['view']     = StudySubjectLesson::path('view') . '.includes.list.index';
        $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('List Study Lesson');
        return $data;
    }
    public function grid($data)
    {
        $data['response'] =  StudySubjectLesson::getData(null, null, 10);
        $data['view']     = StudySubjectLesson::path('view') . '.includes.grid.index';
        $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('Grid Study Lesson');
        return $data;
    }


    public function add($data)
    {
        $data['view']      = StudySubjectLesson::path('view') . '.includes.form.index';
        $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('Add Study Lesson');
        $data['metaImage'] = asset('assets/img/icons/register.png');
        $data['metaLink']  = url(Users::role() . '/add/');
        return $data;
    }



    public function show($data, $id, $type)
    {
        $response = StudySubjectLesson::getData($id, true);
        $data['view']       = StudySubjectLesson::path('view') . '.includes.form.index';
        $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('Study Lesson');
        $data['metaImage']  = asset('assets/img/icons/' . $type . '.png');
        $data['metaLink']   = url(Users::role() . '/' . $type . '/' . $id);
        $data['formData']   = $response['data'][0];
        $data['listData']   = $response['pages']['listData'];
        $data['formAction'] = '/' . $type . '/' . $response['data'][0]['id'];

        return $data;
    }
}
