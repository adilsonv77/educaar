<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disciplina;
use App\Models\User;
use App\Models\School;
use App\Models\Matricula;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DisciplinaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->type != 'admin') {
            return redirect('/');
        }

        /*
        SELECT distinct d.id, d.name, dal.disciplina_id FROM disciplinas d 
        left outer join disciplinas_anos_letivos dal on d.id = disciplina_id;
        */

        $where = DB::table('disciplinas as d')
            ->select('d.id', 'd.name',)
            ->where('school_id', Auth::user()->school_id)
            ->distinct();

        //$where = Disciplina::where('school_id', Auth::user()->school_id);

        $disciplina = $request->titulo;

        if ($disciplina) {
            $r = '%' . $disciplina . '%';

            $where = $where->where('name', 'like', $r);
        }
        $disciplinas = $where->paginate(20);

        // foreach ($disciplinas as $d) {
        //     $d->is_checked = $d->disciplina_id == null;
        // }


        return view('pages.class.index', compact('disciplinas', 'disciplina'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $acao = 'insert';
        $params = [
            'titulo' => 'Adicionar Disciplina',
            'acao' => $acao,
            'id' => 0,
            'name' => ''
        ];

        return view('pages.class.registerClass', $params);
    }

    public function edit(Request $request, $id)
    {
        $disciplina = Disciplina::find($id);

        $params = [
            'titulo' => 'Editar Disciplina',
            'acao' => 'edit',
            'id' => $disciplina->id,
            'name' => $disciplina->name

        ];

        return view('pages.class.registerClass', $params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validation = Validator::make(
            $request->all(),
            $rules = [
                'name' => 'disciplina_ja_existe',
            ]
        );

        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }

        $data['school_id'] = Auth::user()->school_id;

        if ($data['acao'] == 'insert') {
            Disciplina::create($data);
        } else {

            $disciplina = Disciplina::find($data['id']);
            $disciplina->update($data);
        }
        return redirect('/class');
    }

    public function destroy($id)
    {
        $disciplina = Disciplina::find($id);
        $disciplina->delete();
        return redirect('/class');
    }


    // os métodos abaixo não foram revisados !!!

    public function viewCadastrarAlunoDisciplina()
    {
        $disciplinas = Disciplina::all();
        $schools = School::all()->where('id', Auth::user()->school_id)->first();

        $students = User::where('type', 'student')->orderBy('name')->get();

        return view('pages.class.registerStudentDiscipline', compact('disciplinas', 'students', 'schools'));
    }

    public function storeAlunoDisciplina(Request $request)
    {
        $data = $request->all();

        $user = User::all()->where('username', $data['student'])->first();

        if (!isset($data['discipline'])) {
            return redirect('registerStudentDiscipline')
                ->withErrors('Selecione uma disciplina.');
        } else if ($data['student'] == null) {
            return redirect('registerStudentDiscipline')
                ->withErrors('Selecione um aluno.');
        } else {
            $discipline_id = Disciplina::all()->where('name', $data['discipline'])->first()->id;
        }

        $matricula = Matricula::all()->where('user_id', $user->id);

        $matricula_existente = false;

        foreach ($matricula as $m) {
            if ($m->disciplina_id == $discipline_id) {
                $matricula_existente = true;
            }
        }

        if ($matricula_existente) {
            return redirect('registerStudentDiscipline')
                ->withErrors('O usuário selecionado já está matriculado nessa disciplina.');
        } else {
            Matricula::create([
                'user_id' => $user->id,
                'disciplina_id' => $discipline_id
            ]);

            return redirect('/');
        }
    }
}
