<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\DAO\PainelDAO;
use App\DAO\ButtonDAO;
use App\DAO\TestDAO;
use App\Models\Button;
use App\Models\Test;

class MuralPainel extends Component
{
    protected $listeners = ['updateLink', 'updateBtnFormat', 'createButton', 'updatedMidia', 'deleteBtn', 'salvarTexto', 'teste','resetYoutubeLink' => 'resetarLinkYoutube',
                            'addSelecionado', 'removeSelecionado'];

    use WithFileUploads;

    public $buttonRenderizados = [];
    public $painel;
    public $texto;
    public $midia;
    public $link;
    public $btnFormat;
    public $num;

    public $classes = "";
    
   
    public function resetarLinkYoutube()
    {
        $this->link = '';
    }
    

    public function mount() // ok
    {
        $painel = $this->painel;
        $this->buttonRenderizados = ButtonDAO::getByOriginId($painel->id);

        // Garante que esteja decodificado (caso venha string do banco)
        $json = is_string($painel->panel) ? json_decode($painel->panel, true) : $painel->panel;

        $this->texto = $json['txt'] ?? '';
        $this->link = $json['link'] ?? '';
        $this->btnFormat = $json['btnFormat'] ?? '';
        $this->num = 0;
    }

    public function addSelecionado($id): void // ok
    {
        $id = substr($id, 1);
        if ($this->painel->panelnome == $id)
            $this->classes = "selecionadoP";
    }
    
    public function removeSelecionado(): void // ok
    {
        $this->classes = "";
    }

    public function salvarTexto($painelId, $novoTexto)
    {
        if ($this->painel->id != $painelId)
            return;

        $json = is_string($this->painel->panel) ? json_decode($this->painel->panel, true) : (array) $this->painel->panel;
        $json['txt'] = $novoTexto;

        $painelDAO = new PainelDAO();
        $painelDAO->updateById($painelId, ['panel' => json_encode($json)]);

        $this->painel->panel = $json;
        $this->texto = $novoTexto;

        $this->dispatchBrowserEvent('atualizarTextoPainel', [
            'painelId' => $painelId,
            'novoTexto' => $novoTexto,
        ]);

    }
   
    public function updatedMidia($recebeuLink = null)
    {
        $painelDAO = new PainelDAO();
        $id = $this->painel->id;

        $painelAtual = $painelDAO->getById($id);
        $json = json_decode($painelAtual->panel, true);

        $publicPath = public_path('midiasPainel');
        $baseFileName = time();

        $arquivoRecebido = !empty($this->midia);

        if ($arquivoRecebido && $recebeuLink !== true) {
            $midiaExtension = $this->midia->getClientOriginalExtension();
            $nomeTemporario = $baseFileName . '.' . $midiaExtension;
            $nomeReal = $id . '.' . $midiaExtension;

            // Apaga o arquivo anterior se necessário
            if (!empty($json['midiaExtension'])) {
                @unlink($publicPath . '/' . $id . '.' . $json['midiaExtension']);
            }

            // Salva novo arquivo
            $this->midia->storeAs('midiasPainel', $nomeTemporario, 'public_direct');
            rename($publicPath . '/' . $nomeTemporario, $publicPath . '/' . $nomeReal);

            // Atualiza o JSON
            $json['arquivoMidia'] = $nomeReal;
            $json['midiaExtension'] = $midiaExtension;
            $json['link'] = '';

            if ($midiaExtension == "mp4") {
                $json['midiaType'] = 'video'; // Detecta dinamicamente com base na extensão, se preferir
            } else {
                $json['midiaType'] = 'image'; // Detecta dinamicamente com base na extensão, se preferir
            }
        } elseif ($recebeuLink) {
            if (!empty($json['midiaExtension'])) {
                @unlink($publicPath . '/' . $id . '.' . $json['midiaExtension']);
            }

            $json['link'] = $this->link;
            $json['midiaType'] = 'youtube';
            $json['arquivoMidia'] = '';
            $json['midiaExtension'] = '';
        }
        $painelDAO->updateById($id, ['panel' => json_encode($json)]);
        $this->painel->panel = $json; // Atualiza o painel renderizado também

        $this->emit("stopLoading");

        if ($json['midiaType'] == 'video') {
            $this->emit("carregarVideo", $id);
        }
    }

    public function updateLink($payload)
    {
        if ($payload['id'] != $this->painel->id)
            return;
        // só roda se for o painel certo
        $this->link = $payload['link'];
        $this->updatedMidia(true);
    }

    public function createButton($payload)
    {
        if ($payload['id'] != $this->painel->id)
            return;

        $buttonDAO = new ButtonDAO();

        $novo = $buttonDAO->create([
            'painel_origin_id' => $this->painel->id,
            'painel_destination_id' => null,
            'configurations' => '{"color":"#833B8D","text":"","type":"linhas","transition":"","linhaX":"","linhaY":""}'
        ]);

        $novo->configurations = json_decode('{"color":"#833B8D","text":"","type":"linhas","transition":"","linhaX":"","linhaY":""}', true);

        $this->buttonRenderizados = ButtonDAO::getByOriginId($this->painel->id);

        $this->emit("buttonCriado");
        $this->emit("stopLoadingBtn");
    }

    public function updateBtnFormat($payload)
    {
        if ($payload['id'] != $this->painel->id)
            return;

        $painelDAO = new PainelDAO();

        $json = json_decode($this->painel->panel);
        $this->btnFormat = $payload['btnFormat'];
        $json->btnFormat = $payload['btnFormat'];

        $painelDAO->updateById($this->painel->id, [
            'panel' => json_encode($json)
        ]);

        $this->emitSelf('$refresh');
    }

    public function deleteBtn($payload)
    {
        if ($payload['id_painel'] != $this->painel->id)
            return;

        $id = (int) $payload['id'];
        
        ButtonDAO::deleteById($id);

        $this->buttonRenderizados = $this->buttonRenderizados->reject(function ($button) use ($id) {
            return $button->id == $id;
        })->values();
        
        $this->emit("stopLoadingBtn");
    }

    public function teste($payload)
    {
        if ($payload['id_painel'] != $this->painel->id)
            return;
        Button::destroy($payload['id']);
    }

    public function getMaxButtonsProperty()
    {
        return match ($this->btnFormat) {
            'linhas' => 3,
            'blocos' => 6,
            'alternativas' => 4,
            default => 3,
        };
    }

    public function render()
    {
        return view('livewire.panel', [
            'texto' => $this->texto,
            'maxButtons' => $this->maxButtons,
            'btnFormat' => $this->btnFormat,
            'buttonRenderizados' => $this->buttonRenderizados,
            'painel' => $this->painel,
        ]);
    }

}
