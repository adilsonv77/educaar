<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Login;
use App\Mail\ResetPasswordEmail;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Redirecionamento para formulário para modificar senha
     * 
     */
    public function create() {
        return view('auth.passwords.reset');
    }

    /**
     * Verifica se o email existe, caso existe cria uma nova senha
     * de 8 dígitos, associa ao email e envia a senha ao email,
     * excluí o 'login' com a senha antiga
     * 
     */
    public function update(Request $request) {
        $validated = $request -> validate([
            'email' => ['required', 'email', 'max:255']
        ]);

        $user = User::where('email', $validated['email']) -> first();
        if (!$user) {
            return redirect('/password/reset') -> with('error', 'Email não encontrado.');
        }

        try {
            DB::beginTransaction();

            $password = Str::random(8);
            $user = User::where('email', $validated['email']) -> value('name');
            User::where('email', $validated['email']) -> update(['password' => Hash::make($password)]);

            $userId = User::where('email', $validated['email']) -> value('id');
            Login::where('user_id', $userId) -> delete();

            try {
                Mail::to($validated['email'], 'ResetPasswordEmail') -> send(new ResetPasswordEmail($password, $user));
            } catch (\Exception) {
                return redirect('/password/reset') -> with('error', 'Não foi possível encontrar o email.');
            }
            DB::commit();

            return redirect('/login') -> with('success', 'Senha alterada. Uma nova senha foi enviada ao seu email.');
        } catch (\Exception) {
            DB::rollback();

            return redirect('/password/reset') -> with('error', 'Falha ao alterar senha.');
        }

    }

}
