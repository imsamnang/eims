<?php

namespace App\Http\Controllers\Auth;

use App\Models\App as AppModel;
use App\Models\Users;
use App\Models\Languages;
use App\Models\SocailsMedia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

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
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated($request, $user)
    {

        if ($user) {
            if ($request->redirect) {
                return redirect($request->redirect);
            }
            return redirect(Users::role());
        }
    }

    // public function logout()
    // {
    //     Auth::logout();
    //     return redirect('login');
    // }
}
