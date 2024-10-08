<?php

namespace App\Http\Controllers;

use App\Models\Painei;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 

class teste extends Controller
{
    public function index(){
        // $paineis = Painei::all();
        // dd($paineis);
        return view('pages.pasta.criacaoPaineis');
    }

    public function store(Request $request){
        $data = $request->all();
        $baseFileName = $data['id'];
        // Painei::create($data);
        // return redirect()->route('paineis.create');
    }
}
