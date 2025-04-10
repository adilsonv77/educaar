<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\DAO\PainelDAO;
use App\DAO\DisciplinaDAO;
use App\DAO\SceneDAO;

class Scene extends Component
{

    public $texto;
    public $paineis;
    public $scene_id;
    public $disciplinas;
    public $disciplinaSelecionada;
    public $startPainelId;
    public $nameScene;


    public function mount($paineis, $scene_id)
    {
        $this->paineis = $paineis;
        $this->scene_id = $scene_id;

        $dao = new DisciplinaDAO();
        $this->disciplinas = $dao->getDisciplinasDoProfessor(auth()->user()->id);

        // Busca o id do painel inicial
        $this->startPainelId = DB::table('scenes')->where('id', $this->scene_id)->value('start_panel_id');

        // Busca o nome da cena no banco
        $this->nameScene = DB::table('scenes')->where('id', $this->scene_id)->value('name');

        // Carrega a disciplina da cena, se já existir
        $this->disciplinaSelecionada = DB::table('scenes')->where('id', $scene_id)->value('disciplina_id');
        //dd($this->disciplinas);
    }

    public function render()
    {
        return view('livewire.scene');
    }

    public function create()
    {
        $painelDAO = new PainelDAO();

        $painelCriado = $painelDAO->create([
            'panel' => '{"txt":"","link":"","arquivoMidia":"","midiaExtension":"","midiaType":"none"}',
            'scene_id' => $this->scene_id
        ]);

        $painelDAO->updateById($painelCriado->id, [
            'panel' => '{"id":"' . $painelCriado->id . '","txt":"","link":"","arquivoMidia":"","midiaExtension":"","midiaType":"none"}',
        ]);

        // Atualiza a página sem recarregar
        $this->emit('painelCriado', $painelCriado->id);
    }

    public function update($id)
    {
        dd($id . ' e ' . $this->texto);
        $painelDAO = new PainelDAO();
        $painelDAO->updateById($id, ['txt' => ""]);
    }

    public function updateDisciplinaScene()
    {
        $disciplinaDAO = new DisciplinaDAO();
        $disciplinaDAO->updateDisciplinaScene($this->scene_id, $this->disciplinaSelecionada);
    }

    public function updateStartPanel($painelId)
    {
        $sceneDAO = new SceneDAO();
        $sceneDAO->updateById($this->scene_id, ['start_panel_id' => $painelId]);
    }

    public function updatedNameScene()
    {
        $sceneDAO = new SceneDAO();
        $sceneDAO->updateById($this->scene_id, ['name' => $this->nameScene]);
    }

}
