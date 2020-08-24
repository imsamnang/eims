<?php

namespace App\Models;

use DomainException;


use App\Helpers\ImageHelper;
use App\Http\Controllers\Quiz\QuizController;
use App\Http\Requests\FormQuiz;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Validator;

class Quiz extends Model
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

    public static function getData($id = null, $edit = null, $paginate = null, $search = null)
    {
        $pages['form'] = array(
            'action'  => array(
                'add'    => url(Users::role() . '/' . Quiz::path('url') . '/add/'),
            ),
        );


        $orderBy = 'ASC';
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
        }
        $get = Quiz::orderBy('id', $orderBy);

        if ($id) {
            $get = $get->whereIn('id', $id);
        } else {
            if (request('instituteId')) {
                $get = $get->where('institute_id', request('instituteId'));
            }
            if (Auth::user()->role_id == 8) {
                $get = $get->where('staff_id', Auth::user()->node_id);
            }
        }


        if ($search) {
            $get = $get->where('name', 'LIKE', '%' . $search . '%');
            if (config('app.languages')) {
                foreach (config('app.languages') as $lang) {
                    $get = $get->orWhere($lang['code_name'], 'LIKE', '%' . $search . '%');
                }
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
                // if( $row['id'] == 1 && Auth::user()->role_id != 1){
                //     continue;
                // }

                $data[$key]         = array(
                    'id'            => $row['id'],
                    'institute'     => Institute::getData($row['institute_id'])['data'][0],
                    'name'          => $row[app()->getLocale()] ? $row[app()->getLocale()] : $row['name'],
                    'description'   => $row['description'],
                    'image'         => $row['image'] ? (ImageHelper::site(Quiz::path('image'), $row['image'])) : ImageHelper::prefix(),
                    'action'        => [
                        'edit' => url(Users::role() . '/' . Quiz::path('url') . '/edit/' . $row['id']),
                        'view' => url(Users::role() . '/' . Quiz::path('url') . '/view/' . $row['id']),
                        'delete' => url(Users::role() . '/' . Quiz::path('url') . '/delete/' . $row['id']),
                        'question_answer'  => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizQuestion::path('url') . '/list/?quizId=' . $row['id']),
                    ]
                );
                $pages['listData'][] = array(
                    'id'     => $data[$key]['id'],
                    'name'   => $data[$key]['name'],
                    'image'   => $data[$key]['image'],
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



    public static function addToTable()
    {
        $response           = array();
        $rules = FormQuiz::rules();
        $rules['name'] = 'required|unique:' . (new Quiz)->getTable() . ',name';
        $validator          = Validator::make(request()->all(), $rules, FormQuiz::messages(), FormQuiz::attributes());

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {
                $add = Quiz::insertGetId([
                    'staff_id'    => Auth::user()->node_id,
                    'institute_id' => trim(request('institute')),
                    'name'        => trim(request('name')),
                    'description' => request('description'),
                    'en'          => trim(request('en')),
                    'km'          => trim(request('km')),
                ]);
                if ($add) {

                    if (request()->hasFile('image')) {
                        $image      = request()->file('image');
                        Quiz::updateImageToTable($add, ImageHelper::uploadImage($image, Quiz::path('image')));
                    }
                    $controller = new QuizController;

                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'html'      => view(Quiz::path('view') . '.includes.tpl.tr', ['row' => $controller->list([], $add)[0]])->render(),
                        'message'   => __('Add Successfully'),
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
        $rules = FormQuiz::rules();
        $rules['name'] = 'required|unique:' . (new Quiz)->getTable() . ',name,' . $id;
        $validator          = Validator::make(request()->all(), $rules, FormQuiz::messages(), FormQuiz::attributes());

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {

                $update = Quiz::where('id', $id)->update([
                    'institute_id' => trim(request('institute')),
                    'name'        => trim(request('name')),
                    'description' =>  request('description'),
                    'en'          => trim(request('en')),
                    'km'          => trim(request('km')),
                ]);
                if ($update) {
                    if (request()->hasFile('image')) {
                        $image      = request()->file('image');
                        Quiz::updateImageToTable($id, ImageHelper::uploadImage($image, Quiz::path('image')));
                    }
                    $controller = new QuizController;
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'data'      => [
                            [
                                'id' => $id,
                            ]

                        ],
                        'html'      => view(Quiz::path('view') . '.includes.tpl.tr', ['row' => $controller->list([], $id)[0]])->render(),
                        'message'   =>  __('Update Successfully')
                    );
                }
            } catch (DomainException $e) {
                return $e;
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
                $update =  Quiz::where('id', $id)->update([
                    'image'    => $image,
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
    public static function deleteFromTable($id)
    {
        if ($id) {
            $id  = explode(',', $id);
            if (Quiz::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = Quiz::whereIn('id', $id)->delete();
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
