<?php

namespace App\Http\Controllers\users;

use Carbon\Carbon;
use App\Models\App;
use App\Models\Roles;
use App\Models\Staff;
use App\Models\Users;
use App\Models\Marital;
use App\Models\Students;
use App\Models\Institute;
use App\Models\Languages;

use App\Models\BloodGroup;
use App\Models\MotherTong;
use App\Helpers\DateHelper;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Models\Nationality;
use App\Helpers\ImageHelper;
use App\Models\SocailsMedia;
use App\Rules\KhmerCharacter;
use App\Models\AttendancesType;
use App\Http\Requests\FormStaff;
use App\Http\Requests\FormUsers;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Profile\ProfileController;

class usersController extends Controller
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
        request()->merge([
            'ref'   => Users::$path['url'],
        ]);

        $data['listData']       = array();
        if (Auth::user()->role_id == 9) {
            if ($param1 == null || $param1 == 'dashboard') {
                $data = $this->dashboard($data);
            } elseif ($param1 == 'profile') {
                $view = new ProfileController;
                return $view->index($param2, $param3);
            } elseif ($param1 == 'register') {
                if (request()->method() == 'POST') {
                    return Users::register();
                }
            } else {
                abort(404);
            }
        } else {
            $data['role']      = Roles::getData(request('roleId'));
            $data['formData']  = array(
                ['profile' => asset('/assets/img/icons/image.jpg'),]
            );
            $data['formName'] = users::$path['url'];
            $data['formAction'] = '/add';
            if ($param1 == null || $param1 == 'list') {
                if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                    return Users::getData(null, null, 10, request('search'));
                } else {
                    $data = $this->list($data);
                }
            } elseif ($param1 == 'add') {
                if (request()->method() === 'POST') {
                    return users::addToTable();
                }
                $data = $this->show($data, null, $param1);
            } elseif ($param1 == 'edit') {
                $id = request('id', $param2);
                if (request()->method() === 'POST') {
                    return users::updateToTable($id);
                }
                $data  = $this->show($data, $id, $param1);
                $data['title']   = Users::role(app()->getLocale()) . ' | ' . __('Edit Users');
            } elseif (strtolower($param1) == 'list-datatable') {
                if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                    return  Users::getDataTable();
                } else {
                    $data = $this->list($data);
                }
            } elseif ($param1 == 'view') {
                $id = request('id', $param2);
                $data = $this->show($data, $id, $param1);
                $data['title']   = Users::role(app()->getLocale()) . ' | ' . __('View Users');
            } elseif ($param1 == 'report') {
                return $this->report();
            } elseif ($param1 == 'delete') {
                $id = request('id', $param2);
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
                'attributes'  =>  FormStaff::attributeField() + ['teacher_or_student' => __('Teacher or Student')],
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

        //Select Option

        $data['institute']['data']  = Institute::get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
            $row['image']   = ImageHelper::site(Institute::$path['image'], $row->logo);
            return $row;
        });
        $data['instituteFilter']['data'] = Institute::whereIn('id', Users::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::$path['image'], $row->logo);
                return $row;
            });
        $data['roleFilter']['data'] = Roles::whereIn('id', Users::groupBy('role_id')->pluck('role_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Roles::$path['image'], $row->image);
                return $row;
            });
        $data['roles']['data']  = Roles::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = ImageHelper::site(Institute::$path['image'], $row->image);
            return $row;
        });


        $data['student']['data'] = Students::whereHas('institute', function ($query) {
            $query->where('id', request('instituteId'));
        })
            ->whereNotIn('id', Users::whereNotNull('node_id')->where('role_id', Students::$path['roleId'])->pluck('node_id'))
            ->get(['id', 'first_name_km', 'last_name_km', 'first_name_en', 'last_name_en', 'photo'])->map(function ($row) {
                $row['name']  = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                $row['photo']  = ImageHelper::site(Students::$path['image'], $row['photo']);
                return $row;
            });
        $data['staff']['data'] = Staff::whereHas('institute', function ($query) {
            $query->where('institute_id', request('instituteId'));
            $query->whereNotIn('staff_id', Users::whereNotNull('node_id')->whereNotIn('role_id', [1, 6, 7, 9, 10])->pluck('node_id'));
        })->get(['id', 'first_name_km', 'last_name_km', 'first_name_en', 'last_name_en', 'photo'])->map(function ($row) {
            $row['name']  = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
            $row['photo']  = ImageHelper::site(Staff::$path['image'], $row['photo']);
            return $row;
        });
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



        $data['title']   = Users::role(app()->getLocale()) . ' | ' . __('Dashboard');
        $data['view']    = 'Users.includes.dashboard.index';
        return $data;
    }

    public function list($data)
    {

        $table = Users::whereHas('institute', function ($query) {
            $query->where('id', request('instituteId'));
        });

        if (request('roleId')) {
            $table->where('role_id', request('roleId'));
        }

        $data['response']['data'] = $table->orderBy('id', 'DESC')->get()->map(function ($row) {
            $row['profile'] = ImageHelper::site(Users::$path['image'], $row['profile']);
            $row['role'] = Roles::where('id', $row->role_id)->pluck(app()->getLocale())->first();

            $row['action']        = [
                'edit' => url(Users::role() . '/' . Users::$path['url'] . '/edit/' . $row['id']),
                'view' => url(Users::role() . '/' . Users::$path['url'] . '/view/' . $row['id']),
                'delete' => url(Users::role() . '/' . Users::$path['url'] . '/delete/' . $row['id']),
            ];
            return $row;
        });

        $data['view']     =  Users::$path['view'] . '.includes.list.index';
        $data['title']   = Users::role(app()->getLocale()) . ' | ' . __('List Users');
        return $data;
    }

    public function show($data, $id, $type)
    {
        $data['view']       = Users::$path['view'] . '.includes.form.index';
        if ($id) {

            $response           = Users::whereIn('id', explode(',', $id))->get()->map(function ($row) {
                $row['profile'] = ImageHelper::site(Users::$path['image'], $row['profile']);
                $row['role'] = Roles::where('id', $row->role_id)->pluck(app()->getLocale())->first();
                $row['action']  = [
                    'edit'   => url(Users::role() . '/' . Users::$path['url'] . '/edit/' . $row['id']),
                    'view'   => url(Users::role() . '/' . Users::$path['url'] . '/view/' . $row['id']),
                    'delete' => url(Users::role() . '/' . Users::$path['url'] . '/delete/' . $row['id']),
                ];

                return $row;
            });
            $data['response']['data'] =  $response;
            $data['listData'] =  $response->map(function ($row) {
                return [
                    'id'  => $row->id,
                    'name'  => $row->name,
                    'image'  => $row->profile,
                    'action'  => [
                        'edit'    => url(Users::role() . '/' . Users::$path['url'] . '/edit/' . $row->id),
                    ],
                ];
            });

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

        config()->set('app.title', __('List Users'));
        config()->set('pages.parent', Users::$path['view']);

        $data['instituteFilter']['data']  = Institute::whereIn('id', Users::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::$path['image'], $row->logo);
                return $row;
            });
        $data['roleFilter']['data']  = Roles::whereIn('id', Users::groupBy('role_id')->pluck('role_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::$path['image'], $row->image);
                return $row;
            });

        $table = Users::orderBy('id', 'asc');
        if (request('instituteId')) {
            $table->where('institute_id', request('instituteId'));
        }

        if (request('roleId')) {
            $table->where('role_id', request('roleId'));
        }

        $response = $table->get()->map(function ($row) {
            $row['profile'] =  ImageHelper::site(Users::$path['image'], $row->profile);
            $row['role'] = Roles::where('id', $row->role_id)->pluck(app()->getLocale())->first();
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

        config()->set('pages.title', __('List Users'));

        return view(Users::$path['view'] . '.includes.report.index', $data);
    }
}
