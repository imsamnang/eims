<?php

namespace App\Models;

use DomainException;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\FormStaffTeachSubject;
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
        $path = [
            'image'  => $table,
            'url'    => str_replace('_', '-', $table),
            'view'   => str_replace(' ', '', ucwords(str_replace('_', ' ', $table)))
        ];
        return $key ? @$path[$key] : $path;
    }

    public static function getTeachSubjects($id = null, $staff_id = null, $study_subject_id = null, $paginate = null, $groupByYear = true, $year = null)
    {
        $pages['form'] = array(
            'action'  => array(
                'add'    => url(Users::role() . '/teaching/' . StaffTeachSubject::path('url') . '/add/'),
            ),
        );

        $getCallMethods = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];
        if (class_basename($getCallMethods['class']) == class_basename('StaffTeachSubjectController')) {
            $pages['form']['action']['add'] = str_replace('teaching', 'study', $pages['form']['action']['add']);
        }

        $get = StaffTeachSubject::groupBy('year')
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
        $validator          = Validator::make(request()->all(), FormStaffTeachSubject::rules(), FormStaffTeachSubject::messages(), FormStaffTeachSubject::attributes());

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {
            $exists = StaffTeachSubject::existsToTable(request('staff'), request('study_subject'), request('year'));
            if ($exists) {
                $response       = array(
                    'success'   => false,
                    'type'      => 'add',
                    'data'      => StaffTeachSubject::getData($exists->id)['data'],
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
                    $add = StaffTeachSubject::insertGetId($values);
                    if ($add) {
                        $controller = new StaffTeachSubjectController;

                        $response       = array(
                            'success'   => true,
                            'type'      => 'add',
                            'html'      => view(StaffTeachSubject::path('view') . '.includes.tpl.tr', ['row' => $controller->list([], $add)[0]])->render(),
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
        $validator          = Validator::make(request()->all(), FormStaffTeachSubject::rules(), FormStaffTeachSubject::messages(), FormStaffTeachSubject::attributes());

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            $exists = StaffTeachSubject::existsToTable(request('staff'), request('study_subject'), request('year'));
            if ($exists) {
                $response       = array(
                    'success'   => false,
                    'type'      => 'update',
                    'data'      => StaffTeachSubject::getData($exists->id)['data'],
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

                    $update = StaffTeachSubject::where('id', $id)->update($values);
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
                            'html'      => view(StaffTeachSubject::path('view') . '.includes.tpl.tr', ['row' => $controller->list([], $id)[0]])->render(),
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
        return StaffTeachSubject::where('staff_id', $staff_id)
            ->where('study_subject_id', $study_subject_id)
            ->where('year', $year)
            ->first();
    }

    public static function deleteFromTable($id)
    {
        if ($id) {
            $id  = explode(',', $id);
            if (StaffTeachSubject::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = StaffTeachSubject::whereIn('id', $id)->delete();
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
