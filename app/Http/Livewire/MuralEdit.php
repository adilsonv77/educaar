<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\DAO\PainelDAO;
use App\DAO\DisciplinaDAO;
use App\DAO\MuralDAO;

class MuralEdit extends Component
{
    protected $listeners = ['deletePainel', 'updateStartPanel', 'updateCoordinate','updateCanvasPosition','buscarDadosIniciais'];

    public $paineisRenderizados = [];
    public $disciplinas;
    public $disciplinaSelecionada;
    public $startPainelId;
    public $nameScene;
    public $isSelectingInitialPanel = false;

    
    public $muralId;
    public $mural;



    public function mount()
    {
        $this->paineisRenderizados = PainelDAO::getByMuralId($this->muralId);
        foreach ($this->paineisRenderizados as $painel) {
            $painel->panel = json_decode($painel->panel,true);
            $painel->panelId = (int)$painel->panel["id"];
        }

        $this->disciplinas = DisciplinaDAO::getDisciplinasDoProfessor(Auth::user()->id);     

        $this->mural = MuralDAO::getById($this->muralId);

        $this->startPainelId = $this->mural->start_panel_id;;
        $this->disciplinaSelecionada = $this->mural->disciplina_id;
    }

    public function create()
    {
        // Define posição inicial próxima do centro da cena com leve deslocamento
        $xInicial = 40000 - round(291 / 2) + 40;
        $yInicial = 40000 - round(462 / 2) + 40;

        $json = ["txt" => "", "link" => "", "arquivoMidia" => "", "midiaExtension" => "", "midiaType" => "none", "btnFormat" => "linhas", "x" => $xInicial, "y" => $yInicial];

        $novo = PainelDAO::create(['panel' => json_encode($json), 'scene_id' => $this->muralId]);

        //$json['id'] = $novo->id;
        $json['id'] = count( $this->paineisRenderizados) + 1;

        PainelDAO::updateById($novo->id, ['panel' => json_encode($json)]);

        $novo->panel = $json;
        $this->paineisRenderizados[] = $novo;

        $this->emit("painelCriado", $novo->id);
    }


    public function deletePainel($id)
    {
        $id = (int) $id;

        // Filtra usando Collection
        $this->paineisRenderizados = $this->paineisRenderizados->reject(function ($painel) use ($id) {
            return $painel->id == $id;
        })->values(); // Reindexa os itens da Collection

        // Remove do banco
        PainelDAO::deleteById($id);

        // (Opcional) Emitir um evento se quiser avisar algo pro front
        $this->emit('painelDeletado', $id);
    }

    public function updateCanvasPosition($data){
        MuralDAO::updateById($this->muralId,["canvasTop"=>$data[0],"canvasLeft"=>$data[1],"scale"=>$data[2],"centroTop"=>$data[3],"centroLeft"=>$data[4]]);
    }

    public function updateDisciplinaScene()
    {
        $disciplinaDAO = new DisciplinaDAO();
        $disciplinaDAO->updateDisciplinaScene($this->scene_id, $this->disciplinaSelecionada);
        $this->emit("updateHtmlDiscipline");
    }

    public function updateStartPanel($painelId)
    {
        $this->startPainelId = $painelId;

        MuralDAO::updateById($this->scene_id, ['start_panel_id' => $painelId]);

        $this->emit('startPanelUpdated', $painelId);
    }

    public function updatedNameScene()
    {
        MuralDAO::updateById($this->scene_id, ['name' => $this->nameScene]);
        $this->emit('updateHtmlSceneName', $this->nameScene);
    }

    public function updateCoordinate($painelId, $x, $y)
    {
        $painelRaw = PainelDAO::getById($painelId)->panel;
        //$painelRaw = DB::table('paineis')->where('id', $painelId)->value('panel');

        if ($painelRaw) {
            $panelData = json_decode($painelRaw, true);
            $panelData['x'] = $x;
            $panelData['y'] = $y;

            PainelDAO::updateById($painelId, [
                'panel' => json_encode($panelData)
            ]);
        }
    }

    public function buscarDadosIniciais(){
        $centroTop = $this->mural["centroTop"];
        $centroLeft = $this->mural["centroLeft"];
        $scale = $this->mural["scale"];
        $canvasTop = $this->mural["canvasTop"];
        $canvasLeft = $this->mural["canvasLeft"];

        $this->emit("carregarCanvas",[$centroTop, $centroLeft, $scale, $canvasTop, $canvasLeft]);
    }

}
