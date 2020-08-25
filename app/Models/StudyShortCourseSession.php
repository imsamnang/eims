<?php

namespace App\Models;

use DomainException;
use App\Helpers\DateHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class StudyShortCourseSession extends Model
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
                'add'    => url(Users::role() . '/study/' . StudyShortCourseSession::path('url') . '/add/'),
            ),
        );
        if (Auth::user()->role_id == 8) {
            $pages['form']['action']['add'] = str_replace('study', 'teaching', $pages['form']['action']['add']);
        } elseif (Auth::user()->role_id == 6) {
            $pages['form']['action']['add'] = str_replace(StudyShortCourseSession::path('url'), 'approved', $pages['form']['action']['add']);
        }
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

        $get = StudyShortCourseSession::select((new StudyShortCourseSession())->getTable() . '.*')
            ->join((new StudyShortCourseSchedule())->getTable(), (new StudyShortCourseSchedule())->getTable() . '.id', (new StudyShortCourseSession())->getTable() . '.stu_sh_c_schedule_id')
            ->orderBy((new StudyShortCourseSession())->getTable() . '.id', $orderBy);




        if ($id) {
            $get = $get->whereIn((new StudyShortCourseSession())->getTable() . '.id', $id);
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

                $request_id =  StudentsStudyShortCourse::join((new StudentsShortCourseRequest())->getTable(), (new StudentsShortCourseRequest())->getTable() . '.id', (new StudentsStudyShortCourse())->getTable() . '.stu_sh_c_request_id')
                    ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsShortCourseRequest())->getTable() . '.student_id')
                    ->where((new StudentsShortCourseRequest())->getTable() . '.student_id', Auth::user()->node_id)
                    ->where((new StudentsStudyShortCourse())->getTable() . '.stu_sh_c_session_id', $row['id'])
                    ->pluck('stu_sh_c_request_id')->first();


                $data[$key]  = array(
                    'request_id' => $request_id,
                    'id'  => $row['id'],
                    'name' => null,
                    'image' => null,
                    'study_short_course_schedule' => StudyShortCourseSchedule::getData($row['stu_sh_c_schedule_id'])['data'][0],
                    'study_session' => StudySession::getData($row['study_session_id'])['data'][0],
                    'study_start'   => DateHelper::convert($row['study_start'], $edit ? 'd-m-Y' : 'd-M-Y'),
                    'study_end'    => DateHelper::convert($row['study_end'],  $edit ? 'd-m-Y' : 'd-M-Y'),
                    'action'     => [
                        'edit'   => url(Users::role() . '/study/' . StudyShortCourseSession::path('url') . '/edit/' . $row['id']), //?id
                        'delete' => url(Users::role() . '/study/' . StudyShortCourseSession::path('url') . '/delete/' . $row['id']),
                    ]
                );
                $data[$key]['name']  = $data[$key]['study_short_course_schedule']['name'] . ' (' . $data[$key]['study_session']['name'] . ')';
                $data[$key]['image']  = $data[$key]['study_short_course_schedule']['image'];
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
                $exists = StudyShortCourseSession::existsToTable();
                if ($exists) {
                    $response       = array(
                        'success'   => false,
                        'data'      => $exists,
                        'type'      => 'add',
                        'message'   => __('Already exists'),
                    );
                } else {
                    $add = StudyShortCourseSession::insertGetId([
                        'stu_sh_c_schedule_id'  => request('study_short_course_schedule'),
                        'study_session_id'      => request('study_session'),
                        'study_start'      => DateHelper::convert(trim(request('study_start'))),
                        'study_end'      => DateHelper::convert(trim(request('study_end'))),
                        'province_id'      => trim(request('province')),
                        'district_id'      => trim(request('district')),
                        'commune_id'      => trim(request('commune')),
                        'village_id'      => trim(request('village')),
                    ]);
                    if ($add) {
                        $response       = array(
                            'success'   => true,
                            'data'      => StudyShortCourseSession::getData($add)['data'],
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
                $update = StudyShortCourseSession::where('id', $id)->update([
                    'stu_sh_c_schedule_id'  => request('study_short_course_schedule'),
                    'study_session_id'      => request('study_session'),
                    'study_start'      => DateHelper::convert(trim(request('study_start'))),
                    'study_end'      => DateHelper::convert(trim(request('study_end'))),
                    'province_id'      => trim(request('province')),
                    'district_id'      => trim(request('district')),
                    'commune_id'      => trim(request('commune')),
                    'village_id'      => trim(request('village')),
                ]);
                if ($update) {
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'data'      => StudyShortCourseSession::getData($id),
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
        return StudyShortCourseSession::where('stu_sh_c_schedule_id', request('study_short_course_schedule'))
            ->where('study_session_id', request('study_session'))
            ->where('study_start', DateHelper::convert(trim(request('study_start'))))
            ->where('study_end', DateHelper::convert(trim(request('study_end'))))
            ->first();
    }
    public static function deleteFromTable($id)
    {
        if ($id) {
            $id  = explode(',', $id);
            if (StudyShortCourseSession::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = StudyShortCourseSession::whereIn('id', $id)->delete();
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
