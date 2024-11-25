<?php

namespace App\Http\Controllers;


use App\Models\AnoLetivo;
use App\Models\ProfConfig;
use App\DAO\TurmaDisciplinaDAO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfConfigController extends Controller
{
    public function index(Request $request)
    {
        $anoletivo = AnoLetivo::where('school_id', Auth::user()->school_id)
                    ->where('bool_atual', 1)->first();

        $prof_id = Auth::user()->id;

        $where = TurmaDisciplinaDAO::buscarTurmasProf($prof_id, $anoletivo->id);
        $where = $where->get();

        $turmas = [];
        foreach($where as $where_t) {
            $turma = [
                't_id' => $where_t->t_id,
                'serie' => $where_t->nome,
                'd_id' => $where_t->d_id,
                'disciplina' => $where_t->name,
                'dataCorte' => date_format(now(), "Y-m-d")
            ];
            array_push($turmas, $turma);
        }

        return view('pages.profconfig.edit', compact('turmas'));
    }

    public function store(Request $request)
    {
        $ids = $request->keys();
        foreach ($ids as $id) {
            if (str_starts_with($id, "data_")) {
                
                //dd($request->input($id));
                $id_d_t = explode("_", substr($id, 5) );
                
                $data = array();
                $data['anoletivo_id'] = AnoLetivo::where('school_id', Auth::user()->school_id)
                    ->where('bool_atual', 1)->first()->id;
                $data['disciplina_id'] = $id_d_t[0];
                $data['professor_id'] = Auth::user()->id;
                $data['turma_id'] = $id_d_t[1];
                $data['dt_corte'] = date('Y-m-d', strtotime($request->input($id)));
                
                ProfConfig::create($data);
            }
        }
        dd($ids);
    }
}