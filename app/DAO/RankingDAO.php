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
     * Retorna o ranking ordernado de forma decrescente de uma atividade
    */
    public static function buscarRankingPorAtividade($activityId) {
        $dados = DB::table('activities')
            ->where('activities.id', $activityId)
            ->join('pontuacoes', 'pontuacoes.activity_id', '=', 'activities.id')
            ->join('users', 'pontuacoes.user_id', '=', 'users.id')
            ->select([
                'users.name',
                'pontuacoes.pontuacao'
            ])
            ->orderBy('pontuacoes.pontuacao', 'DESC')
            ->get();

        return([
            'nome' => $dados->pluck('name')->toArray(),
            'pontuacao'=> $dados->pluck('pontuacao')->toArray()
        ]);
    }
}