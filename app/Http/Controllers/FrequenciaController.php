<?php

namespace App\Http\Controllers;

use App\Models\AnoLetivo;
use App\Models\Turma;

use App\DAO\TurmaDAO;
use App\DAO\LoginDAO;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class FrequenciaController extends Controller
{

    public function index(Request $request) {
        
        $turma_id = $request->input('turma_id');

        $anoletivo = AnoLetivo::where('school_id', Auth::user()->school_id)
                        ->where('bool_atual', 1)->first();

        $prof_id = Auth::user()->id;
        

        $where = TurmaDAO::buscarTurmasProf($prof_id, $anoletivo->id);

        $turmas= $where->get();

        if ($turma_id) {
            $turma = Turma::find($turma_id);
        } else {
            $turma = $where->first();
            $turma_id = $turma->id;
        }

       $acesso = $request->input('acessos');
        if (!$acesso) {
            $acesso = "pordia";
        }

        $freq = LoginDAO::qtosLogins($prof_id, $turma_id, $acesso);
        $maxquantos = 0;
        foreach ($freq as $item) {
            if ($item->quantos > $maxquantos)
               $maxquantos = $item->quantos;
        }

        $ticksquantos = [];
        for ($i = 0; $i <= $maxquantos; $i++) {
            array_push( $ticksquantos, $i );
        }

        if ($acesso == "pordia") {
            $titgrafico = "Acessos por dia";
        } else {
            $titgrafico = "Alunos que acessaram por dia";
        }

        return view('pages.user.frequencia', compact('turmas', 'turma', 'freq', 'ticksquantos', 'titgrafico', 'acesso'));
    }

 }
?>