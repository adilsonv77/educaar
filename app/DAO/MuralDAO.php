<?php

namespace App\DAO;

use App\Models\Mural;
use App\Models\Painel;

class MuralDAO
{
    public static function getById($id)
    {
        return Mural::where('id', $id)->first();
    }

    public static function getAll()
    {
        return Mural::all();
    }
    
    public static function create(array $data)
    {
        $mural = Mural::create($data);
        
        $datapainel = [
            'scene_id' => $mural->id,
            'panel' => '{"id":"1","txt":"","link":"","arquivoMidia":"","midiaExtension":"","midiaType":"none"}'
        ];
        $muralpainel = Painel::create($datapainel);

        $mural->update(['start_panel_id'=> $muralpainel->id] );

        return $mural;

    }

    public static function updateById($id, array $data)
    {
        return Mural::where('id', $id)->update($data);
    }

    public static function deleteById($id)
    {
        Mural::where('id', $id)->delete();
    }

    public static function getByName($name)
    {
        return Mural::where('name', 'like', '%' . $name . '%')->get();
    }

    public static function getByDisciplinaId($id)
    {
        return Mural::where('disciplina_id', $id)->get();
    }
}
