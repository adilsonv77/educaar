<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSalaRequest;
use App\Http\Requests\UpdateSalaRequest;
use App\Models\Regras;
use Illuminate\Http\Request;
use App\DAO\TurmaDAO;
use App\DAO\SalaDAO;
use Illuminate\Support\Facades\Auth;
use App\Models\Jogo;
use App\Models\Turma;
use Exception;

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
        $salas = SalaDAO::buscarSalasENomeTurma($jogoId);
        $titulo = 'Salas';
        $classes = TurmaDAO::getTurmasDisponiveisParaSala(Auth::id());
        $rules = Regras::all();

        return view('pages.sala.index', compact('salas', 'titulo', 'jogo', 'jogoId', 'classes', 'rules'));
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

        return redirect()->route('sala.index', ['jogo_id' => $data['jogo_id']])->with('success', 'Sala criada!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function enter($salaId){

        $sala = SalaDAO::buscarInfosSala($salaId);
        if (!$sala) {
            return redirect()->back()->with('error', 'Sala não encontrada.');
        }

        return view('pages.sala.enter', compact('sala'));
    }

    public function show($salaId){
        $results = SalaDAO::buscarPontuacoesSala($salaId);
        $id_jogo = $results->first() ? $results->first()->jogo_id : null;
        return view('pages.sala.results', compact('results', 'id_jogo'));
    }

    public function resultsDestroy($resultId){
        $result = SalaDAO::buscarResultadoPorId($resultId);
        if (!$result) {
            return redirect()->back()->with('error', 'Resultado não encontrado.');
        }

        SalaDAO::deletarResultado($resultId);
        return redirect()->route('sala.index')->with('success', 'Resultado deletado com sucesso.');
    }

    public function comecarJogo($id){

        $sala = Sala::find($id);
        $sala->aberta = true;
        $sala->started_at = now();
        $sala->save();

        return redirect()->back()->with('success', 'Jogo iniciado!');
    }

    public function terminarJogo($id){

        $sala = Sala::find($id);
        $sala->aberta = false;
        $sala->save();

        return redirect()->back()->with('success', 'Jogo terminado!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $data = $request->validate([
            'sala_id' => 'integer|min:0', 
            'nome' => 'string|min:3|max:255|required',
            'regra_id' => 'integer|min:0|required',
            'jogo_id' => 'integer|min:0|required',
            'turma_id' => 'integer|min:0|required'
        ]);

        try {
            $sala = Sala::find($data['sala_id']);
            $sala->update($data);
        } catch(Exception $e) {
            report($e);
            return redirect()->back()->withInput()->withErrors(['erro' => 'Falha ao editar sala']);
        }

        return redirect()->route('sala.index', ['jogo_id' => $data['jogo_id']])->with('success', 'Sala editada!');
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($salaId)
    {
        $sala = Sala::find($salaId);
        $sala->delete();
        return redirect()->back()->with('success', 'Sala excluída');
    }
}
