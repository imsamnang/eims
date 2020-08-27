<?php

namespace App\Models;

use DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Staff\StaffTeachSubjectController;

class StaffTeachSubject extends Model
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
            'attributes'  =>  $formRequests->attributes(),
            'messages'    =>  $formRequests->messages(),
            'questions'   =>  $formRequests->questions(),
        ];
        return $key? @$validate[$key] : $validate;
    }

    public static function getTeachSubjects($id = null, $staff_id = null, $study_subject_id = null, $paginate = null, $groupByYear = true, $year = null)
    {
        $pages['form'] = array(
            'action'  => array(
                'add'    => url(Users::role() . '/teaching/' . self::path('url') . '/add/'),
            ),
        );

        $getCallMethods = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];
        if (class_basename($getCallMethods['class']) == class_basename('StaffTeachSubjectController')) {
            $pages['form']['action']['add'] = str_replace('teaching', 'study', $pages['form']['action']['add']);
        }

        $get = self::groupBy('year')
            ->groupBy('study_subject_id')
            ->orderBy('year', 'DESC');

        if ($id) {
            $get = $get->where('id', $id);
        } else {

            if ($staff_id) {
                $get = $get->where('staff_id', $staff_id);
            }

            if ($study_subject_id) {
                $get = $get->where('study_subject_id', $study_subject_id);
            }
            if ($year) {
                $get = $get->where('year', $year);
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
        //dd($get);
        if ($get) {
            $data = [];

            foreach ($get as $key => $row) {

                $study_subject = StudySubjects::getData($row['study_subject_id'])['data'][0];
                if ($groupByYear) {
                    $data[$row['year']][$key] = [
                        'id'    => $row['id'],
                        'staff'  => Staff::getData($row['staff_id'])['data'][0],
                        'study_subject' => $study_subject,
                        'lesson_count'    => StudySubjectLesson::where('staff_teach_subject_id', $row['id'])->count(),
                        'action'        => [
                            'link' => url(Users::role() . '/teaching/' . StudySubjectLesson::path('url') . '/list?t-subjectId=' . $row['id']), //?id
                        ],
                    ];
                    if (class_basename($getCallMethods['class']) == class_basename('StaffTeachSubjectController')) {
                        $data[$row['year']][$key]['action']['link'] = str_replace('teaching', 'study', $data[$row['year']][$key]['action']['link']);
                    }
                } else {
                    $data[$key] = [
                        'id'    => $row['id'],
                        'name'   => $study_subject['name'] . ' - (' . $row['year'] . ')',
                        'image'  => $study_subject['image'],
                    ];
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
            $exists = self::existsToTable(request('staff'), request('study_subject'), request('year'));
            if ($exists) {
                $response       = array(
                    'success'   => false,
                    'type'      => 'add',
                    'data'      => self::getData($exists->id)['data'],
                    'message'   => array(
                        'title' => __('Error'),
                        'text'  => __('Add Unsuccessful') . PHP_EOL .
                            __('Already exists'),
                        'button'   => array(
                            'confirm' => __('Ok'),
                            'cancel'  => __('Cancel'),
                        ),
                    ),
                );
            } else {
                try {

                    $values['staff_id']        = request('staff');
                    $values['study_subject_id'] = request('study_subject');
                    $values['year']       = trim(request('year'));
                    $add = self::insertGetId($values);
                    if ($add) {

                        $class  = self::path('controller');
                        $controller = new $class;
                        $response       = array(
                            'success'   => true,
                            'type'      => 'add',
                            'html'      => view(self::path('view') . '.includes.tpl.tr', ['row' => $controller->list([], $add)[0]])->render(),
                            'message'   => __('Add Successfully'),
                        );
                    }
                } catch (DomainException $e) {
                    return $e;
                }
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

            $exists = self::existsToTable(request('staff'), request('study_subject'), request('year'));
            if ($exists) {
                $response       = array(
                    'success'   => false,
                    'type'      => 'update',
                    'data'      => self::getData($exists->id)['data'],
                    'message'   => array(
                        'title' => __('Error'),
                        'text'  => __('Update Unsuccessful') . PHP_EOL .
                            __('Already exists'),
                        'button'   => array(
                            'confirm' => __('Ok'),
                            'cancel'  => __('Cancel'),
                        ),
                    ),
                );
            } else {

                try {
                    $values['staff_id']        = request('staff');
                    $values['study_subject_id'] = request('study_subject');
                    $values['year']       = trim(request('year'));

                    $update = self::where('id', $id)->update($values);
                    if ($update) {

                        $controller = new StaffTeachSubjectController;
                        $response       = array(
                            'success'   => true,
                            'type'      => 'update',
                            'data'      => [
                                [
                                    'id' => $id,
                                ]

                            ],
                            'html'      => view(self::path('view') . '.includes.tpl.tr', ['row' => $controller->list([], $id)[0]])->render(),
                            'message'   =>  __('Update Successfully')
                        );
                    }
                } catch (DomainException $e) {
                    return $e;
                }
            }
        }
        return $response;
    }

    public static function existsToTable($staff_id, $study_subject_id, $year)
    {
        return self::where('staff_id', $staff_id)
            ->where('study_subject_id', $study_subject_id)
            ->where('year', $year)
            ->first();
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
