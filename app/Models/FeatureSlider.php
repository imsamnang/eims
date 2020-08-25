<?php

namespace App\Models;

use App\Models\App as AppModel;
use DomainException;
use App\Helpers\ImageHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class FeatureSlider extends Model
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
            'requests'   => 'App\Http\Requests\Form' . $tableUcwords,
            'controller'   => 'App\Http\Controllers\Settings\\'. $tableUcwords.'Controller',
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
        return $key ? @$validate[$key] : $validate;
    }

    public static function getData($id = null, $paginate = null, $random = null)
    {
        $pages['form'] = array(
            'action'  => array(
                'add'    => url(Users::role() . '/' . AppModel::path('url') . '/' .  FeatureSlider::path('url') . '/add/'),
            ),
        );

        $orderBy = 'DESC';
        $data = array();
        if ($id) {
            $id  =  gettype($id) == 'array' ? $id : explode(',', $id);
            $sorted = array_values($id);
            sort($sorted);
            if ($id === $sorted) {
                $orderBy = 'ASC';
            } else {
                $orderBy = 'DESC';
            }
            $get = FeatureSlider::orderBy('id', $orderBy);
        }
        if ($random) {
            $get = FeatureSlider::orderByRaw('RAND()');
        } else {
            $get = FeatureSlider::orderBy('id', $orderBy);
        }

        if ($id) {
            $get = $get->whereIn('id', $id);
        } else {
            if (request('instituteId')) {
                $get = $get->where('institute_id', request('instituteId'));
            }
        }

        if ($paginate) {
            $get = $get->paginate($paginate)->toArray();
            foreach ($get as $key => $value) {
                if ($key == 'data') {
                } else {
                    $pages[$key] = $value;
                }
            }

            $get = $get['data'];
        } else {
            $get = $get->get()->toArray();
        }

        if ($get) {

            foreach ($get as $key => $row) {
                $data[$key]         = array(
                    'id'            => $row['id'],
                    'title'         => $row['title'],
                    'institute'     => Institute::getData($row['institute_id'])['data'][0],
                    'description'   => $row['description'],
                    'image'         => $row['image'] ? (ImageHelper::site(FeatureSlider::path('image'), $row['image'])) : ImageHelper::prefix(),                    'status'        => $key == 0 ? 'active' : '',
                    'action'        => [
                        'edit' => url(Users::role() . '/' . AppModel::path('url') . '/' . FeatureSlider::path('url') . '/edit/' . $row['id']),
                        'view' => url(Users::role() . '/' . AppModel::path('url') . '/' . FeatureSlider::path('url') . '/view/' . $row['id']),
                        'delete' => url(Users::role() . '/' . AppModel::path('url') . '/' . FeatureSlider::path('url') . '/delete/' . $row['id']),
                    ]
                );
                $pages['listData'][] = array(
                    'id'     => $data[$key]['id'],
                    'name'   => $data[$key]['title'],
                    'image'  => $data[$key]['image'],
                    'action' => $data[$key]['action'],

                );
            }

            $response       = array(
                'success'   => true,
                'data'      => $data,
                'pages'     => $pages,
            );
        } else {
            $response = array(
                'success'   => false,
                'data'      => [],
                'pages'     => $pages,
                'message'   => __('No Data'),
            );
        }

        return $response;
    }


    public static function addToTable()
    {
        $response           = array();

        $validate = self::validate();
        $rules  = $validate['rules'];
        $rules['image'] = 'required';
        $validator = Validator::make(request()->all(), $rules, $validate['messages'], $validate['attributes']);


        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {

                $values['institute_id']  = request('institute');
                $values['title']         = request('name');
                $values['description']   = request('description');
                $values['image']         = null;

                $add = FeatureSlider::insertGetId($values);

                if ($add) {

                    if (request()->hasFile('image')) {
                        $image = request()->file('image');
                        $image = ImageHelper::uploadImage($image, FeatureSlider::path('image'), null, null, true);
                        FeatureSlider::updateImageToTable($add, $image);
                    }
                    $class     = self::path('controller');
                    $controller  = new $class;
                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'html'      => view(self::path('view') . '.includes.tpl.tr', ['row' => $controller->list([], $add)[0]])->render(),
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
        $validate = self::validate();
        $validator          = Validator::make(request()->all(), $validate['rules'], $validate['messages'], $validate['attributes']);

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {
                $values['institute_id']  = request('institute');
                $values['title']         =request('name');
                $values['description']   =request('description');
                $update = FeatureSlider::where('id', $id)->update($values);

                if ($update) {
                    if (request()->hasFile('image')) {
                        $image      = request()->file('image');
                        $image = ImageHelper::uploadImage($image, FeatureSlider::path('image'), null, null, true);
                        FeatureSlider::updateImageToTable($id, $image);
                    }
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'data'      => FeatureSlider::getData($id),
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
                $update =  FeatureSlider::where('id', $id)->update([
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
            if (FeatureSlider::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = FeatureSlider::whereIn('id', $id)->delete();
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
