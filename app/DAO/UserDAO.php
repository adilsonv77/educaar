<?php

namespace App\DAO;

use App\Models\AnoLetivo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

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

    /**
     * Retorna um usuário
    */
    public static function buscarUsuarioPorId($id) {
        return User::where('id', $id)->get();
    }

    public static function buscarUsuariosPorNome($tipo, $usuarios){
         $wusers = User::where('users.school_id', Auth::user()->school_id)
            ->where('users.type', '=', $tipo)
            ->leftJoin('alunos_turmas', 'users.id', '=', 'alunos_turmas.aluno_id')
            ->leftJoin('turmas', 'alunos_turmas.turma_id', '=', 'turmas.id')
            ->select('users.*', DB::raw('GROUP_CONCAT(turmas.nome SEPARATOR ", ") as turma_nome'))
            ->groupBy('users.id'); 

        if ($usuarios) {
            $r = '%' . $usuarios . '%';
            $wusers = $wusers->where('name', 'like', $r);
        }

        return $wusers->distinct()->paginate(20);
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
