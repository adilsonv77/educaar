<?php 

namespace App\DAO;

use Illuminate\Support\Facades\DB;
use App\Models\Jogo;

class JogoDAO {
    public static function listarJogos(){
        
    }

    public static function buscarJogosPorNome($nome) {
        return Jogo::where('name', 'like', "%$nome%")->get();
    }
}




?>