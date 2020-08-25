<?php

namespace App\Models;

use DomainException;
use App\Helpers\DateHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class StudyCourseSession extends Model
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
            'controller'   => 'App\Http\Controllers\\'.$tableUcwords.'\Controller',
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

    public static function getData($id = null, $edit = null, $paginate = null)
    {
        $pages['form'] = array(
            'action'  => array(
                'add'    => url(Users::role() . '/study/' . StudyCourseSession::path('url') . '/add/'),
            ),
        );
        if (Auth::user()->role_id == 8) {
            $pages['form']['action']['add'] = str_replace('study', 'teaching', $pages['form']['action']['add']);
        } elseif (Auth::user()->role_id == 6) {
            $pages['form']['action']['add'] = str_replace(StudyCourseSession::path('url'), 'approved', $pages['form']['action']['add']);
        }
        $data = array();

        $getCallMethods = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);

        if (class_basename($getCallMethods[1]['class']) == class_basename('StudyCourseSessionController')) {
            $search = request('search');
        } elseif (class_basename($getCallMethods[1]['class']) == class_basename('TeacherController')) {
            $pages['form']['action']['add'] = str_replace('study', 'teaching', $pages['form']['action']['add']);
        } else {
            $search = null;
        }


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

        $get = StudyCourseSession::select((new StudyCourseSession())->getTable() . '.*')
            ->join((new StudyCourseSchedule())->getTable(), (new StudyCourseSchedule())->getTable() . '.id', (new StudyCourseSession())->getTable() . '.study_course_schedule_id')
            ->orderBy((new StudyCourseSession())->getTable() . '.id', $orderBy);

        // if (class_basename($getCallMethods[2]['class']) == class_basename('StudyController')) {
        //     $courseRoutine = StudyCourseRoutine::select('study_course_session_id')->groupBy('study_course_session_id')->get()->toArray();
        //     $exists = [];
        //     foreach ($courseRoutine as $key => $value) {
        //         $exists[] = $value['study_course_session_id'];
        //     }
        //     if ($exists) {
        //         $get = $get->whereNotIn((new StudyCourseSession())->getTable() . '.id', $exists);
        //     }
        // }


        if ($id) {
            $get = $get->whereIn((new StudyCourseSession())->getTable() . '.id', $id);
        } else {
            if (request('instituteId')) {
                $get = $get->where('institute_id', request('instituteId'));
            }
        }




        // if ($search) {
        //     $get = $get->where('name', 'LIKE', '%' . $search . '%');
        //     if (array_key_exists('en', request()->all())) {
        //         $get = $get->orWhere('en', 'LIKE', '%' . $search . '%');
        //     }
        //     if (array_key_exists('km', request()->all())) {
        //         $get = $get->orWhere('km', 'LIKE', '%' . $search . '%');
        //     }
        // }


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
                $data[$key]  = array(
                    'id'  => $row['id'],
                    'name' => null,
                    'image' => null,
                    'study_course_schedule' => StudyCourseSchedule::getData($row['study_course_schedule_id'])['data'][0],
                    'study_session' => StudySession::getData($row['study_session_id'])['data'][0],
                    'study_start'   => DateHelper::convert($row['study_start'], $edit ? 'd-m-Y' : 'd-M-Y'),
                    'study_end'    => DateHelper::convert($row['study_end'],  $edit ? 'd-m-Y' : 'd-M-Y'),
                    'action'     => [
                        'edit'   => url(Users::role() . '/study/' . StudyCourseSession::path('url') . '/edit/' . $row['id']), //?id
                        'delete' => url(Users::role() . '/study/' . StudyCourseSession::path('url') . '/delete/' . $row['id']),
                    ]
                );
                $data[$key]['name']  = $data[$key]['study_course_schedule']['name'] . ' (' . $data[$key]['study_session']['name'] . ')';
                $data[$key]['image']  = $data[$key]['study_course_schedule']['image'];
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
                'pages'     => $pages
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

            try {
                $exists = StudyCourseSession::existsToTable();
                if ($exists) {
                    $response       = array(
                        'success'   => false,
                        'data'      => $exists,
                        'type'      => 'add',
                        'message'   => __('Already exists'),
                    );
                } else {
                    $add = StudyCourseSession::insertGetId([
                        'study_course_schedule_id'  => request('study_course_schedule'),
                        'study_session_id'      => request('study_session'),
                        'study_start'      => DateHelper::convert(trim(request('study_start'))),
                        'study_end'      => DateHelper::convert(trim(request('study_end'))),
                    ]);
                    if ($add) {
                        $response       = array(
                            'success'   => true,
                            'data'      => StudyCourseSession::getData($add)['data'],
                            'type'      => 'add',
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
                $update = StudyCourseSession::where('id', $id)->update([
                    'study_course_schedule_id'  => request('study_course_schedule'),
                    'study_session_id'      => request('study_session'),
                    'study_start'      => DateHelper::convert(trim(request('study_start'))),
                    'study_end'      => DateHelper::convert(trim(request('study_end'))),
                ]);
                if ($update) {
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'data'      => StudyCourseSession::getData($id),
                        'message'   => array(
                            'title' => __('Success'),
                            'text'  => __('Update Successfully'),
                            'button'      => array(
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

    public static function existsToTable()
    {
        return StudyCourseSession::where('study_course_schedule_id', request('study_course_schedule'))
            ->where('study_session_id', request('study_session'))
            ->where('study_start', DateHelper::convert(trim(request('study_start'))))
            ->where('study_end', DateHelper::convert(trim(request('study_end'))))
            ->first();
    }
    public static function deleteFromTable($id)
    {
        if ($id) {
            $id  = explode(',', $id);
            if (StudyCourseSession::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = StudyCourseSession::whereIn('id', $id)->delete();
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
