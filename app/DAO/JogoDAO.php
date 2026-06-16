<?php 

namespace App\DAO;

use Illuminate\Support\Facades\DB;
use App\Models\Jogo;
use Illuminate\Support\Collection;

class JogoDAO {
    public static function buscarJogoConteudoESalaAberta(int $conteudoId) {
    return DB::table('jogos')
        ->join('contents', 'contents.id', '=', 'jogos.content_id')
        ->join('salas', 'salas.jogo_id', '=', 'jogos.id')
        ->where('contents.id', $conteudoId)
        ->where('salas.aberta', 1)
        ->first();    
}

    public static function buscarJogosPorNome($nome) {
        return Jogo::where('name', 'like', "%$nome%")->get();
    }

    public static function getJogoByContentId(int $contentId) {
        return DB::table('jogos')
            ->where('jogos.content_id', $contentId)
            ->first();
    }
}


