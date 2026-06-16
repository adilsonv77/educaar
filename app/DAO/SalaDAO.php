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
        return DB::table('pontuacoesSalas')
            ->join('users', 'pontuacoesSalas.aluno_id', '=', 'users.id')
            ->join('salas', 'pontuacoesSalas.sala_id', '=', 'salas.id')
            ->select('pontuacoesSalas.*', 'users.name as nome_aluno', 'salas.jogo_id as jogo_id')
            ->where('sala_id', $salaId)
            
            ->get();
    }

    public static function buscarResultadoPorId($resultId){
        $result = DB::table('pontuacoesSalas')
            ->join('users', 'pontuacoesSalas.aluno_id', '=', 'users.id')
            ->join('salas', 'pontuacoesSalas.sala_id', '=', 'salas.id')
            ->select('pontuacoesSalas.*', 'users.name as nome_aluno', 'salas.jogo_id as jogo_id')
            ->where('pontuacoesSalas.id', $resultId)
            ->first();

        return $result;
    }

    public static function deletarResultado($resultId){
        return DB::table('pontuacoesSalas')
            ->where('id', $resultId)
            ->delete();
    }

    public static function buscarInfosSala($salaId){
        return DB::table('salas')
            ->join('turmas', 'salas.turma_id', '=', 'turmas.id')
            ->join('jogos', 'salas.jogo_id', '=', 'jogos.id')
            ->join('contents', 'jogos.content_id', '=', 'contents.id')
            ->join('regras', 'salas.regra_id', '=', 'regras.id')
            ->select('salas.*', 'turmas.nome as nome_turma', 'contents.name as nome_conteudo', 'regras.pontMax as pontuacaoMaxima', 'regras.tempo as tempo')
            ->where('salas.id', $salaId)
            ->first();
    }

}




?>