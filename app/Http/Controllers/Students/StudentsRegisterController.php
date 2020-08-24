<?php

namespace App\Http\Controllers\Students;

use App\Models\App as AppModel;
use App\Models\Users;
use App\Models\Gender;
use App\Models\Marital;
use App\Models\Students;
use App\Models\Institute;
use App\Models\Languages;
use App\Models\BloodGroup;
use App\Models\MotherTong;
use App\Helpers\FormHelper;

use App\Helpers\MetaHelper;
use App\Models\Nationality;
use App\Helpers\ImageHelper;
use App\Models\SocailsMedia;
use App\Imports\StudentsImport;
use App\Http\Requests\FormStudents;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsRegisterTemplateExport;

class StudentsRegisterController extends Controller
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

    public function index($param1 = null, $param2 = null, $param3 = null, $param4 = null)
    {


        $data['formAction']          = 'add';
        $data['formData']            = array(
            'photo'                  => asset('/assets/img/user/male.jpg'),
        );
        $data['formName']            = '';
        $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('Student Register');
        $data['metaImage']           = asset('assets/img/icons/' . $param1 . '.png');
        $data['metaLink']            = url(Users::role() . '/' . $param1);


        $data['listData']            = array();

        if ($param1 == null || $param1 == 'add') {
            if (request()->method() == 'POST') {
                return Students::register();
            } else {
                $data = $this->add($data);
            }
                // Select Option

            $data['institute']['data']  = Institute::get(['id', app()->getLocale() . ' as name', 'logo'])->map(function ($row) {
                $row['image']   = ImageHelper::site(Institute::path('image'), $row->logo);
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
        } elseif ($param1 == 'excel') {
            if ($param2 == 'import') {
                if (request()->method() == 'POST') {
                    if (request()->hasFile('file')) {
                        $file = request()->file('file');
                        $fileExtension    = pathinfo(str_replace('/', '.', $file->getClientOriginalName()), PATHINFO_EXTENSION);
                        if (preg_match("/{$fileExtension}/i", '.xls,.xlsx')) {
                            $import = new StudentsImport;
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
                                'text'  => __('import Unsuccessful') . PHP_EOL
                                    . __('Excel empty'),
                                'button'   => array(
                                    'confirm' => __('Ok'),
                                    'cancel'  => __('Cancel'),
                                ),
                            ),
                        );
                    }
                }
            } elseif ($param2 == 'template') {
                return Excel::download(new StudentsRegisterTemplateExport, 'ទម្រង់បញ្ចូលទិន្នន័យសិស្ស.xlsx');
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
            'form'       => FormHelper::form($data['formData'], $data['formName'], $data['formAction'], 'student-register'),
            'parent'     => 'StudentsRegister',
            'modal'      => 'StudentsRegister.includes.modal.index',
            'view'       => $data['view'],
        );


        $validate = Students::validate();
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
            'rules'       => $param1 == 'excel' ? ['file' => 'required'] : $rules,
            'attributes'  => $param1 == 'excel' ? ['file' => __('File Excel')] : $validate['attributes'],
            'messages'    => $param1 == 'excel' ? [] : $validate['messages'],
            'questions'   => $param1 == 'excel' ? [] : $validate['questions'],
        ];

        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view('StudentsRegister.index', $data);
    }



    public function add($data)
    {
        $data['view']  = 'StudentsRegister.includes.form.index';
        $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('Student Register');
        $data['metaImage'] = asset('assets/img/icons/register.png');
        $data['metaLink']  = url(Users::role() . '/add/');
        return $data;
    }
    public function excel($data)
    {
        $export = new StudentsRegisterTemplateExport;

        $data['response'] = [
            'data' => $export->collection()->toArray(),
            'heading' => $export->headings(),
        ];

        $data['institute'] = Institute::pluck('km')->toArray();
        $data['gender'] = Gender::pluck('km')->toArray();
        $data['marital'] = Marital::pluck('km')->toArray();
        $data['view']  = 'StudentsRegister.includes.excel.index';
        $data['title'] = Users::role(app()->getLocale()) . ' | ' . __('Excel Register');
        $data['metaImage'] = asset('assets/img/icons/register.png');
        $data['metaLink']  = url(Users::role() . '/add/');
        return $data;
    }
}
