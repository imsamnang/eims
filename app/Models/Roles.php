<?php

namespace App\Models;

use DomainException;


use App\Helpers\ImageHelper;
use App\Http\Requests\FormRoles;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Validator;

class Roles extends Model
{
    public static $path = [
        'image'  => 'role',
        'url'    => 'role',
        'view'   => 'Roles'
    ];

    public static function getData($id = null, $edit = null, $paginate = null)
    {
        $pages['form'] = array(
            'action'  => array(
                'add'    => url(Users::role() . '/' . App::$path['url'] . '/' . Roles::$path['url'] . '/add/'),
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
        }
        $get = Roles::orderBy('id', $orderBy);

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
                // if( $row['id'] == 1 && Auth::user()->role_id != 1){
                //     continue;
                // }

                $data[$key]         = array(
                    'id'            => $row['id'],
                    'view_path'     => $row['view_path'],
                    'name'          => $row[app()->getLocale()] ? $row[app()->getLocale()] : $row['name'],
                    'description'   => $row['description'],
                    'image'         =>  $row['image'] ? (ImageHelper::site(Roles::$path['image'], $row['image'])) : ImageHelper::prefix(),
                    'action'        => [
                        'edit' => url(Users::role() . '/' . Roles::$path['url'] . '/edit/' . $row['id']),
                        'view' => url(Users::role() . '/' . Roles::$path['url'] . '/view/' . $row['id']),
                        'delete' => url(Users::role() . '/' . Roles::$path['url'] . '/delete/' . $row['id']),
                    ]
                );
                $pages['listData'][] = array(
                    'id'     => $data[$key]['id'],
                    'name'   => $data[$key]['name'],
                    'image'  => $data[$key]['image'],
                    'action' => $data[$key]['action'],

                );
                if ($edit) {
                    $data[$key]['name'] =  $row['name'];
                    if (config('app.languages')) {
                        foreach (config('app.languages') as $lang) {
                            $data[$key][$lang['code_name']] = $row[$lang['code_name']];
                        }
                    }
                }
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
        $model = Roles::query();
        return DataTables::eloquent($model)
            ->setTransformer(function ($row) {
                $row = $row->toArray();
                return [
                    'id'            => $row['id'],
                    'name'          => $row[app()->getLocale()] ? $row[app()->getLocale()] : $row['name'],
                    'view_path'     => $row['view_path'],
                    'description'   => $row['description'],
                    'image'         => $row['image'] ? (ImageHelper::site(Roles::$path['image'], $row['image'])) : ImageHelper::prefix(),
                    'action'        => [
                        'edit' => url(Users::role() . '/' . App::$path['url'] . '/' . Roles::$path['url'] . '/edit/' . $row['id']),
                        'view' => url(Users::role() . '/' . App::$path['url'] . '/' . Roles::$path['url'] . '/view/' . $row['id']),
                        'delete' => url(Users::role() . '/' . App::$path['url'] . '/' . Roles::$path['url'] . '/delete/' . $row['id']),

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
                                    if (config('app.languages')) {
                                        foreach (config('app.languages') as $lang) {
                                            $q->orWhere($lang['code_name'], 'LIKE', '%' . request('search.value') . '%');
                                        }
                                    }
                                });
                            } elseif ($value['data'] == 'description') {
                                $query =  $query->orWhere('description', 'LIKE', '%' . request('search.value') . '%');
                            } elseif ($value['data'] == 'view_path') {
                                $query =  $query->orWhere('view_path', 'LIKE', '%' . request('search.value') . '%');
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
        $validator          = Validator::make(request()->all(), FormRoles::rulesField(), FormRoles::customMessages(), FormRoles::attributeField());

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {
                $add = Roles::insertGetId([
                    'name'        => trim(request('name')),
                    'en'          => trim(request('en')),
                    'km'          => trim(request('km')),
                    'view_path'   => request('view_path'),
                    'description' => request('description'),

                ]);
                if ($add) {

                    if (request()->hasFile('image')) {
                        $image      = request()->file('image');
                        Roles::updateImageToTable($add, ImageHelper::uploadImage($image, Roles::$path['image']));
                    } else {
                        ImageHelper::uploadImage(false, Roles::$path['image'], Roles::$path['image'], public_path('/assets/img/icons/image.jpg'), null, true);
                    }

                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'data'      => Roles::getData($add)['data'],
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
        $validator          = Validator::make(request()->all(), FormRoles::rulesField(), FormRoles::customMessages(), FormRoles::attributeField());

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {
                $update = Roles::where('id', $id)->update([
                    'name' => trim(request('name')),
                    'en' => trim(request('en')),
                    'km' =>  trim(request('km')),
                    'view_path'   => request('view_path'),
                    'description' =>  request('description'),
                ]);
                if ($update) {
                    if (request()->hasFile('image')) {
                        $image      = request()->file('image');
                        Roles::updateImageToTable($id, ImageHelper::uploadImage($image, Roles::$path['image']));
                    }
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'data'      => Roles::getData($id),
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
                $update =  Roles::where('id', $id)->update([
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
            if (Roles::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = Roles::whereIn('id', $id)->delete();
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
