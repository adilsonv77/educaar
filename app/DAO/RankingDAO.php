<?php

namespace App\DAO;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Retorna a soma da pontuacao de todas as atividades de um conteúdo por aluno.
     * Caso tenha mais de 10 respostas, irá ser retornado as 10 primeiras
     * mais a posição do aluno autenticado.
     * 
     * @return array<int, array{name: string, user_id: int, pontuacao: string, posicao: int}
    */
    public static function somaDasPontuacoesDeUmConteudo(int $contentId): \Illuminate\Support\Collection {
        $simple = DB::table('contents')
            ->join('activities', 'activities.content_id', '=', 'contents.id')
            ->join('pontuacoes', 'pontuacoes.activity_id', '=', 'activities.id')
            ->join('users', 'users.id', '=', 'pontuacoes.user_id')
            ->where('contents.id', $contentId)
            ->select(
                'users.name',
                'pontuacoes.user_id',
                DB::raw('SUM(pontuacoes.pontuacao) as pontuacao'),
                DB::raw('ROW_NUMBER() OVER (ORDER BY SUM(pontuacoes.pontuacao) DESC) as posicao'),
            )
            ->groupBy('pontuacoes.user_id');

        if(count($simple->get()) > 10) {
            $userId = Auth::id();

            $top10 = DB::table(DB::raw("({$simple->toSql()}) as ranking"))
                ->mergeBindings($simple)
                ->where(function ($query) use ($userId) {
                    $query->where('posicao', '<=', 10)
                        ->orWhere('user_id', $userId);
                })
                ->orderBy('posicao');

            return $top10->get();
        }

        return $simple->get();
    }

}

