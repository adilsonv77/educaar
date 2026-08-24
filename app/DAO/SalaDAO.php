<?php 

namespace App\DAO;

use Illuminate\Support\Facades\DB;

class SalaDAO {
    public static function buscarSalasENomeTurma($jogoId){
        return DB::table('salas')
            ->join('turmas', 'salas.turma_id', '=', 'turmas.id')
            ->join('regras', 'salas.regra_id', '=', 'regras.id')
            ->select('salas.*', 'turmas.nome as nome_turma', 'regras.data_inicio as data_inicio')
            ->where('salas.jogo_id', $jogoId)
            ->get();
    }

    public static function buscarPontuacoesSala($salaId){
        return DB::table('pontuacao_salas')
            ->join('users', 'pontuacao_salas.aluno_id', '=', 'users.id')
            ->join('salas', 'pontuacao_salas.sala_id', '=', 'salas.id')
            ->select('pontuacao_salas.*', 'users.name as nome_aluno', 'salas.jogo_id as jogo_id')
            ->where('sala_id', $salaId)
            
            ->get();
    }

    public static function buscarResultadoPorId($resultId){
        return DB::table('pontuacao_salas')
        ->join('users', 'pontuacao_salas.aluno_id', '=', 'users.id')
        ->join('salas', 'pontuacao_salas.sala_id', '=', 'salas.id')
        ->join('jogos', 'salas.jogo_id', '=', 'jogos.id')
        ->select(
            'pontuacao_salas.id',
            'pontuacao_salas.aluno_id',
            'pontuacao_salas.sala_id',
            'users.name as nome_aluno',
            'jogos.content_id'
        )
        ->where('pontuacao_salas.id', $resultId)
        ->first();
    }

    public static function deletarResultado($resultId){
        return DB::table('pontuacao_salas')
            ->where('id', $resultId)
            ->delete();
    }

    public static function buscarInfosSala($salaId){
        return DB::table('salas')
            ->join('turmas', 'salas.turma_id', '=', 'turmas.id')
            ->join('jogos', 'salas.jogo_id', '=', 'jogos.id')
            ->join('contents', 'jogos.content_id', '=', 'contents.id')
            ->join('regras', 'salas.regra_id', '=', 'regras.id')
            ->select('salas.*', 'turmas.nome as nome_turma', 'contents.name as nome_conteudo', 'regras.pontMax as pontuacaoMaxima', 'regras.tempo as tempo', 'contents.id as content_id')
            ->where('salas.id', $salaId)
            ->first();
    }

    public static function getSalaIDByJogo(int $jogoId) : int {
        return DB::table('salas')
            ->join('jogos', 'jogos.id', '=', 'salas.jogo_id')
            ->where('salas.jogo_id', $jogoId)
            ->where('salas.aberta', 1)
            ->value('salas.id');
    }

    public static function podeCriarSala(int $jogoId): bool {
        return !DB::table('salas')
            ->where('jogo_id', $jogoId)
            ->whereNull('started_at')
            ->exists();
    }

    public static function getSalaPorData(int $salaId): bool {
        return DB::table('salas')
        ->join('regras', 'salas.regra_id', 'regras.id')
        ->where('salas.id', $salaId)
        ->where(function ($query) {
            $query->whereNotNull('regras.data_inicio')
                  ->orWhereNotNull('regras.data_limite');
        })
        ->exists();
    }

    public static function getSchoolIdBySala(int $salaId): int {
        return DB::table('salas')
            ->join('jogos', 'salas.jogo_id', 'jogos.id')
            ->join('contents', 'jogos.content_id', 'contents.id')
            ->join('turmas_modelos', 'contents.turma_modelo_id', 'turmas_modelos.id')
            ->where('salas.id', $salaId)
            ->value('turmas_modelos.school_id');
    }

    public static function getDeadline(int $salaId) {
        return DB::table('salas')
            ->join('regras', 'salas.regra_id', 'regras.id')
            ->where('salas.id', $salaId)
            ->value('regras.data_limite');
    }

}




?>