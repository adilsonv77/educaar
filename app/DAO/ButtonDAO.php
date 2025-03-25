<?php

namespace App\DAO;

use App\Models\Button;

class ButtonDAO
{
    public function getById($id)
    {
        return Button::where('id', $id)->first();
    }

    public function getAll()
    {
        return Button::all();
    }

    public function create(array $data)
    {
        return Button::create($data);
    }

    public function updateById($id, array $data)
    {
        return Button::where('id', $id)->update($data);
    }

    public function deleteById($id){
        Button::where('id',$id)->delete();
    }
}
