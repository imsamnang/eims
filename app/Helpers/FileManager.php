<?php

namespace App\Helpers;

use DateTime;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FileManager
{

    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }

    protected static $filesIconClass = [
        'pdf'   => 'fas fa-file-pdf',
        'jpg'   => 'fas fa-image',
        'jpeg'  => 'fas fa-image',
        'png'   => 'fas fa-image',
        'svg'   => 'fas fa-image',
        'weba'  => 'fas fa-image',
    ];

    public static function get()
    {

        $directories = [];
        foreach (Storage::disk('public')->Directories() as  $directory) {
            $directories[$directory] =  [
                'name'              => $directory,
                'icon_class'        => 'fas fa-folder',
                'sub_directories'   => self::directory($directory),
                'link'              => route('filemanager.directory', [$directory]),
                'type'              => 'directory'
            ];
            $files = self::files($directory);
            $directories[$directory] += $files;

        }

        return $directories;
    }

    /**
     * @param string $path
     */
    public static function directory($path)
    {
        $directories = [];
        foreach (Storage::disk('public')->Directories($path) as  $directory) {

            $directories[$directory] = [
                'name'    =>  str_replace($path . '/', '', $directory),
                'icon_class' =>  'fas fa-folder',
                'sub_directories'   => self::directory($directory),
                'link'              => route('filemanager.directory', [$directory]),
                'type'              => 'directory'
            ];
        }
        return array_merge($directories, self::files($path));
    }

    /**
     * @param string $path
     */
    public static function files($path)
    {
        $files = [];
        foreach (File::files(storage_path('app/public/' . $path)) as $i=> $file) {
            $isImage = getImageSize($file->getPathname());
            $files[$i] = [
                'name'    =>  $file->getFilename(),
                'icon_class' =>  @self::$filesIconClass[$file->getExtension()],
                'icon_url' => $isImage?route('filemanager.file', [$path . '/' . $file->getFilename()]):null,
                'sub_directories'   => [],
                'link'              => route('filemanager.file', [$path . '/' . $file->getFilename()]),
                'type'              => $isImage? 'image':'file',
                'file_info'         => [
                    'name'  => $file->getFilename(),
                    'extension'  => $file->getExtension(),
                    'size'  => self::byteconvert($file->getSize()),
                    'date'  => DateHelper::convert(date('Y-m-d',$file->getCTime()), 'd-F-Y'),
                ]
            ];

            if($isImage){
                $files[$i]['file_info']['width'] = $isImage[0];
                $files[$i]['file_info']['height'] = $isImage[1];
            }
        }
        return $files;
    }

    /**
     * @param int $bytes
     * @return string
     */
    public static function byteconvert($bytes)
    {
        $label = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $bytes >= 1024 && $i < (count($label) - 1); $bytes /= 1024, $i++);
        return (round($bytes, 2) . " " . $label[$i]);
    }
}
