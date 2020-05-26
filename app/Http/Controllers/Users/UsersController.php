<?php

namespace App\Http\Controllers\users;

use App\Models\App;
use App\Models\Roles;
use App\Models\Staff;
use App\Models\Users;
use App\Models\Institute;
use App\Models\Languages;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Helpers\Translator;
use App\Models\SocailsMedia;
use App\Models\AttendancesType;
use App\Http\Requests\FormUsers;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Requests\FormStaff;
use App\Models\BloodGroup;
use App\Models\Marital;
use App\Models\MotherTong;
use App\Models\Nationality;
use App\Models\Students;
use App\Rules\KhmerCharacter;
use Illuminate\Support\Facades\Auth;

class usersController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
        App::setConfig();
        SocailsMedia::setConfig();
        Languages::setConfig();
    }


    public function index($param1 = null, $param2 = null, $param3 = null)
    {
        request()->merge([
            'ref'   => Users::$path['url'],
        ]);
        $data['institute'] = Institute::getData(request('instituteId', 'null'));
        $data['student'] = Students::getData(request('studentId', 'null'));
        $data['staff'] = Staff::getData(request('staffId', 'null'));


        $data['listData']       = array();
        if (Auth::user()->role_id == 9) {
            if ($param1 == null || $param1 == 'dashboard') {
                $data = $this->dashboard($data);
            } elseif ($param1 == 'profile') {
                $view = new ProfileController;
                return $view->index($param2, $param3);
            } elseif ($param1 == 'register') {
               if(request()->method() == 'POST'){
                   request()->merge([
                       'institute'  => 1
                   ]);
                    return Users::register();
               }
            }else{
                abort(404);
            }
        } else {
            $data['role']      = Roles::getData(request('roleId'));
            $data['formData']  = array(
                'profile' => asset('/assets/img/icons/image.jpg'),
            );
            $data['formName'] = users::$path['url'];
            $data['formAction'] = '/add';
            if ($param1 == null || $param1 == 'list') {
                $data = $this->list($data);
            } elseif ($param1 == 'add') {
                if (request()->method() === 'POST') {
                    return users::addToTable();
                }
                $data = $this->add($data);
            } elseif ($param1 == 'edit') {
                if ($param2) {
                    $id = $param2;
                } else if (request('id')) {
                    $id = request('id');
                }

                if (request()->method() === 'POST') {
                    return users::updateToTable($id);
                }


                $data  = $this->show($data, $id, $param1);
            } elseif (strtolower($param1) == 'list-datatable') {
                if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                    return  Users::getDataTable();
                } else {
                    $data = $this->list($data);
                }
            } elseif ($param1 == 'view') {
                if ($param2) {
                    $id = $param2;
                } else if (request('id')) {
                    $id = request('id');
                }

                $data = $this->show($data, $id, $param1);
            } elseif ($param1 == 'delete') {

                if ($param2) {
                    $id = $param2;
                } else if (request('id')) {
                    $id = request('id');
                }
                return users::deleteFromTable($id);
            } else {
                abort(404);
            }
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
            'parent'     => Users::$path['view'],
            'view'       => $data['view'],
        );
        if (Auth::user()->role_id == 9) {
            $pages['form']['validate'] = [
                'rules'       =>  FormUsers::rulesField2(),
                'attributes'  =>  FormStaff::attributeField() + ['teacher_or_student' => Translator::phrase('teacher. .or. .student')],
                'messages'    =>  FormStaff::customMessages(),
                'questions'   =>  FormStaff::questionField(),
            ];
        } else {
            $pages['form']['validate'] = [
                'rules'       =>  FormUsers::rulesField(),
                'attributes'  =>  FormUsers::attributeField(),
                'messages'    =>  FormUsers::customMessages(),
                'questions'   =>  FormUsers::questionField(),
            ];
        }

        if ($param1 == 'edit') {
            $rule = [];
            foreach ($pages['form']['validate']['rules'] as $key => $value) {
                if ($key != 'password')
                    $rule[$key] = $value;
            }
            $pages['form']['validate']['rules'] =  $rule;
        }

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view($pages['parent'] . '.index', $data);
    }

    public function dashboard($data)
    {
        $data['nationality']          = Nationality::getData();
        $data['mother_tong']          = MotherTong::getData();
        $data['marital']              = Marital::getData();
        $data['blood_group']              = BloodGroup::getData();
        $data['blood_group']              = BloodGroup::getData();

        $data['attendances_type']     = AttendancesType::getData();
        $data['formAction']          = 'register';
        $data['formName']            = '';
        $data['metaImage']           = asset('assets/img/icons/add.png');
        $data['metaLink']            = url(Users::role() . '/add');
        $data['formData']            = array(
            'photo'                  => asset('/assets/img/user/male.jpg'),
        );

        $km = new KhmerCharacter;
        $name = explode(' ', Auth::user()->name);
        $data['formData']['last_name_km'] = '';
        $data['formData']['last_name_en'] = '';
        foreach ($name as $key => $value) {

            if ($key == 0) {
                if ($km->passes('first_name_km', $value)) {
                    $data['formData']['first_name_km'] = $value;
                } else {
                    $data['formData']['first_name_en'] = $value;
                }
            } else {
                if ($km->passes('last_name_km', $value)) {
                    $data['formData']['last_name_km'] .= $value;
                } else {
                    $data['formData']['last_name_en'] .= $value;
                }
            }
        }



        $data['title'] = Translator::phrase(Users::role(app()->getLocale()) . '. | .dashboard');
        $data['view']    = 'Users.includes.dashboard.index';
        return $data;
    }

    public function list($data)
    {
        $data['response'] =  users::getData(null, null, 10);
        $data['view']     =  users::$path['view'] . '.includes.list.index';
        $data['title']    =  Translator::phrase(Users::role(app()->getLocale()) . '. | .list.' . str_replace('-', '_', $data['formName']));
        return $data;
    }

    public function add($data)
    {
        $data['view']      = users::$path['view'] . '.includes.form.index';
        $data['title']     = Translator::phrase(Users::role(app()->getLocale()) . '. | .add.' . str_replace('-', '_', $data['formName']));
        $data['metaImage'] = asset('assets/img/icons/register.png');
        $data['metaLink']  = url(Users::role() . '/add/');
        return $data;
    }

    public function show($data, $id, $type)
    {
        $response           = Users::getData($id);
        $data['view']       = Users::$path['view'] . '.includes.form.index';
        $data['title']      = Translator::phrase(Users::role(app()->getLocale()) . '. | .' . $type . '.' . str_replace('-', '_', $data['formName']));
        $data['metaImage']  = asset('assets/img/icons/register.png');
        $data['metaLink']   = url(Users::role() . '/' . $type . '/' . $id);
        $data['formData']   = $response['data'][0];
        $data['listData']   = $response['pages']['listData'];
        $data['formAction'] = '/' . $type . '/' . $response['data'][0]['id'];
        $data['institute']  = Institute::getData($response['data'][0]['institute']['id']);
        $data['role']       = Roles::getData($response['data'][0]['role']['id']);
        return $data;
    }
}
