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

    public static function buscarSalasPorNome($nome) {
        return Sala::where('nome', 'like', "%$nome%")->get();
    }
}




?>