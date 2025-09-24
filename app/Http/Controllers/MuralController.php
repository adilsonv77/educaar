<?php

namespace App\Http\Controllers;
use App\DAO\MuralDAO;
use Illuminate\Http\Request;

class MuralController extends Controller
{
    public function edit($id)
    {
        $muralId = $id;
        return view('pages.mural2.conexoes', ['muralId' => $id]);
    }

    
    public function view($id) {

    }

}
