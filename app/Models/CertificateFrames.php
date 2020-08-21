<?php

namespace App\Models;

use DomainException;
use App\Helpers\ImageHelper;
use App\Http\Controllers\Certificate\CertificateController;
use App\Http\Requests\FormCertificate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class CertificateFrames extends Model
{
    public static $path = [
        'image'  => 'certificate',
        'url'    => 'certificate',
        'view'   => 'Certificate'
    ];


    public static function addToTable()
    {

        if (!request()->hasFile('foreground')) {
            return array(
                'success'   => false,
                'type'      => 'add',
                'message'   => __('Add Unsuccessful') . PHP_EOL
                    . __('Frame foreground empty'),
            );
        }
        $response           = array();
        $rules = FormCertificate::rulesField();
        $rules['name'] = 'required|unique:' . (new CertificateFrames)->getTable() . ',name';

        $validator          = Validator::make(request()->all(), $rules, FormCertificate::customMessages(), FormCertificate::attributeField());

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {
                $values['institute_id'] = request('institute');
                $values['name']        = request('name');
                $values['type']        = request('type');
                $values['layout']      = request('layout');
                $values['description'] = request('description');



                $add = CertificateFrames::insertGetId($values);
                if ($add) {

                    if (request()->hasFile('foreground')) {
                        $image      = request()->file('foreground');
                        CertificateFrames::updateImageToTable($add, ImageHelper::uploadImage($image, CertificateFrames::$path['image']), 'foreground');
                    }

                    $controller = new CertificateController;
                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'html'      => view(CertificateFrames::$path['view'] . '.includes.tpl.tr', ['row' => $controller->list([], $add)[0]])->render(),
                        'message'   =>  __('Update Successfully')
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
        $rules = FormCertificate::rulesField();
        $rules['name'] = 'required|unique:' . (new CertificateFrames)->getTable() . ',name,' . $id;
        $validator          = Validator::make(request()->all(), $rules, FormCertificate::customMessages(), FormCertificate::attributeField());

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {
                $values['institute_id'] = request('institute');
                $values['name']        = request('name');
                $values['type']        = request('type');
                $values['layout']     = request('layout');
                $values['description'] = request('description');

                $update = CertificateFrames::where('id', $id)->update($values);
                if ($update) {
                    if (request()->hasFile('foreground')) {
                        $image      = request()->file('foreground');
                        CertificateFrames::updateImageToTable($id, ImageHelper::uploadImage($image, CertificateFrames::$path['image']), 'foreground');
                    }
                    $controller = new CertificateController;
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'html'      => view(CertificateFrames::$path['view'] . '.includes.tpl.tr', ['row' => $controller->list([], $id)[0]])->render(),
                        'message'   =>  __('Update Successfully')
                    );
                }
            } catch (DomainException $e) {
                return $e;
            }
        }
        return $response;
    }

    public static function updateImageToTable($id, $image, $column)
    {
        $response = array(
            'success'   => false,
            'message'   => __('Update Failed'),
        );
        if ($image) {
            try {
                $update =  CertificateFrames::where('id', $id)->update([
                    $column   => $image,
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


    public static function frameData($get = 'all')
    {
        if ($get == 'all') {
            $get = array(
                'id'           => __('Id'),
                'fullname'     => __('Fullname'),
                '_fullname'    => __('Fullname Latin'),
                'photo'        => __('Photo'),
                'program'       => __('Study program'),
                '_program'       => __('Study program Latin'),
                'course'       => __('Course'),
                '_course'       => __('Course Latin'),
                'dob'       => __('Dob'),
                '_dob'       => __('Dob Latin'),
            );
        } else if ($get == 'selected') {
            $get = array(
                'id'           => __('Id'),
                'fullname'     => __('Fullname'),
                '_fullname'    => __('Fullname Latin'),
                'photo'        => __('Photo'),
                'program'       => __('Study program'),
                '_program'       => __('Study program Latin'),
                'course'       => __('Course'),
                '_course'       => __('Course Latin'),
                'dob'       => __('Dob'),
                '_dob'       => __('Dob Latin'),

            );
        } else {
            $get = [];
        }
        return $get;
    }

    public static function setToTable($id)
    {
        $response = array(
            'success'   => false,
            'message'   => __('Update Failed'),
        );
        if ($id && request()->ajax()) {
            if (request()->method() == 'POST') {
                try {
                    CertificateFrames::where('status', 1)->update([
                        'status' => 0,
                    ]);
                    $update = CertificateFrames::where('id', $id)->update([
                        'status' => 1,
                    ]);
                    if ($update) {
                        $response       = array(
                            'success'   => true,
                            'data'      => CertificateFrames::where('id', $id)->get(['foreground', 'background', 'layout'])->map(function ($row) {
                                $row['foreground'] = ImageHelper::site(CertificateFrames::$path['image'], $row->foreground, 'original');
                                $row['background'] = ImageHelper::site(CertificateFrames::$path['image'], $row->background, 'original');
                                return $row;
                            })->first(),
                            'message'   => __('Set as default successfully'),
                        );
                    }
                } catch (DomainException $e) {
                    return $e;
                }
            }
        }
        return $response;
    }
    public static function deleteFromTable($id)
    {
        if ($id) {
            $id  = explode(',', $id);
            if (CertificateFrames::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = CertificateFrames::whereIn('id', $id)->delete();
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
}
