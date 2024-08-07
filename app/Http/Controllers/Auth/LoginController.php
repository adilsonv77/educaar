<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Login;
use App\DAO\LoginDao;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    protected $username;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username = $this->findUsername();

    }

    public function findUsername()
    {
        $login = request()->input('login');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    public function username()
    {
        return $this->username;
    }


    public function login(Request $request)
    {
        $this->validate($request, [
            'login'    => 'required',
            'password' => 'required',
        ]);

        /*
        no nosso sistema o login é sempre pelo nome do usuario
        $login_type = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL )
            ? 'email'
            : 'username';

        $request->merge([
            $login_type => $request->input('login')
        ]);
        */
        $user_password = [
            'username'    => $request->input('login'),
            'password' => $request->input('password')
        ];

        if (Auth::attempt($user_password)) {
            $data = [];
            $data['user_id'] = Auth::user()->id;
            $data['entrada_momento'] = now();
            $request->session()->put('type', Auth::user()->type);
            Login::create($data);

            return redirect()->intended($this->redirectPath());
        }
        $logindev = "";
        if (str_ends_with($request->input('login'), '_u')) {
            $logindev = substr($request->input('login'), 0, -2);
            $user_password = [
                'username'    => $logindev,
                'password' => $request->input('password')
            ];
            if (Auth::attempt($user_password)) {
                if (Auth::user()->type == 'developer') {
                    $data = [];
                    $data['user_id'] = Auth::user()->id;

                    $request->session()->put('type', 'student');

                    $data['entrada_momento'] = now();
                    
                    Login::create($data);
        
                    return redirect()->intended($this->redirectPath());
                }

            }

        }

        return redirect()->back()
            ->withInput()
            ->withErrors([
                'login' => 'Usuário ou senha inválidos'
            ]);
    }

    public function logout(Request $request) {
        // nao sei o problema... mas ele nao encontra a classe DAO quando executa do celular
        $login = Login::query()
            ->where('user_id', Auth::user()->id)
            ->latest() // ordenado por created_at que tem o mesmo valor do entrada_momento
            ->take(1)
            ->first();
        $login->saida_momento = now();
        $login->update();

        //  $login = LoginDAO::ultimoLogin(Auth::user()->id)->first();

        Auth::logout();
        return redirect('/login');
    }

}

