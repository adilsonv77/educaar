<?php 

namespace App\DAO;

use Illuminate\Support\Facades\DB;
use App\Models\Sala;

class SalaDAO {
    public static function buscarSalasENomeTurma($jogoId){
        return DB::table('salas')
            ->join('turmas', 'salas.turma_id', '=', 'turmas.id')
            ->select('salas.*', 'turmas.nome as nome_turma')
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
        $result = DB::table('pontuacao_salas')
            ->join('users', 'pontuacao_salas.aluno_id', '=', 'users.id')
            ->join('salas', 'pontuacao_salas.sala_id', '=', 'salas.id')
            ->select('pontuacao_salas.*', 'users.name as nome_aluno', 'salas.jogo_id as jogo_id')
            ->where('pontuacao_salas.id', $resultId)
            ->first();

        return $result;
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

}




?>