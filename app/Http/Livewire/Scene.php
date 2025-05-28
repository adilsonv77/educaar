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
    protected $listeners = ['deletePainel', 'updateStartPanel', 'updateCoordinate','updateCanvasPosition','buscarDadosIniciais'];

    public $paineisRenderizados = [];
    public $scene_id;
    public $disciplinas;
    public $disciplinaSelecionada;
    public $startPainelId;
    public $nameScene;
    public $isSelectingInitialPanel = false;


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

        // Define posição inicial próxima do centro da cena com leve deslocamento
        $xInicial = 40000 - round(291 / 2) + 40;
        $yInicial = 40000 - round(462 / 2) + 40;

        $json = ["txt" => "", "link" => "", "arquivoMidia" => "", "midiaExtension" => "", "midiaType" => "none", "btnFormat" => "linhas", "x" => $xInicial, "y" => $yInicial];

        $novo = $painelDAO->create(['panel' => json_encode($json), 'scene_id' => $this->scene_id]);

        $json['id'] = $novo->id;

        $painelDAO->updateById($novo->id, ['panel' => json_encode($json),]);

        $novo->panel = $json;
        $this->paineisRenderizados[] = $novo;

        $this->emit("painelCriado", $novo->id);
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

    public function updateCanvasPosition($data){
        SceneDAO::updateById($this->scene_id,["canvasTop"=>$data[0],"canvasLeft"=>$data[1],"scale"=>$data[2],"centroTop"=>$data[3],"centroLeft"=>$data[4]]);
    }

    public function updateDisciplinaScene()
    {
        $disciplinaDAO = new DisciplinaDAO();
        $disciplinaDAO->updateDisciplinaScene($this->scene_id, $this->disciplinaSelecionada);
    }

    public function updateStartPanel($painelId)
    {
        $this->startPainelId = $painelId;

        $sceneDAO = new SceneDAO();
        $sceneDAO->updateById($this->scene_id, ['start_panel_id' => $painelId]);

        $this->emit('startPanelUpdated', $painelId);
    }

    public function updatedNameScene()
    {
        $sceneDAO = new SceneDAO();
        $sceneDAO->updateById($this->scene_id, ['name' => $this->nameScene]);
        $this->emit('updateHtmlSceneName', $this->nameScene);
    }

    public function updateCoordinate($painelId, $x, $y)
    {
        $painelDAO = new PainelDAO();
        $painelRaw = DB::table('paineis')->where('id', $painelId)->value('panel');

        if ($painelRaw) {
            $panelData = json_decode($painelRaw, true);
            $panelData['x'] = $x;
            $panelData['y'] = $y;

            $painelDAO->updateById($painelId, [
                'panel' => json_encode($panelData)
            ]);
        }
    }

    public function buscarDadosIniciais(){
        $scene = SceneDAO::getById($this->scene_id);
        $centroTop = $scene["centroTop"];
        $centroLeft = $scene["centroLeft"];
        $scale = $scene["scale"];
        $canvasTop = $scene["canvasTop"];
        $canvasLeft = $scene["canvasLeft"];

        $this->emit("carregarCanvas",[$centroTop, $centroLeft, $scale, $canvasTop, $canvasLeft]);
    }

}
