<?php

namespace App\Http\Controllers\FileManager;

use App\Models\Languages;
use App\Helpers\FileManager;
use App\Models\SocailsMedia;
use App\Http\Controllers\Controller;
use App\Models\App as AppModel;
use Illuminate\Support\Facades\File;

class FileManagerController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        AppModel::setConfig();
        Languages::setConfig();
        SocailsMedia::setConfig();
        view()->share('breadcrumb', []);
        view()->share('segments', null);
    }

    public function index()
    {
        $data['directories'] = FileManager::get();
        $data['view_directories'] = $data['directories'];
        return view('FileManager.index', $data);
    }
    public function directory($directory)
    {
        view()->share('segments', $directory);
        $data['directories'] = FileManager::get();
        $data['view_directories'] = FileManager::directory($directory);
        return view('FileManager.index', $data);
    }
    public function file($path)
    {
        return response(File::get(storage_path('app/public/' . $path)), 200, [
            'Content-Type' => mime_content_type(storage_path('app/public/' . $path))
        ]);
    }
}
