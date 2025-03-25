<?php

namespace App\Http\Controllers;

use App\DAO\ButtonDAO;
use App\DAO\SceneDAO;
use App\DAO\ActivityDAO;
use App\DAO\PainelDAO;
use Illuminate\Http\Request;

class SceneController extends Controller
{
    protected $ButtonDAO;
    protected $activityDAO;
    protected $painelDAO;

    public function __construct(ButtonDAO $ButtonDAO, ActivityDAO $activityDAO, PainelDAO $painelDAO)
    {
        $this->painelDAO = $painelDAO;
        $this->activityDAO = $activityDAO;
        $this->ButtonDAO = $ButtonDAO;
    }

    public function index()
    {
        $data = $this->SceneDAO->getAll();

        return view('pages.painel.sceneListing', ['data' => $data]);
    }

    public function create()
    {
        dd("Há fazer");
        return view('pages.painel.criacaoPaineis');
    }

    public function edit($id)
    {
        dd("Há fazer");
        return view('pages.painel.criacaoPaineis', $data);
    }

    public function store(Request $request)
    {   
        $data = $request->all();
        $nomeTemporario = time();

        $this->painelDAO->create([
            'name'=> $nomeTemporario,
            'start_panel_id'=>null,
            'author_id'=>$data['author_id'],
            'disciplina_id'=>$data['disciplina_id']
        ]);
        
        return redirect()->route('paineis.index');
    }

    public function destroy($id)
    {
        dd("Há fazer");
        return redirect()->route('paineis.index');
    }
}
