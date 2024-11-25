<?php

namespace App\Http\Controllers;


use App\Models\AnoLetivo;
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
        dd($ids);
    }
}