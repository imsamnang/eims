<?php

namespace App\Models;

use Embed\Embed;
use DomainException;
use App\Helpers\FileHelper;
use App\Helpers\ImageHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class StudySubjectLesson extends Model
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
            'file'  => $table,
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

    public static function getData($id = null, $staff_teach_subject_id = null, $paginate = null, $groupByStaffTeachSubjectId = true)
    {


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
            $get = self::rderBy('id', $orderBy);
        } else {
            $get = self::rderBy('id', 'DESC');
        }


        if ($id) {
            $get = $get->whereIn('id', $id);
        } else {
            if ($staff_teach_subject_id) {
                $get = $get->where('staff_teach_subject_id', $staff_teach_subject_id);
            }
        }


        $pages = [];
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


                $data[$key]                       = array(
                    'id'   => $row['id'],
                    'staff_teach_subject'      => StaffTeachSubject::where('id',$row['staff_teach_subject_id'])->first(),
                    'title' => $row['title'],
                    'source_file'              => FileHelper::site(self::path('file'), $row['source_file']),
                    'source_file_info'         => FileHelper::getFileInfo(self::path('file'), $row['source_file']),
                    'source_link'              => $row['source_link'] ? json_decode($row['source_link'], true) : [],
                    'image'                   => $row['image'] ? (ImageHelper::site(self::ath('image'), $row['image'])) : asset('/assets/img/icons/pdf.png'),
                    'action'                   => [
                        'edit' => url(Users::role() . '/teaching/' . self::ath('url') . '/edit/' . $row['id']), //?id
                        'view' => url(Users::role() . '/teaching/' . self::ath('url') . '/view/' . $row['id']), //?id
                        'delete' => url(Users::role() . '/teaching/' . self::ath('url') . '/delete/' . $row['id']), //?id
                    ]
                );
                if ($data[$key]['source_link']['facebook']) {
                    $fb =  Embed::create($data[$key]['source_link']['facebook']);
                    $data[$key]['source_link']['facebook'] = [
                        'iframe' => $fb->getCode(),
                        'url' => $fb->getUrl(),
                    ];
                }

                if ($data[$key]['source_link']['youtube']) {
                    $yt =  Embed::create($data[$key]['source_link']['youtube']);
                    $data[$key]['source_link']['youtube'] = [
                        'iframe' => $yt->getCode(),
                        'url' => $yt->getUrl(),
                    ];
                }


                if (request('ref') == self::ath('url')) {
                    $data[$key]['action'] = [
                        'edit' => url(Users::role() . '/study/' . self::ath('url') . '/edit/' . $row['id']), //?id
                        'view' => url(Users::role() . '/study/' . self::ath('url') . '/view/' . $row['id']), //?id
                        'delete' => url(Users::role() . '/study/' . self::ath('url') . '/delete/' . $row['id']), //?id
                    ];
                }
                $pages['listData'][] = array(
                    'id'     => $data[$key]['id'],
                    'name'   => $data[$key]['title'],
                    'image'  => $data[$key]['image'],
                    'action' => $data[$key]['action'],

                );
            }



            $response       = array(
                'success'   => true,
                'data'      => $data,
                'pages'      => $pages,

            );
        } else {
            $response = array(
                'success'   => false,
                'data'      => [],
                'pages'      => $pages,
                'message'   => __('No Data'),
            );
        }

        return $response;
    }


    public static function addToTable()
    {

        if (!request()->hasFile('source_file')) {
            return array(
                'success'   => false,
                'type'      => 'add',
                'message'   => array(
                    'title' => __('Error'),
                    'text'  => __('Add Unsuccessful') . PHP_EOL
                        . __('File empty'),
                    'button'   => array(
                        'confirm' => __('Ok'),
                        'cancel'  => __('Cancel'),
                    ),
                ),
            );
        }

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

                $values['title']                    = trim(request('title'));
                $values['staff_teach_subject_id']    =  request('staff_teach_subject');
                $values['source_link']         = json_encode([
                    'youtube'   => request('source_link_youtube'),
                    'facebook'   => request('source_link_facebook'),
                ]);

                $add = self::nsertGetId($values);
                if ($add) {
                    if (request()->hasFile('source_file')) {
                        $file      = request()->file('source_file');
                        self::pdateFileToTable($add, FileHelper::uploadFile($file, self::path('file')));
                    }
                    if (request()->hasFile('image')) {
                        $image      = request()->file('image');
                        self::pdateImageToTable($add, ImageHelper::uploadImage($image, self::ath('image')));
                    }

                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'data'      => self::etData($add)['data'],
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

            try {
                $values['title']               = trim(request('title'));
                $values['staff_teach_subject_id']    =  request('staff_teach_subject');
                $values['source_link']         = json_encode([
                    'youtube'   => request('source_link_youtube'),
                    'facebook'   => request('source_link_facebook'),
                ]);
                $update = self::here('id', $id)->update($values);
                if ($update) {
                    if (request()->hasFile('source_file')) {
                        $file      = request()->file('source_file');
                        self::pdateFileToTable($id, FileHelper::uploadFile($file, self::path('file')));
                    }
                    if (request()->hasFile('image')) {
                        $image      = request()->file('image');
                        self::pdateImageToTable($id, ImageHelper::uploadImage($image, self::ath('image')));
                    }
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

    public static function updateFileToTable($id, $source_file)
    {
        $response = array(
            'success'   => false,
            'message'   => __('Update Failed'),
        );
        if ($source_file) {
            try {
                $update =  self::here('id', $id)->update([
                    'source_file'    => $source_file,
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

    public static function updateImageToTable($id, $image)
    {
        $response = array(
            'success'   => false,
            'message'   => __('Update Failed'),
        );
        if ($image) {
            try {
                $update =  self::here('id', $id)->update([
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
            if (self::hereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = self::hereIn('id', $id)->delete();
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
