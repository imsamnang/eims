<?php

namespace App\Http\Controllers\Staff;

use App\Models\App as AppModel;
use App\Models\Staff;
use App\Models\Users;
use App\Models\Gender;
use App\Models\Marital;
use App\Models\Institute;
use App\Models\Languages;
use App\Models\BloodGroup;
use App\Models\MotherTong;
use App\Helpers\FormHelper;
use App\Helpers\MetaHelper;
use App\Models\Nationality;
use App\Models\StaffStatus;
use App\Helpers\ImageHelper;
use App\Models\SocailsMedia;
use App\Imports\StaffsImport;
use App\Models\StaffCertificate;
use App\Models\StaffDesignations;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StaffsReqisterTemplateExport;

class StaffRegisterController extends Controller
{


    public function __construct()
    {

        AppModel::setConfig();
       Languages::setConfig();
       AppModel::setConfig();
        SocailsMedia::setConfig();
        view()->share('breadcrumb', []);
    }

    public function index($param1 = null, $param2 = null, $param3 = null, $param4 = null)
    {


        $data['formData']            = array(
            'photo'                  => asset('/assets/img/user/male.jpg'),
        );

        $data['formAction']          = 'add';
        $data['formName']            = '';
        $data['title']               = Users::role(app()->getLocale()) . ' | ' . __('Staff Register');
        $data['metaImage']           = asset('assets/img/icons/' . $param1 . '.png');
        $data['metaLink']            = url(Users::role() . '/' . $param1);
        $data['listData']            = array();

        if ($param1 == null || $param1 == 'add') {
            if (request()->method() == 'POST') {
                return Staff::register();
            } else {
                $data = $this->add($data);
            }
        } elseif ($param1 == 'excel') {
            if ($param2 == 'import') {
                if (request()->method() == 'POST') {
                    if (request()->hasFile('file')) {
                        $file = request()->file('file');
                        $fileExtension    = pathinfo(str_replace('/', '.', $file->getClientOriginalName()), PATHINFO_EXTENSION);
                        if (preg_match("/{$fileExtension}/i", '.xls,.xlsx')) {
                            $import = new StaffsImport;
                            $import->import($file);
                            return [
                                'success'   => true,
                                'message'   => 'ប្រតិបត្តិនេះត្រូវបានបញ្ចប់',
                            ];
                        } else {
                            return [
                                'success'   => false,
                                'message'   => 'ឯកសារដែលអ្នកបញ្ចូលមិនត្រឹមត្រូវទេ (.xls,.xlsx)!!',
                            ];
                        }
                    } else {
                        return array(
                            'success'   => false,
                            'type'      => 'import',
                            'message'   => array(
                                'title' => __('Error'),
                                'text'  => __('import.unsuccessful') . PHP_EOL
                                    . __('Excel empty'),
                                'button'   => array(
                                    'confirm' => __('ok'),
                                    'cancel'  => __('Cancel'),
                                ),
                            ),
                        );
                    }
                }
            } elseif ($param2 == 'template') {
                return Excel::download(new StaffsReqisterTemplateExport, 'ទម្រង់បញ្ចូលទិន្នន័យបុគ្គលិក & គ្រូបង្រៀន.xlsx');
            }

            $data = $this->excel($data);
        } else {
            abort(404);
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

        //Select Option

        $data['institute']['data']  = Institute::get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
            $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
            return $row;
        });
        $data['status']['data']   = StaffStatus::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ?  ImageHelper::site(StaffStatus::path('image'), $row->image) : ImageHelper::prefix();
            return $row;
        });
        $data['designation']['data']  = StaffDesignations::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ?  ImageHelper::site(StaffDesignations::path('image'), $row->image) : ImageHelper::prefix();
            return $row;
        });

        $data['mother_tong']['data']         = MotherTong::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ?  ImageHelper::site(MotherTong::path('image'), $row->image) : ImageHelper::prefix();
            return $row;
        });

        $data['nationality']['data']         = Nationality::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ?  ImageHelper::site(Nationality::path('image'), $row->image) : ImageHelper::prefix();
            return $row;
        });
        $data['marital']['data']             = Marital::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ?  ImageHelper::site(Marital::path('image'), $row->image) : ImageHelper::prefix();
            return $row;
        });
        $data['blood_group']['data']         = BloodGroup::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ?  ImageHelper::site(BloodGroup::path('image'), $row->image) : ImageHelper::prefix();
            return $row;
        });
        $data['staff_certificate']['data']   = StaffCertificate::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
            $row['image']   = $row->image ?  ImageHelper::site(StaffCertificate::path('image'), $row->image) : ImageHelper::prefix();
            return $row;
        });

        $validate = Staff::validate();
        $rules = $validate['rules'];
        unset($rules['pob_province']);
        unset($rules['pob_district']);
        unset($rules['pob_commune']);
        unset($rules['pob_village']);
        unset($rules['curr_province']);
        unset($rules['curr_district']);
        unset($rules['curr_commune']);
        unset($rules['curr_village']);
        unset($rules['father_fullname']);
        unset($rules['father_occupation']);
        unset($rules['father_phone']);
        unset($rules['mother_fullname']);
        unset($rules['mother_occupation']);
        unset($rules['mother_phone']);
        unset($rules['guardian']);
        unset($rules['__guardian']);


        $pages['form']['validate'] = [
            'rules'       => $rules,
            'attributes'  => $validate['attributes'],
            'messages'    => $validate['messages'],
            'questions'   => $validate['questions'],
        ];


        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);


        return view('StaffRegister.index', $data);
    }



    public function add($data)
    {
        $data['view']  = 'StaffRegister.includes.form.index';
        $data['title']               = Users::role(app()->getLocale()) . ' | ' . __('Staff Register');
        $data['metaImage'] = asset('assets/img/icons/register.png');
        $data['metaLink']  = url(Users::role() . '/add/');
        return $data;
    }
    public function excel($data)
    {
        $export = new StaffsReqisterTemplateExport;

        $data['response'] = [
            'data' => $export->collection()->toArray(),
            'heading' => $export->headings(),
        ];

        $data['institute'] = Institute::pluck('km')->toArray();
        $data['designation'] = StaffDesignations::pluck('km')->toArray();
        $data['status'] = StaffStatus::pluck('km')->toArray();
        $data['gender'] = Gender::pluck('km')->toArray();
        $data['marital'] = Marital::pluck('km')->toArray();

        $data['view']  = 'StaffRegister.includes.excel.index';
        $data['title']               = Users::role(app()->getLocale()) . ' | ' . __('Staff Register');
        $data['metaImage'] = asset('assets/img/icons/register.png');
        $data['metaLink']  = url(Users::role() . '/add/');
        return $data;
    }
}
