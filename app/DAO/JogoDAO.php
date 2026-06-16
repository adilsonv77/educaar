<?php 

namespace App\DAO;

use Illuminate\Support\Facades\DB;
use App\Models\Jogo;

class JogoDAO {
    public static function buscarJogoConteudoESalaAberta(int $conteudoId){
        return DB::table('jogos')
            ->join('contents', 'contents.id', '=', $conteudoId)
            ->join('salas', 'salas.jogo_id', '=', 'jogos.id')
            ->where('salas.aberta', 1)->first();    
    }

    public static function buscarJogosPorNome($nome) {
        return Jogo::where('name', 'like', "%$nome%")->get();
    }
}


