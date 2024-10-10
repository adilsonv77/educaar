<?php

namespace App\Http\Controllers;

use App\Models\Turma;
use App\Models\AnoLetivo;

use App\DAO\TurmaDAO;
use App\DAO\ContentDAO;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultadosController extends Controller
{
    public function index(Request $request) {
        $turma_id = $request->input('turma_id');

        $prof_id = Auth::user()->id;
        $anoletivo = AnoLetivo::where('school_id', Auth::user()->school_id)
                ->where('bool_atual', 1)->first();

        $where = TurmaDAO::buscarTurmasProf($prof_id, $anoletivo->id);
        $turmas= $where->get();

        if ($turma_id) {
            $turma = Turma::find($turma_id);
        } else {
            $turma = $where->first();
            $turma_id = $turma->id;
        }

        $contentsprof = ContentDAO::buscarContentsDoProf($prof_id, $anoletivo->id)
                            ->select('contents.id', 'contents.name', 'contents.turma_id')
                            ->get();
        
        $contents  = array();
        foreach ($contentsprof as $linha) {
            if ($linha->turma_id == $turma_id) {
                $newd = [
                    'name' => $linha->name,
                ];
            }

            array_push($contents, $newd);
        }

        $compact = compact('turmas', 'turma', 'contents');
        return view('pages.turma.resultados', $compact);
    }

}