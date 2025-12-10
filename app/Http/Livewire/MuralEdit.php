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
    public $startPainelConta;
    public $nameMural;
    public $isSelectingInitialPanel = false;

    
    public $muralId;
    public $mural;

    public $panelNomeMax;


    public function mount() // ok
    {
        $this->mural = MuralDAO::getById($this->muralId);
        $this->startPainelId = 1;

        $this->paineisRenderizados = PainelDAO::getByMuralId($this->muralId);
        foreach ($this->paineisRenderizados as $painel) {
            if ($painel->panelnome > $this->panelNomeMax) {
                $this->panelNomeMax = $painel->panelnome;
            }
            if ($painel->id == $this->mural->start_painel_id) {
                $this->startPainelId = $this->mural->start_painel_id;
                $this->startPainelConta = $painel->panelnome;
            }
            $painel->panel = json_decode($painel->panel,true);
            $painel->panelId = $painel->panel["id"];
        }

        $this->disciplinas = DisciplinaDAO::getDisciplinasDoProfessor(Auth::user()->id);     
        $this->disciplinaSelecionada = $this->mural->disciplina_id;

        $this->nameMural = $this->mural->name;
    }

    public function create() // ok
    {
        // Define posição inicial próxima do centro da cena com leve deslocamento
        $xInicial = 40000 - round(291 / 2) + 40;
        $yInicial = 40000 - round(462 / 2) + 40;

        $this->panelNomeMax += 1;

        $json = ["id" => $this->panelNomeMax, "txt" => "", "link" => "", "arquivoMidia" => "", "midiaExtension" => "", "midiaType" => "none", "btnFormat" => "linhas", "x" => $xInicial, "y" => $yInicial];
        
        $novo = PainelDAO::create(['panel' => json_encode($json), 'mural_id' => $this->muralId, 'panelnome' => $this->panelNomeMax]);

        $this->paineisRenderizados[] = $novo;

        $this->emit("painelCriado", $novo->id);
    }


    public function deletePainel($id)  // ok
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
        $disciplinaDAO->updateDisciplinaScene($this->mural_id, $this->disciplinaSelecionada);
        $this->emit("updateHtmlDiscipline");
    }

    public function updateStartPanel($painelId) // ok
    {
        $startPainelConta = 0;
        foreach ($this->paineisRenderizados as $painel) {
            if ($painel->id == $painelId) {
                $startPainelConta = $painel->panelnome;
                break;
            }
        }
       
        $this->startPainelId = $painelId;
        $this->startPainelConta = $startPainelConta;

        MuralDAO::updateById($this->muralId, ['start_painel_id' => $painelId]);

        $this->emit('startPanelUpdated', $startPainelConta);
    }

    public function updatedNameMural()
    {
        MuralDAO::updateById($this->muralId, ['name' => $this->nameMural]);
        $this->emit('updateHtmlSceneName', $this->nameMural);
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
