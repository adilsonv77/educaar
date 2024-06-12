<?php

namespace App\DAO;

use App\Models\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginDAO
{

    public static function ultimoLogin($profid) {
       /*
        SELECT id from logins where user_id = 5 and entrada_momento = (
            SELECT max(entrada_momento) FROM `logins` WHERE user_id = 5)
        */

        $login = Login::query()
                        ->where('user_id', $profid)
                        ->latest() // ordenado por created_at que tem o mesmo valor do entrada_momento
                        ->take(1)
                        ->first();
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
                ->orderBy("momento")
                 ->get();
        return $freq;
    }
}
?>