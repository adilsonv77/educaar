<?php

namespace App\DAO;

use App\Models\Scene;

class SceneDAO
{
    public static function getById($id)
    {
        return Scene::where('id', $id)->first();
    }

    public function getAll()
    {
        return Scene::all();
    }

    public function create(array $data)
    {
        return Scene::create($data);
    }

    public function updateById($id, array $data)
    {
        return Scene::where('id', $id)->update($data);
    }

    public function deleteById($id)
    {
        Scene::where('id', $id)->delete();
    }

    public function getByName($name)
    {
        return Scene::where('name', 'like', '%' . $name . '%')->get();
    }

    public static function getByDisciplinaId($id)
    {
        return Scene::where('disciplina_id', $id)->get();
    }
}
