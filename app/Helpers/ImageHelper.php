<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ImageHelper
{

    public static $path = [
        'image'     =>  'images',
        'resize'    =>  [120, 240, 480],
        'mime'      => [
            'image/png',
            'image/x-png',
            'image/jpg',
            'image/jpeg',
            'image/pjpeg',
            'image/gif',
            'image/webp',
            'image/x-webp',
        ],
    ];

    public static function uploadImage($photo, $destination, $rename = null, $file = null)
    {

        $folder = 'public/'.ImageHelper::$path['image'] . '/' . $destination;
        Storage::makeDirectory($folder);
        $destinationPath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . $folder;
        $newFilenameNoExtension =  ImageHelper::num_random(8) . '_' . ImageHelper::num_random(15) . '_' . ImageHelper::num_random(19) . '_n.';
        $imageEx  = pathinfo($file, PATHINFO_EXTENSION);
        $name  =  $rename ? ($rename . '.' . pathinfo($file, PATHINFO_EXTENSION)) : ($newFilenameNoExtension . pathinfo($file, PATHINFO_EXTENSION));

        if ($photo) {
            $getMimeType = explode('/', $photo->getMimeType());
            $imageEx    = $photo->getClientOriginalExtension() ? $photo->getClientOriginalExtension() : end($getMimeType);
            $name       =  $rename ? ($rename . '.' . $imageEx) : ($newFilenameNoExtension . $imageEx);
            if ($photo->move($destinationPath, $name)) {
                if(in_array(File::mimeType($destinationPath . '/' . $name),ImageHelper::$path['mime'])){
                    $image = Image::make($destinationPath . '/' . $name);
                    $image->widen($image->width(), function ($constraint) {
                        $constraint->upsize();
                    })->save(null, 50);
                }
            }
        } else {
            if ($file && file_exists($file)) {
                File::copy($file,  $destinationPath . '/' . $name);
            } elseif ($file && preg_match('/data:image/i', $file) && preg_match('/base64/i', $file)) {
                $getMimeType = explode('/', explode(';', $file)[0]);
                $name   .= end($getMimeType);
                Image::make(file_get_contents($file))->save($destinationPath . '/' . $name);
            }
        }

        

        return $name;
    }
    public static function getImage($filename, $path, $encode = null, $type = null, $width = null, $height = null, $quality = null)
    {

        $response = null;
        if ($quality) {
            $quality = $quality > 100 ? 100 : $quality;
        }

        if ($filename && $path) {
            $dir = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() .'public/'. (ImageHelper::$path['image'] . '/' . $path);

            $file = $dir . '/' . $filename;
            if(File::exists($file)){
                if(in_array(File::mimeType($file),ImageHelper::$path['mime'])){
                    if ($type || !$width || !$height) {
                        if ($type == 'original') {
                            if ($encode) {
                                $response = (string) Image::make($file)->encode('data-url', $quality);
                            } else {
                                $response = Image::make($file)->response(null, $quality);
                            }
                        } elseif ($type == 'larg') {
                            if ($encode) {
                                $response = (string) Image::make($file)->fit(ImageHelper::$path['resize'][2], ImageHelper::$path['resize'][2], function ($constraint) {
                                    $constraint->aspectRatio();
                                })->encode('data-url', $quality);
                            } else {
                                $response = Image::make($file)->fit(ImageHelper::$path['resize'][2], ImageHelper::$path['resize'][2], function ($constraint) {
                                    $constraint->aspectRatio();
                                })->response(null, $quality);
                            }
                        } elseif ($type == 'slide') {

                            if ($encode) {
                                $response = (string) Image::make($file)->fit(1000, 400, function ($constraint) {
                                    $constraint->aspectRatio();
                                })->encode('data-url', $quality);
                            } else {
                                $response = Image::make($file)->fit(1000, 400, function ($constraint) {
                                    $constraint->aspectRatio();
                                })->response(null, $quality);
                            }
                        } elseif ($type == 'cover') {

                            if ($encode) {
                                $response = (string) Image::make($file)->fit(380, 140, function ($constraint) {
                                    $constraint->aspectRatio();
                                })->encode('data-url', $quality);
                            } else {
                                $response = Image::make($file)->fit(380, 140, function ($constraint) {
                                    $constraint->aspectRatio();
                                })->response(null, $quality);
                            }
                        } elseif ($type == 'middle') {
                            if ($encode) {
                                $response = (string) Image::make($file)->fit(ImageHelper::$path['resize'][1], ImageHelper::$path['resize'][1], function ($constraint) {
                                    $constraint->aspectRatio();
                                })->encode('data-url', $quality);
                            } else {
                                $response = Image::make($file)->fit(ImageHelper::$path['resize'][1], ImageHelper::$path['resize'][1], function ($constraint) {
                                    $constraint->aspectRatio();
                                })->response(null, $quality);
                            }
                        } else {
                            if ($encode) {
                                $response = (string) Image::make($file)->fit(ImageHelper::$path['resize'][0], ImageHelper::$path['resize'][0], function ($constraint) {
                                    $constraint->aspectRatio();
                                })->encode('data-url', $quality);
                            } else {
                                $response = Image::make($file)->fit(ImageHelper::$path['resize'][0], ImageHelper::$path['resize'][0], function ($constraint) {
                                    $constraint->aspectRatio();
                                })->response(null, $quality);
                            }
                        }
                    } else {
                        $image = Image::make($file);
                        if ($width > $image->getWidth()) {
                            $response = [
                                'success'  => false,
                                'message'  => 'Bad URL timestamp',
                                'image'    => [
                                    'width' => $image->getWidth()
                                ]
                            ];
                        } elseif ($height > $image->getHeight()) {
                            $response = [
                                'success'  => false,
                                'message'  => 'Bad URL timestamp',
                                'image'    => [
                                    'height' => $image->getHeight()
                                ]
                            ];
                        } else {
                        }
                        if ($encode) {
                            $response = (string) Image::make($file)->resize($width, $height, function ($constraint) {
                                $constraint->aspectRatio();
                            })->encode('data-url', $quality);
                        } else {
                            $response = Image::make($file)->resize($width, $height, function ($constraint) {
                                $constraint->upsize();
                            })->response(null, $quality);
                        }
                    }
                }else{
                    $headers = [
                        'Accept-Ranges'  => 'bytes',
                        'Content-Type'   => File::mimeType($file),
                        'Content-Length' => File::size($file),
                        'Content-Disposition' => 'inline; filename='. $filename
                    ];
                    return response()->stream(function() use ($file) {
                        try {
                            $stream = fopen($file, 'r');
                            fpassthru($stream);
                        } catch(Exception $e) {
                            Log::error($e);
                        }
                    }, 200, $headers);
                }
            }

        }

        return $response;
    }
    public static function getImageNoType($filename, $path, $encode = null)
    {
        if ($filename && $path) {
            $dir = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() .'public/'. (ImageHelper::$path['image'] . '/' . $path);

            $file = $dir . '/' . $filename;
            if ($encode) {
                $response = (string) Image::make($file)->encode('data-url');
            } else {
                $response = Image::make($file)->response();
            }
            return $response;
        }

        return null;
    }
    public static function site($path, $filename, $type = null, $width = null, $height = null, $quality = null)
    {
        if ($path && $filename) {
            $uurl = url(ImageHelper::$path['image'] . '/' . $path . '/' . $filename);

            if ($type) {
                $uurl .= '?type=' . $type;
            } else {
                if ($width) {
                    $uurl .= '?w=' . $width;
                }
                if ($height) {
                    if($width){
                        $uurl .= '&h=' . $height;
                    }else{
                        $uurl .= '?h=' . $height;
                    }
                }
                if ($quality) {
                    if ($height) {
                        $uurl .= '&q=' . $quality;
                    }else{
                        $uurl .= '?q=' . $quality;
                    }
                }
            }
            return $uurl;
        }
        return null;
    }

    public static function num_random($length = 10)
    {
        $chars = '0123456789011121314151617181920';
        $str = '';
        $size = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }
        return $str;
    }
}
