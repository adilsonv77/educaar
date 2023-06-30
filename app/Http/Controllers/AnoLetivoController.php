<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disciplina;
use App\Models\AnoLetivo;
use App\Models\DisciplinaAnoLetivo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Hash;

class AnoLetivoController extends Controller
{
    public function index(Request $request)
    {

        if (Auth::user()->type != 'admin') {
            return redirect('/');
        }

        $where = AnoLetivo::where('school_id', Auth::user()->school_id);

        $anoLetivo = $request->titulo;

        if ($anoLetivo) {
            $r = '%' . $anoLetivo . '%';
            $where = $where->where('name', 'like', $r);
        }
        $anosletivos = $where->paginate(20);

        return view('pages.anoletivo.index', compact('anosletivos', 'anoLetivo'));
    }

    public function create()
    {
        // Agradecimentos a https://laracasts.com/discuss/channels/laravel/update-multiple-row-using-checkbox
        //$disciplinas = Disciplina::where('school_id', Auth::user()->school_id)->get();

        $acao = 'insert';
        $params = [
            'titulo' => 'Adicionar Ano Letivo',
            'acao' => $acao,
            'id' => 0,
            'name' => '',
            //'disciplinas' => $disciplinas
        ];


        return view('pages.anoletivo.registerAnoLetivo', $params);
    }

    public function edit(Request $request, $id)
    {
        /*
            select * from disciplinas d

              left outer join (select * from disciplinas_anos_letivos where anoletivo_id=1) dal on d.id = dal.disciplina_id 
              
	        where school_id = 1;    
        */
        $anoletivo = AnoLetivo::find($id);

        // $dal = DB::table('disciplinas_anos_letivos')->where('anoletivo_id', $id);
        // $disciplinas = DB::table('disciplinas')
        //     ->leftJoinSub($dal, 'dal', function ($join) {
        //         $join->on('disciplinas.id', '=', 'dal.disciplina_id');
        //     })
        //     ->where('school_id', Auth::user()->school_id)
        //        ->get();


        // foreach ($disciplinas as $d) {
        //     $d->is_checked = $d->disciplina_id <> null && $d->bool_ativo == 1;
        // }

        $params = [
            'titulo' => 'Editar Ano Letivo',
            'acao' => 'edit',
            'id' => $anoletivo->id,
            'name' => $anoletivo->name,
            // 'disciplinas' => $disciplinas

        ];


        return view('pages.anoletivo.registerAnoLetivo', $params);
    }


    public function store(Request $request)
    {

        $data = $request->all();

        $validation = Validator::make(
            $request->all(),
            $rules = [
                'name' => 'ano_letivo_ja_existe'
            ]
        );

        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }

        $data['school_id'] = Auth::user()->school_id;
        //        $disciplinas = Disciplina::where('school_id', Auth::user()->school_id)->get();

        if ($data['acao'] == 'insert') {

            $data['bool_atual'] = false;
            $anoletivo = AnoLetivo::create($data);
            $dal = [];
        } else {

            //$dal = DisciplinaAnoLetivo::where('anoletivo_id', $data['id'])->get();
            //DisciplinaAnoLetivo::where('anoletivo_id', $data['id'])->delete();

            $anoletivo = AnoLetivo::find($data['id']);
            $anoletivo->update($data);
        }

        //$this->storeDisciplinasAnoLetivo($data, $dal, $anoletivo->id);

        return redirect('/anoletivo');
    }

    public function destroy($id)
    {
        $anoletivo = AnoLetivo::find($id);
        $anoletivo->delete();
        return redirect('/anoletivo');
    }


    // public function storeDisciplinasAnoLetivo($data, $dal, $anoletivo_id) 
    // {
    //     if (array_key_exists('disciplina', $data)) {
    //         $disciplinas = array_keys($data['disciplina']);
    //     } else {
    //         $disciplinas = [];
    //     }

    //     // Desabilitar as disciplinas que não estão na lista
    //     foreach($dal as $d) {
    //         if (array_search($d->disciplina_id, $disciplinas) === false) {
    //             $d->bool_ativo = false;
    //             $d->save();
    //         }
    //     }

    //     // Habilitar as disciplinas que estão na lista
    //     foreach($disciplinas as $disciplina) {
    //         $disc_anoletivo = DisciplinaAnoLetivo::firstOrNew(['anoletivo_id'=>$anoletivo_id, 
    //                                                            'disciplina_id'=>$disciplina]);

    //         $disc_anoletivo->bool_ativo = 1;
    //         $disc_anoletivo->save();
    //     }


    // }
}
