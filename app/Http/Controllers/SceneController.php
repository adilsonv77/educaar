<?php

namespace App\Http\Controllers;

use App\DAO\ButtonDAO;
use App\DAO\ActivityDAO;
use Illuminate\Http\Request;

class SceneController extends Controller
{
    protected $ButtonDAO;
    protected $activityDAO;

    public function __construct(ButtonDAO $ButtonDAO, ActivityDAO $activityDAO)
    {
        $this->activityDAO = $activityDAO;
        $this->ButtonDAO = $ButtonDAO;
    }

    public function index()
    {
        dd("Há fazer")
        return view('pages.painel.panelListing');
    }

    public function create()
    {
        dd("Há fazer")
        return view('pages.painel.criacaoPaineis');
    }

    public function edit($id)
    {
        dd("Há fazer")
        return view('pages.painel.criacaoPaineis', $data);
    }

    public function store(Request $request)
    {   
        dd("Há fazer")
        return redirect()->route('paineis.index');
    }

    public function destroy($id)
    {
        dd("Há fazer")
        return redirect()->route('paineis.index');
    }
}
