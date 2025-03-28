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

    public function index(Request $request)
    {
        if ($request->has('titulo') && !empty($request->titulo)) {
            $data = $this->sceneDAO->getByName($request->titulo);
        } else {
            $data = $this->sceneDAO->getAll();
        }

        return view('pages.painel.sceneListing', compact('data'));
    }


    public function create()
    {
        dd("Há fazer");
        return view('pages.painel.criacaoPaineis');
    }

    public function edit($id)
    {
        $paineis = $this->painelDAO->getBySceneId($id);
        foreach ($paineis as $painel) {
            $painel->panel = json_decode($painel->panel);
        }

        return view('pages.painel.conexoes',["paineis"=>$paineis]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $nomeTemporario = time();

        $cenaCriada = $this->sceneDAO->create([
            'name' => $nomeTemporario,
            'start_panel_id' => null,
            'author_id' => $data['author_id'],
            'disciplina_id' => $data['disciplina_id'] ?? null,
        ]);

        $painelCriado = $this->painelDAO->create([
            'panel' => '{"txtSuperior":"","link":"","arquivoMidia":"","midiaExtension":""}',
            'scene_id' => $cenaCriada->id
        ]);

        $this->sceneDAO->updateById($cenaCriada->id, [
            'start_panel_id' => $painelCriado->id
        ]);

        return redirect()->route('paineis.conexoes');
    }

    public function destroy($id)
    {
        dd("Há fazer");
        return redirect()->route('paineis.index');
    }
}
