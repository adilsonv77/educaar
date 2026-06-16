<?php 

namespace App\DAO;

use Illuminate\Support\Facades\DB;
use App\Models\Jogo;

class JogoDAO {
    public static function buscarJogoConteudo(int $conteudoId){
        return Jogo::where('content_id', $conteudoId)->get();
    }

    public static function buscarJogosPorNome($nome) {
        return Jogo::where('name', 'like', "%$nome%")->get();
    }
}




?>