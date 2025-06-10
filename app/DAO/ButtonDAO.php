<?php

namespace App\DAO;

use App\Models\Button;

class ButtonDAO
{
    public static function getById($id)
    {
        return Button::where('id', $id)->first();
    }

    public static function getByOriginId($id)
    {
        return Button::where('origin_id', $id)->get();
    }


    public function getAll()
    {
        return Button::all();
    }

    public function create(array $data)
    {
        return Button::create($data);
    }

    public static function updateById($id, array $data)
    {
        return Button::where('id', $id)->update($data);
    }

    public static function deleteById($id){
        Button::where('id',$id)->delete();
    }

    
}
