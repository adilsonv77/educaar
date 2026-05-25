<?php

namespace App\Http\Controllers;

ini_set('default_charset', 'UTF-8');

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\AlunoTurma;
use App\Models\AnoLetivo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;




class UserController extends Controller
{

    private function doIndex(Request $request, $userindex, $tipo, $tipo2, $userCreate)
    {
        if (session('type') != 'admin') {
            return redirect('/');
        }

        $wusers = User::where('users.school_id', Auth::user()->school_id)
            ->where('users.type', '=', $tipo)
            ->leftJoin('alunos_turmas', 'users.id', '=', 'alunos_turmas.aluno_id')
            ->leftJoin('turmas', 'alunos_turmas.turma_id', '=', 'turmas.id')
            ->select('users.*', DB::raw('GROUP_CONCAT(turmas.nome SEPARATOR ", ") as turma_nome')) // Junta os nomes das turmas
            ->groupBy('users.id'); // Agrupa pelo ID do usuário

       

        if (!empty($tipo2)) {
            $wusers = $wusers->orWhere('type', '=', $tipo2);
        }

        $usuarios = $request->titulo;
        if ($usuarios) {
            $r = '%' . $usuarios . '%';
            $wusers = $wusers->where(DB::raw('concat(name, " - ", username) '), 'like', $r);
        }


        

        
        $users = $wusers->distinct()->paginate(20);
       
        $type = $tipo;

        return view('pages.user.index', compact('users', 'usuarios', 'userindex', 'userCreate', 'type'));
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
        return $this->doIndex($request, 'user.indexProf', 'teacher', '', 'user.createTeacher');
    }
    public function indexDev(Request $request)
    {
        return $this->doIndex($request, 'user.indexDev', 'developer', 'admin', 'user.createDeveloper');
    }

    public function createStudent()
    {
        $titulo = 'Adicionar Aluno';
        $acao = 'insert';
        $anoletivo = AnoLetivo::where('bool_atual', 1)
                ->where("school_id", Auth::user()->school_id)
                ->first();
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
            $anoletivo = AnoLetivo::where('bool_atual', 1)
            ->where("school_id", Auth::user()->school_id)
            ->first();
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
        $titulo = __('Import students from file');

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

    public function createMatricula(Request $request) {
        $validation = Validator::make($request->all(), [
            'csv'  => 'required|file|mimes:csv,txt',
            'turma_id' => 'required|integer',
        ]);

        if($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }

        $file = $request->csv;
        $turma = $request->turma_id;

        $students = [];
        $lines = file($file->getRealPath());
        $csvFile = time() . '.' . $file->getClientOriginalExtension();
        $request->csv->move(public_path('uploads'), $csvFile);

        $isFirst = true;
        foreach($lines as $line) {
            if($isFirst) { $isFirst = false; continue; }

            $campos = explode(';', trim($line));
            $students[] = [
                'username' => $campos[1],
                'name' => mb_convert_encoding($campos[2], 'UTF-8', 'ISO-8859-1'),
                'type' => 'student',
                'password' => Hash::make($campos[1]),
                'email' => '@',
                'school_id' => Auth::user()->school_id,
            ];
        }

        $token = Str::uuid()->toString();
        Cache::put("import:{$token}", [$students, $turma], now()->addMinutes(30));
        session(['importToken' => $token]);

        unlink('uploads/' . $csvFile);

        $anoletivo = AnoLetivo::where('school_id', Auth::user()->school_id)
            ->where('bool_atual', 1)->first();
        $turmas = DB::table('turmas')
            ->select('turmas.nome', 'turmas.id')
            ->where('school_id', Auth::user()->school_id)
            ->where('ano_id', $anoletivo->id)
            ->get();

        return view('pages.turma.matricula', [
            'turmas' => $turmas,
            'students' => $students,
            'turma_id' => $turma,
            'titulo' => __('Import students from file')
        ]); 
    }

    public function storeMatricula() {
        $token = session()->pull('importToken');
        $cached = Cache::pull("import:{$token}");

        $students = $cached[0];
        $turmaId = $cached[1];

        foreach($students as $student) {
            try {
                $stu = User::create($student);

                AlunoTurma::create([
                    'turma_id' => $turmaId,
                    'aluno_id' => $stu->id
                ]);
            } catch(\Illuminate\Database\QueryException $e) {
                $stu2 = User::where('username', $student['username'])->first();

                AlunoTurma::updateOrCreate(
                    ['aluno_id' => $stu2->id],
                    ['turma_id' => $turmaId]
                );
            }
        }

        return redirect()->route('turmas.turmasAlunosIndex', ['turma_id' => $turmaId]);
    }

    public function cancelMatricula() {
        $token = session()->pull('importToken');
        Cache::forget("import:{$token}");

        return redirect()->route('user.matricula');
    }

    public function localeUpdate(string $locale) {
        if(! in_array($locale, ['en', 'pt_BR', 'es'])) {
            return redirect()->back()->with('errors', 'Idioma não disponível');
        }

        session()->put('locale', $locale);
        app()->setLocale($locale);


        return redirect()->back();
    }
    
}
