<?php

namespace App\Models;

use DomainException;
use App\Helpers\ImageHelper;
use App\Http\Requests\FormCard;
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
        $rules = (new FormCard)->rules();
        $rules['name'] = 'required|unique:' . (new CardFrames)->getTable() . ',name';
        $rules['foreground'] = 'required';
        $rules['background'] = 'required';

        $validator          = Validator::make(request()->all(), $rules, (new FormCard)->messages(), (new FormCard)->attributes());

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


                $add = CardFrames::insertGetId($values);

                if ($add) {

                    if (request()->hasFile('foreground')) {
                        $image      = request()->file('foreground');
                        CardFrames::updateImageToTable($add, ImageHelper::uploadImage($image, CardFrames::path('image')), 'foreground');
                    }
                    if (request()->hasFile('background')) {
                        $image      = request()->file('background');
                        CardFrames::updateImageToTable($add, ImageHelper::uploadImage($image, CardFrames::path('image')), 'background');
                    }
                    $controller = new CardFramesController;
                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'html'      => view(CardFrames::path('view') . '.includes.tpl.tr', ['row' => $controller->list([], $add)[0]])->render(),
                        'message'   =>  __('Add Successfully')
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
        $rules = (new FormCard)->rules();
        $rules['name'] = 'required|unique:' . (new CardFrames)->getTable() . ',name,' . $id;
        $validator          = Validator::make(request()->all(), $rules, (new FormCard)->messages(), (new FormCard)->attributes());

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


                $update = CardFrames::where('id', $id)->update($values);
                if ($update) {
                    if (request()->hasFile('foreground')) {
                        $image      = request()->file('foreground');
                        CardFrames::updateImageToTable($id, ImageHelper::uploadImage($image, CardFrames::path('image')), 'foreground');
                    }
                    if (request()->hasFile('background')) {
                        $image      = request()->file('background');
                        CardFrames::updateImageToTable($id, ImageHelper::uploadImage($image, CardFrames::path('image')), 'background');
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
                        'html'      => view(CardFrames::path('view') . '.includes.tpl.tr', ['row' => $controller->list([], $id)[0]])->render(),
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
                $update =  CardFrames::where('id', $id)->update([
                    $column    => $image,
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
                    CardFrames::where('status', 1)->update([
                        'status' => 0,
                    ]);
                    $update = CardFrames::where('id', $id)->update([
                        'status' => 1,
                    ]);
                    if ($update) {
                        $response       = array(
                            'success'   => true,
                            'data'      => CardFrames::where('id', $id)->get(['foreground', 'background', 'layout'])->map(function ($row) {
                                $row['foreground'] = ImageHelper::site(CardFrames::path('image'), $row->foreground, 'large');
                                $row['background'] = ImageHelper::site(CardFrames::path('image'), $row->background, 'large');
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
            if (CardFrames::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = CardFrames::whereIn('id', $id)->delete();
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
