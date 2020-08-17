<?php

namespace App\Models;

use DateTime;
use DomainException;
use App\Helpers\QRHelper;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\StudentsGuardians;
use App\Http\Requests\FormStudents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Students extends Model
{
    public static $path = [
        'image'   => 'student',
        'url'     => 'student',
        'view'    => 'Student',
        'role'    => 'student',
        'roleId'  => 6,
    ];

    public function institute()
    {
        return $this->hasMany(Institute::class,  'id', 'institute_id');
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
            $male   = [];
            $female = [];
            foreach ($query->get()->toArray() as  $row) {
                if ($row['gender_id']  == 1) {
                    if (isset($row['student_id'])) {
                        $male[$row['student_id']] = $row['student_id'];
                    } else {
                        $male[$row['id']] = $row['id'];
                    }
                } elseif ($row['gender_id']  == 2) {
                    if (isset($row['student_id'])) {
                        $female[$row['student_id']] = $row['student_id'];
                    } else {
                        $female[$row['id']] = $row['id'];
                    }
                };
            }

            return array(
                'male'      => [
                    'title' => __('Students male'),
                    'text'  => count($male) . ((app()->getLocale() == 'km') ? ' នាក់' : ' Poeple'),
                ],
                'female'    => [
                    'title' => __('Students female'),
                    'text'  => count($female) . ((app()->getLocale() == 'km') ? ' នាក់' : ' Poeple'),
                ],
                'total'      => [
                    'title' => __('Students total'),
                    'text'  => count($male) + count($female) . ((app()->getLocale() == 'km') ? ' នាក់' : ' Poeple'),
                ],
            );
        } else {
            return array(
                'male'      => [
                    'title' => __('Students male'),
                    'text'  => ((app()->getLocale() == 'km') ? '0 នាក់' : '0 Poeple'),
                ],
                'female'    => [
                    'title' => __('Students female'),
                    'text'  => ((app()->getLocale() == 'km') ? '0 នាក់' : '0 Poeple'),
                ],
                'total'      => [
                    'title' => __('Students total'),
                    'text'  => ((app()->getLocale() == 'km') ? '0 នាក់' : '0 Poeple'),
                ],
            );
        }
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


        $rules += FormStudents::rulesField();

        $validator          = Validator::make(request()->all(), $rules, FormStudents::customMessages(), FormStudents::attributeField());

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {
            try {
                $add = Students::insertGetId([
                    'institute_id'      => trim(request('institute')),
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
                    'email'              => strtolower(trim(request('email'))),
                    'extra_info'         => trim(request('student_extra_info')),
                    'photo'              => (request('gender') == '1') ? 'male.jpg' : 'female.jpg',

                ]);

                if ($add && StudentsGuardians::addToTable($add)) {
                    if (request()->hasFile('photo')) {
                        $photo      = request()->file('photo');
                        Students::updateImageToTable($add, ImageHelper::uploadImage($photo, Students::$path['image']));
                    } else {
                        ImageHelper::uploadImage(false, Students::$path['image'], (request('gender') == '1') ? 'male' : 'female', public_path('/assets/img/user/' . ((request('gender') == '1') ? 'male.jpg' : 'female.jpg')), true);
                    }

                    $response       = array(
                        'success'   => true,
                        // 'data'      => Students::getData($add)['data'][0],
                        'type'      => 'add',
                        'message'   => array(
                            'title' => __('Success'),
                            'text'  => __('Add Successfully'),
                            'button'   => array(
                                'confirm' => __('Ok'),
                                'cancel'  => __('Cancel'),
                            ),
                        ),
                    );
                }
            } catch (DomainException $e) {
                $response       = $e;
            }
        }
        return $response;
    }

    public static function register()
    {

        $rules = FormStudents::rulesField();

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
        $rules['phone'] = 'required|regex:/^([0-9\(\)\/\+ \-]*)$/|min:9|unique:students,phone';
        $rules['email'] = 'required|email|unique:students,email';

        $validator          = Validator::make(request()->all(), $rules, FormStudents::customMessages(), FormStudents::attributeField());
        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            $values = [
                'institute_id'     => trim(request('institute')),
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
                'extra_info'         => trim(request('student_extra_info')),
                'photo'              => (request('gender') == '1') ? 'male.jpg' : 'female.jpg',
            ];
            $add = Students::insertGetId($values);

            if ($add) {
                StudentsGuardians::insert(['student_id' => $add]);
                if (request()->hasFile('photo')) {
                    $photo      = request()->file('photo');
                    Students::updateImageToTable($add, ImageHelper::uploadImage($photo, Students::$path['image']));
                }

                $response       = array(
                    'success'   => true,
                    'type'      => 'add',
                    'message'   => array(
                        'title' => __('Success'),
                        'text'  => __('Register Successfully'),
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


    public static function updateToTable($id)
    {
        $response           = array();
        $validator          = Validator::make(request()->all(), FormStudents::rulesField(), FormStudents::customMessages(), FormStudents::attributeField());

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {
            try {

                $update = Students::where('id', $id)->update([
                    'institute_id'      => trim(request('institute')),
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
                    'extra_info'         => trim(request('student_extra_info')),
                ]);

                if ($update &&  StudentsGuardians::updateToTable($id)) {

                    if (request()->hasFile('photo')) {
                        $photo      = request()->file('photo');
                        Students::updateImageToTable($id, ImageHelper::uploadImage($photo, Students::$path['image']));
                    }

                    $response       = array(
                        'success'   => true,
                        'data'      => Students::getData($id)['data'][0],
                        'type'      => 'update',
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
            } catch (DomainException $e) {
                $response       = $e;
            }
        }
        return $response;
    }

    public static function updateImageToTable($student_id, $image)
    {
        $response = array(
            'success'   => false,
            'message'   => __('Update Failed'),
        );
        if ($image) {
            try {
                $update =  Students::where('id', $student_id)->update([
                    'photo'    => $image,
                ]);

                if ($update) {
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
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
            } catch (DomainException $e) {
                $response       = $e;
            }
        }
        return $response;
    }


    public static function makeQrCodeToTable($id = null, $options = null)
    {
        $response = array(
            'success'   => false,
            'type'   => 'makeQRCode',
            'data'   => [],
        );


        if ($id) {
            $make = Students::getData($id, true);
        } else {
            $make = Students::getData(null, true);
        }

        if ($make['success']) {
            $data = array();
            foreach ($make['data'] as $row) {
                $oldQrcode = $row['qrcode']['image'];
                if ($oldQrcode) {
                    if (file_exists(storage_path(ImageHelper::$path['image'] . '/' . Students::$path['image'] . '/' . QRHelper::$path['image'] . '/' . $oldQrcode))) {
                        unlink(storage_path(ImageHelper::$path['image'] . '/' . Students::$path['image'] . '/' . QRHelper::$path['image'] . '/' . $oldQrcode));
                    }
                }

                $date = new DateTime();
                $date->modify('+1 year');
                $q['size'] = $options && $options['code'] ? $options['code'] : 100;
                $q['code']  = Qrcode::encryptQrcode([
                    'id'    => $row['id'],
                    'type'  => Students::$path['role'],
                    'aYear'  =>  $row['study_academic_year_id']['id'],
                    'exp'  =>  $date->format('Y-m-d'),
                ]);

                if ($options && $options['image'] > 0) {
                    $q['center']  = array(
                        'image' => $row['photo'] . '?type=larg', //ImageHelper::getImage($row['photoName'], Students::$path['image'], true), //storage_path(ImageHelper::$path['image'] . '/' . Students::$path['image'] . '/' . ImageHelper::$path['resize'][0] . '/' . $row['photoName']),
                        'percentage' => $options && $options['image'] ? $options['image'] / $options['code']  : .19
                    );
                }

                $qrCode  = QRHelper::make($q, true);
                $qrCode_image = ImageHelper::uploadImage($qrCode, ImageHelper::$path['image'] . '/' . Students::$path['image'] . '/' . QRHelper::$path['image']);
                if ($qrCode_image) {

                    try {
                        Students::where('id', $row['id'])->update([
                            'qrcode'        => Qrcode::decryptQrcode($q['code']),
                            'qrcode_image'  => $qrCode_image,
                        ]);
                    } catch (DomainException $e) {
                        $response       = $e;
                    }

                    $data[] = ImageHelper::site(QRHelper::$path['image'], $qrCode_image);
                }
            }

            $response       = array(
                'success'   => true,
                'type'   => 'makeQRCode',
                'data'   => $data,
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


        return $response;
    }


    public static function deleteFromTable($id)
    {
        if ($id) {
            $id  = explode(',', $id);
            if (Students::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = Students::whereIn('id', $id)->delete();
                        if ($delete) {
                            $response       =  array(
                                'success'   => true,
                                'message'   => array(
                                    'title' => __('Deleted'),
                                    'text'  => __('Delete Successfully'),
                                    'button'   => array(
                                        'confirm' => __('Ok'),
                                        'cancel'  => __('Cancel'),
                                    ),
                                ),
                            );
                        }
                    } catch (\Exception $e) {
                        $response       = $e;
                    }
                } else {
                    $response = response(
                        array(
                            'success'   => true,
                            'message'   => array(
                                'title' => __('Are you sure?'),
                                'text'  => __('You wont be able to revert this!') . PHP_EOL .
                                    'ID : (' . implode(',', $id) . ')',
                                'button'   => array(
                                    'confirm' => __('Yes delete!'),
                                    'cancel'  => __('Cancel'),
                                ),
                            ),
                        )
                    );
                }
            } else {
                $response = response(
                    array(
                        'success'   => false,
                        'message'   => array(
                            'title' => __('Error'),
                            'text'  => __('No Data'),
                            'button'   => array(
                                'confirm' => __('Ok'),
                                'cancel'  => __('Cancel'),
                            ),
                        ),
                    )
                );
            }
        } else {
            $response = response(
                array(
                    'success'   => false,
                    'message'   => array(
                        'title' => __('Error'),
                        'text'  => __('Please select data!'),
                        'button'   => array(
                            'confirm' => __('Ok'),
                            'cancel'  => __('Cancel'),
                        ),
                    ),
                )
            );
        }
        return $response;
    }
}
