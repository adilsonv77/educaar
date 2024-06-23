<?php

namespace App\DAO;

use App\Models\AnoLetivo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserDAO
{

    public static function buscarAlunosProf($profid, $anoletivoid)
    {
        // ver como fazer, pois se um professor tiver mais de uma disciplina na mesma turma é contado mais de uma vez


        $sql = DB::table('turmas_disciplinas')
        ->join("alunos_turmas as ta", "ta.turma_id", "=", "turmas_disciplinas.turma_id")
        ->join("turmas", "turmas.id", "=", "ta.turma_id")
        ->join("users as u", "u.id", "=", "ta.aluno_id")
            ->where([
                ['turmas_disciplinas.professor_id', '=', $profid],
                ['turmas.ano_id', '=', $anoletivoid]
            ]);

        return $sql;
    }

    public static function buscarAlunos($listaalunos) {
        $sql = DB::table('users')
            ->whereIn('id', $listaalunos)
            ->select('name')
            ->get();

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
