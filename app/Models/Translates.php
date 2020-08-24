<?php

namespace App\Models;

use DomainException;


use App\Http\Requests\FormTranslates;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Validator;

class Translates extends Model
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

    public static function getData($id = null, $paginate = null)
    {
        $pages['form'] = array(
            'action'  => array(
                'add'    => url(Users::role() . '/' . Translates::path('url') . '/add/'),
            ),
        );

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
            $get = Translates::orderBy('id', $orderBy);
        } else {
            $get = Translates::orderBy('id', 'DESC');
        }

        if ($id) {
            $get = $get->whereIn('id', $id);
        }



        if ($paginate) {
            $get = $get->paginate(20)->toArray();
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
            $data = array();
            foreach ($get as $key => $row) {

                $data[$key] = $row;
                $data[$key]['action'] = [
                    'edit'    => url(Users::role() . '/' . App::path('url') . '/' . Translates::path('url') . '/edit/' . $row['id']),
                    'view'    => url(Users::role() . '/' . App::path('url') . '/' . Translates::path('url') . '/view/' . $row['id']),
                    'delete'  => url(Users::role() . '/' . App::path('url') . '/' . Translates::path('url') . '/delete/' . $row['id']),
                ];
                $pages['listData'][] = array(
                    'id'     => $data[$key]['id'],
                    'name'   => $data[$key]['phrase'],
                    'image'  => null,
                    'action' => $data[$key]['action'],

                );
            }

            $response = array(
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
        $model = Translates::query();
        return DataTables::eloquent($model)
            ->setTransformer(function ($row) {
                $row = $row->toArray();
                return $row + [
                    'action'        => [
                        'edit'    => url(Users::role() . '/' . App::path('url') . '/' . Translates::path('url') . '/edit/' . $row['id']),
                        'view'    => url(Users::role() . '/' . App::path('url') . '/' . Translates::path('url') . '/view/' . $row['id']),
                        'delete'  => url(Users::role() . '/' . App::path('url') . '/' . Translates::path('url') . '/delete/' . $row['id']),
                    ]
                ];
            })
            ->filter(function ($query) {

                if (request('search.value')) {
                    foreach (request('columns') as $i => $value) {
                        if ($value['searchable']) {
                            if ($value['data'] == 'phrase') {
                                $query =  $query->where(function ($q) {
                                    $q->where('phrase', 'LIKE', '%' . request('search.value') . '%');
                                    if (config('app.languages')) {
                                        foreach (config('app.languages') as $lang) {
                                            $q->orWhere($lang['code_name'], 'LIKE', '%' . request('search.value') . '%');
                                        }
                                    }
                                });
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
        $validator          = Validator::make(request()->all(), FormTranslates::rules(), FormTranslates::messages(), FormTranslates::attributes());

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {
                $values['phrase']        = trim(request('phrase'));
                if (config('app.languages')) {
                    foreach (config('app.languages') as $lang) {
                        $values[$lang['code_name']] = trim(request($lang['code_name']));
                    }
                }
                $add = Translates::insertGetId($values);

                if ($add) {
                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'data'      => Translates::getData($add)['data'],
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
        $validator          = Validator::make(request()->all(), FormTranslates::rules(), FormTranslates::messages(), FormTranslates::attributes());

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {
                $values['phrase']        = trim(request('phrase'));
                if (config('app.languages')) {
                    foreach (config('app.languages') as $lang) {
                        $values[$lang['code_name']] = trim(request($lang['code_name']));
                    }
                }
                $update = Translates::where('id', $id)->update($values);

                if ($update) {

                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'data'      => Translates::getData($id),
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
            if (Translates::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = Translates::whereIn('id', $id)->delete();
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
