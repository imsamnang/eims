<?php

namespace App\Http\Controllers\Staff;

use Carbon\Carbon;
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
use App\Models\BloodGroup;
use App\Models\MotherTong;
use App\Helpers\DateHelper;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Models\Nationality;
use App\Models\StaffStatus;
use App\Helpers\ImageHelper;
use App\Models\SocailsMedia;
use App\Models\StaffGuardians;
use App\Models\StaffExperience;
use App\Models\StaffInstitutes;
use App\Http\Requests\FormStaff;
use App\Models\StaffCertificate;
use App\Models\StaffDesignations;
use App\Models\StaffTeachSubject;
use Illuminate\Support\Collection;
use App\Models\StaffQualifications;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StaffsReportTemplateExport;
use App\Http\Controllers\Staff\StaffCertificateController;
use App\Http\Controllers\Staff\StaffDesignationController;
use Mpdf\Mpdf;

class StaffController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
        App::setConfig();
        SocailsMedia::setConfig();
        Languages::setConfig();
        view()->share('breadcrumb', []);
    }

    public function index($param1 = null, $param2 = null, $param3 = null, $param4 = null)
    {
        $breadcrumb  = [
            [
                'title' => __('Staff & Teacher'),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . Staff::$path['url']),
            ],
            [
                'title' => __('List Staff'),
                'status' => false,
                'link'  => url(Users::role() . '/' . Staff::$path['url']) . '/list',
            ]
        ];

        $data['formAction']          = '/add';
        $data['formName']            = Staff::$path['url'];
        $data['metaImage']           = asset('assets/img/icons/' . $param1 . '.png');
        $data['metaLink']            = url(Users::role() . '/' . $param1);
        $data['formData']            = array(
            [
                'photo'                  => asset('/assets/img/user/male.jpg'),
            ]
        );
        $data['listData']            = array();

        if ($param1 == null) {
            unset($breadcrumb[1]);
            $data['shortcut'] = [
                [
                    'name'  => __('Add Staff'),
                    'link'  => url(Users::role() . '/' . Staff::$path['url'] . '/add'),
                    'icon'  => 'fas fa-user-plus',
                    'image' => null,
                    'color' => 'bg-' . config('app.theme_color.name'),
                ], [
                    'name'  => __('Add Staff short form'),
                    'link'  => url('staff-register'),
                    'target' => '_blank',
                    'icon'  => 'fas fa-user-plus',
                    'image' => null,
                    'color' => 'bg-' . config('app.theme_color.name'),
                ], [
                    'name'  => __('List all Staff'),
                    'link'  => url(Users::role() . '/' . Staff::$path['url'] . '/list'),
                    'icon'  => 'fas fa-users-class',
                    'image' => null,
                    'color' => 'bg-' . config('app.theme_color.name'),
                ], [
                    'name'  => __('List Designation'),
                    'link'  => url(Users::role() . '/' . Staff::$path['url'] . '/' . StaffDesignations::$path['url'] . '/list'),
                    'icon'  => 'fas fa-user-tie',
                    'image' => null,
                    'color' => 'bg-' . config('app.theme_color.name'),
                ], [
                    'name'  => __('List Staff status'),
                    'link'  => url(Users::role() . '/' . Staff::$path['url'] . '/' . StaffStatus::$path['url'] . '/list'),
                    'icon'  => 'fas fa-question-square',
                    'image' => null,
                    'color' => 'bg-' . config('app.theme_color.name'),
                ], [
                    'name'  => __('List Staff Certificate'),
                    'link'  => url(Users::role() . '/' . Staff::$path['url'] . '/' . StaffCertificate::$path['url'] . '/list'),
                    'icon'  => 'fas fa-file-certificate',
                    'image' => null,
                    'color' => 'bg-' . config('app.theme_color.name'),
                ], [
                    'name'  => __('List Staff teach subject'),
                    'link'  => url(Users::role() . '/' . Staff::$path['url'] . '/' . StaffTeachSubject::$path['url'] . '/list'),
                    'icon'  => 'fas fa-chalkboard-teacher',
                    'image' => null,
                    'color' => 'bg-' . config('app.theme_color.name'),
                ],

            ];
            $data['view']  = Staff::$path['view'] . '.includes.dashboard.index';
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Staff & Teacher');
        } elseif ($param1 == 'list') {
            $breadcrumb[1]['status']  = 'active';

            if ((request()->server('CONTENT_TYPE')) == 'application/json') {
                return  Staff::getData(null, null, 10, request('search'));
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'list-datatable') {
            if ((request()->server('CONTENT_TYPE')) == 'application/json') {
                return  Staff::getDataTable();
            } else {
                $data = $this->list($data);
            }
        } elseif ($param1 == 'add') {

            $breadcrumb[]  = [
                'title' => __('Add Staff'),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . Staff::$path['url'] . '/add'),
            ];

            if (request()->method() === 'POST') {
                return Staff::addToTable();
            }
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Add Staff');
            $data = $this->show($data, null, $param1);
        } elseif (($param1) == 'view') {
            $id = request('id', $param2);
            $breadcrumb[]  = [
                'title' => __('View Staff'),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . Staff::$path['url'] . '/view/' . $id),
            ];


            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('View Staff');
            $data['response']['data'] = Staff::whereIn('id', explode(',', $id))->get()->map(function ($row) {
                $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
                $row['nationality'] = Nationality::where('id', $row->nationality_id)->pluck(app()->getLocale())->first();
                $row['mother_tong'] = MotherTong::where('id', $row->mother_tong_id)->pluck(app()->getLocale())->first();
                $row['marital'] = Marital::where('id', $row->marital_id)->pluck(app()->getLocale())->first();
                $row['blood_group'] = BloodGroup::where('id', $row->blood_group_id)->pluck(app()->getLocale())->first();
                $row['staff_guardian'] = StaffGuardians::getData($row->id)['data'][0];
                $row['photo'] = $row['photo'] ? ImageHelper::site(Staff::$path['image'], $row['photo']) : ImageHelper::site(Staff::$path['image'], ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
                $row['action']  = [
                    'edit'   => url(Users::role() . '/' . Staff::$path['url'] . '/edit/' . $row['id']),
                    'view'   => url(Users::role() . '/' . Staff::$path['url'] . '/view/' . $row['id']),
                    'account' => url(Users::role() . '/' . Staff::$path['url'] . '/account/create/' . $row['id']),
                    'print' => url(Users::role() . '/' . Staff::$path['url'] . '/print/' . $row['id']),
                    'delete' => url(Users::role() . '/' . Staff::$path['url'] . '/delete/' . $row['id']),
                ];
                return $row;
            });
            $data['formAction']          = '/view/' . $id;
            $data['view']  = Staff::$path['view'] . '.includes.view.index';
        } elseif (($param1) == 'print') {
            $id = request('id', $param2);
            return $this->print($id);
        } elseif (($param1) == 'edit') {
            $id = request('id', $param2);
            $breadcrumb[]  = [
                'title' => __('Edit Staff'),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . Staff::$path['url'] . '/edit/' . $id),
            ];

            if (request()->method() === 'POST') {
                return Staff::updateToTable($id);
            }
            $data = $this->show($data, $id, $param1);
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Edit Staff');
        } elseif (($param1) == 'delete') {
            $id = request('id', $param2);
            return Staff::deleteFromTable($id);
        } elseif (($param1) == 'report') {
            return $this->report($param2);
        } elseif (($param1) == 'account') {

            $id = request('id', $param3);
            $breadcrumb[]  = [
                'title' => __('Create account'),
                'status' => 'active',
                'link'  => url(Users::role() . '/' . Staff::$path['url'] . '/account/create/' . $id),
            ];
            if ($param2 == 'create') {
                if (request()->method() == 'POST') {
                    return Staff::createAccountToTable($id);
                }

                $data = $this->show($data, $id, $param1);
                $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Create account');
                $data['view']       = Staff::$path['view'] . '.includes.account.index';

                $data['roles']['data']  = Roles::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                    $row['image']   = $row->image ?  ImageHelper::site(Roles::$path['image'], $row->image) : ImageHelper::prefix();
                    return $row;
                });
            }
        } elseif (($param1) == StaffDesignations::$path['url']) {
            $view = new StaffDesignationController();
            return $view->index($param2, $param3, $param4);
        } elseif (($param1) == StaffStatus::$path['url']) {
            $view = new StaffStatusController();
            return $view->index($param2, $param3, $param4);
        } elseif (($param1) == StaffCertificate::$path['url']) {
            $view = new StaffCertificateController();
            return $view->index($param2, $param3, $param4);
        } elseif (($param1) == StaffTeachSubject::$path['url']) {
            $view = new StaffTeachSubjectController();
            return $view->index($param2, $param3, $param4);
        } else {
            abort(404);
        }

        view()->share('breadcrumb', $breadcrumb);

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
            'form'       => FormHelper::form($data['formData'], $data['formName'], $data['formAction']),
            'parent'     => Staff::$path['view'],
            'modal'      => Staff::$path['view'] . '.includes.modal.index',
            'view'       => $data['view'],
        );

        $pages['form']['validate'] = [
            'rules'       => ($param1) == 'account' ? [] : FormStaff::rulesField(),
            'attributes'  => ($param1) == 'account' ? [] : FormStaff::attributeField(),
            'messages'    => ($param1) == 'account' ? [] : FormStaff::customMessages(),
            'questions'   => ($param1) == 'account' ? [] : FormStaff::questionField(),
        ];


        //Select Option

        $data['institute']['data']  = Institute::get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
            $row['image']   = ImageHelper::site(Institute::$path['image'], $row->logo);
            return $row;
        });
        $data['instituteFilter']['data'] = Institute::whereIn('id', StaffInstitutes::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::$path['image'], $row->logo);
                return $row;
            });
        $data['designationFilter']['data']  = StaffDesignations::whereIn('id', StaffInstitutes::groupBy('designation_id')->pluck('designation_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = $row->image ?  ImageHelper::site(Institute::$path['image'], $row->image) : ImageHelper::prefix();
                return $row;
            });

        $data['status']['data']   = StaffStatus::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ?  ImageHelper::site(Institute::$path['image'], $row->image) : ImageHelper::prefix();
            return $row;
        });
        $data['designation']['data']  = StaffDesignations::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ?  ImageHelper::site(Institute::$path['image'], $row->image) : ImageHelper::prefix();
            return $row;
        });
        $data['mother_tong']['data']         = MotherTong::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ?  ImageHelper::site(Institute::$path['image'], $row->image) : ImageHelper::prefix();
            return $row;
        });

        $data['nationality']['data']         = Nationality::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ?  ImageHelper::site(Institute::$path['image'], $row->image) : ImageHelper::prefix();
            return $row;
        });
        $data['marital']['data']             = Marital::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ?  ImageHelper::site(Institute::$path['image'], $row->image) : ImageHelper::prefix();
            return $row;
        });
        $data['blood_group']['data']         = BloodGroup::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ?  ImageHelper::site(Institute::$path['image'], $row->image) : ImageHelper::prefix();
            return $row;
        });
        $data['provinces']['data']           = Provinces::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ?  ImageHelper::site(Institute::$path['image'], $row->image) : ImageHelper::prefix();
            return $row;
        });
        $data['districts']           =  [
            'data'  => [],
            'action' => [
                'list'  =>  url(Users::role() . '/general/' . Districts::$path['url'] . '/list'),
            ]
        ];
        $data['communes']            = [
            'data'  => [],
            'action' => [
                'list'  =>  url(Users::role() . '/general/' . Communes::$path['url'] . '/list'),
            ]
        ];
        $data['villages']            = [
            'data'  => [],
            'action' => [
                'list'  =>  url(Users::role() . '/general/' . Villages::$path['url'] . '/list'),
            ]
        ];
        $data['staff_certificate']['data']   = StaffCertificate::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ?  ImageHelper::site(Institute::$path['image'], $row->image) : ImageHelper::prefix();
            return $row;
        });
        $data['curr_districts']      = $data['districts'];
        $data['curr_communes']       = $data['communes'];
        $data['curr_villages']       = $data['villages'];


        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);

        return view(Staff::$path['view'] . '.index', $data);
    }

    public function list($data)
    {


        $table = Staff::whereHas('institute', function ($query) {
            $query->where('institute_id', request('instituteId'));

            if (request('designationId')) {
                $query->where('designation_id', request('designationId'));
            }
        })->join((new StaffInstitutes)->getTable(), (new StaffInstitutes)->getTable() . '.staff_id', (new Staff)->getTable() . '.id');

        $response = $table->orderBy((new Staff)->getTable() . '.id', 'DESC')
            ->get()->map(function ($row) {
                $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
                $row['date_of_birth'] = DateHelper::convert($row->date_of_birth, 'd-M-Y');
                $row['designation'] = StaffDesignations::where('id', $row->designation_id)->pluck(app()->getLocale())->first();
                $row['staff_guardian'] = StaffGuardians::getData($row->id)['data'][0];
                $row['photo'] = $row['photo'] ? ImageHelper::site(Staff::$path['image'], $row['photo']) : ImageHelper::site(Staff::$path['image'], ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
                $row['account'] = Users::where('node_id', $row->id)->where('email', $row->email)->exists();
                $row['action']  = [
                    'edit'   => url(Users::role() . '/' . Staff::$path['url'] . '/edit/' . $row['id']),
                    'view'   => url(Users::role() . '/' . Staff::$path['url'] . '/view/' . $row['id']),
                    'account' => url(Users::role() . '/' . Staff::$path['url'] . '/account/create/' . $row['id']),
                    'delete' => url(Users::role() . '/' . Staff::$path['url'] . '/delete/' . $row['id']),
                ];

                return $row;
            })->toArray();


        $data['response'] = [
            'data'      => $response,
            'gender'    => Staff::gender($table),
            'staffStatus' => Staff::staffStatus($table),
        ];


        $data['view']  = Staff::$path['view'] . '.includes.list.index';
        $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('List Staff');
        return $data;
    }

    public function show($data, $id, $type)
    {
        $data['view']       = Staff::$path['view'] . '.includes.form.index';
        if ($id) {

            $response           = Staff::whereIn('id', explode(',', $id))->get()->map(function ($row) {
                $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                $row['date_of_birth'] = DateHelper::convert($row->date_of_birth, 'd-m-Y');
                $row['staff_institute'] = StaffInstitutes::where('staff_id', $row->id)->first();
                $row['institute'] = Institute::where('id', $row['staff_institute']->institute_id)->pluck(app()->getLocale())->first();
                $row['designation'] = StaffDesignations::where('id', $row['staff_institute']->designation_id)->pluck(app()->getLocale())->first();


                $row['staff_guardian'] = StaffGuardians::where('staff_id', $row->id)->first();
                $row['staff_experience']    = StaffExperience::where('staff_id', $row->id)->get();
                $row['staff_qualification'] = StaffQualifications::where('staff_id', $row->id)->first();
                $row['photo'] = $row['photo'] ? ImageHelper::site(Staff::$path['image'], $row['photo']) : ImageHelper::site(Staff::$path['image'], ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
                $row['account'] = Users::where('email', $row->email)->where('node_id', $row->id)->exists();
                $row['action']  = [
                    'edit'   => url(Users::role() . '/' . Staff::$path['url'] . '/edit/' . $row['id']),
                    'view'   => url(Users::role() . '/' . Staff::$path['url'] . '/view/' . $row['id']),
                    'account' => url(Users::role() . '/' . Staff::$path['url'] . '/account/create/' . $row['id']),
                    'print' => url(Users::role() . '/' . Staff::$path['url'] . '/print/' . $row['id']),
                    'delete' => url(Users::role() . '/' . Staff::$path['url'] . '/delete/' . $row['id']),
                ];

                if ($row['staff_institute']->designation_id == 1) {
                    $row['suggest_role']       = 1;
                } elseif (in_array($row['staff_institute']->designation_id, [2, 3])) {
                    $row['suggest_role']       = 8;
                }

                return $row;
            });
            $data['listData'] =  $response->map(function ($row) {
                return [
                    'id'  => $row->id,
                    'name'  => $row->name,
                    'image'  => $row->photo,
                    'action'  => [
                        'edit'    => url(Users::role() . '/' . Staff::$path['url'] . '/edit/' . $row->id),
                    ],
                ];
            });

            $data['formData']   = $response;
            $data['formAction'] = '/' . $type . '/' . $id;
        }


        return $data;
    }
    public function report($excel = null)
    {



        $table = Staff::join((new StaffInstitutes)->getTable(), (new StaffInstitutes)->getTable() . '.staff_id', (new Staff)->getTable() . '.id');
        if (request('instituteId')) {
            $table->where('institute_id', request('instituteId'));
        }
        if (request('designationId')) {
            $table->where('designation_id', request('designationId'));
        }
        $response = $table->get()->map(function ($row) {
            // $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
            $row['name'] = $row['first_name_' . app()->getLocale()] . ' ' . $row['last_name_' . app()->getLocale()];
            $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
            $row['date_of_birth'] = DateHelper::convert($row->date_of_birth, 'd-M-Y');
            $row['designation'] = StaffDesignations::where('id', $row->designation_id)->pluck(app()->getLocale())->first();
            $row['photo'] = $row['photo'] ? ImageHelper::site(Staff::$path['image'], $row['photo']) : ImageHelper::site(Staff::$path['image'], ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));

            return $row;
        })->toArray();

        $data['institute'] = Institute::where('id', request('instituteId'))
            ->get(['logo', app()->getLocale() . ' as name'])
            ->map(function ($row) {
                $row['logo'] = ImageHelper::site(Institute::$path['image'], $row['logo']);
                return $row;
            })->first();

        $data['designation']  = StaffDesignations::where('id', request('designationId'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = $row->image ?  ImageHelper::site(Institute::$path['image'], $row->image) : ImageHelper::prefix();
                return $row;
            })->first();

        config()->set('pages.title', __('List all Staff') . ($data['designation'] ? ' (' . $data['designation']['name'] . ')' : ''));

        if ($excel) {
            return Excel::download(new StaffsReportTemplateExport($response),  str_replace('/', '-', $data['institute']['name'] . ' - ' . config('pages.title')) . '.xlsx');
        }

        request()->merge([
            'size'  => request('size', 'A4'),
            'layout'  => request('layout', 'portrait'),
        ]);

        config()->set('app.title', __('List all Staff'));
        config()->set('pages.parent', Staff::$path['view']);

        $data['instituteFilter']['data']           = Institute::whereIn('id', StaffInstitutes::groupBy('institute_id')->pluck('institute_id'))
            ->get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::$path['image'], $row->logo);
                return $row;
            });
        $data['designationFilter']['data']           = StaffDesignations::whereIn('id', StaffInstitutes::groupBy('designation_id')->pluck('designation_id'))
            ->get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = $row->image ?  ImageHelper::site(Institute::$path['image'], $row->image) : ImageHelper::prefix();
                return $row;
            });

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
            'genders' => Staff::gender($table),
            'date'      => [
                'day'   => $date->day,
                '_day'  => $date->getTranslatedDayName(),
                'month' => $date->getTranslatedMonthName(),
                'year'  => $date->year,
                'def'   => DateHelper::convert($date, 'd-M-Y'),
            ]
        ];

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'fontDir' => array_merge($fontDirs, [
                public_path('/assets/fonts/'),
            ]),
            'fontdata' => $fontData + [
                'khmerosmoul' => [
                    'R' => 'KhmerOSMoul.ttf',
                    'useOTL' => 0xFF,
                ],
                'khmerosbattambang' => [
                    'R' => 'KhmerOS_battambang.ttf',
                    'useOTL' => 0xFF,
                ]
            ],
            'default_font' => 'khmerosbattambang'
        ]);

        $mpdf->WriteHTML(view(Staff::$path['view'] . '.includes.report.index', $data)->render());
        return $mpdf->Output();




        return view(Staff::$path['view'] . '.includes.report.index', $data);
    }

    public function print($id)
    {
        request()->merge([
            'size'  => request('size', 'A4'),
            'layout'  => request('layout', 'portrait'),
        ]);

        config()->set('app.title', __('List all Staff'));
        config()->set('pages.parent', Staff::$path['view']);

        if ($id) {
            $data['title']    = Users::role(app()->getLocale()) . ' | ' . __('Print Staff');
            $data['response']['data'] = Staff::whereIn('id', explode(',', $id))->get()->map(function ($row) {
                $row['name'] = $row->first_name_km . ' ' . $row->last_name_km . ' - ' . $row->first_name_en . ' ' . $row->last_name_en;
                $row['gender'] = Gender::where('id', $row->gender_id)->pluck(app()->getLocale())->first();
                $row['nationality'] = Nationality::where('id', $row->nationality_id)->pluck(app()->getLocale())->first();
                $row['mother_tong'] = MotherTong::where('id', $row->mother_tong_id)->pluck(app()->getLocale())->first();
                $row['marital'] = Marital::where('id', $row->marital_id)->pluck(app()->getLocale())->first();
                $row['blood_group'] = BloodGroup::where('id', $row->blood_group_id)->pluck(app()->getLocale())->first();
                $row['staff_guardian'] = StaffGuardians::getData($row->id)['data'][0];
                $row['photo'] = $row['photo'] ? ImageHelper::site(Staff::$path['image'], $row['photo']) : ImageHelper::site(Staff::$path['image'], ($row->gender_id == 1 ? 'male.jpg' : 'female.jpg'));
                return $row;
            });
            $data['view']  = Staff::$path['view'] . '.includes.print.index';
        }

        return view(Staff::$path['view'] . '.includes.print.index', $data);
    }
}
