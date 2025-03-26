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
    protected $sceneDAO;

    public function __construct(ButtonDAO $ButtonDAO, ActivityDAO $activityDAO, PainelDAO $painelDAO, SceneDAO $sceneDAO)
    {
        $this->painelDAO = $painelDAO;
        $this->activityDAO = $activityDAO;
        $this->ButtonDAO = $ButtonDAO;
        $this->sceneDAO = $sceneDAO;
    }

    public function index()
    {
        $data = $this->sceneDAO->getAll();

        return view('pages.painel.sceneListing', ['data' => $data, 'content'=>""]);
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

        $cenaCriada = $this->sceneDAO->create([
            'name'=> $nomeTemporario,
            'start_panel_id'=>null,
            'author_id'=>$data['author_id'],
            'disciplina_id'=>$data['disciplina_id']
        ]);

        $this->painelDAO->create([
            'panel'=>"{\"txtSuperior\":\"\"link\":null,\"txtInferior\":null,\"trumbowyg-demo\":null,\"arquivoMidia\":{},\"midiaExtension\":\"\"}",
            'scene_id'=> $cenaCriada->id
        ]);
        
        return redirect()->route('paineis.conexoes');
    }

    public function destroy($id)
    {
        dd("Há fazer");
        return redirect()->route('paineis.index');
    }
}
