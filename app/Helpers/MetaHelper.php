<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;


class MetaHelper
{
    public static function setConfig(array $arrayObject)
    {
        Config::set("app.meta", MetaHelper::all($arrayObject));
    }

    public static function all(array $arrayObject)
    {
        return [
            'simple'   => MetaHelper::simple($arrayObject),
            'twitter'   => MetaHelper::twitter($arrayObject),
            'graph-data'   => MetaHelper::graphData($arrayObject),
            'google+'   => MetaHelper::googlePlus($arrayObject),
        ];
    }


    public static function simple(array $arrayObject)
    {
        return [
            [
                'charset'  => 'utf-8'
            ],
            [
                "name" => "csrf-token",
                "content" => csrf_token(),
            ],
            [
                'http-equiv'  => 'content-type',
                'content'  => 'text/html;charset=utf-8',
            ],
            [
                'name'  => 'viewport',
                'content'  => 'width=device-width, initial-scale=1, shrink-to-fit=no',
            ],
            [
                'name'  => 'author',
                'content'  => $arrayObject["author"],
            ],
            [
                'name'  => 'keywords',
                'content'  => $arrayObject["keywords"],
            ],
            [
                'name'  => 'description',
                'content'  => $arrayObject["description"],
            ],
        ];
    }

    public static function twitter(array $arrayObject)
    {
        return [
            [
                'name'  => 'twitter:card',
                'content'  => 'twitter:card',
            ],
            [
                'name'  => 'twitter:site',
                'content'  => $arrayObject["link"],
            ],
            [
                'name'  => 'twitter:title',
                'content'  => $arrayObject["title"],
            ],
            [
                'name'  => 'twitter:description',
                'content'  => $arrayObject["description"],
            ],
            [
                'name'  => 'twitter:creator',
                'content'  => $arrayObject["author"],
            ],
            [
                'name'  => 'twitter:image',
                'content'  =>  $arrayObject["image"],
            ],
        ];
    }

    public static function graphData(array $arrayObject)
    {
        return [
            [
                'property'  => 'fb:app_id',
                'content'  => '',
            ],
            [
                'property'  => 'og:title',
                'content'  => $arrayObject["title"],
            ],
            [
                'property'  => 'og:type',
                'content'  => '',
            ],
            [
                'property'  => 'og:url',
                'content'  => $arrayObject["link"],
            ],
            [
                'property'  => 'og:image',
                'content'  => $arrayObject["image"],
            ],
            [
                'property'  => 'og:description',
                'content'  => $arrayObject["description"],
            ],
            [
                'property'  => 'og:site_name',
                'content'  => $arrayObject["title"],
            ],
        ];
    }

    public static function googlePlus(array $arrayObject)
    {
        return [
            [
                'itemprop'  => 'name',
                'content'  => $arrayObject["title"],
            ],
            [
                'itemprop'  => 'description',
                'content'  => $arrayObject["description"],
            ],
            [
                'itemprop'  => 'image',
                'content'  => $arrayObject["image"],
            ],
        ];
    }
}
