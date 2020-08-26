<?php

namespace App\Models;

use Carbon\Carbon;
use DomainException;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Holidays extends Model
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
            'controller'   => 'App\Http\Controllers\\'.$tableUcwords.'Controller',
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

    public static function getHoliday($year = null, $month = null, $study_course_session_id = null)
    {
        $response = array(
            'success'   => false,
            'data'      => array(),
            'message'   => __('No Data'),
        );
        $data = array();

        if ($year && $month) {
            $get = self::where('year', $year)->where('month', $month)->get()->toArray();
        } else {
            $year = date('Y');
            $month = Months::now();
            $get = self::where('year', $year)->where('month', $month)->get()->toArray();
        }


        if ($get) {
            foreach ($get as $row) {
                $data[$row['date']] = array(
                    'id'    => $row['id'],
                    'day'   =>  __(DateHelper::dayOfWeek($year . '-' . $month . '-' . $row['date'])['day']),
                    'date'   => $row['date'],
                    'description' => $row[app()->getLocale()] ? $row[app()->getLocale()] : $row['description'],
                );
            }


            $scheduleSession = StudyCourseSession::where('id', request('course-sessionId', $study_course_session_id))->get()->toArray();

            if ($scheduleSession) {

                $routine = StudyCourseRoutine::where('study_course_session_id', $scheduleSession[0]['id'])->groupBy('day_id')->get()->toArray();


                if ($routine) {
                    $result  = [];
                    $HolHolidays    = [1, 2, 3, 4, 5, 6, 7];
                    foreach ($routine as $key => $value) {
                        if ($value['teacher_id']) {
                            $result[] =  $value['day_id'];
                        }
                    }


                    foreach ($HolHolidays as $value) {
                        if (in_array($value, $result) == false) {
                            $date = Days::where('id', $value)->first()->toArray();
                            if ($date) {
                                $data += DateHelper::dateOfMonth($year, $month, $date['name']);
                            }
                        }
                    }
                }
            }

            ksort($data);
            $sortArray = array();
            foreach ($data as $key => $val) {
                $sortArray[$key] = $val;
            }

            $response = array(
                'success'   => true,
                'data'      => $sortArray,
            );
        }
        return $response;
    }

    public static function addToTable()
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
            $date = new Carbon(trim(request('date')));

            try {
                $values['name']        = trim(request('name'));
                $values['year']        = $date->year;
                $values['month']       = $date->month;
                $values['date']        = $date->day;
                $values['description'] = trim(request('description'));
                $values['image']       = null;

                if (config('app.languages')) {
                    foreach (config('app.languages') as $lang) {
                        $values[$lang['code_name']] = trim(request($lang['code_name']));
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
        $validator          = Validator::make(request()->all(), $validate['rules'], $validate['messages'], $validate['attributes']);

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {
            $date = new Carbon(trim(request('date')));
            try {
                $values['name']        = trim(request('name'));
                $values['year']        = $date->year;
                $values['month']       = $date->month;
                $values['date']        = $date->day;
                $values['description'] = trim(request('description'));

                if (config('app.languages')) {
                    foreach (config('app.languages') as $lang) {
                        $values[$lang['code_name']] = trim(request($lang['code_name']));
                    }
                }
                $update = self::where('id', $id)->update($values);
                if ($update) {
                    if (request()->hasFile('image')) {
                        $image      = request()->file('image');
                        self::updateImageToTable($id, ImageHelper::uploadImage($image, self::path('image')));
                    }
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'data'      => self::getData($id),
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
                $update = self::where('id', $id)->update([
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
                        $delete    = self::whereIn('id', $id)->delete();
                        if ($delete) {
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
