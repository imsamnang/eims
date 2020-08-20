<?php

namespace App\Models;


use App\Models\App;


use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\FormLanguages;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Validator;

class Languages extends Model
{
    public static $path = [
        'image'  => 'language',
        'url'    => 'language',
        'view'   => 'Language'
    ];

    public static function getData($id = null, $edit = null, $paginate = null)
    {

        $pages['form'] = array(
            'action'  => array(
                'add'    => url(Users::role() . '/' . Languages::$path['url'] . '/add/'),
            ),
        );

        $data = array();
        $orderBy = 'DESC';
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
        $get = Languages::orderBy('id', $orderBy);

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

                $image = $row['image'] ? (ImageHelper::getImage($row['image'], Languages::$path['image'])) : null;

                $data[$key]         = array(
                    'id'            => $row['id'],
                    'name'          => array_key_exists(app()->getLocale(), $row) ? $row[app()->getLocale()] : $row['name'],
                    'code_name'      => $row['code_name'],
                    'country_code'   => $row['country_code'],
                    'description'    => $row['description'],
                    'image'         => $image ? ImageHelper::site(Languages::$path['image'], $row['image']) : ($row['image'] ? asset('/assets/img/icons/flags/' . $row['image']) : ImageHelper::prefix()),
                    'action'        => [
                        'edit'   => url(Users::role() . '/' . App::$path['url'] . '/' . Languages::$path['url'] . '/edit/' . $row['id']),
                        'view'   => url(Users::role() . '/' . App::$path['url'] . '/' . Languages::$path['url'] . '/view/' . $row['id']),
                        'delete' => url(Users::role() . '/' . App::$path['url'] . '/' . Languages::$path['url'] . '/delete/' . $row['id']),
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
                    $langauges = Languages::getLanguages();
                    if ($langauges['success']) {
                        foreach ($langauges['data'] as $langauge) {
                            $data[$key][$langauge['code_name']]   =  $row[$langauge['code_name']];
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
        $model = Languages::query();
        return DataTables::eloquent($model)
            ->setTransformer(function ($row) {
                $row = $row->toArray();
                $image = $row['image'] ? (ImageHelper::getImage($row['image'], Languages::$path['image'])) : null;
                return [
                    'id'            => $row['id'],
                    'name'          => array_key_exists(app()->getLocale(), $row) ? $row[app()->getLocale()] : $row['name'],
                    'code_name'      => $row['code_name'],
                    'country_code'   => $row['country_code'],
                    'description'    => $row['description'],
                    'image'         => $image ? ImageHelper::site(Languages::$path['image'], $row['image']) : ($row['image'] ? asset('/assets/img/icons/flags/' . $row['image']) : ImageHelper::prefix()),
                    'action'        => [
                        'edit'   => url(Users::role() . '/' . App::$path['url'] . '/' . Languages::$path['url'] . '/edit/' . $row['id']),
                        'view'   => url(Users::role() . '/' . App::$path['url'] . '/' . Languages::$path['url'] . '/view/' . $row['id']),
                        'delete' => url(Users::role() . '/' . App::$path['url'] . '/' . Languages::$path['url'] . '/delete/' . $row['id']),
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
                            } elseif ($value['data'] == 'code_name') {
                                $query->orWhere('code_name', 'LIKE', '%' . request('search.value') . '%');
                            } elseif ($value['data'] == 'country_code') {
                                $query->orWhere('country_code', 'LIKE', '%' . request('search.value') . '%');
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

    public static function getLanguages($id = null)
    {
        $get = Languages::orderBy('id', 'desc');
        if ($id) {
            $get = $get->where('id', $id);
        }

        $get = $get->get()->toArray();
        if ($get) {
            foreach ($get as $key => $row) {

                $image = $row['image'] ? (ImageHelper::getImage($row['image'], Languages::$path['image'])) : null;
                $data[$row['code_name']] = array(
                    'id'             => $row['id'],
                    'name'           => $row['name'],
                    'translate_name' => array_key_exists(app()->getLocale(), $row) ? $row[app()->getLocale()] : $row['name'],
                    'code_name'      => $row['code_name'],
                    'image'         => $image ? ImageHelper::site(Languages::$path['image'], $row['image']) : ($row['image'] ? asset('/assets/img/icons/flags/' . $row['image']) : ImageHelper::prefix()),
                    'action'        => [
                        'set'       => url(Languages::$path['url'] . '/set/' . $row['code_name']),
                    ]
                );
            }

            $response       = array(
                'success'   => true,
                'data'      => $data,
            );
        } else {
            $response = array(
                'success'   => false,
                'data'      => [],
                'message'   => __('No Data'),
            );
        }
        return $response;
    }

    public static function setConfig()
    {
        config()->set('app.languages', Languages::getLanguages()['data']);
    }

    public static function addToTable()
    {
        $response           = array();
        $validator          = Validator::make(request()->all(), FormLanguages::rulesField(), FormLanguages::customMessages(), FormLanguages::attributeField());

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {
            if (Languages::exists()) {
                $response       = array(
                    'success'   => false,
                    'type'      => 'add',
                    'message'   => array(
                        'title' => __('Error'),
                        'text'  => __('Update Unsuccessful') . PHP_EOL
                            . __('Code Name') . '( ' . trim(request('code_name')) . ' )'
                            . PHP_EOL
                            . __('Already exists'),
                        'button'   => array(
                            'confirm' => __('Ok'),
                            'cancel'  => __('Cancel'),
                        ),
                    ),
                );
            } else {
                try {
                    $values['name']        = trim(request('name'));
                    $values['code_name']   = trim(request('code_name'));
                    $values['country_code'] = trim(request('country_code'));
                    $values['description'] = trim(request('description'));
                    $values['image']       = null;

                    if (config('app.languages')) {
                        foreach (config('app.languages') as $lang) {
                            $values[$lang['code_name']] = trim(request($lang['code_name']));
                        }
                    }
                    $lang =  $values['code_name'];
                    $tables_in_db = DB::select('SHOW TABLES');
                    $db = 'Tables_in_' . env('DB_DATABASE');
                    foreach ($tables_in_db as $table) {
                        if (Schema::hasColumn($table->{$db}, 'km') && Schema::hasColumn($table->{$db}, 'en')) {
                            if (!Schema::hasColumn($table->{$db}, $lang)) {
                                $last = Languages::latest('id')->first()->code_name;
                                Schema::table($table->{$db}, function ($table) use ($lang, $last) {
                                    $table->string($lang)->after($last)->nullable();
                                });
                            }
                        }
                    }

                    $add = Languages::insertGetId($values);

                    if ($add) {

                        if (request()->hasFile('image')) {
                            $image      = request()->file('image');
                            Languages::updateImageToTable($add, ImageHelper::uploadImage($image, Languages::$path['image']));
                        } else {
                            ImageHelper::uploadImage(false, Languages::$path['image'], Languages::$path['image'], public_path('/assets/img/icons/image.jpg'));
                        }

                        $response       = array(
                            'success'   => true,
                            'type'      => 'add',
                            'data'      => Languages::getData($add),
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
                } catch (\DomainException $e) {
                    return $e;
                }
            }
        }
        return $response;
    }

    public static function updateToTable($id)
    {
        if (in_array($id, [1, 2])) {
            return array(
                'success'   => false,
                'type'      => 'update',
                'message'   => array(
                    'title' => __('Error'),
                    'text'  => __('Update Unsuccessful') . PHP_EOL
                        . __('km.and.en.is.default.language.of.application'),
                    'button'   => array(
                        'confirm' => __('Ok'),
                        'cancel'  => __('Cancel'),
                    ),
                ),
            );
        }

        $response           = array();
        $validator          = Validator::make(request()->all(), FormLanguages::rulesField(), FormLanguages::customMessages(), FormLanguages::attributeField());

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {
            if (Languages::exists()) {
                $response       = array(
                    'success'   => false,
                    'type'      => 'update',
                    'message'   => array(
                        'title' => __('Error'),
                        'text'  => __('Update Unsuccessful') . PHP_EOL
                            . __('Code Name') . '( ' . trim(request('code_name')) . ' )'
                            . PHP_EOL
                            . __('Already exists'),
                        'button'   => array(
                            'confirm' => __('Ok'),
                            'cancel'  => __('Cancel'),
                        ),
                    ),
                );
            } else {
                try {

                    $values['name']        = trim(request('name'));
                    $values['code_name']   = trim(request('code_name'));
                    $values['country_code'] = trim(request('country_code'));
                    $values['description'] = trim(request('description'));
                    $values['image']       = null;

                    if (config('app.languages')) {
                        foreach (config('app.languages') as $lang) {
                            $values[$lang['code_name']] = trim(request($lang['code_name']));
                        }
                    }

                    $update = Languages::where('id', $id)->uupdate($values);

                    if ($update) {
                        if (request()->hasFile('image')) {
                            $image      = request()->file('image');
                            Languages::updateImageToTable($id, ImageHelper::uploadImage($image, Languages::$path['image']));
                        }
                        $response       = array(
                            'success'   => true,
                            'type'      => 'update',
                            'data'      => Languages::getData($id),
                            'message'   =>  __('Update Successfully'),
                        );
                    }
                } catch (\DomainException $e) {
                    return $e;
                }
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
                $update =  Languages::where('id', $id)->update([
                    'image'    => $image,
                ]);

                if ($update) {
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'message'   => __('Update Successfully'),
                    );
                }
            } catch (\DomainException $e) {
                return $e;
            }
        }
        return $response;
    }

    public static function exists()
    {
        return Languages::where('code_name', trim(request('code_name')))->exists();
    }

    public static function deleteFromTable($id)
    {

        if ($id) {
            $id  = explode(',', $id);
            foreach ($id as $key => $value) {
                if (in_array($value, [1, 2])) {
                    return array(
                        'success'   => false,
                        'type'      => 'update',
                        'message'   => array(
                            'title' => __('Error'),
                            'text'  => __('Delete Unsuccessful') . PHP_EOL
                                . __('Khmer and english is default languages of application.'),
                            'button'   => array(
                                'confirm' => __('Ok'),
                                'cancel'  => __('Cancel'),
                            ),
                        ),
                    );
                }
            }

            if (Languages::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        foreach (Languages::whereIn('id', $id)->get()->toArray() as $key => $value) {
                            $lang =  $value['code_name'];
                            $tables_in_db = DB::select('SHOW TABLES');
                            $db = 'Tables_in_' . env('DB_DATABASE');
                            foreach ($tables_in_db as $table) {
                                if (Schema::hasColumn($table->{$db}, 'km') && Schema::hasColumn($table->{$db}, 'en')) {
                                    if (Schema::hasColumn($table->{$db}, $lang)) {
                                        Schema::table($table->{$db}, function ($table) use ($lang) {
                                            $table->dropColumn($lang);
                                        });
                                    }
                                }
                            }
                        }

                        $delete    = Languages::whereIn('id', $id)->delete();
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
