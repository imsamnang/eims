<?php

namespace App\Models;

use DomainException;
use App\Helpers\ImageHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class BloodGroup extends Model
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
            'controller'   => 'App\Http\Controllers\\'.$tableUcwords.'\Controller',
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
        $validate = self::validate();
        $response           = array();
        $validator          = Validator::make(request()->all(), $validate['rules'], $validate['messages'], $validate['attributes']);

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {

                $values['name']        = trim(request('name'));
                $values['description'] = trim(request('description'));
                $values['image']       = null;

                if (config('app.languages')) {
                    foreach (config('app.languages') as $lang) {
                        $values[$lang['code_name']] = trim(request($lang['code_name']));
                    }
                }

                $add = BloodGroup::insertGetId($values);

                if ($add) {

                    if (request()->hasFile('image')) {
                        $image      = request()->file('image');
                        BloodGroup::updateImageToTable($add, ImageHelper::uploadImage($image, BloodGroup::path('image')));
                    } else {
                        ImageHelper::uploadImage(false, BloodGroup::path('image'), BloodGroup::path('image'), public_path('/assets/img/icons/image.jpg'), null, true);
                    }

                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'data'      => BloodGroup::getData($add)['data'],
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
        $validate = self::validate();
        $validator          = Validator::make(request()->all(), $validate['rules'], $validate['messages'], $validate['attributes']);

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {
                $values['name']        = trim(request('name'));
                $values['description'] = trim(request('description'));

                if (config('app.languages')) {
                    foreach (config('app.languages') as $lang) {
                        $values[$lang['code_name']] = trim(request($lang['code_name']));
                    }
                }

                $update = BloodGroup::where('id', $id)->update($values);

                if ($update) {
                    if (request()->hasFile('image')) {
                        $image      = request()->file('image');
                        BloodGroup::updateImageToTable($id, ImageHelper::uploadImage($image, BloodGroup::path('image')));
                    }
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'data'      => BloodGroup::getData($id),
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
                $update =  BloodGroup::where('id', $id)->update([
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
            if (BloodGroup::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = BloodGroup::whereIn('id', $id)->delete();
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
