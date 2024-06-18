<?php

namespace App\DAO;

use App\Models\Login;
use App\Models\AlunoTurma;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginDAO
{

    public static function ultimoLogin($userid) {
       /*
        SELECT id from logins where user_id = 5 and entrada_momento = (
            SELECT max(entrada_momento) FROM `logins` WHERE user_id = 5)
        */

        $login = Login::query()
                        ->where('user_id', $userid)
                        ->latest() // ordenado por created_at que tem o mesmo valor do entrada_momento
                        ->take(1);
        return $login;
    }

    public static function qtosLogins($profid, $turmaid, $op) {
        // op = pordia : SELECT DATE(entrada_momento) AS MOMENTO, COUNT(*) AS QUANTOS FROM `logins` GROUP BY MOMENTO

        // op = poralunos : SELECT DATE(entrada_momento) AS MOMENTO, COUNT(distinct user_id) AS QUANTOS FROM logins GROUP BY MOMENTO

        if ($op == "pordia") {
            $count = "*";
        } else {
            $count = "distinct user_id";
        }

        $freq = Login::query()
                ->select(DB::raw('DATE(entrada_momento) as momento'), DB::raw('count('.$count.') as quantos'))
                ->join("alunos_turmas as atu", "atu.aluno_id", "=", "logins.user_id")
                ->where("atu.turma_id", "=", $turmaid)
                ->groupBy("momento")
                ->orderBy("momento");
        return $freq;
    }

    public static function listaUltAcessoAlunos($profid, $turmaid) {
        /*SELECT u.id, u.name, max(date(entrada_momento)) FROM alunos_turmas as atu 
              join users u on u.id = atu.aluno_id 
              left outer join logins on atu.aluno_id = logins.user_id 
              where atu.turma_id = 5 GROUP by u.id, u.name order by u.name;
              */

        $lista = AlunoTurma::query()
                    -> select ("u.id" , "u.name", DB::raw("max(date(entrada_momento)) as acesso"))
                    -> join("users as u", "u.id", "=", "aluno_id")
                    -> leftJoin("logins", "aluno_id", "=", "logins.user_id")
                    -> where("turma_id", "=", $turmaid)
                    -> groupBy("u.id", "u.name")
                    -> orderBy("u.name");

        return $lista;

    }
 }
?>