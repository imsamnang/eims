<?php

namespace App\Models;

use DomainException;


use App\Helpers\ImageHelper;
use App\Http\Requests\FormApp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class App extends Model
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
            'url'    => 'settings',
            'view'   => 'Settings',
            'requests'   => 'App\Http\Requests\Form'.$tableUcwords,
        ];
        return $key ? @$path[$key] : $path;
    }
    public static function setConfig()
    {
        $app = self::where('status', 1)->first()->toArray();
        $themeColor = ThemesColor::where('id', $app['theme_color_id'])->first()->toArray();
        $themeBackground = ThemeBackground::where('status', 1)->first();
        if ($themeBackground) {
            config()->set('app.theme_background', [
                'id'    => $themeBackground->id,
                'name'  => $themeBackground->name,
                'image' => ImageHelper::site(ThemeBackground::path('url'), $themeBackground->image, 'original'),
            ]);
        }
        foreach ($app as $key => $value) {
            if ($key != 'status' && $key != 'created_at' && $key != 'updated_at') {
                if ($key == 'theme_color_id') {
                    config()->set('app.theme_color', $themeColor);
                } elseif ($key == 'logo' || $key == 'favicon') {
                    config()->set('app.' . $key, ImageHelper::site(self::path('image'), $value));
                } elseif (array_key_exists($key, Languages::getLanguages()['data'])) {
                    config()->set('app.name', $app[app()->getLocale()] ? $app[app()->getLocale()] : $app['name']);
                } else {
                    config()->set('app.' . $key, $value);
                }
            }
        }
    }
    public static function getData($id = null, $edit = null, $paginate = null)
    {
        $pages['form'] = array(
            'action'  => array(
                'add'    => url(Users::role() . '/' . self::path('url') . '/add/'),
            ),
        );

        $getCallMethods = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];
        if (class_basename($getCallMethods['class']) == class_basename('AppController')) {
            $search = request('search');
        } else {
            $search = null;
        }


        $data = array();
        if ($id) {
            $id  = explode(',', $id);
            $sorted = array_values($id);
            sort($sorted);
            if ($id === $sorted) {
                $orderBy = 'ASC';
            } else {
                $orderBy = 'DESC';
            }
            $get = self::orderBy('id', $orderBy);
        } else {
            $get = self::orderBy('id', 'DESC');
        }

        if ($id) {
            $get = $get->whereIn('id', $id);
        }

        if ($search) {
            $get = $get->where('name', 'LIKE', '%' . $search . '%');

            if (array_key_exists('en', request()->all())) {
                $get = $get->orWhere('en', 'LIKE', '%' . $search . '%');
            }
            if (array_key_exists('km', request()->all())) {
                $get = $get->orWhere('km', 'LIKE', '%' . $search . '%');
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
                    'name'          => $row[app()->getLocale()] ? $row[app()->getLocale()] : $row['name'],
                    'phone'         => $row['phone'],
                    'email'         => $row['email'],
                    'address'       => $row['address'],
                    'location'      => $row['location'],
                    'website'       => $row['website'],
                    'description'   => $row['description'],
                    'logo'          => ImageHelper::site(self::path('image'), $row['logo'], 'original'),
                    'favicon'       => ImageHelper::site(self::path('image'), $row['favicon']),
                    'theme_color'   => ThemesColor::getData($row['theme_color_id'])['data'][0],
                    'action'        => [
                        'edit' => url(Users::role() . '/' . self::path('url') . '/edit/' . $row['id']),
                        'view' => url(Users::role() . '/' . self::path('url') . '/view/' . $row['id']),
                        'delete' => url(Users::role() . '/' . self::path('url') . '/delete/' . $row['id']),
                    ]
                );
                $pages['listData'][] = array(
                    'id'     => $data[$key]['id'],
                    'name'   => $data[$key]['name'],
                    'image'  => $data[$key]['logo'],
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

    public static function updateToTable($id)
    {

        $response           = array();
        $validator          = Validator::make(request()->all(), (new FormApp)->rules(), (new FormApp)->messages(), (new FormApp)->attributes());

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {
                $values['name']        = trim(request('name'));
                $values['phone']       = trim(request('phone'));
                $values['email']       = trim(request('email'));
                $values['address']     = trim(request('address'));
                $values['location']    = trim(request('location'));
                $values['description'] = trim(request('description'));

                if (config('app.languages')) {
                    foreach (config('app.languages') as $lang) {
                        $values[$lang['code_name']] = trim(request($lang['code_name']));
                    }
                }
                $update = self::where('id', $id)->update($values);
                if ($update) {
                    SocailsMedia::updateToTable($id);
                    if (request()->hasFile('logo')) {
                        $logo      = request()->file('logo');
                        self::updateImageToTable($id, ImageHelper::uploadImage($logo, self::path('image')), 'logo');
                    }

                    if (request()->hasFile('favicon')) {
                        $favicon      = request()->file('favicon');
                        self::updateImageToTable($id, ImageHelper::uploadImage($favicon, self::path('image')), 'favicon');
                    }

                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'data'      => [],
                        'message'   => __('Update Successfully'),
                    );
                }
            } catch (DomainException $e) {
                return $e;
            }
        }
        return $response;
    }

    public static function updateImageToTable($id, $image, $col_name)
    {
        $response = array(
            'success'   => false,
            'message'   => __('Update Failed'),
        );
        if ($image) {
            try {
                $update =  self::where('id', $id)->update([
                    $col_name   => $image,
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
                return $e;
            }
        }

        return $response;
    }



    public static function updateThemeColorToTable($id, $theme_color_id)
    {
        $response = array(
            'success'   => false,
            'message'   => __('Update Failed'),
        );
        if ($id && $theme_color_id) {
            try {
                $update =  self::where('id', $id)->update([
                    'theme_color_id'   => $theme_color_id,
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
}
