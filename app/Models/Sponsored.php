<?php

namespace App\Models;

use DomainException;


use App\Helpers\ImageHelper;
use App\Http\Requests\FormSponsored;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Validator;

class Sponsored extends Model
{
    public static $path = [
        'image'  => 'donate',
        'url'    => 'sponsored',
        'view'   => 'Sponsored'
    ];

    public static function getData($id = null, $paginate = null, $random = null)
    {
        $pages['form'] = array(
            'action'  => array(
                'add'    => url(Users::role() . '/' . App::$path['url'] . '/' . Sponsored::$path['url'] . '/add/'),
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
            $get = Sponsored::orderBy('id', $orderBy);
        }
        if ($random) {
            $get = Sponsored::orderByRaw('RAND()');
        } else {
            $get = Sponsored::orderBy('id', $orderBy);
        }

        if ($id) {
            $get = $get->whereIn('id', $id);
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
                    'name'          => $row['name'],
                    'link'          => $row['link'],
                    'description'   => $row['description'],
                    'image'         => ImageHelper::site(Sponsored::$path['image'], $row['image']),
                    'status'        => $key == 0 ? 'active' : '',
                    'action'        => [
                        'edit' => url(Users::role() . '/' . App::$path['url'] . '/' . Sponsored::$path['url'] . '/edit/' . $row['id']),
                        'view' => url(Users::role() . '/' . App::$path['url'] . '/' . Sponsored::$path['url'] . '/view/' . $row['id']),
                        'delete' => url(Users::role() . '/' . App::$path['url'] . '/' . Sponsored::$path['url'] . '/delete/' . $row['id']),
                    ]
                );
                $pages['listData'][] = array(
                    'id'     => $data[$key]['id'],
                    'name'   => $data[$key]['name'],
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

    public static function getDataTable()
    {
        $model = Sponsored::query();
        return DataTables::eloquent($model)
            ->setTransformer(function ($row) {
                $row = $row->toArray();
                return [
                    'id'            => $row['id'],
                    'name'          => $row['name'],
                    'link'          => $row['link'],
                    'description'   => $row['description'],
                    'image'         => ImageHelper::site(Sponsored::$path['image'], $row['image']),
                    'action'        => [
                        'edit' => url(Users::role() . '/' . App::$path['url'] . '/' . Sponsored::$path['url'] . '/edit/' . $row['id']),
                        'view' => url(Users::role() . '/' . App::$path['url'] . '/' . Sponsored::$path['url'] . '/view/' . $row['id']),
                        'delete' => url(Users::role() . '/' . App::$path['url'] . '/' . Sponsored::$path['url'] . '/delete/' . $row['id']),
                    ]

                ];
            })
            ->filter(function ($query) {

                if (request('search.value')) {
                    foreach (request('columns') as $i => $value) {
                        if ($value['searchable']) {
                            if ($value['data'] == 'name') {
                                $query =  $query->where(function ($q) {
                                    $q->where('name', 'LIKE', '%' . request('search.value') . '%');
                                });
                            } elseif ($value['data'] == 'description') {
                                $query->orWhere('description', 'LIKE', '%' . request('search.value') . '%');
                            }
                        }
                    }
                }

                return $query;
            })
            ->order(function ($query) {
                if (request('order')) {
                    foreach (request('order') as $order) {
                        $col = request('columns')[$order['column']];
                        if ($col['data'] == 'id') {
                            $query->orderBy('id', $order['dir']);
                        }
                    }
                }
            })
            ->toJson();
    }


    public static function addToTable()
    {

        $response           = array();
        $validator          = Validator::make(request()->all(), FormSponsored::rulesField(), FormSponsored::customMessages(), FormSponsored::attributeField());

        if (!request()->hasFile('image')) {
            return array(
                'success'   => false,
                'type'      => 'add',
                'message'   => array(
                    'title' => __('Error'),
                    'text'  => __('Add Unsuccessful') . PHP_EOL
                        . __('Image empty'),
                    'button'   => array(
                        'confirm' => __('Ok'),
                        'cancel'  => __('Cancel'),
                    ),
                ),
            );
        }
        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {

                $values['name']          = trim(request('name'));
                $values['link']          = trim(request('link'));
                $values['description']   = trim(request('description'));
                $values['image']         = null;

                $add = Sponsored::insertGetId($values);

                if ($add) {

                    if (request()->hasFile('image')) {
                        $image      = request()->file('image');
                        Sponsored::updateImageToTable($add, ImageHelper::uploadImage($image, Sponsored::$path['image']));
                    }

                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'data'      => Sponsored::getData($add)['data'],
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
        $validator          = Validator::make(request()->all(), FormSponsored::rulesField(), FormSponsored::customMessages(), FormSponsored::attributeField());

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {
                $values['name']          = trim(request('name'));
                $values['link']          = trim(request('link'));
                $values['description']   = trim(request('description'));
                $update = Sponsored::where('id', $id)->update($values);

                if ($update) {
                    if (request()->hasFile('image')) {
                        $image      = request()->file('image');
                        Sponsored::updateImageToTable($id, ImageHelper::uploadImage($image, Sponsored::$path['image']));
                    }
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'data'      => Sponsored::getData($id),
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
                $update =  Sponsored::where('id', $id)->update([
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
            if (Sponsored::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = Sponsored::whereIn('id', $id)->delete();
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
