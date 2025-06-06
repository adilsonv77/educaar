<?php

namespace App\DAO;

use App\Models\Test;
use App\Models\Button;

class TestDAO
{
    public static function getById($id)
    {
        return Test::where('id', $id)->first();
    }

    public static function getBySceneId($id)
    {
        return Test::where('scene_id', $id)->get();
    }

    public function getAll()
    {
        return Test::all();
    }

    public function create(array $data)
    {
        return Test::create($data);
    }

    public function updateById($id, array $data)
    {
        return Test::where('id', $id)->update($data);
    }

    public static function deleteById($id){
        Test::where('id',$id)->delete();
    }
}
