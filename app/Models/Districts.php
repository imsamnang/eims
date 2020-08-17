<?php

namespace App\Models;

use DomainException;
use App\Helpers\ImageHelper;
use App\Http\Requests\FormDistrict;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Districts extends Model
{
    public static $path = [
        'image'  => 'district',
        'url'    => 'district',
        'view'   => 'Cambodia'
    ];


    public function province()
    {
        return $this->hasMany(Provinces::class,  'id', 'province_id');
    }

    public static function addToTable()
    {

        $response           = array();
        $validator          = Validator::make(request()->all(), FormDistrict::rulesField(), FormDistrict::customMessages(), FormDistrict::attributeField());

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {

                $values['province_id'] = request('province');
                $values['name']        = trim(request('name'));
                $values['description'] = trim(request('description'));
                $values['image']       = null;

                if (config('app.languages')) {
                    foreach (config('app.languages') as $lang) {
                        $values[$lang['code_name']] = trim(request($lang['code_name']));
                    }
                }

                $add = Districts::insertGetId($values);

                if ($add) {

                    if (request()->hasFile('image')) {
                        $image      = request()->file('image');
                        Districts::updateImageToTable($add, ImageHelper::uploadImage($image, Districts::$path['image']));
                    } else {
                        ImageHelper::uploadImage(false, Districts::$path['image'], Districts::$path['image'], public_path('/assets/img/icons/image.jpg'), null, true);
                    }

                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'data'      => Districts::getData(null, $add)['data'],
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

    public static function updateToTable($id)
    {

        $response           = array();
        $validator          = Validator::make(request()->all(), FormDistrict::rulesField(), FormDistrict::customMessages(), FormDistrict::attributeField());

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {
                $values['province_id'] = request('province');
                $values['name']        = trim(request('name'));
                $values['description'] = trim(request('description'));

                if (config('app.languages')) {
                    foreach (config('app.languages') as $lang) {
                        $values[$lang['code_name']] = trim(request($lang['code_name']));
                    }
                }

                $update = Districts::where('id', $id)->update($values);

                if ($update) {
                    if (request()->hasFile('image')) {
                        $image      = request()->file('image');
                        Districts::updateImageToTable($id, ImageHelper::uploadImage($image, Districts::$path['image']));
                    }
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'data'      => Districts::getData($id),
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

    public static function updateImageToTable($id, $image)
    {
        $response = array(
            'success'   => false,
            'message'   => __('Update Failed'),
        );
        if ($image) {
            try {
                $update =  Districts::where('id', $id)->update([
                    'image'    => $image,
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

    public static function deleteFromTable($id)
    {
        if ($id) {
            $id  = explode(',', $id);
            if (Districts::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = Districts::whereIn('id', $id)->delete();
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
