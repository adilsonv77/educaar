<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;

use App\Models\User;
use App\Models\Disciplina;
use App\Models\AnoLetivo;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (env('FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }

        Validator::extend('senhas_nao_conferem', function ($attribute, $value, $parameters, $validator) {
            $inputs = $validator->getData();
            $senha = $inputs['password'];
            $senhaconfere = $inputs['password_confirmation'];

            return $senha == $senhaconfere;
        }, 'As senhas não conferem. Por favor, digite novamente.');

        Validator::extend('login_ja_existe', function ($attribute, $value, $parameters, $validator) {
            $inputs = $validator->getData();
            $username = $inputs['username'];
            $userid = $inputs['id'];

            $users = User::all();
            foreach ($users as $user) {
                if ($username == $user->username && $user->id != $userid) {
                    return false;
                }
            }

            return true;
        }, 'Login já existe. Por favor, digite novamente.');

        Validator::extend('email_ja_existe', function ($attribute, $value, $parameters, $validator) {
            $inputs = $validator->getData();
            $username = $inputs['email'];
            $userid = $inputs['id'];

            $users = User::all();
            foreach ($users as $user) {
                if ($username == $user->email && $user->id != $userid) {
                    return false;
                }
            }

            return true;
        }, 'E-mail já existe. Por favor, digite novamente.');

        Validator::extend('disciplina_ja_existe', function ($attribute, $value, $parameters, $validator) {
            $inputs = $validator->getData();
            $name = $inputs['name'];
            $id = $inputs['id'];

            $disciplinas = Disciplina::where('school_id', Auth::user()->school_id)->get();
            foreach ($disciplinas as $disciplina) {
                if ($name == $disciplina->name && $disciplina->id != $id) {
                    return false;
                }
            }

            return true;
        }, 'Disciplina já existe. Por favor, digite novamente.');

        Validator::extend('ano_letivo_ja_existe', function ($attribute, $value, $parameters, $validator) {
            $inputs = $validator->getData();
            $name = $inputs['name'];
            $id = $inputs['id'];

            $anosletivos = AnoLetivo::where('school_id', Auth::user()->school_id)->get();
            foreach ($anosletivos as $anoletivo) {
                if ($name == $anoletivo->name && $anoletivo->id != $id) {
                    return false;
                }
            }

            return true;
        }, 'Ano Letivo já existe. Por favor, digite novamente.');


        Validator::extend('extensao_invalida', function ($attribute, $value, $parameters, $validator) {
            // dd($attribute, $value, $parameters);

            $extensao = \Illuminate\Support\Str::afterLast($value->getClientOriginalName(), '.');

            $extensao = strtolower($extensao);
            $validator->addReplacer(
                'extensao_invalida',
                function ($message, $attribute, $rule, $parameters) use ($extensao) {
                    return \str_replace(':custom_message', $extensao, $message);
                }
            );

            if (in_array(($extensao), $parameters)) {
                return true;
            }
            return false;
        }, 'Extensão inválida  (:custom_message). Selecione outro arquivo.');
    }
}


// 
 // 
// var_dump($d['glb']->getClientOriginalName());
// 
