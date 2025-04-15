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
    protected $listeners = ['deletePainel','dd'];

    public $paineisRenderizados = [];
    public $scene_id;
    public $disciplinas;
    public $disciplinaSelecionada;
    public $startPainelId;
    public $nameScene;

    public function mount($paineis, $scene_id)
    {
        $this->scene_id = $scene_id;
        $this->paineisRenderizados = $paineis;

        $dao = new DisciplinaDAO();
        $this->disciplinas = collect($dao->getDisciplinasDoProfessor(auth()->user()->id));

        // Busca o id do painel inicial
        $this->startPainelId = DB::table('scenes')->where('id', $this->scene_id)->value('start_panel_id');

        // Busca o nome da cena no banco
        $this->nameScene = DB::table('scenes')->where('id', $this->scene_id)->value('name');

        // Carrega a disciplina da cena, se já existir
        $this->disciplinaSelecionada = DB::table('scenes')->where('id', $scene_id)->value('disciplina_id');
    }

    public function create()
    {
        $painelDAO = new PainelDAO();

        $novo = $painelDAO->create([
            'panel' => '{"txt":"","link":"","arquivoMidia":"","midiaExtension":"","midiaType":"none"}',
            'scene_id' => $this->scene_id
        ]);

        $painelDAO->updateById($novo->id,[
            'panel'=>'{"id":"'.$novo->id.'","txt":"","link":"","arquivoMidia":"","midiaExtension":"","midiaType":"none"}'
        ]);

        $novo->panel = json_decode('{"id":"'.$novo->id.'","txt":"","link":"","arquivoMidia":"","midiaExtension":"","midiaType":"none"}',true);

        $this->paineisRenderizados[] = $novo;

        $this->emit("painelCriado",$novo->id);
    }

    public function deletePainel($id)
    {
        $id = (int) $id;
        $painelDAO = new PainelDAO();

        // Filtra usando Collection
        $this->paineisRenderizados = $this->paineisRenderizados->reject(function ($painel) use ($id) {
            return $painel->id == $id;
        })->values(); // Reindexa os itens da Collection

        // Remove do banco
        $painelDAO->deleteById($id);

        // (Opcional) Emitir um evento se quiser avisar algo pro front
        $this->emit('painelDeletado', $id);
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

    //Fazer dd por JS para testes
    //window.livewire.emit("dd","variavel");
    public function dd($var)
    {
        dd($this->paineisRenderizados);
        dd($var);
    }

}
