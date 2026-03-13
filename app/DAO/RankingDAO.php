<?php

namespace App\DAO;

use App\Models\AlunoTurma;
use App\Models\Pontuacao;
use App\DAO\UserDAO;
use App\Models\Activity;
use Illuminate\Notifications\Action;
use Illuminate\Support\Facades\DB;

class RankingDAO {
    /**
     * @return array<int, array{nome: string, pontuacao: int, tentativas: int}
    */
    public static function buscarRankingPorAtividade(int $activityId) : \Illuminate\Support\Collection {
        $ultimaTentativa = DB::table('student_answers')
            ->select([
                'user_id',
                'activity_id',
                DB::raw('MAX(tentativas) as tentativas')
            ])
            ->groupBy('user_id', 'activity_id');

        $dados = DB::table('activities')
            ->where('activities.id', $activityId)
            ->join('pontuacoes', 'pontuacoes.activity_id', '=', 'activities.id')
            ->join('users', 'pontuacoes.user_id', '=', 'users.id')
            ->joinSub($ultimaTentativa, 'student_answers', function($join) {
                $join->on('student_answers.activity_id', '=', 'activities.id')
                    ->on('student_answers.user_id', '=', 'users.id');
            })
            ->select([
                'users.name',
                'pontuacoes.pontuacao',
                'student_answers.tentativas'
            ])
            ->orderBy('pontuacao', 'DESC')
            ->get();

        return($dados);
    }
}