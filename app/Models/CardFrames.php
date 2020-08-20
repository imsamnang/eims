<?php

namespace App\Models;

use DomainException;
use App\Helpers\ImageHelper;
use App\Http\Requests\FormCard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Card\CardController;

class CardFrames extends Model
{
    public static $path = [
        'image'  => 'card',
        'url'    => 'card',
        'view'   => 'Card'
    ];

    public static function addToTable()
    {
        $response           = array();

        if (!request()->hasFile('front')) {
            return array(
                'success'   => false,
                'type'      => 'add',
                'message'   => __('Add Unsuccessful') . PHP_EOL
                    . __('Frame Front empty'),
            );
        }
        if (!request()->hasFile('background')) {
            return array(
                'success'   => false,
                'type'      => 'add',
                'message'   => __('Add Unsuccessful') . PHP_EOL
                    . __('Frame Background empty'),
            );
        }

        $rules = FormCard::rulesField();
        $rules['name'] = 'required|unique:' . (new CardFrames)->getTable() . ',name';

        $validator          = Validator::make(request()->all(), $rules, FormCard::customMessages(), FormCard::attributeField());

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

                    if (request()->hasFile('front')) {
                        $image      = request()->file('front');
                        CardFrames::updateImageToTable($add, ImageHelper::uploadImage($image, CardFrames::$path['image']), 'front');
                    }
                    if (request()->hasFile('background')) {
                        $image      = request()->file('background');
                        CardFrames::updateImageToTable($add, ImageHelper::uploadImage($image, CardFrames::$path['image']), 'background');
                    }
                    $controller = new CardController;
                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'html'      => view(CardFrames::$path['view'] . '.includes.tpl.tr', ['row' => $controller->list([], $add)[0]])->render(),
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
        $rules = FormCard::rulesField();
        $rules['name'] = 'required|unique:' . (new CardFrames)->getTable() . ',name,' . $id;
        $validator          = Validator::make(request()->all(), $rules, FormCard::customMessages(), FormCard::attributeField());

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
                    if (request()->hasFile('front')) {
                        $image      = request()->file('front');
                        CardFrames::updateImageToTable($id, ImageHelper::uploadImage($image, CardFrames::$path['image']), 'front');
                    }
                    if (request()->hasFile('background')) {
                        $image      = request()->file('background');
                        CardFrames::updateImageToTable($id, ImageHelper::uploadImage($image, CardFrames::$path['image']), 'background');
                    }
                    $controller = new CardController;
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'data'      => [
                            [
                                'id' => $id,
                            ]

                        ],
                        'html'      => view(CardFrames::$path['view'] . '.includes.tpl.tr', ['row' => $controller->list([], $id)[0]])->render(),
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
                            'data'      => CardFrames::getData($id, true)['data'][0],
                            'message'   => array(
                                'title' => __('Success'),
                                'text'  => __('Set as default successfully'),
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
        return $response;
    }
}
