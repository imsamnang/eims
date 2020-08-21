<?php

namespace App\Helpers;

use DomainException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ImageHelper
{

    public static $path = [
        'image'     =>  'images',
        'resize'    =>  [
            'small' => 120,
            'large' => 480,
        ],
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
    /**
     * @return asset('/assets/img/icons/image.jpg');
     */
    public static function prefix()
    {
        return asset('/assets/img/icons/image.jpg');
    }

    public static function uploadImage($photo, $destination, $rename = null, $file = null, $slide = null, $resize = true)
    {
        ini_set('memory_limit', '1G');

        $folder = 'public/' . self::$path['image'] . '/' . $destination;
        Storage::makeDirectory($folder);

        $destinationPath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . $folder;
        $newFilenameNoExtension =  self::num_random(8) . '_' . self::num_random(15) . '_' . self::num_random(19) . '_n.';
        $imageEx  = pathinfo($file, PATHINFO_EXTENSION);
        $name  =  $rename ? ($rename . '.' . pathinfo($file, PATHINFO_EXTENSION)) : ($newFilenameNoExtension . pathinfo($file, PATHINFO_EXTENSION));

        Storage::makeDirectory($folder . '/original');
        $destinationPath .= '/original';

        if ($photo) {
            $getMimeType = explode('/', $photo->getMimeType());
            $imageEx    = $photo->getClientOriginalExtension() ? $photo->getClientOriginalExtension() : end($getMimeType);
            $name       =  $rename ? ($rename . '.' . $imageEx) : ($newFilenameNoExtension . $imageEx);
            $name       = strtolower($name);


            if ($photo->move($destinationPath, $name)) {
                if (in_array(File::mimeType($destinationPath . '/' . $name), self::$path['mime'])) {
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

        if ($resize) {
            foreach (self::$path['resize'] as $size) {
                $folderSize = 'public/' . self::$path['image'] . '/' . $destination . '/' . $size;
                Storage::makeDirectory($folderSize);
                $fileInFolderSize = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . $folderSize . '/' . $name;
                if (!file_exists($fileInFolderSize)) {
                    File::copy($destinationPath . '/' . $name, $fileInFolderSize);
                    if ($size == 'small') {
                        try {
                            Image::make($fileInFolderSize)->resize(120, 120, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            })->save(null, 100);
                        } catch (DomainException $e) {
                            return $e;
                        }
                    } elseif ($size == 'large') {
                        try {
                            Image::make($fileInFolderSize)->resize(480, 480, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            })->save(null, 100);
                        } catch (DomainException $e) {
                            return $e;
                        }
                    }
                }
            }
        }

        if ($slide) {
            $folderSize = 'public/' . self::$path['image'] . '/' . $destination . '/slide';
            Storage::makeDirectory($folderSize);
            $fileInFolderSize = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . $folderSize . '/' . $name;
            if (!file_exists($fileInFolderSize)) {
                File::copy($destinationPath . '/' . $name, $fileInFolderSize);
                Image::make($fileInFolderSize)->fit(1000, 400, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save(null, 100);
            }
        }
        return $name;
    }
    public static function getImage($filename, $path, $type = null, $width = null, $height = null, $quality = null)
    {


        if ($type) {
            $folderSize = 'public/' . self::$path['image'] . '/' . $path . '/' . $type;
        } else {
            $folderSize = 'public/' . self::$path['image'] . '/' . $path . '/small';
        }



        $dir = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . $folderSize;
        $file = $dir . '/' . $filename;
        $response = [
            'success' => false,
            'error'   => '?type=[small, large, original]'
        ];
        if (File::exists($file)) {
            if (in_array(File::mimeType($file), self::$path['mime'])) {
                $response = Image::make($file)->response(null, $quality);
            }
        } else {
            $response = [
                'success' => false,
                'error'   => 'File not found.'
            ];
        }
        return $response;
    }
    public static function getImageNoType($filename, $path, $encode = null)
    {
        if ($filename && $path) {
            $dir = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . 'public/' . (self::$path['image'] . '/' . $path);

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
            $uurl = url(self::$path['image'] . '/' . $path . '/' . $filename);

            if ($type) {
                $uurl .= '?type=' . $type;
            } else {
                if ($width) {
                    $uurl .= '?w=' . $width;
                }
                if ($height) {
                    if ($width) {
                        $uurl .= '&h=' . $height;
                    } else {
                        $uurl .= '?h=' . $height;
                    }
                }
                if ($quality) {
                    if ($height) {
                        $uurl .= '&q=' . $quality;
                    } else {
                        $uurl .= '?q=' . $quality;
                    }
                }
            }
            return $uurl;
        }
        return null;
    }

    public static function delete($path, $filename)
    {
        $dir = Storage::disk('local')
            ->getDriver()
            ->getAdapter()
            ->getPathPrefix() . 'public/' . self::$path['image'] . '/' . $path;

        if (File::exists($dir . '/original/' . $filename)) {
            File::delete($dir . '/original/' . $filename);
        }
        foreach (self::$path['resize'] as $size) {
            $file = $dir . '/' . $size . '/' . $filename;
            if (File::exists($file)) {
                File::delete($file);
            }
        }
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

    /**
     * @param array $paths
     */
    public static function generate()
    {
        foreach (Storage::disk('public')->allDirectories(self::$path['image']) as  $directory) {
            if (Str::contains($directory, 'original')) {
                foreach ((File::allFiles(storage_path('app/public/' . $directory))) as $image) {
                    foreach (self::$path['resize'] as $sizeName => $size) {
                        $target = str_replace('original', $sizeName, $image->getPathname());
                        File::copy($image->getPathname(), $target);
                        try {
                            Image::make($target)->resize($size, $size, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            })->save(null, 100);
                        } catch (DomainException $e) {
                            return $e;
                        }
                    }
                }
            }
        }
    }
}
