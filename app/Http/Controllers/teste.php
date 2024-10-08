<?php

namespace App\Http\Controllers;

use App\Models\Painei;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class teste extends Controller
{
    public function index()
    {
        // $paineis = Painei::all();
        // dd($paineis);
        return view('pages.pasta.criacaoPaineis');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $baseFileName = time();

        $imgFile = $baseFileName . '.' . $request->img->getClientOriginalExtension();
        $request->img->move(public_path('imagemPainel'), $imgFile);
        $data['imagemPainel'] = $imgFile;

        $painel = Painei::create($data);

        $data['imagemPainel'] = $painel->id . '.' . $request->img->getClientOriginalExtension();
        $public_path = public_path('imagensPaineis');

        rename($public_path . '/' . $imgFile, $public_path . '/' . $data['imagemPainel']);

        $painel->update($data);
        return redirect()->route('paineis.create');
    }
}
