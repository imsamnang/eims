<?php

namespace App\Models;

use DomainException;
use App\Helpers\ImageHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\CardFrames\CardFramesController;

class CardFrames extends Model
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
            'table'  => $table,
            'image'  => $table,
            'url'    => str_replace('_', '-', $table),
            'view'   => $tableUcwords,
            'requests'   => 'App\Http\Requests\Form'.$tableUcwords,
            'controller'   => 'App\Http\Controllers\\'.$tableUcwords.'Controller',
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
    public static function addToTable()
    {
        $response           = array();
        $validate = self::validate();
        $rules = $validate['rules'];
        $rules['name'] = 'required|unique:' . (new CardFrames)->getTable() . ',name';
        $rules['foreground'] = 'required';
        $rules['background'] = 'required';

        $validator          = Validator::make(request()->all(), $rules, $validate['messages'], $validate['attributes']);

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
                $values['status'] = 0;


                $add = self::insertGetId($values);

                if ($add) {
                    if (request()->hasFile('foreground')) {
                        $image    = request()->file('foreground');
                        $image   = ImageHelper::uploadImage($image, self::path('image'));
                        self::updateImageToTable($add, $image,'foreground');
                    }
                    if (request()->hasFile('background')) {
                        $image    = request()->file('background');
                        $image   = ImageHelper::uploadImage($image, self::path('image'));
                        self::updateImageToTable($add, $image,'background');
                    }
                    $class  = self::path('controller');
                    $controller = new $class;
                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'html'      => view(self::path('view') . '.includes.tpl.tr', ['row' => $controller->list([], $add)[0]])->render(),
                        'message'   => __('Add Successfully'),
                    );
                }
           } catch (\Throwable $th) {
                        throw $th;
                    }
        }
        return $response;
    }

    public static function updateToTable($id)
    {

        $response           = array();
        $validate = self::validate();
        $rules = $validate['rules'];
        $rules['name'] = 'required|unique:' . (new CardFrames)->getTable() . ',name,' . $id;
        $validator          = Validator::make(request()->all(), $rules, $validate['messages'], $validate['attributes']);

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


                $update = self::where('id', $id)->update($values);
                if ($update) {
                    if (request()->hasFile('foreground')) {
                        $image      = request()->file('foreground');
                        self::updateImageToTable($id, ImageHelper::uploadImage($image, self::path('image')), 'foreground');
                    }
                    if (request()->hasFile('background')) {
                        $image      = request()->file('background');
                        self::updateImageToTable($id, ImageHelper::uploadImage($image, self::path('image')), 'background');
                    }
                    $controller = new CardFramesController;
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'data'      => [
                            [
                                'id' => $id,
                            ]

                        ],
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

    public static function updateImageToTable($id, $image, $column)
    {
        $response = array(
            'success'   => false,
            'message'   => __('Update Failed'),
        );
        if ($image) {
            try {
                $update =  self::where('id', $id)->update([
                    $column    => $image,
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


    public static function frameData($get = 'all')
    {
        if ($get == 'all') {
            $get = array(
                'id'           => __('Id'),
                'fullname'     => __('Fullname'),
                '_fullname'    => __('Fullname Latin'),
                'photo'        => __('Photo'),
                'qrcode'       => __('Qrcode'),
                'gender'       => __('Gender'),
                'course'       => __('Course'),
            );
        } else if ($get == 'selected') {
            $get = array(
                'id'           => __('Id'),
                'fullname'     => __('Fullname'),
                '_fullname'    => __('Fullname Latin'),
                'photo'        => __('Photo'),
                'qrcode'       => __('Qrcode'),
                'gender'       => __('Gender'),
                'course'       => __('Course'),
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
                    self::where('status', 1)->update([
                        'status' => 0,
                    ]);
                    $update = self::where('id', $id)->update([
                        'status' => 1,
                    ]);
                    if ($update) {
                        $response       = array(
                            'success'   => true,
                            'data'      => self::where('id', $id)->get(['foreground', 'background', 'layout'])->map(function ($row) {
                                $row['foreground'] = ImageHelper::site(self::path('image'), $row->foreground, 'large');
                                $row['background'] = ImageHelper::site(self::path('image'), $row->background, 'large');
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
}
