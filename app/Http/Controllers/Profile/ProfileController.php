<?php

namespace App\Http\Controllers\Profile;

use App\Models\App;
use App\Models\Users;
use App\Models\Languages;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;

use App\Helpers\ImageHelper;
use App\Models\SocailsMedia;
use App\Http\Requests\FormProfile;
use App\Http\Controllers\Controller;
use App\Http\Requests\FormPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class ProfileController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
        App::setConfig();
       Languages::setConfig(); App::setConfig();  SocailsMedia::setConfig();
        view()->share('breadcrumb', []);
    }


    public function index($param1 = 'general', $param2 = null, $param3 = null)
    {
        $data['formData'] = array(
            'image' => asset('/assets/img/icons/image.jpg'),
        );
        $data['formName'] = 'profile';
        $data['formAction'] = '/edit';
        $data['listData']       = array();

        if (strtolower($param1) == null || strtolower($param1) == 'general') {
            if (request()->method() == "POST") {
                $validator          = Validator::make(request()->all(), FormProfile::rulesField(), FormProfile::customMessages(), FormProfile::attributeField());
                if ($validator->fails()) {
                    $response       = array(
                        'success'   => false,
                        'errors'    => $validator->getMessageBag(),
                    );
                } else {
                    $value = [
                        'name'        => trim(request('name')),
                        'phone'       => trim(request('phone')),
                        'email'       => trim(request('email')),
                        'address'     => trim(request('address')),
                        'location'    => trim(request('location')),
                    ];

                    $update = Users::where('id', Auth::user()->id)->update($value);
                    if ($update) {
                        if (request()->hasFile('profile')) {
                            $image      = request()->file('profile');
                            Users::updateImageToTable(Auth::user()->id, ImageHelper::uploadImage($image, Users::$path['image'], null, null, true));
                        }
                        $response       = array(
                            'success'   => true,
                            'type'      => 'update',
                            'data'      => Users::getData(Auth::user()->id),
                            'message'   => array(
                                'title' => __('Success'),
                                'text'  => __('Update Successfully'),
                                'button'   => array(
                                    'confirm' => __('Ok'),
                                    'cancel'  => __('Cancel'),
                                ),
                            ),
                        );
                    }
                }
                return $response;
            }
            $data = $this->general($data);
        } elseif (strtolower($param1) == 'password') {
            if (request()->method() == "POST") {
                $validator          = Validator::make(request()->all(), FormPassword::rulesField(), FormPassword::customMessages(), FormPassword::attributeField());
                if ($validator->fails()) {
                    $response       = array(
                        'success'   => false,
                        'errors'    => $validator->getMessageBag(),
                    );
                } else {
                    if (Hash::check(request('old_password'), Auth::user()->password)) {
                        if (Hash::check(request('password'), Auth::user()->password)) {
                            $response       = array(
                                'success'   => false,
                                'message'   => array(
                                    'title' => __('Error'),
                                    'text'  => __('New password is same old password'),
                                    'button'   => array(
                                        'confirm' => __('Ok'),
                                        'cancel'  => __('Cancel'),
                                    ),

                                ),
                            );
                        } else {
                            if (request('password') == request('password_confirmation')) {
                                $update = Users::where('id', Auth::user()->id)->update([
                                    'password'  => Hash::make(request('password'))
                                ]);
                                if ($update)
                                    $response       = array(
                                        'success'   => true,
                                        'message'   => array(
                                            'title' => __('Success'),
                                            'text'  => __('Update Successfully'),
                                            'button'   => array(
                                                'confirm' => __('Ok'),
                                                'cancel'  => __('Cancel'),
                                            ),

                                        ),
                                    );
                            } else {
                                $response       = array(
                                    'success'   => false,
                                    'message'   => array(
                                        'title' => __('Error'),
                                        'text'  => __('Password and password confirmation not match'),
                                        'button'   => array(
                                            'confirm' => __('Ok'),
                                            'cancel'  => __('Cancel'),
                                        ),

                                    ),
                                );
                            }
                        }
                    } else {
                        $response       = array(
                            'success'   => false,
                            'message'   => array(
                                'title' => __('Error'),
                                'text'  => __('Old password incrrent'),
                                'button'   => array(
                                    'confirm' => __('Ok'),
                                    'cancel'  => __('Cancel'),
                                ),

                            ),
                        );
                    }
                }
                return $response;
            }
            $data = $this->password($data);
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
            'parent'     => 'Profile',
            'view'       => $data['view'],
        );

        $pages['form']['validate'] = [
            'rules'       => (strtolower($param1) == 'password') ? FormPassword::rulesField() :  FormProfile::rulesField(),
            'attributes'  => (strtolower($param1) == 'password') ? FormPassword::attributeField() :  FormProfile::attributeField(),
            'messages'    => (strtolower($param1) == 'password') ? FormPassword::customMessages() :  FormProfile::customMessages(),
            'questions'   => (strtolower($param1) == 'password') ? FormPassword::questionField() :  FormProfile::questionField(),
        ];

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view($pages['parent'] . '.index', $data);
    }

    public function general($data)
    {
        $response           = Users::getUsers(Auth::user()->id);
        $data['view']       = 'Profile.includes.form.general.index';
        $data['title']      = Users::role(app()->getLocale()) . ' | ' . __('General');
        $data['metaImage']  = asset('assets/img/icons/profile.png');
        $data['metaLink']   = url(Users::role() . '/profile/');
        $data['formData']   = $response['data'][0];
        $data['formAction'] = '/general/';
        return $data;
    }

    public function password($data)
    {
        $response           = Users::getUsers(Auth::user()->id);
        $data['view']       = 'Profile.includes.form.password.index';
        $data['title']      = Users::role(app()->getLocale()) . ' | ' . __('Password');
        $data['metaImage']  = asset('assets/img/icons/profile.png');
        $data['metaLink']   = url(Users::role() . '/password/');
        $data['formData']   = $response['data'][0];
        $data['formAction'] = '/password/';
        return $data;
    }
}
