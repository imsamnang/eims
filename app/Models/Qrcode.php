<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Qrcode extends Model
{
    public static $path = [
        'url'   => 'qrcode',
    ];

    public static function encryptQrcode($code)
    {
        $encrypt        = base64_encode(gettype($code) ? json_encode($code) : $code);
        $qrCodeWithUrl  = url(Qrcode::path('url').'?c=' . $encrypt);
        return $qrCodeWithUrl;
    }

    public static function decryptQrcode($codeUrl , $unserialize = false)
    {
        $query_str = parse_url($codeUrl, PHP_URL_QUERY);
        parse_str($query_str, $query_params);
        if($unserialize){
            $decrypt = json_decode(base64_decode($query_params['c']) , true);
        }else{
            $decrypt = $query_params['c'];
        }
        return $decrypt;
    }

}
