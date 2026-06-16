<?php

namespace App\Http\Controllers;

use App\Models\Regras;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegrasRequest;
use App\Http\Requests\UpdateRegrasRequest;
use Illuminate\Http\Request;

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
            'pontMax' => 'integer|min:0|required',
            'tempo' => 'integer|min:0|required'
        ]);

        Regras::create($data);

        if(isset($request->type)) {
            return redirect()->back()->with('success', 'Regra criada!');
        }

        return redirect()->route('sala.create')->with('success', 'Regra criada!');
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
