<?php

namespace App\DAO;

use App\Models\Mural;
use App\Models\MuralPainel;

class MuralDAO
{
    public static function getAll()
    {
        return Mural::all();
    }

    public static function create($data) {
        
        $mural = Mural::create($data);
        
        $datapainel = [
            'mural_id' => $mural->id,
            'panelnome' => 1,
            'panel' => '{"id":"1","txt":"","link":"","arquivoMidia":"","midiaExtension":"","midiaType":"none","buttons":[]}'
        ];
        $muralpainel = MuralPainel::create($datapainel);

        $mural->update(['start_painel_id'=> $muralpainel->id] );

        return $mural;
    }

    public static function find($id) {

        return Mural::find($id);
    }
}
