<?php

namespace App\Models;

use DateTime;
use DomainException;
use App\Helpers\QRHelper;
use App\Helpers\ImageHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\FormStudentsStudyCourse;
use App\Http\Controllers\Student\StudentsStudyCourseController;

class StudentsStudyCourse extends Model
{
    public static $path = [
        'image'  => 'study-course',
        'url'    => 'study-course',
        'view'   => 'StudentsStudyCourse'
    ];


    public static function studyStatus($query)
    {
        $data = [];
        if (gettype($query) == 'object' && $query->count()) {
            $studyStatus = StudyStatus::getData();
            if ($studyStatus['success']) {
                foreach ($studyStatus['data'] as  $status) {
                    $data[$status['id']] = [
                        'title' => in_array($status['id'], [2, 3]) ? $status['name'] :  __('Students') . $status['name'],
                        'color' => $status['color'],
                        'text'  => [],
                    ];
                    foreach ($query->get()->toArray() as  $row) {

                        if ($status['id'] == $row['study_status_id']) {
                            $data[$status['id']]['text'][$row['student_request_id']][] = $row['student_request_id'];
                        }

                        if (strpos($query->toSql(), 'institute_id') > 0) {
                            $value = $query->getBindings();
                            if ($value) {
                                $data[$status['id']]['link'] = url(Users::role() . '/' . Students::$path['url'] . '/' . self::$path['url'] . '/list/?instituteId=' . $value[0] . '&statusId=' . $status['id']);
                            }
                        } elseif (strpos($query->toSql(), 'study_program_id') > 0) {
                            $value = $query->getBindings();
                            if ($value) {
                                $data[$status['id']]['link'] = url(Users::role() . '/' . Students::$path['url'] . '/' . self::$path['url'] . '/list/?programId=' . $value[0] . '&statusId=' . $status['id']);
                            }
                        } else {
                            $data[$status['id']]['link'] = url(Users::role() . '/' . Students::$path['url'] . '/' . self::$path['url'] . '/list/?statusId=' . $status['id']);
                        }
                    }
                }
            }
        }

        $newData = [];

        foreach ($data as $key => $value) {
            $newData[$key] = $value;

            $newData[$key]['text'] = count($value['text']) . ((app()->getLocale() == 'km') ? ' នាក់' : ' Poeple');
        }
        return $newData;
    }

    public static function updateStudyStatus($student_study_course_id, $study_status_id)
    {
        return self::where('id', $student_study_course_id)->update([
            'study_status_id' => $study_status_id
        ]);
    }

    public static function addToTable()
    {
        $response           = array();
        $validator          = Validator::make(request()->all(), FormStudentsStudyCourse::rulesField('.*'), FormStudentsStudyCourse::customMessages(), FormStudentsStudyCourse::attributeField());
        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {
            try {
                $sid = '';
                foreach (request('student') as $student_request_id) {

                    if (!self::existsToTable($student_request_id, request('study_course_session'))) {

                        $values = [
                            'student_request_id'  => $student_request_id,
                            'study_course_session_id'  => request('study_course_session'),
                            'study_status_id'  => request('study_status'),
                        ];
                        $add = self::insertGetId($values);
                        if ($add) {
                            $sid  .= $add . ',';
                        }
                    }
                }
                if ($sid) {
                    $controller = new StudentsStudyCourseController;
                    $html = '';
                    foreach ($controller->list([], $sid) as  $row) {
                        $html .= view(self::$path['view'] . '.includes.tpl.tr', ['row' => $row])->render();
                    }

                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'html'      => $html,
                        'message'   =>  __('Add Successfully')
                    );
                } else {
                    $response       = array(
                        'success'   => false,
                        'errors'    => [],
                        'message'   => __('Add Unsuccessful') . PHP_EOL . __('Already exists'),
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
        $validator          = Validator::make(request()->all(), FormStudentsStudyCourse::rulesField('.*'), FormStudentsStudyCourse::customMessages(), FormStudentsStudyCourse::attributeField());
        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {
            try {
                $exists =  self::existsToTable(request('student')[0], request('study_course_session'));
                if ($exists) {
                    $response       = array(
                        'success'   => false,
                        'errors'    => [],
                        'message'   =>  __('Update Unsuccessful') . PHP_EOL . __('Already exists'),
                    );
                    if ($exists->study_status_id  !== request('study_status')) {
                        $exists = null;
                    } elseif ($exists->student_request_id  !== request('student')[0]) {
                        $exists = null;
                    }
                }
                if (!$exists) {
                    $update = self::where('id', $id)->update([
                        'student_request_id'  =>    request('student')[0],
                        'study_course_session_id'  => request('study_course_session'),
                        'study_status_id'  => request('study_status'),
                    ]);
                    if ($update) {
                        $controller = new StudentsStudyCourseController;
                        $response       = array(
                            'success'   => true,
                            'type'      => 'update',
                            'data'      => [
                                [
                                    'id' => $id,
                                ]

                            ],
                            'html'      => view(self::$path['view'] . '.includes.tpl.tr', ['row' => $controller->list([], $id)[0]])->render(),
                            'message'   =>  __('Update Successfully')
                        );
                    }
                }
            } catch (DomainException $e) {
                return $e;
            }
        }
        return $response;
    }

    public static function existsToTable($student_request_id, $study_course_session_id)
    {
        $student_request = StudentsRequest::where('id', $student_request_id)->get()->first();
        return self::join((new StudentsRequest())->getTable(), (new StudentsRequest())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.student_request_id')
            ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsRequest())->getTable() . '.student_id')
            ->where('student_id', $student_request->student_id)
            ->where('study_course_session_id', $study_course_session_id)
            ->groupBy('student_id')
            ->first();
    }

    public static function updateImageToTable($id, $photo)
    {
        $response = array(
            'success'   => false,
            'message'   => __('Update Failed'),
        );
        if ($photo) {
            try {
                $update =  self::where('id', $id)->update([
                    'photo'    => $photo,
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
    public static function getImage($student_request_id)
    {
        $response = array(
            'success'   => false,
            'data'      => [],
            'message'   => __('No photo'),
        );

        if ($student_request_id) {
            $get = self::where('student_request_id', $student_request_id)->get()->toArray();
            $data = array();
            if ($get) {
                foreach ($get as $key => $row) {
                    if ($row['photo']) {
                        $data[] = array(
                            'id'                      => $row['id'],
                            'study_course_session'    => ($row['study_course_session_id'] == null ? null : StudyCourseSession::getData($row['study_course_session_id'])['data'][0]),
                            'photo'                   => (ImageHelper::site(Students::$path['image'] . '/' . self::$path['image'], $row['photo'])),
                        );
                    }
                }
            }

            if ($data) {
                $response       = array(
                    'success'   => true,
                    'data'      => $data,
                );
            }
        }
        return $response;
    }
    public static function makeImageToTable($id, $options = null)
    {
        $response = array(
            'success'   => false,
            'type'   => 'makePhoto',
            'data'   => [],
        );

        if ($id) {
            $photo = null;
            if (request()->hasFile('photo')) {
                $photo      = request()->file('photo');
            }

            $photo = ImageHelper::uploadImage(
                $photo,
                Students::$path['image'] . '/' . self::$path['image'],
                null,
                request('photo')
            );
            if ($photo) {
                try {
                    $update =  self::where('id', $id)->update([
                        'photo' => $photo,
                    ]);
                    if ($update) {
                        $response       = array(
                            'success'   => true,
                            'type'      => 'makePhoto',
                            'data'      => ImageHelper::site(Students::$path['image'] . '/' . self::$path['image'], $photo),
                            'message'   =>  __('Update Successfully'),
                        );
                    }
                } catch (DomainException $e) {
                    return $e;
                }
            }
        }
        return $response;
    }
    public static function makeQrcodeToTable($id = null, $options = null)
    {

        if ($id) {
            return [
                'success'   => true,
                'type'   => 'makeQRCode',
                'message'   =>  __('Update Successfully'),
                'data'  => self::whereIn('id', explode(',', $id))->get()->map(function ($row) {
                    $q['size'] = request('qrcode_size') ? request('qrcode_size') : 100;
                    $date = new DateTime();
                    $date->modify(request('expired', '+1 year'));
                    $q['code']  = QRHelper::encrypt([
                        'stuId'  => $row->student_request_id,
                        'id'     => $row->id,
                        'type'   => Students::$path['role'],
                        'exp'    => $date->format('Y-m-d'),
                    ], '?fc');
                    if (request('qrcode_image_size')) {
                        $q['center']  = array(
                            'image' => $row['photo'],
                            'percentage' => request('qrcode_image_size') / $q['size']
                        );
                    }

                    $qrCode = ImageHelper::uploadImage(null,  Students::$path['image'] . '/' . QRHelper::$path['image'], null, QRHelper::make($q, true));

                    if ($qrCode) {
                        try {
                            $table = self::where('id', $row['id']);
                            $exists = $table->pluck('qrcode');
                            if ($exists) {
                                ImageHelper::delete(Students::$path['image'] . '/' . QRHelper::$path['image'], $exists->first());
                            }
                            $table->update([
                                'qrcode'  => $qrCode,
                            ]);
                        } catch (DomainException $e) {
                            return $e;
                        }
                        return ImageHelper::site(Students::$path['image'] . '/' . QRHelper::$path['image'], $qrCode);
                    }
                })
            ];
        }
        return [
            'success'   => false,
            'type'   => 'makeQRCode',
            'data'   => [],
        ];
    }

    public static function makeCardToTable()
    {

        $response = array(
            'success'   => false,
            'type'   => 'make',
            'data'   => [],
        );
        $id = '';
        if (request('cards')) {
            foreach (request('cards') as $key => $card) {
                if (count(request('cards')) == $key + 1) {
                    $id .= $card['id'];
                } else {
                    $id .= $card['id'] . ',';
                }

                $image =  ImageHelper::uploadImage($card['image'], Students::$path['image'] . '/' . CardFrames::$path['image']);
                if ($image) {

                    try {
                        $table = self::where('id', $card['id']);
                        $exists = $table->pluck('card');
                        if ($exists) {
                            ImageHelper::delete(Students::$path['image'] . '/' . CardFrames::$path['image'], $exists->first());
                        }
                        $table->update([
                            'card'  => $image,
                        ]);
                    } catch (DomainException $e) {
                        return $e;
                    }
                }
            }
            $response       = array(
                'success'   => true,
                'type'   => 'make',
                'message'   => __('Success'),
            );
        }
        return $response;
    }

    public static function makeCertificateToTable()
    {

        $response = array(
            'success'   => false,
            'type'   => 'make',
            'data'   => [],
        );
        $id = '';
        if (request('certificates')) {
            foreach (request('certificates') as $key => $certificate) {
                if (count(request('certificates')) == $key + 1) {
                    $id .= $certificate['id'];
                } else {
                    $id .= $certificate['id'] . ',';
                }

                $image =  ImageHelper::uploadImage($certificate['image'], Students::$path['image'] . '/' . CertificateFrames::$path['image']);
                if ($image) {
                    $table = self::where('id', $certificate['id']);
                    $exists = $table->get();
                    if ($exists) {
                        dd($exists);
                    }
                    $table->update([
                        'certificate'  => $image,
                    ]);
                }
            }
            $response       = array(
                'success'   => true,
                'type'   => 'make',
                'message'   => __('Success'),
            );
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

    public static function getStudy($student_id)
    {
        $get =  self::join((new StudentsStudyCourse())->getTable(), (new StudentsStudyCourse())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.student_request_id')
            ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.student_id')
            ->where((new StudentsStudyCourse())->getTable() . '.student_id', $student_id)
            ->groupBy('study_course_session_id')
            ->get()->toArray();

        $study_course_session_id = [];
        if ($get) {
            foreach ($get as $key => $row) {
                $study_course_session_id[] = $row['study_course_session_id'];
            }
            return StudyCourseSession::getData($study_course_session_id);
        } else {
            return StudyCourseSession::getData('null');
        }
    }
}
