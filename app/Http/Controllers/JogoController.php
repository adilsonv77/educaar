<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Jogo;
use Illuminate\Http\Request;

class JogoController extends Controller
{
    public function index()
    {
        $jogos = Jogo::all();
        $titulo = 'Jogos';
        return view('pages.game.index', compact('jogos', 'titulo'));
    }

    public function create()
    {
        $titulo = 'Criar Jogo';
        return view('pages.game.create', compact('titulo'));
    }

    public function store(Request $request)
    {
        $jogo = Jogo::create($request->all());
        return redirect()->route('game.index');
    }

    public function show(Jogo $jogo)
    {
        return view('game.show', compact('jogo'));
    }

    public function edit(Jogo $jogo)
    {
        return view('game.edit', compact('jogo'));
    }

    public function update(Request $request, Jogo $jogo)
    {
        $jogo->update($request->all());
        return redirect()->route('game.index');
    }

    public function destroy(Jogo $jogo)
    {
        $jogo->delete();
        return redirect()->route('game.index');
    }
}
