<?php

namespace App\Models;

use DomainException;


use Illuminate\Database\Eloquent\Model;

class StaffExperience extends Model
{

    public static function getData($staff_id)
    {
        if ($staff_id) {
            try {
                $get = StaffExperience::where('staff_id', $staff_id)->get()->toArray();
                if ($get) {
                    $data = [];
                    foreach ($get as $key => $row) {
                        $data[] = array(
                            'id' =>  $row['id'],
                            'experience' => $row['experience'],
                            'experience_info' => $row['experience_info'],
                        );
                    }
                    $response       = array(
                        'success'   => true,
                        'data'      => $data,
                    );
                }
           } catch (\Throwable $th) {
                        throw $th;
                    }
        }
    }


    public static function addToTable($staff_id)
    {
        $response = array();
        try {
            $values = [];
            foreach (request('experience') as $key => $value) {
                $values[] = [
                    'staff_id'   => $staff_id,
                    'experience' => $value,
                    'experience_info' => request('experience_info')[$key],
                ];
            }
            $add = StaffExperience::insert($values);

            if ($add) {
                $response       = array(
                    'success'   => true,
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
        } catch (DomainException $e) {
            return $e;
        }
        return $response;
    }

    public static function updateToTable($staff_id)
    {
        $response = array();
        try {
            $ids = [];
            $update = null;
            foreach (request('experience') as $key => $experience) {
                preg_match('/^id-/', $key, $match);
                if ($match) {
                    $id = str_replace('id-', '', $key);
                    $ids[] = $id;
                    $update = StaffExperience::where('id', $id)->update([
                        'experience'         => trim($experience),
                        'experience_info' => isset(request('experience_info')[$key]) ? trim(request('experience_info')[$key]) : null,
                    ]);
                } else {
                    if ($experience) {
                        $update = StaffExperience::insertGetId([
                            'staff_id' => $staff_id,
                            'experience'         => trim($experience),
                            'experience_info' => isset(request('experience_info')[$key]) ? trim(request('experience_info')[$key]) : null,
                        ]);
                        $ids[] = $update;
                    }
                };
            }
            StaffExperience::whereNotIn('id', $ids)->where('staff_id', $staff_id)->delete();

            if ($update) {
                $response       = array(
                    'success'   => true,
                    'message'   =>  __('Update Successfully'),
                );
            }
        } catch (DomainException $e) {
            return $e;
        }
        return $response;
    }
}
