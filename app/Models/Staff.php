<?php

namespace App\Models;

use DomainException;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\StaffGuardians;
use App\Models\StaffInstitutes;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Staff extends Model
{
    /**
     *  @param string $key
     *  @param string|array $key
     */
    public static function path($key = null)
    {
        $table = (new self)->getTable();
        $tableUcwords = str_replace(' ', '', ucwords(str_replace('_', ' ', $table)));
        $role = Roles::find(5)->first();
        $path = [
            'table'  => $table,
            'image'  => $table,
            'url'    => str_replace('_', '-', $table),
            'view'   => $tableUcwords,
            'role'   => $role->name,
            'roleId'   => $role->id,
            'requests'   => 'App\Http\Requests\Form'.$tableUcwords,
            'controller'   => 'App\Http\Controllers\Staff\\'.$tableUcwords.'Controller',
        ];
        return $key ? @$path[$key] : $path;
    }
    /**
     *  @param string $key
     *  @param string $flag
     *  @return array
     */
    public static function validate($key = null, $flag = '[]')
    {
        $class = self::path('requests');
        $formRequests = new $class;
        $validate =  [
            'rules'       =>  $formRequests->rules($flag),
            'attributes'  =>  $formRequests->attributes(),
            'messages'    =>  $formRequests->messages(),
            'questions'   =>  $formRequests->questions(),
        ];
        return $key? @$validate[$key] : $validate;
    }

    public function staff_institute()
    {
        return $this->hasOne(StaffInstitutes::class,  'staff_id', 'id');
    }

    public static function searchName($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name_en', 'LIKE', '%' . $search . '%')
                ->orWhere('last_name_en', 'LIKE', '%' . $search . '%')
                ->orWhere('first_name_km', 'LIKE', '%' . $search . '%')
                ->orWhere('last_name_km', 'LIKE', '%' . $search . '%')
                ->orWhereRaw('CONCAT(`first_name_en`, \'\', `last_name_en`) LIKE ?', '%' . $search . '%')
                ->orWhereRaw('CONCAT(`last_name_en`, \'\', `first_name_en`) LIKE ?', '%' . $search . '%')
                ->orWhereRaw('CONCAT(`first_name_km`, \'\', `last_name_km`) LIKE ?', '%' . $search . '%')
                ->orWhereRaw('CONCAT(`last_name_km`, \'\', `first_name_km`) LIKE ?', '%' . $search . '%')

                ->orWhereRaw('CONCAT(`first_name_en`, \' \', `last_name_en`) LIKE ?', '%' . $search . '%')
                ->orWhereRaw('CONCAT(`last_name_en`, \' \', `first_name_en`) LIKE ?', '%' . $search . '%')
                ->orWhereRaw('CONCAT(`first_name_km`, \' \', `last_name_km`) LIKE ?', '%' . $search . '%')
                ->orWhereRaw('CONCAT(`last_name_km`, \' \', `first_name_km`) LIKE ?', '%' . $search . '%');
        });
    }

    public static function gender($query)
    {

        if (gettype($query) == 'object' && $query->count()) {
            $male   = 0;
            $female = 0;
            foreach ($query->get()->toArray() as  $row) {
                if ($row['gender_id']  == 1) {
                    $male++;
                } elseif ($row['gender_id']  == 2) {
                    $female++;
                };
            }
            return array(
                'male'      => [
                    'title' => __('Male'),
                    'text'  => $male . ((app()->getLocale() == 'km') ? ' នាក់' : ' Poeple'),
                ],
                'female'    => [
                    'title' => __('Female'),
                    'text'  => $female . ((app()->getLocale() == 'km') ? ' នាក់' : ' Poeple'),
                ],
                'total'      => [
                    'title' => __('Staff total'),
                    'text'  => $query->count() . ((app()->getLocale() == 'km') ? ' នាក់' : ' Poeple'),
                ],
            );
        } else {
            return array(
                'male'      => [
                    'title' => __('Male'),
                    'text'  => ((app()->getLocale() == 'km') ? '0 នាក់' : '0 Poeple'),
                ],
                'female'    => [
                    'title' => __('Female'),
                    'text'  => ((app()->getLocale() == 'km') ? '0 នាក់' : '0 Poeple'),
                ],
                'total'      => [
                    'title' => __('Staff total'),
                    'text'  => ((app()->getLocale() == 'km') ? '0 នាក់' : '0 Poeple'),
                ],
            );
        }
    }

    public static function staffStatus($query)
    {
        $data = [];
        if (gettype($query) == 'object' && $query->count()) {
            $staffStatus = StaffStatus::get();
            if ($staffStatus->count()) {
                foreach ($staffStatus as  $status) {
                    $data[$status['id']] = [
                        'title' => in_array($status['id'], [2, 3]) ? $status[app()->getLocale()] :  __('Staff') . $status[app()->getLocale()],
                        'color' => $status['color'],
                        'text' => 0,
                    ];
                    foreach ($query->get()->toArray() as  $row) {

                        if ($status['id'] == $row['staff_status_id']) {
                            $data[$status['id']]['text']++;
                        }

                        if (strpos($query->toSql(), 'institute_id') > 0) {
                            $value = $query->getBindings();
                            $data[$status['id']]['link'] = url(Users::role() . '/' . self::path('url') . '/list/?instituteId=' . $value[0] . '&statusId=' . $status['id']);
                        } else {
                            $data[$status['id']]['link'] = url(Users::role() . '/' . self::path('url') . '/list/?statusId=' . $status['id']);
                        }
                    }
                }
            }
        }
        $newData = [];
        foreach ($data as $key => $value) {
            $newData[$key] = $value;
            $newData[$key]['text'] = $value['text'] . ((app()->getLocale() == 'km') ? ' នាក់' : ' Poeple');
        }
        return $newData;
    }

    public static function updatestaffStatus($staff_id, $staff_status_id)
    {
        return self::where('id', $staff_id)->update([
            'staff_status_id' => $staff_status_id
        ]);
    }



    public static function addToTable()
    {
        $response           = array();
        $rules = [];
        if (strtolower(trim(request('guardian'))) == 'other') {
            $rules += [
                'guardian_fullname'    => 'required|only_string',
                'guardian_occupation'  => 'required',
                'guardian_phone'       => 'required',
            ];
        } elseif (strtolower(trim(request('guardian'))) == 'father' || strtolower(trim(request('guardian'))) == 'mother') {
            $rules = [];
        }

        // if(request('permanent_address')){
        //     $rules += [
        //         'permanent_address'    => 'required'
        //     ];
        // }else{
        //     $rules += [
        //         'pob_province'         => 'required',
        //         'pob_district'         => 'required',
        //         'pob_commune'          => 'required',
        //         'pob_village'          => 'required',
        //     ];
        // }



        // if(request('temporaray_address')){
        //     $rules += [
        //         'temporaray_address'    => 'required',
        //     ];
        // }else{
        //     $rules += [
        //         'curr_province'         => 'required',
        //         'curr_district'         => 'required',
        //         'curr_commune'          => 'required',
        //         'curr_village'          => 'required',
        //     ];
        // }
        // if(request()->hasFile('photo')){
        //     $rules = [
        //         'photo'         => 'required',
        //     ];
        // }

        $validate = self::validate();
        $rules += $validate['rules'];
        $rules['phone'] = 'required|regex:/^([0-9\(\)\/\+ \-]*)$/|min:9|unique:'.self::path('table').',phone';
        $rules['email'] = 'required|email|unique:'.self::path('table').',email';
        $validator          = Validator::make(request()->all(), $rules, $validate['messages'], $validate['attributes']);

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {


            try {
                $add = self::insertGetId([
                    'first_name_km' => trim(request('first_name_km')),
                    'last_name_km'  => trim(request('last_name_km')),
                    'first_name_en' => trim(request('first_name_en')),
                    'last_name_en'  => trim(request('last_name_en')),

                    'nationality_id'   => request('nationality'),
                    'mother_tong_id'   => request('mother_tong'),
                    'national_id'   => trim(request('national_id')),
                    'gender_id'        => request('gender'),
                    'date_of_birth' => DateHelper::convert(trim(request('date_of_birth'))),
                    'marital_id'       => request('marital'),
                    'blood_group_id'   => request('blood_group'),

                    'pob_province_id' => request('pob_province'),
                    'pob_district_id' => request('pob_district'),
                    'pob_commune_id'  => request('pob_commune'),
                    'pob_village_id'  => request('pob_village'),


                    'curr_province_id' => request('curr_province'),
                    'curr_district_id' => request('curr_district'),
                    'curr_commune_id'  => request('curr_commune'),
                    'curr_village_id'  => request('curr_village'),


                    'permanent_address'  => trim(request('permanent_address')),
                    'temporaray_address' => trim(request('temporaray_address')),
                    'phone'              => trim(request('phone')),
                    'email'              => trim(request('email')),
                    'password'           => trim(request('password')),
                    'extra_info'         => trim(request('staff_extra_info')),
                    'photo'              => (request('gender') == '1') ? 'male.jpg' : 'female.jpg',
                ]);


                if (
                    $add && StaffInstitutes::addToTable($add)['success']  &&
                    StaffGuardians::addToTable($add)['success'] &&
                    StaffQualifications::addToTable($add)['success'] &&
                    StaffExperience::addToTable($add)['success']
                ) {

                    if (request('status')) {
                        self::updatestaffStatus($add, request('status'));
                    }
                    if (request()->hasFile('photo')) {
                        $photo      = request()->file('photo');
                        self::updateImageToTable($add, ImageHelper::uploadImage($photo, self::path('image')));
                    } else {
                        ImageHelper::uploadImage(false, self::path('image'), (request('gender') == '1') ? 'male' : 'female', public_path('/assets/img/user/' . ((request('gender') == '1') ? 'male.jpg' : 'female.jpg')));
                    }

                    $class = self::path('controller');
                    $controller = new $class;
                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'html'      => view(self::path('view') . '.includes.tpl.tr', ['row' => $controller->list([], $add)[0]])->render(),
                        'message'   =>  __('Add Successfully')
                    );
                }
           } catch (\Throwable $th) {
                        throw $th;
                    }
        }
        return $response;
    }

    public static function register()
    {
        $validate = self::validate();
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
        $rules['phone'] = 'required|regex:/^([0-9\(\)\/\+ \-]*)$/|min:9|unique:'.self::path('table').',phone';
        $rules['email'] = 'required|email|unique:'.self::path('table').',email';

        $validator          = Validator::make(request()->all(), $rules, $validate['messages'], $validate['attributes']);
        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            $values = [
                'first_name_km'    => trim(request('first_name_km')),
                'last_name_km'     => trim(request('last_name_km')),
                'first_name_en'    => trim(request('first_name_en')),
                'last_name_en'     => trim(request('last_name_en')),
                'nationality_id'   => request('nationality'),
                'mother_tong_id'   => request('mother_tong'),
                'national_id'      => trim(request('national_id')),
                'gender_id'        => request('gender'),
                'date_of_birth'    => DateHelper::convert(trim(request('date_of_birth'))),
                'marital_id'       => request('marital'),

                'permanent_address'  => trim(request('permanent_address')),
                'temporaray_address' => trim(request('temporaray_address')),
                'phone'              => trim(request('phone')),
                'email'              => strtolower(trim(request('email'))),
                'extra_info'         => trim(request('extra_info')),
                'photo'              => (request('gender') == '1') ? 'male.jpg' : 'female.jpg',
            ];
            $add = self::insertGetId($values);

            if ($add) {
                StaffInstitutes::addToTable($add);
                StaffGuardians::addToTable($add);
                StaffQualifications::addToTable($add);
                StaffExperience::insert([
                    'staff_id'  => $add
                ]);

                if (request()->hasFile('photo')) {
                    $photo      = request()->file('photo');
                    self::updateImageToTable($add, ImageHelper::uploadImage($photo, self::path('image')));
                }

                $response       = array(
                    'success'   => true,
                    'type'      => 'add',
                    'message'   => __('Register successfully'),
                );
            }
        }

        return $response;
    }

    public static function updateToTable($id)
    {
        $response           = array();
        $validate = self::validate();
        $rules = $validate['rules'];
        $rules['phone'] = 'required|regex:/^([0-9\(\)\/\+ \-]*)$/|min:9|unique:staff,phone,'.$id;
        $rules['email'] = 'required|email|unique:staff,email,'.$id;
        $validator          = Validator::make(request()->all(), $rules, $validate['messages'], $validate['attributes']);

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {
            try {

                $update = self::where('id', $id)->update([
                    'first_name_km' => trim(request('first_name_km')),
                    'last_name_km'  => trim(request('last_name_km')),
                    'first_name_en' => trim(request('first_name_en')),
                    'last_name_en'  => trim(request('last_name_en')),

                    'nationality_id'   => request('nationality'),
                    'mother_tong_id'   => request('mother_tong'),
                    'national_id'   => trim(request('national_id')),
                    'gender_id'        => request('gender'),
                    'date_of_birth' => DateHelper::convert(trim(request('date_of_birth'))),
                    'marital_id'       => request('marital'),
                    'blood_group_id'   => request('blood_group'),

                    'pob_province_id' => request('pob_province'),
                    'pob_district_id' => request('pob_district'),
                    'pob_commune_id'  => request('pob_commune'),
                    'pob_village_id'  => request('pob_village'),


                    'curr_province_id' => request('curr_province'),
                    'curr_district_id' => request('curr_district'),
                    'curr_commune_id'  => request('curr_commune'),
                    'curr_village_id'  => request('curr_village'),

                    'permanent_address'  => trim(request('permanent_address')),
                    'temporaray_address' => trim(request('temporaray_address')),
                    'phone'              => trim(request('phone')),
                    'email'              => trim(request('email')),
                    'password'           => trim(request('password')),
                    'extra_info'         => trim(request('extra_info')),
                ]);

                if (
                    $update &&  StaffInstitutes::updateToTable($id)['success']  &&
                    StaffGuardians::updateToTable($id)['success'] &&
                    StaffQualifications::updateToTable($id)['success'] &&
                    StaffExperience::updateToTable($id)['success']
                ) {

                    if (request()->hasFile('photo')) {
                        $photo      = request()->file('photo');
                        $photo = ImageHelper::uploadImage($photo, self::path('image'));
                        self::updateImageToTable($id, $photo);
                    }


                    if (request('status')) {
                        self::updatestaffStatus($id, request('status'));
                    }
                    $class = self::path('controller');
                    $controller = new $class;
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'data'      => [['id'=>$id]],
                        'html'      => view(self::path('view') . '.includes.tpl.tr', ['row' => $controller->list([], $id)[0]])->render(),
                        'message'   =>  __('Update Successfully')
                    );
                }
           } catch (\Throwable $th) {
                        throw $th;
                    }
        }
        return $response;
    }

    public static function updateImageToTable($add, $image)
    {
        $response = array(
            'success'   => false,
            'message'   => __('Update Failed'),
        );
        if ($image) {
            try {
                $update =  self::where('id', $add)->update([
                    'photo'    => $image,
                ]);

                if ($update) {
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'message'   => __('Update Successfully'),
                    );
                }
           } catch (\Throwable $th) {
                        throw $th;
                    }
        }
        return $response;
    }


    public static function createAccountToTable($id)
    {
        $response = [
            'data'  => []
        ];
        $id = (gettext($id) == 'array') ? $id : explode(',', $id);

        $staff = self::whereIn('id', $id)->get();

        foreach ($staff as $row) {
            $account = Users::where('email', $row->email)->where('node_id', $row->id)->exists();
            if ($account) {

                if ($staff->count() == 1) {
                    return [
                        'success' => false,
                        'data'    => [],
                        'message'   => array(
                            'title' => __('account'),
                            'text'  => __('Already exists'),
                            'button'   => array(
                                'confirm' => __('Ok'),
                                'cancel'  => __('Cancel'),
                            ),
                        ),
                    ];
                }
                $response['errors'][$row->id] = __('Already exists');
            } else {
                request()->merge(['email' => $row->email]);
                $validator  = Validator::make(
                    request()->all(),
                    ['password' => 'required', 'role' => 'required', 'email' => 'required|email|unique:users,email'],
                    [],
                    ['password' => __('Password'), 'role' => __('Role'), 'email' => __('Email')]
                );

                if ($validator->fails()) {
                    $response       = array(
                        'success'   => false,
                        'errors'    => $validator->getMessageBag(),
                    );
                    if ($staff->count() == 1) {
                        return $response;
                    }
                    //$response['errors'][$row->id] = $response['errors']->getMessages();
                } else {
                    try {
                        $folder  = 'public/' . ImageHelper::path('image') . '/' . self::path('image');
                        $filePath = storage_path('app/'. $folder);

                        $first_name = $row['first_name_' . app()->getLocale()] ? $row['first_name_' . app()->getLocale()] : $row->first_name_en;
                        $last_name  = $row['first_name_' . app()->getLocale()] ? $row['last_name_' . app()->getLocale()] : $row->last_name_en;
                        $create = Users::insertGetId([
                            'name'          => $first_name . ' ' . $last_name,
                            'email'         => $row->email,
                            'password'      => Hash::make(request('password')),
                            'phone'         => $row->phone,
                            'address'       => $row->permanent_address,
                            'role_id'       => request('role', self::path('roleId')),
                            'node_id'       => $row->id,
                            'institute_id'  => StaffInstitutes::where('staff_id', $row->id)->pluck('institute_id')->first(),
                        ]);

                        if ($create) {

                            if ($row->photo && File::exists($filePath . '/original/' . $row->photo)) {
                                $profile = ImageHelper::uploadImage(null, Users::path('image'), null, $filePath  . '/original/' . $row->photo);
                                Users::updateImageToTable($create, $profile);
                            } else {
                                $profile = ($row->gender_id == 1) ? 'male.jpg' : 'female.jpg';
                                Users::updateImageToTable($create, $profile);
                            }

                            $response['data'][] = ['id'=> $row->id];
                        }
                    } catch (DomainException $e) {
                        $response['errors'][$row->id] = $e;
                    }
                }
            }
        }

        $class = self::path('controller');
        $controller = new $class;
        $html = '';
        $sid =  implode(',',array_column(@$response['data'],'id'));
        if($sid){
            foreach($controller->list([], $sid) as $row){
                $html .= view(self::path('view') . '.includes.tpl.tr', ['row' => $row])->render();
            }
            return [
                'success'   => true,
                'type'      => 'update',
                'data'      => $response['data'],
                'html'      => $html,
                'message'   => __('Create Successfully'),
            ];
        }

        return [
            'success'   =>  false,
            'type'      => 'update',
            'message'   => __('Create unsuccessful'),
            'errors' => @$response['errors'],
        ];
    }
    public static function deleteFromTable($id)
    {
        if ($id) {
            $id  = explode(',', $id);
            if (self::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = self::whereIn('id', $id)->delete();
                        if ($delete) {
                            return [
                                'success'   => true,
                                'message'   => __('Delete Successfully'),
                            ];
                        }
                    } catch (\Throwable $th) {
                        throw $th;
                    }
                }
            } else {
                return [
                    'success'   => false,
                    'message'   =>   __('No Data'),

                ];
            }
        } else {
            return [
                'success'   => false,
                'message'   =>  __('Please select data!'),

            ];
        }
    }

    public static function getTeaching($teacher_id)
    {
        $course_routine = StudyCourseRoutine::where('teacher_id', $teacher_id)->groupBy('study_course_session_id')->get()->toArray();
        $study_course_session_id = [];
        if ($course_routine) {
            foreach ($course_routine as $key => $value) {
                $study_course_session_id[] = $value['study_course_session_id'];
            }
        }
        return StudyCourseSession::getData($study_course_session_id);
    }

    /** Get Teacher teaching classes and subjects.
     * GroupBy classes
     * @param int $teacher_id
     */
    public static function getClassTeaching($teacher_id)
    {
        $course_routine = StudyCourseRoutine::where('teacher_id', $teacher_id)->groupBy(['study_course_session_id', 'study_class_id'])->get()->toArray();
        if ($course_routine) {
            $data = [];
            foreach ($course_routine as $row) {
                $class = StudyClass::where('id', $row['study_class_id'])->first(['id', app()->getLocale() . ' as name', 'image']);

                $data[] = [
                    'class' => [
                        'id'    => $class->id,
                        'name'    => $class->name,
                        'image'    => $class->image ? ImageHelper::site(StudyClass::path('image'), $class->image) : ImageHelper::prefix(),
                    ],
                    'subjects' => self::getSubjectsTeaching($teacher_id, $row['study_class_id']),
                ];
            }
            return [
                'success'    => true,
                'data'       => $data
            ];
        }
    }

    public static function getSubjectsTeaching($teacher_id, $study_class_id)
    {
        $course_routine = StudyCourseRoutine::where('teacher_id', $teacher_id)->where('teacher_id', $teacher_id)->groupBy(['study_course_session_id', 'study_class_id', 'study_subject_id'])->get()->toArray();
        if ($course_routine) {
            $data = [];
            foreach ($course_routine as $row) {
                $subject = StudySubjects::where('id', $row['study_subject_id'])->first(['id', app()->getLocale() . ' as name', 'image']);
                $data[] =   [
                    'id'    => $subject->id,
                    'name'    => $subject->name,
                    'image'    => $subject->image ? ImageHelper::site(StudySubjects::path('image'), $subject->image) : ImageHelper::prefix(),
                ];
            }
            return $data;
        }
    }
}
