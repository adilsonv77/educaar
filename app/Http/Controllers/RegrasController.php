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
            'pontMax'     => 'integer|min:0|required',
            'tempo'       => 'nullable|integer|min:0|required_if:time_limit,1',
            'data_inicio' => 'nullable|date|required_if:time_limit,0',
            'data_limite' => 'nullable|date|required_if:time_limit,0|after_or_equal:data_inicio'
        ]);

        if(($data['tempo'] == null || $data['tempo'] == 0) && $data['data_inicio'] == null){
            $data['tempo'] = 999999999;
        }

        if($data['data_inicio'] != null && $data['data_limite'] != null){
            $data['tempo'] = '0';
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
