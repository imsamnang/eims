<?php

namespace App\Models;

use DomainException;
use App\Helpers\ImageHelper;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\FormStaffCertificate;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Staff\StaffCertificateFramesController;
use Illuminate\Support\Facades\Auth;

class StaffCertificate extends Model
{
    /**
     *  @param string $key
     *  @param string|array $key
     */
    public static function path($key = null)
    {
        $table = (new self)->getTable();
        $tableUcwords = str_replace(' ', '', ucwords(str_replace('_', ' ', $table)));

        $path = [
            'image'  => $table,
            'url'    => str_replace('_', '-', $table),
            'view'   => $tableUcwords,
            'requests'   => 'App\Http\Requests\Form'.$tableUcwords,
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
            'attributes'  =>  $formRequests->attributes($flag),
            'messages'    =>  $formRequests->messages($flag),
            'questions'   =>  $formRequests->questions($flag),
        ];
        return $key? @$validate[$key] : $validate;
    }


    public static function addToTable()
    {

        $response           = array();
        $validator          = Validator::make(request()->all(), FormStaffCertificate::rules(), FormStaffCertificate::messages(), FormStaffCertificate::attributes());

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {
                $values['institute_id'] = Auth::user()->institute_id;
                $values['name']        = request('name');
                $values['description'] = request('description');

                if (config('app.languages')) {
                    foreach (config('app.languages') as $lang) {
                        $values[$lang['code_name']] = request($lang['code_name']);
                    }
                }

                $add = StaffCertificate::insertGetId($values);

                if ($add) {

                    if (request()->hasFile('image')) {
                        $image      = request()->file('image');
                        StaffCertificate::updateImageToTable($add, ImageHelper::uploadImage($image, StaffCertificate::path('image')));
                    }

                    $controller = new StaffCertificateFramesController;

                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'html'      => view(StaffCertificate::path('view') . '.includes.tpl.tr', ['row' => $controller->list([], $add)[0]])->render(),
                        'message'   => __('Add Successfully'),
                    );
                }
            } catch (DomainException $e) {
                return $e;
            }
        }
        return $response;
    }

    public static function updateToTable($id)
    {

        $response           = array();
        $validator          = Validator::make(request()->all(), FormStaffCertificate::rules(), FormStaffCertificate::messages(), FormStaffCertificate::attributes());

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {
                $values['institute_id'] = Auth::user()->institute_id;
                $values['name']        = request('name');
                $values['description'] = request('description');

                if (config('app.languages')) {
                    foreach (config('app.languages') as $lang) {
                        $values[$lang['code_name']] = request($lang['code_name']);
                    }
                }

                $update = StaffCertificate::where('id', $id)->update($values);
                if ($update) {
                    if (request()->hasFile('image')) {
                        $image      = request()->file('image');
                        StaffCertificate::updateImageToTable($id, ImageHelper::uploadImage($image, StaffCertificate::path('image')));
                    }
                    $controller = new StaffCertificateFramesController;
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'data'      => [
                            [
                                'id' => $id,
                            ]

                        ],
                        'html'      => view(StaffCertificate::path('view') . '.includes.tpl.tr', ['row' => $controller->list([], $id)[0]])->render(),
                        'message'   =>  __('Update Successfully')
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
                $update =  StaffCertificate::where('id', $id)->update([
                    'image'    => $image,
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
            if (StaffCertificate::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = StaffCertificate::whereIn('id', $id)->delete();
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
    }
}
