<?php

namespace App\DAO;

use App\Models\Painei;

class PainelDAO
{
    public static function getById($id)
    {
        return Painei::where('id', $id)->first();
    }

    public static function getBySceneId($id)
    {
        return Painei::where('scene_id', $id)->get();
    }

    public function getAll()
    {
        return Painei::all();
    }

    public function create(array $data)
    {
        return Painei::create($data);
    }

    public function updateById($id, array $data)
    {
        return Painei::where('id', $id)->update($data);
    }

    public function deleteById($id){
        Painei::where('id',$id)->delete();
    }
}
