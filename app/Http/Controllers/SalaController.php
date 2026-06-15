<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSalaRequest;
use App\Http\Requests\UpdateSalaRequest;
use App\Models\Regras;
use Illuminate\Http\Request;
use App\DAO\TurmaDAO;
use Illuminate\Support\Facades\Auth;
use App\Models\Jogo;

class SalaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $jogoId = $request->input('jogo_id');
        $jogo = Jogo::find($jogoId);
        $salas = Sala::where('jogo_id', $jogoId)->get();
        $titulo = 'Salas';
        return view('pages.sala.index', compact('salas', 'titulo', 'jogo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $titulo = 'Salas';
        $rules = Regras::all();
        $jogoId = $request->jogo_id;
        $classes = TurmaDAO::getTurmasDisponiveisParaSala(Auth::id());

        return view('pages.sala.create', compact('rules', 'titulo', 'jogoId', 'classes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSalaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'string|min:3|max:255|required',
            'regra_id' => 'integer|min:0|required',
            'jogo_id' => 'integer|min:0|required',
            'turma_id' => 'integer|min:0|required'
        ]);
        
        Sala::create($data);

        return redirect()->route('sala.index')->with('success', 'Sala criada!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function enter(int $salaId)
    {
        $sala = Sala::find($salaId);
        if (!$sala) {
            return redirect()->back()->with('error', 'Sala não encontrada.');
        }

        // Lógica para entrar na sala
        return view('pages.sala.enter', compact('sala'));
    }

    public function results(){
        $salas = Sala::all();
        return view('pages.sala.results', compact('salas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function edit(Sala $sala)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSalaRequest  $request
     * @param  \App\Models\Sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSalaRequest $request, Sala $sala)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sala $sala)
    {
        //
    }
}
