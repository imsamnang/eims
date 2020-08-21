<?php

namespace App\Helpers;

use App\Models\CardFrames;
use Illuminate\Support\Facades\Session;

class CardHelper
{
    /**
     * @param array $nodes students | teachers | staff
     * @return array
     */
    public static function make($nodes)
    {
        $card = Session::get('card');
        $frame  = CardFrames::where('status', 1)->get()->map(function ($row) {
            $row['foreground'] = ImageHelper::site(CardFrames::$path['image'], $row->foreground, 'large');
            $row['background'] = ImageHelper::site(CardFrames::$path['image'], $row->background, 'large');
            return $row;
        })->first();
        $card_foreground = Session::has('card_foreground') ? (Session::get('card_foreground')) : $frame["foreground"];
        $card_background  = Session::has('card_background') ? (Session::get('card_background')) : $frame["background"];
        $layout     = ($card && $card["settings"]["layout"]) ? $card["settings"]["layout"] : $frame["layout"];

        $allCards = array();
        $cardContainer = [
            'width'  => ($layout ==  'vertical') ? 500 : 700,
            'height' => ($layout ==  'vertical') ?  350 : 250,
        ];
        $card["settings"]["container"] = $cardContainer;
        $card_foreground = [
            'x'     => 0,
            'y'     => 0,
            'width' => ($layout ==  'vertical') ? 250 : 350,
            'height' => ($layout ==  'vertical') ? 350 : 250,
            'image' => $card_foreground
        ];


        $card_background  = [
            'x'     => ($layout ==  'vertical') ? 252 : 352,
            'y'     => 0,
            'width' => ($layout ==  'vertical') ? 250 : 350,
            'height' => ($layout ==  'vertical') ? 350 : 250,
            'image' => $card_background
        ];

        $textColor = '#23499E';

        if ($nodes) {
            foreach ($nodes as $row) {

                $cardTmp = $card && $card["attributes"] ? $card["attributes"] : CardFrames::FrameData();
                $cardData  = array();
                foreach ($cardTmp  as $key => $tmp) {
                    if (array_key_exists($key, $row)) {
                        $obj = array();
                        $x = $cardContainer["width"] / 2;
                        $y = $cardContainer["height"] / 2;
                        $fontFamily = "NiDAKhmerEmpire";
                        $fontStyle = "normal";
                        $visible = false;
                        $draggable = false;

                        if ($key == "fullname") {
                            $x = $layout == "vertical" ? 100 : 180;
                            $y = $layout == "vertical" ? 205 : 80;
                            $fontFamily = "KhmerOSMoul";
                            $fontStyle = "bold";
                            $visible = true;
                        } else if ($key == "_fullname") {
                            $x = $layout == "vertical" ? 100 : 180;
                            $y = $layout == "vertical" ? 226 : 102;
                            $visible = true;
                        } else if ($key == "gender") {
                            $x = $layout == "vertical" ? 100 : 180;
                            $y = $layout == "vertical" ? 248 : 122;
                            $visible = true;
                        } else if ($key == "course") {
                            $x = $layout == "vertical" ? 100 : 180;
                            $y = $layout == "vertical" ? 272 : 148;
                            $visible = true;
                        } else if ($key == "id") {
                            $x = $layout == "vertical" ? 100 : 180;
                            $y = $layout == "vertical" ? 290 : 162;
                            $visible = true;
                        } else if ($key == "photo") {
                            $x = $layout == "vertical" ? 87  : 20;
                            $y = $layout == "vertical" ? 105 : 76;
                            $visible = true;
                        } else if ($key == "qrcode") {
                            $x = $layout == "vertical" ? 330 : 480;
                            $y = $layout == "vertical" ? 75 : 35;
                            $visible = true;
                        }

                        if ($key == "photo" || $key == "qrcode") {
                            $obj =  array(
                                'attrs' => [
                                    'x'       => $x,
                                    'y'       => $y,
                                    'width'   => $key == "photo" ? 75 : 90,
                                    'height'  => $key == "photo" ? 85 : 90,
                                    'source'  => $row[$key],
                                    'visible' => $visible,
                                    'name'    => $key,
                                    'id'      => $key,
                                ],
                                'className' => 'Image',
                            );
                            if ($tmp && gettype($tmp) == "array") {
                                $tmp["attrs"]['source']  = $row[$key];
                                $tmp["attrs"]['draggable']  = $draggable;
                                $obj = $tmp;
                            }
                        } else {

                            $obj =  array(
                                'attrs' => [
                                    'x'          => $x,
                                    'y'          => $y,
                                    'text'       => $row[$key],
                                    'fill'       => $textColor,
                                    'fontSize'   => 14,
                                    'fontFamily' => $fontFamily,
                                    'fontStyle'  => $fontStyle,
                                    'width'      => 150,
                                    'height'     => 14,
                                    'visible'    => $visible,
                                    'name'       => $key,
                                    'id'         => $key,
                                ],
                                'className' => 'Text',
                            );

                            if ($tmp && gettype($tmp) == "array") {
                                $tmp["attrs"]['text']  = $row[$key];
                                $tmp["attrs"]['draggable']  = $draggable;
                                $obj = $tmp;
                            }
                        }

                        $cardData[] = $obj;
                    }
                }

                $makeCard = [
                    'realId' => $row["realId"],
                    'id' => $row["id"],
                    'attrs' => [
                        'width'  => $cardContainer["width"],
                        'height' => $cardContainer["height"],
                    ],
                    'className' => 'Stage',
                    'children' =>
                    [
                        [
                            'attrs' => [],
                            'className' => 'Layer',
                            'children'  => [
                                [
                                    'attrs' =>
                                    [
                                        'x'      => $card_foreground['x'],
                                        'y'      => $card_foreground['y'],
                                        'width'  => $card_foreground['width'],
                                        'height' => $card_foreground['height'],
                                        'source' => $card_foreground['image'],
                                    ],
                                    'className' => 'Image',
                                ],

                                [
                                    'attrs' =>
                                    [
                                        'x'      => $card_background['x'],
                                        'y'      => $card_background['y'],
                                        'width'  => $card_background['width'],
                                        'height' => $card_background['height'],
                                        'source' => $card_background['image'],
                                    ],
                                    'className' => 'Image',
                                ],
                            ],
                        ],

                        [
                            'attrs' => [],
                            'className' => 'Layer',
                            'children'  => $cardData,
                        ],
                    ],
                ];

                $allCards[] = $makeCard;
            }
            return array(
                'success'   => true,
                'data'      => $allCards,
                'settings'  => $card && $card["settings"] ? $card["settings"] : [
                    "layout" => $layout,
                    "container"  => $cardContainer
                ],
                "frame" => $frame,
            );
        }

        return array(
            'success'   => false,
            'data'      => $allCards,
            'settings'  => $card && $card["settings"] ? $card["settings"] : [
                "layout" => $layout,
                "container"  => $cardContainer
            ],
            "frame" => $frame,
        );
    }
}
