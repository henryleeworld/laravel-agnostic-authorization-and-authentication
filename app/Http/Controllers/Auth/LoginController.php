<?php

namespace App\Http\Controllers\Auth;

use Activation;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Sentinel;
use Session;
use Redirect;
use Validator;
use App\Http\Controllers\Controller;

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

   use  ThrottlesLogins;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    protected function login(Request $request)
    {


        try {

            // Validation
            $validation = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if ($validation->fails()) {
                return Redirect::back()->withErrors($validation)->withInput();
            }
            $remember = ($request->remember_me == 'on') ? true : false;
            if ($user = Sentinel::authenticate($request->all(), $remember)) {
                
                   return redirect('admin/dashboard'); 
                
            }

            return Redirect::back()->withErrors(['global' => 'Invalid password or this user does not exist' ]);

        } catch (NotActivatedException $e) {
            return Redirect::back()->withErrors(['global' => 'This user is not activated','activate_contact'=>1]);

        }
        catch (ThrottlingException $e) {
            $delay = $e->getDelay();
            return Redirect::back()->withErrors(['global' => 'You are temporary susspended' .' '. $delay .' seconds','activate_contact'=>1]);
        }

        return Redirect::back()->withErrors(['global' => 'Login problem please contact the administrator']);

        
    }

    protected function logout()
    {
        Sentinel::logout();
        return redirect('/');
    }

    protected function activate($id){
        $user = Sentinel::findById($id);

        $activation = Activation::create($user);
        $activation = Activation::complete($user, $activation->code);
        Session::flash('message', trans('messages.activation'));
        Session::flash('status', 'success');
        return redirect('login');
    }

}
