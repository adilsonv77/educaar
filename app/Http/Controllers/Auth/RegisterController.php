<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Turma;
use App\Models\AlunoTurma;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Mail\ContaCriadaEmail;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'e-mail', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
    */

    /**
     * Redirecionamento para formulário de registro de usuário público
     * 
     */
    public function create() {
        $publicSchools = School::where('publico', 1)
                                -> pluck('name');
                                        
        return view('auth.register', ['escolas' => $publicSchools]);
    }

    /**
     * Cadastra um novo usuário, associa-o com uma senha aleatória de 8 dígitos,
     * envia a senha ao email associado ao usuário, cadastra uma nova coluna na
     * tabela "aluno_turmas" associado ao usuário e turma
     *
     */
    public function store(Request $request) {
      $validated = $request -> validate([
          'username' => ['required', 'string', 'max:100', 'unique:users'],
          'email' => ['required', 'string', 'e-mail', 'max:255', 'unique:users'],
          'projeto' => ['required', 'string', 'exists:schools,name'],
      ]);   

      try {
        DB::beginTransaction();

        $school = School::where('name', $validated['projeto']);
        $turma = Turma::where('school_id', $school->value('id'));

        $password = Str::random(8);
        User::create([
            'name' => $validated['username'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($password),
            'school_id' => $school -> value('id'),
        ]);

        $user = User::where('email', $validated['email']);
        AlunoTurma::create([
            'aluno_id' => $user -> value('id'),
            'turma_id' => $turma -> value('id'),
        ]);

        try {
            Mail::to($validated['email'], 'MyMail') -> send(new ContaCriadaEmail($password, $validated['username']));
        } catch (\Exception $e) {
            //dd($e);
            $mailer = Mail::getFacadeRoot();
            dd(config('mail.mailers.smtp'), $e->getMessage()); 
            return redirect('/register') -> with ('error', 
            'Erro ao enviar e-mail: ' . $e->getMessage() . '. Tente novamente.');
        }
        DB::commit();

        return redirect('/login') -> with ('success', 'Conta criada. Suas credenciais foram enviadas para seu e-mail. Também verifique sua caixa de spam.');
        
      } catch (\Exception $e) {
        DB::rollback();
        dd($e);

        return redirect('/register') -> with ('error', 'Erro ao criar conta.');

      }

    }

}
