<?php

namespace App\Http\Controllers;

use App\Models\App as AppModel;
use App\Models\Users;
use App\Models\Languages;
use App\Models\Sponsored;
use App\Helpers\FormHelper;
use App\Helpers\ImageHelper;
use App\Helpers\MetaHelper;

use App\Models\StudyCourse;
use App\Models\ThemesColor;
use App\Models\ActivityFeed;
use App\Models\SocailsMedia;
use App\Models\FeatureSlider;
use App\Models\StudyPrograms;
use App\Http\Requests\FormContact;
use Illuminate\Support\Facades\Auth;
use Laracasts\Utilities\JavaScript\JavaScriptFacade;

class FrontController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        AppModel::setConfig();
       Languages::setConfig(); AppModel::setConfig();  SocailsMedia::setConfig();
        view()->share('breadcrumb', []);
    }
    public function index($param1 = null, $param2 = null, $param3 = null)
    {
        if (Auth::user()) {
            JavaScriptFacade::put([
                'User'  => [
                    'id'  => Auth::user()->id,
                    'name'  => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'profile'   => Auth::user()->profile(),
                ]
            ]);
        }

        $data['formName'] = null;
        $data['formAction'] = null;
        $data['title']    = config('app.name');
        $data['view']     = 'Front.includes.home.index';
        $data['sliders']['data']  = FeatureSlider::get(['id', 'image'])->map(function ($row) {
            $row['image']   = ImageHelper::site(FeatureSlider::path('image'), $row->image);
            return $row;
        });
        $data['sponsored']['data'] = Sponsored::get(['id', 'image'])->map(function ($row) {
            $row['image']   = ImageHelper::site(Sponsored::path('image'), $row->image);
            return $row;
        });

        if (strtolower($param1) == null) {
            $data['title']  = config('app.name');
        } elseif (strtolower($param1) == 'home') {
            $data['title']  = config('app.name') . ' | ' . __('Home');
            $data['view']   = 'Front.includes.home.index';
        } elseif (strtolower($param1) == 'about') {
            $data['title']  = config('app.name') . ' | ' . __('About');
            $data['view']   = 'Front.includes.about.index';
        } elseif (strtolower($param1) == 'contact') {
            $data['title']  = config('app.name') . ' | ' . __('Contact');
            $data['view']   = 'Front.includes.contact.index';
        } elseif (strtolower($param1) == 'training') {
            $data['title']  = config('app.name') . ' | ' . __('Training');
            $data['view']   = 'Front.includes.training.index';
            request()->merge([
                'programId' =>  request('programId') ? request('programId') : 4
            ]);

            $data['study_program']['data']= StudyPrograms::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = $row->image? ImageHelper::site(StudyPrograms::path('image'), $row->image) : ImageHelper::prefix();
                return $row;
            });
            $data['study_course']['data']   = StudyCourse::get(['id', app()->getLocale() . ' as name', 'image'])->map(function ($row) {
                $row['image']   = $row->image? ImageHelper::site(StudyCourse::path('image'), $row->image) : ImageHelper::prefix();
                return $row;
            });;
        } elseif (strtolower($param1) == 'news-even') {
            $data['response']    =  ActivityFeed::getData(null, 10);
            $data['theme_color'] = ThemesColor::getData();
            $data['title']  = config('app.name') . ' | ' . __('News & Even');
            $data['view']   = 'Front.includes.newsEven.index';
            $data['formName'] = 'feed';
            $data['formAction'] = '/post';
        } else {
            abort(404);
        }


        MetaHelper::setConfig([
            'title'       => $data['title'],
            'author'      => config('app.name'),
            'keywords'    => '',
            'description' => '',
            'link'        => null,
            'image'       => null
        ]);
        $pages = array(
            'host'       => url('/'),
            'path'       => '/' . Users::role(),
            'pathview'   => '/' . $data['formName'] . '/',
            'parameters' => array(
                'param1' => $param1,
                'param2' => $param2,
                'param3' => $param3,
            ),
            'search'     => parse_url(request()->getUri(), PHP_URL_QUERY) ? '?' . parse_url(request()->getUri(), PHP_URL_QUERY) : '',
            'form'       => FormHelper::form([], $data['formName'], $data['formAction']),
            'parent'     => "Front",
            'view'       => $data['view'],
        );
        $pages['form']['validate'] = [
            'rules'       =>  strtolower($param1) == 'contact' ? (new FormContact)->rules() : [],
            'attributes'  =>  strtolower($param1) == 'contact' ? (new FormContact)->attributes() : [],
            'messages'    =>  strtolower($param1) == 'contact' ? (new FormContact)->messages() : [],
            'questions'   =>  strtolower($param1) == 'contact' ? (new FormContact)->questions() : [],
        ];
        config()->set('app.title', $data['title']);
        config()->set('pages', $pages);
        return view($pages['parent'] . '.index', $data);
    }


    public function home()
    {
        return $this->index('home');
    }
    public function training()
    {
        return $this->index('training');
    }

    public function newsEven()
    {
        return $this->index('news-even');
    }

    public function about()
    {
        return $this->index('about');
    }
    public function contact()
    {
        return $this->index('contact');
    }

    public function ajax()
    {
        if (request()->method() == 'POST')
            $feed = ActivityFeed::getData();
        if ($feed['success'])
            return $feed;
    }
}
