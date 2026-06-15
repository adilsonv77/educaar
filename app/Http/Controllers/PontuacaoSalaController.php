<?php

namespace App\Http\Controllers;

use App\Models\PontuacaoSala;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePontuacaoSalaRequest;
use App\Http\Requests\UpdatePontuacaoSalaRequest;

class PontuacaoSalaController extends Controller
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
     * @param  \App\Http\Requests\StorePontuacaoSalaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePontuacaoSalaRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PontuacaoSala  $pontuacaoSala
     * @return \Illuminate\Http\Response
     */
    public function show(PontuacaoSala $pontuacaoSala)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PontuacaoSala  $pontuacaoSala
     * @return \Illuminate\Http\Response
     */
    public function edit(PontuacaoSala $pontuacaoSala)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePontuacaoSalaRequest  $request
     * @param  \App\Models\PontuacaoSala  $pontuacaoSala
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePontuacaoSalaRequest $request, PontuacaoSala $pontuacaoSala)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PontuacaoSala  $pontuacaoSala
     * @return \Illuminate\Http\Response
     */
    public function destroy(PontuacaoSala $pontuacaoSala)
    {
        //
    }
}
