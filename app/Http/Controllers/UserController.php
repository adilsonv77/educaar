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

    private function doIndex(Request $request, $userindex, $tipo, $tipo2)
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

        return view('pages.user.index', compact('users', 'usuarios', 'userindex'));
    }

    public function index(Request $request)
    {
        return $this->indexAluno($request);
    }

    public function indexAluno(Request $request)
    {
        return $this->doIndex($request, 'user.indexAluno', 'student', '');
    }

    public function indexProf(Request $request)
    {
        return $this->doIndex($request, 'user.indexProf', 'teacher', 'admin');
    }

    public function createStudent()
    {
        $titulo = 'Adicionar Aluno';
        $acao = 'insert';
        $params = [
            'titulo' => $titulo,
            'acao' => $acao,
            'id' => 0,
            'type' => 'student',
            'name' => '',
            'email' => '',
            'username' => ''
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

    public function edit(Request $request, $id)
    {
        $user = User::find($id);

        $titulo = 'Editar ' . ($user->type == "teacher" ? "Professor" : "Aluno");
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




        //     $data = $request->all();




        //     if (array_key_exists('csv', $data)){
        //         $csvFile = time().'.'.$request->csv->getClientOriginalExtension();
        //         $request->csv->move(public_path('uploads'), $csvFile);

        //         $data['csv'] = $csvFile;
        //     } 


        //     // $file=fopen('C:\Users\07949338903\Documents\GitHub\educaar\docs\x.csv','r');
        //     // while($line = fgetcsv($file) !== FALSE){
        //     //     print_r($line);


        //     // }

        //     // fclose($file);







        //     // $row = 1;
        //     // if (($handle = fopen("C:\Users\07949338903\Documents\GitHub\educaar\docs\x.csv", "r")) !== FALSE) {
        //     //     while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        //     //         $num = count($data);
        //     //         return "<p> $num campos na linha $row: <br /></p>\n";
        //     //         $row++;
        //     //         for ($c=0; $c < $num; $c++) {
        //     //             return $data[$c] . "<br />\n";
        //     //         }
        //     //     }
        //     //     fclose($handle);
        //     // }



        //     // abre o arquivo CSV
        //     $file = fopen('C:\Users\07949338903\Documents\GitHub\educaar\docs\x.csv', 'r');

        //     // percorre o arquivo linha por linha
        //     while (($line = fgetcsv($file)) !== FALSE) {
        //         // imprime cada linha como um array
        //         print_r($line);
        //     }
        //     var_dump($file);
        //     // fecha o arquivo
        //     fclose($file);







        //     $titulo = 'Adicionar Aluno Excel';
        //     $acao = 'insert';
        //     $params = [
        //         'titulo' => $titulo, 
        //         'acao' => $acao,
        //         'id' => 0,
        //         'type' => 'student',
        //    ];


        //     return view('pages.user.registerUserExcel',$params);



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

            User::create($data);
        } else {

            $user = User::find($data['id']);
            $user->update($data);
        }

        if ($data['type'] == 'student') {
            return redirect('/indexAluno');
        } else {
            return redirect('/indexProf');
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
        // dd($arquivo);
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
                    $aluno_turma = AlunoTurma::where('aluno_id', $aluno2->id)
                        ->update([
                            'turma_id' => $turma
                        ]);
                    continue;
                }
            }
            $count++;
        }

        return redirect('/user');
    }
}
