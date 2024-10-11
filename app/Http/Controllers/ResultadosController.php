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
        $contents_id = array();
        foreach ($contentsprof as $linha) {
            if ($linha->turma_id == $turma_id) {
                $newd = [
                    'name' => $linha->name,
                    'questoes' => []
                ];
   
                array_push($contents_id, $linha->id);
                $contents[$linha->id] = $newd;
            }

        }

        $questoes = ContentDAO::buscarQuestoesDosConteudos($contents_id)->get();
        foreach($questoes as $questao) {
            $content = $contents[$questao->content_id];
            $newd = [
                'qid' => $questao->id,
                'qdesc' => $questao->question
            ];
            array_push($content['questoes'], $newd);
            $contents[$questao->content_id] = $content;
        }

        dd($contents);

        $compact = compact('turmas', 'turma', 'contents');
        return view('pages.turma.resultados', $compact);
    }

}