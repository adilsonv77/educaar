<?php

namespace App\DAO;

use App\Models\AnoLetivo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserDAO
{

    public static function buscarContentsDoProf($profid, $usarprojecao)
    {

        $anoletivoAtual = AnoLetivo::where('school_id', Auth::user()->school_id)
            ->where('bool_atual', 1)->first();

        $sql = DB::table('turmas_disciplinas')

            ->join('turmas', 'turmas_disciplinas.turma_id', '=', 'turmas.id')
            ->join('contents', function ($join) {
                $join->on('turmas.turma_modelo_id', '=', 'contents.turma_id');
                $join->on('turmas_disciplinas.disciplina_id', '=', 'contents.disciplina_id');
            })
            ->join('disciplinas', 'disciplinas.id', '=', 'contents.disciplina_id')
            ->join('turmas_modelos', 'turmas_modelos.id', '=', 'contents.turma_id')
            ->where('professor_id', '=', $profid)
            ->where("ano_id", "=", $anoletivoAtual->id)
            ->distinct();
        /*
        if ($usarprojecao)
            $sql = $sql->select('contents.*');
        */
        return $sql;
    }
}

// SELECT COUNT(u.id), t.nome FROM `turmas_disciplinas` as td 
// 		inner join alunos_turmas as ta 
//         on td.turma_id=ta.turma_id
//         INNER join turmas as t
//         on t.id= ta.turma_id
//         inner join users as u 
//         on ta.aluno_id= u.id
// WHERE td.professor_id= 4 and t.ano_id= 4
// GROUP BY t.nome
