<?php

namespace App\Http\Controllers;

use App\Models\Regras;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegrasRequest;
use App\Http\Requests\UpdateRegrasRequest;

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
     * @param  \App\Http\Requests\StoreRegrasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRegrasRequest $request)
    {
        //
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
