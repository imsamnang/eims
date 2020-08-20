<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use DomainException;

use App\Helpers\DateHelper;

use App\Helpers\ImageHelper;
use App\Http\Requests\FormStaff;
use App\Http\Requests\FormUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Validator;
use Laravolt\Avatar\Avatar;

class Users extends Model
{
    public static $path = [
        'image'  => 'profile',
        'url'    => 'user',
        'view'   => 'Users'
    ];

    public function institute()
    {
        return $this->hasMany(Institute::class,  'id', 'institute_id');
    }

    public static function role($get = null)
    {
        if (Auth::user()) {
            $roles = Roles::where('id', Auth::user()->role_id)->get()->toArray();
            if ($get) {
                return $roles[0][$get];
            }
            return $roles[0]['name'];
        }
        return null;
    }

    public static function addToTable()
    {

        $response           = array();

        if (request('role') != 1 && request('role') != 9) {
            if (!request('reference')) {
                $empty = (request('role') == 6 || request('role') == 7) ? __('Students required') : __('Staff required');
                return array(
                    'success'   => false,
                    'type'      => 'add',
                    'data'      => [],
                    'message'   => array(
                        'title' => __('Error'),
                        'text'  => __('Add Unsuccessful') . PHP_EOL . $empty,
                        'button'   => array(
                            'confirm' => __('Ok'),
                            'cancel'  => __('Cancel'),
                        ),
                    ),
                );
            }
        }
        $validator          = Validator::make(request()->all(), FormUsers::rulesField(), FormUsers::customMessages(), FormUsers::attributeField());
        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {
            $existsEmail = Users::existsFromTable(request('email'));
            if ($existsEmail) {
                $response = array(
                    'success'   => false,
                    'type'      => 'add',
                    'data'      => [],
                    'message'   => array(
                        'title' => __('Error'),
                        'text'  => __('Already exists email'),
                        'button'   => array(
                            'confirm' => __('Ok'),
                            'cancel'  => __('Cancel'),
                        ),
                    ),
                );
            } else {
                try {
                    $add = Users::insertGetId([
                        'institute_id' => trim(request('institute')),
                        'name'        => trim(request('name')),
                        'phone'       => trim(request('phone')),
                        'email'       => trim(request('email')),
                        'password'    => Hash::make(trim(request('password'))),
                        'address'     => trim(request('address')),
                        'location'    => trim(request('location')),
                        'role_id'     => trim(request('role')),
                        'node_id'     => trim(request('reference')),
                        'profile'     => null,
                    ]);

                    if ($add) {



                        if (request()->hasFile('profile')) {
                            $image      = request()->file('profile');
                            Users::updateImageToTable($add, ImageHelper::uploadImage($image, Users::$path['image']));
                        }

                        $response       = array(
                            'success'   => true,
                            'type'      => 'add',
                            'data'      => Users::getData($add),
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
                    return $e;
                }
            }
        }
        return $response;
    }

    public static function updateToTable($id)
    {

        $response           = array();

        $rule = [];
        foreach (FormUsers::rulesField() as $key => $value) {
            if ($key != 'password')
                $rule[$key] = $value;
        }


        $validator          = Validator::make(request()->all(), $rule, FormUsers::customMessages(), FormUsers::attributeField());
        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {
                $value = [
                    'institute_id' => trim(request('institute')),
                    'name'        => trim(request('name')),
                    'phone'       => trim(request('phone')),
                    'email'       => trim(request('email')),
                    'address'     => trim(request('address')),
                    'location'    => trim(request('location')),
                    'role_id'     => trim(request('role')),
                    'node_id'     => trim(request('reference')),
                ];
                if (request('password')) {
                    $value['password'] += Hash::make(trim(request('password')));
                }

                $update = Users::where('id', $id)->update($value);
                if ($update) {
                    if (request()->hasFile('profile')) {
                        $image      = request()->file('profile');
                        Users::updateImageToTable($id, ImageHelper::uploadImage($image, Users::$path['image']));
                    }
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'data'      => Users::getData($id),
                        'message'   => __('Update Successfully'),
                    );
                }
            } catch (DomainException $e) {
                return $e;
            }
        }
        return $response;
    }

    public static function updateImageToTable($id, $image)
    {
        $response = array(
            'success'   => false,
            'message'   => __('Update Failed'),
        );
        if ($image) {
            try {
                $update =  Users::where('id', $id)->update([
                    'profile'    => $image,
                ]);

                if ($update) {
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'message'   => __('Update Successfully'),
                    );
                }
            } catch (DomainException $e) {
                return $e;
            }
        }

        return $response;
    }

    public static function deleteFromTable($id)
    {
        if ($id) {
            $id  = explode(',', $id);
            if (Users::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = Users::whereIn('id', $id)->delete();
                        if ($delete) {
                           return [
                                'success'   => true,
                                'message'   => __('Delete Successfully'),
                            ];
                        }
                    } catch (\Exception $e) {
                        return $e;
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
        return $response;
    }
    public static function getUsers($id = null)
    {
        $response = array(
            'success'   => false,
            'message'   => __('No Data')
        );
        if ($id) {
            $get = Users::where('id', $id)->get()->toArray();
        } else {
            $get = Users::get()->toArray();
        }

        if ($get) {
            $data = array();
            foreach ($get as $row) {
                $socail_auth = SocialAuth::getData(null, $row['id']);
                $profile = null;
                if ($row['profile']) {
                    $profile = ImageHelper::site('profile', $row['profile']);
                } elseif ($socail_auth) {
                    $profile = $socail_auth['_avatar'];
                }
                $data[] = array(
                    'id'       => $row['id'],
                    'name'     => $row['name'],
                    'phone'    => $row['phone'],
                    'email'    => $row['email'],
                    'address'  => $row['address'],
                    'location' => $row['location'],
                    'profile'  => $profile,
                );
            }

            $response = array(
                'success'   => true,
                'data'      => $data
            );
        }

        return $response;
    }
    public static function existsFromTable($email, $node_id = null)
    {
        $get  = Users::where('email', $email);
        if ($node_id) {
            $get = $get->where('node_id', $node_id);
        }
        return $get->first();
    }
    public static function register()
    {
        $validator          = Validator::make(request()->all(), FormUsers::rulesField2(), FormStaff::customMessages(), FormStaff::attributeField());
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
                'blood_group_id'   => request('blood_group'),
                //'photo'            => (request('gender') == '1') ? 'male.jpg' : 'female.jpg',
            ];
            $add = Students::insertGetId($values);
            if ($add) {
                StudentsGuardians::insert(['student_id' => $add]);
                Users::where('id', Auth::user()->id)->update([
                    'node_id' => $add,
                    'institute_id' => request('institute'),
                    'role_id' => Students::$path['roleId'],
                ]);
                //ImageHelper::uploadImage(false, Students::$path['image'], (request('gender') == '1') ? 'male' : 'female', public_path('/assets/img/user/' . ((request('gender') == '1') ? 'male.jpg' : 'female.jpg')), true);
            }
            // if (request('teacher_or_student') == 6) {
            // } else {
            //     $add = Students::insertGetId($values);
            //     if ($add) {
            //         StaffInstitutes::insert([
            //             'staff_id' => $add,
            //             'institute_id' => request('institute'),
            //             'designation_id'  => 2
            //         ]);

            //         StaffGuardians::insert([
            //             'staff_id' => $add,
            //         ]);

            //         StaffQualifications::insert([
            //             'staff_id' => $add,
            //         ]);

            //         StaffExperience::insert([
            //             'staff_id' => $add,
            //         ]);

            //         Users::where('id', Auth::user()->id)->update([
            //             'node_id' => $add,
            //             'role_id' => 8,
            //         ]);
            //         //ImageHelper::uploadImage(false, Staff::$path['image'], (request('gender') == '1') ? 'male' : 'female', public_path('/assets/img/user/' . ((request('gender') == '1') ? 'male.jpg' : 'female.jpg')), true);
            //     }
            // }

            if ($add) {
                $response       = array(
                    'success'   => true,
                    'type'      => 'add',
                    'reload'    => true,
                    'message'   => array(
                        'title' => __('Success'),
                        'text'  => __('Register Successfully') . PHP_EOL
                            . __('Reload page'),
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
}
