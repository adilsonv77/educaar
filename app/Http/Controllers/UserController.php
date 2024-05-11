<?php

namespace App\Http\Controllers;

ini_set('default_charset', 'UTF-8');

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\School;
use App\Models\Disciplina;
use App\Models\Matricula;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\csv;
use App\Models\AlunoTurma;
use App\Models\AnoLetivo;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use mysqli;
use UConverter;

class UserController extends Controller
{

    private function doIndex(Request $request, $userindex, $tipo, $tipo2, $userCreate)
    {
        if (Auth::user()->type != 'admin') {
            return redirect('/');
        }

        $wusers = User::where('school_id', Auth::user()->school_id)
            ->where('type', '=', $tipo);

        if (!empty($tipo2)) {
            $wusers = $wusers->orWhere('type', '=', $tipo2);
        }

        $usuarios = $request->titulo;
        if ($usuarios) {
            $r = '%' . $usuarios . '%';
            $wusers = $wusers->where('name', 'like', $r);
        }

        $users = $wusers->paginate(20);

        return view('pages.user.index', compact('users', 'usuarios', 'userindex', 'userCreate'));
    }

    public function index(Request $request)
    {
        return $this->indexAluno($request);
    }

    public function indexAluno(Request $request)
    {
        return $this->doIndex($request, 'user.indexAluno', 'student', '', 'user.createStudent');
    }

    public function indexProf(Request $request)
    {
        return $this->doIndex($request, 'user.indexProf', 'teacher', 'admin', 'user.createTeacher');
    }
    public function indexDev(Request $request)
    {
        return $this->doIndex($request, 'user.indexDev', 'developer', '', 'user.createDeveloper');
    }

    public function createStudent()
    {
        $titulo = 'Adicionar Aluno';
        $acao = 'insert';
        $anoletivo = AnoLetivo::where('bool_atual', 1)->first();
        $turmas = DB::table('turmas')
            ->where([
                ['ano_id', '=', $anoletivo->id],
                ['school_id', '=', Auth::user()->school_id]
            ])
            ->get();

        $params = [
            'titulo' => $titulo,
            'acao' => $acao,
            'id' => 0,
            'type' => 'student',
            'name' => '',
            'email' => '',
            'username' => '',
            'turmas' => $turmas,
            'turma' => $turmas->first(),
            'anoletivo' => $anoletivo
        ];

        return view('pages.user.registerUser', $params);
    }

    public function createTeacher()
    {

        $titulo = 'Adicionar Professor';
        $acao = 'insert';
        $params = [
            'titulo' => $titulo,
            'acao' => $acao,
            'id' => 0,
            'type' => 'teacher',
            'name' => '',
            'email' => '',
            'username' => ''
        ];

        return view('pages.user.registerUser', $params);
    }
    public function createDeveloper()
    {

        $titulo = 'Adicionar Desenvolvedor';
        $acao = 'insert';
        $params = [
            'titulo' => $titulo,
            'acao' => $acao,
            'id' => 0,
            'type' => 'developer',
            'name' => '',
            'email' => '',
            'username' => ''
        ];

        return view('pages.user.registerUser', $params);
    }

    public function edit(Request $request, $id)
    {
        $user = User::find($id);

        if($user->type == "teacher"){
            $titulo = 'Editar Professor';
        }else if($user->type == "student"){
            $titulo = 'Editar Aluno';
        }else{
            $titulo = 'Editar Desenvolvedor';
        }

        // $titulo = 'Editar ' . ($user->type == "teacher" ? "Professor" : "Aluno");
        $acao = 'edit';
        $params = [
            'titulo' => $titulo,
            'acao' => $acao,
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'type' => $user->type,
            'username' => $user->username

        ];

        if ($user->type == 'student') {
            $anoletivo = AnoLetivo::where('bool_atual', 1)->first();
            $turmas = DB::table('turmas')
                ->where([
                    ['ano_id', '=', $anoletivo->id],
                    ['school_id', '=', Auth::user()->school_id]
                ])
                ->get();
            $turma = DB::table('turmas')
                ->join('alunos_turmas', 'alunos_turmas.turma_id', '=', 'turmas.id')
                ->where([['turmas.ano_id', '=', $anoletivo->id], ['alunos_turmas.aluno_id', '=', $user->id]])
                ->first();

            $params += ['anoletivo' => $anoletivo, 'turmas' => $turmas];
            if (isset($turma)) {
                $params += ['turma' => $turma];
            } else {
                $params += ['turma' =>  $turmas->first()];
            }
        }


        return view('pages.user.registerUser', $params);
    }
    public function matricula()
    {
        $titulo = 'Adicionar alunos';

        $anoletivo = AnoLetivo::where('school_id', Auth::user()->school_id)
            ->where('bool_atual', 1)->first();
        $turmas = DB::table('turmas')
            ->select('turmas.nome', 'turmas.id')
            ->where('school_id', Auth::user()->school_id)
            ->where('ano_id', $anoletivo->id)
            ->get();
        $turma_id = 0;

        return view('pages.turma.matricula', compact('titulo', 'turmas', 'turma_id'));
    }



    public function store(Request $request)
    {
        //lembrar que quando for atualizar o usuario, ele precisa estar no estado "1" do mial senao na hora de salvar ele vai pedir um arquivo csv

        $data = $request->all();

        $validation = Validator::make(
            $request->all(),
            $rules = [
                'username' => 'login_ja_existe',
                'email' => 'email_ja_existe',
                'password' => 'min:5|senhas_nao_conferem'

            ]
        );

        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }


        $data['type'] = $data['tipo'];
        $data['school_id'] = Auth::user()->school_id;

        $data['password'] = Hash::make($data['password']);

        if ($data['acao'] == 'insert') {

            $user = User::create($data);
            if ($data['type'] == 'student') {
                AlunoTurma::create(['turma_id' => $data['turma'], 'aluno_id' => $user->id]);
            }
        } else {
            $user = User::find($data['id']);
            $user->update($data);


            if ($data['type'] == 'student') {
                $query = DB::table('alunos_turmas')->where('aluno_id', $user->id);

                if ($query->exists()) {
                    $query->update(['turma_id' => $data['turma']]);
                } else {
                    AlunoTurma::create(['turma_id' => $data['turma'], 'aluno_id' => $user->id]);
                }
            }
        }

        if ($data['type'] == 'student') {
            return redirect('/indexAluno');
        } else if($data['type'] == 'teacher'){
            return redirect('/indexProf');
        }else{
            return redirect('/indexDev');
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect('/user');
    }
    public function storeMatricula(Request $request)
    {
        $data = $request->all();
        $arquivo = $data['csv'];
        //  dd($arquivo);
        $validation = Validator::make(
            $request->all(),
            $rules = [
                'csv' => 'required|file|mimes:csv,txt'
            ]
        );

        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }

        $turma = $data['turma_id'];
        $count = 0;
        $user = array();
        $lines = file($arquivo->getRealPath());
        $csvFile = time() . '.' . $request->csv->getClientOriginalExtension();

        // dd($csvFile);
        $request->csv->move(public_path('uploads'), $csvFile);
        foreach ($lines as $line) {
            if ($count != 0) {
                $campos = explode(";", $line);
                try {
                    $user['username'] = $campos[1];
                    $user['name'] = utf8_encode($campos[2]);
                    $user['type'] = 'student';
                    $user['password'] = Hash::make($campos[1]);
                    $user['email'] = '@';
                    $user['school_id'] = Auth::user()->school_id;
                    $aluno = User::create($user);

                    $aluno_turma = AlunoTurma::create([
                        'turma_id' => $turma,
                        'aluno_id'  => $aluno->id
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    $aluno2 = User::where('username', $campos[1])->first();
                    if (!AlunoTurma::where('aluno_id', $aluno2->id)->exists()) {
                        $aluno_turma = AlunoTurma::create([
                            'turma_id' => $turma,
                            'aluno_id'  => $aluno2->id
                        ]);
                    } else {
                        $aluno_turma = AlunoTurma::where('aluno_id', $aluno2->id)
                            ->update([
                                'turma_id' => $turma
                            ]);
                    }
                    continue;
                }
            }
            $count++;
        }

        unlink('uploads/' . $csvFile);
        // dd(unlink('uploads/'.$csvFile));

        return redirect()->route('turmas.turmasAlunosIndex', $data);
    }
}
