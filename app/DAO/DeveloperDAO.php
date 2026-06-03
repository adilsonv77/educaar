<?php 
namespace App\DAO;

use Illuminate\Support\Facades\DB;

class DeveloperDAO {
    public static function buscarDevPorNome($nome) {
        return DB::table('users')->where('type', 'developer')->where('name', 'like', "%$nome%")->get();
    }
}







?>