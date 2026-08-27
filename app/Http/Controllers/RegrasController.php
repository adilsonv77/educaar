<?php

namespace App\Http\Controllers;

use App\Models\Regras;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegrasRequest;
use App\Http\Requests\UpdateRegrasRequest;
use Illuminate\Http\Request;
use App\Models\RegraProfessor;
use Illuminate\Support\Facades\Auth;

class RegrasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     *  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'data_inicio' => 'required_with:data_limite|nullable|date', 
            'data_limite' => 'required_with:data_inicio|nullable|date|after_or_equal:data_inicio',          
            'pontMax'     => 'required|numeric|min:0',
            'tempo'       => 'nullable|numeric|min:0',
        ], [
            'data_inicio.required_with'  => 'A data de início é obrigatória quando a data limite é preenchida.',
            'data_limite.required_with'  => 'A data limite é obrigatória quando a data de início é preenchida.',
            'data_limite.after_or_equal' => 'A data limite deve ser igual ou posterior à data de início.'
        ]);

        if(($data['tempo'] == null || $data['tempo'] == 0) && $data['data_inicio'] == null){
            $data['tempo'] = 999999999;
        }

        $novaRegra = Regras::create($data);

        RegraProfessor::create([
            'regra_id'     => $novaRegra->id,
            'professor_id' => Auth::id(),
        ]);

        if(isset($request->type)) {
            return redirect()->route('sala.create', ['jogo_id' => $request['jogo_id']])->with('error', 'Erro ao criar regra! Tente novamente');
        }

        return redirect()->route('sala.create', ['jogo_id' => $request['jogo_id']])->with('success', 'Regra criada!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Regras  $regras
     * @return \Illuminate\Http\Response
     */
    public function show(Regras $regras)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Regras  $regras
     * @return \Illuminate\Http\Response
     */
    public function edit(Regras $regras)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRegrasRequest  $request
     * @param  \App\Models\Regras  $regras
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRegrasRequest $request, Regras $regras)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Regras  $regras
     * @return \Illuminate\Http\Response
     */
    public function destroy(Regras $regras)
    {
        //
    }
}
