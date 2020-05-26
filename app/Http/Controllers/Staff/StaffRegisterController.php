<?php

namespace App\Http\Controllers\Staff;

use App\Models\App;
use App\Models\Roles;
use App\Models\Staff;
use App\Models\Users;
use App\Models\Gender;
use App\Models\Marital;
use App\Models\Communes;
use App\Models\Villages;
use App\Models\Districts;
use App\Models\Institute;
use App\Models\Languages;
use App\Models\Provinces;
use App\Models\MotherTong;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Helpers\Translator;
use App\Models\Nationality;
use App\Models\StaffStatus;
use App\Models\SocailsMedia;
use App\Http\Requests\FormStaff;
use App\Models\StaffCertificate;
use App\Models\StaffDesignations;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Staff\StaffCertificateController;
use App\Http\Controllers\Staff\StaffDesignationController;
use App\Models\BloodGroup;
use App\Models\StaffTeachSubject;
use App\Models\Students;
use Illuminate\Support\Facades\Auth;

class StaffRegisterController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
        App::setConfig();
        SocailsMedia::setConfig();
        Languages::setConfig();
    }

    public function index($param1 = null, $param2 = null, $param3 = null, $param4 = null)
    {

        // $data['institute']           = Institute::getData(1);
        // $data['status']              = StaffStatus::getData();
        // $data['designation']         = StaffDesignations::getData();
        $data['formData']            = array(
            'photo'                  => asset('/assets/img/user/male.jpg'),
        );

        $data['mother_tong']         = MotherTong::getData();
        $data['gender']              = Gender::getData();
        $data['nationality']         = Nationality::getData();
        $data['marital']             = Marital::getData();
        $data['blood_group']         = BloodGroup::getData();
        $data['provinces']           = Provinces::getData();
        $data['districts']           = Districts::getData('null');
        $data['communes']            = Communes::getData('null');
        $data['villages']            = Villages::getData('null');
        $data['staff_certificate']   = StaffCertificate::getData();
        $data['curr_districts']      = $data['districts'];
        $data['curr_communes']       = $data['communes'];
        $data['curr_villages']       = $data['villages'];
        $data['formAction']          = '';
        $data['formName']            = '';
        $data['title']               = Translator::phrase(Users::role(app()->getLocale()) . '. | .' . $data['formName']);
        $data['metaImage']           = asset('assets/img/icons/' . $param1 . '.png');
        $data['metaLink']            = url(Users::role() . '/' . $param1);





        $data['listData']            = array();


        if (request()->method() == 'POST') {
            request()->merge([
                'institute' => 1,
                'designation'   => 2,
                'status'   =>  2,
            ]);
            return Staff::addToTable();
        } else {
            $data = $this->add($data);
        }


        MetaHelper::setConfig(
            [
                'title'       => $data['title'],
                'author'      => config('app.name'),
                'keywords'    => '',
                'description' => '',
                'link'        => $data['metaLink'],
                'image'       => $data['metaImage']
            ]
        );

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
            'form'       => FormHelper::form($data['formData'], $data['formName'], $data['formAction'], 'staff-register'),
            'parent'     => 'StaffRegister',
            'modal'      => 'StaffRegister.includes.modal.index',
            'view'       => $data['view'],
        );

        $rules = FormStaff::rulesField();
        unset($rules['institute']);
        unset($rules['designation']);
        unset($rules['status']);

        $pages['form']['validate'] = [
            'rules'       => $rules,
            'attributes'  => FormStaff::attributeField(),
            'messages'    => FormStaff::customMessages(),
            'questions'   => FormStaff::questionField(),
        ];


        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);


        return view('StaffRegister.index', $data);
    }



    public function add($data)
    {
        $data['view']  = 'StaffRegister.includes.form.index';
        $data['title'] = Translator::phrase(Users::role(app()->getLocale()) . '. | .add.' . $data['formName']);
        $data['metaImage'] = asset('assets/img/icons/register.png');
        $data['metaLink']  = url(Users::role() . '/add/');
        return $data;
    }
}
