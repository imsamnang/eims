<?php

namespace App\Models;

use DomainException;


use Illuminate\Database\Eloquent\Model;

class StaffQualifications extends Model
{

    public static function getData($staff_id)
    {
        if ($staff_id) {
            try {
                $get = StaffQualifications::where('staff_id', $staff_id)->first();
                if ($get) {
                    $data[] = array(
                        'certificate' => StaffCertificate::getData($get['certificate_id'])['data'][0],
                        'extra_info'  => $get['extra_info']
                    );
                    $response       = array(
                        'success'   => true,
                        'data'      => $data,
                    );
                }
            } catch (DomainException $e) {
                return $e;
            }
        }
    }


    public static function addToTable($staff_id)
    {
        $response = array();
        try {
            $add = StaffQualifications::insert([
                'staff_id' => $staff_id,
                'certificate_id' => request('staff_certificate'),
                'extra_info' => request('staff_certificate_info'),


            ]);
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
            $add = StaffQualifications::where('staff_id', $staff_id)->update([
                'certificate_id' => request('staff_certificate'),
                'extra_info' => request('staff_certificate_info'),
            ]);
            if ($add) {
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
