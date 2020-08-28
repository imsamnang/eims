<?php

namespace App\Models;

use App\Helpers\ImageHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class StudyPrograms extends Model
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
            'requests'   => 'App\Http\Requests\Form' . $tableUcwords,
            'controller'   => 'App\Http\Controllers\Study\\' . $tableUcwords . 'Controller',
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
            'attributes'  =>  $formRequests->attributes(),
            'messages'    =>  $formRequests->messages(),
            'questions'   =>  $formRequests->questions(),
        ];
        return $key ? @$validate[$key] : $validate;
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class, 'institute_id');
    }

    public static function addToTable()
    {
        $response = array();
        $validate = self::validate();
        $rules = $validate['rules'];
        $rules['name'] = 'required|unique:' . self::path('table') . ',name';

        $validator          = Validator::make(request()->all(), $rules, $validate['messages'], $validate['attributes']);

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {
                $values['institute_id'] = request('institute');
                $values['name']        = request('name');

                $values['description'] = request('description');

                if (config('app.languages')) {
                    foreach (config('app.languages') as $lang) {
                        $values[$lang['code_name']] = request($lang['code_name']);
                    }
                }
                $add = self::insertGetId($values);
                if ($add) {
                    if (request()->hasFile('image')) {
                        $image    = request()->file('image');
                        $image   = ImageHelper::uploadImage($image, self::path('image'));
                        self::updateImageToTable($add, $image);
                    }
                    $class  = self::path('controller');
                    $controller = new $class;
                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'html'      => view(self::path('view') . '.includes.tpl.tr', ['row' => $controller->list([], $add)[0]])->render(),
                        'message'   => __('Add Successfully'),
                    );
                }
            } catch (\Throwable $th) {
                throw $th;
            }
        }
        return $response;
    }

    public static function updateToTable($id)
    {
        $response           = array();
        $validate = self::validate();
        $rules    = $validate['rules'];
        $rules['name'] = 'required|unique:' . self::path('table') . ',name,' . $id;
        $validator          = Validator::make(request()->all(), $rules, $validate['messages'], $validate['attributes']);

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {
                $values['institute_id'] = request('institute');
                $values['name']        = request('name');

                $values['description'] = request('description');

                if (config('app.languages')) {
                    foreach (config('app.languages') as $lang) {
                        $values[$lang['code_name']] = request($lang['code_name']);
                    }
                }
                $table = self::where('id', $id);
                $old = $table->first();
                $update = $table->update($values);
                if ($update) {

                    if (request()->hasFile('image')) {
                        $image    = request()->file('image');
                        $image   = ImageHelper::uploadImage($image, self::path('image'));
                        if (self::updateImageToTable($id, $image)['success']) {
                            ImageHelper::delete(self::path('image'),$old->image);
                        }
                    }

                    $class  = self::path('controller');
                    $controller = new $class;
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'data'      => [['id' => $id]],
                        'html'      => view(self::path('view') . '.includes.tpl.tr', ['row' => $controller->list([], $id)[0]])->render(),
                        'message'   => __('Update Successfully'),
                    );
                }
            } catch (\Throwable $th) {
                throw $th;
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
                $update =  self::where('id', $id)->update([
                    'image'    => $image,
                ]);

                if ($update) {
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'message'   => __('Update Successfully'),
                    );
                }
            } catch (\Throwable $th) {
                throw $th;
            }
        }

        return $response;
    }

     public static function deleteFromTable($id)
    {
        if ($id) {

            $id  = explode(',', $id);

            if (self::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $table = self::whereIn('id', $id);
                        $olds = $table->get();
                        $delete    = $table->delete();
                        if ($delete) {
                            foreach ($olds as $old) {
                                ImageHelper::delete(self::path('image'),$old->image);
                            }
                            return [
                                'success'   => true,
                                'message'   => __('Delete Successfully'),
                            ];
                        }
                    } catch (\Throwable $th) {
                        throw $th;
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
