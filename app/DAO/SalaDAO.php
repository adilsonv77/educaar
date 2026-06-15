<?php 

namespace App\DAO;

use Illuminate\Support\Facades\DB;
use App\Models\Sala;

class SalaDAO {
    public static function buscarSalasENomeTurma(int $jogoId){
        return DB::table('salas')
            ->join('turmas', 'salas.turma_id', '=', 'turmas.id')
            ->select('salas.*', 'turmas.nome as nome_turma')
            ->where('salas.jogo_id', $jogoId)
            ->get();
    }

    public static function buscarPontuacoesSala(int $salaId){
        return DB::table('pontuacoesSalas')
            ->join('users', 'pontuacoesSalas.aluno_id', '=', 'users.id')
            ->join('salas', 'pontuacoesSalas.sala_id', '=', 'salas.id')
            ->select('pontuacoesSalas.*', 'users.name as nome_aluno', 'salas.jogo_id as jogo_id')
            ->where('sala_id', $salaId)
            
            ->get();
    }

    public static function buscarResultadoPorId(int $resultId){
        $result = DB::table('pontuacoesSalas')
            ->join('users', 'pontuacoesSalas.aluno_id', '=', 'users.id')
            ->join('salas', 'pontuacoesSalas.sala_id', '=', 'salas.id')
            ->select('pontuacoesSalas.*', 'users.name as nome_aluno', 'salas.jogo_id as jogo_id')
            ->where('pontuacoesSalas.id', $resultId)
            ->first();

        return $result;
    }

    public static function deletarResultado(int $resultId){
        return DB::table('pontuacoesSalas')
            ->where('id', $resultId)
            ->delete();
    }
}




?>