<?php

namespace App\DAO;

use App\Models\Painel;

class PainelDAO
{

    
    public static function getByMuralId($id)
    {
        return Painel::where('mural_id', $id)->get();
    }
    public static function getById($id)
    {
        return Painel::where('id', $id)->first();
    }

    public static function getAll()
    {
        return Painel::all();
    }

    public static function getBySceneId($id){
        return Painel::where('scene_id', $id);
    }

    public static function create(array $data)
    {
        return Painel::create($data);
    }

    public static function updateById($id, array $data)
    {
        return Painel::where('id', $id)->update($data);
    }

    public static function deleteById($id){
        Painel::where('id',$id)->delete();
    }
}
