<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\DAO\PainelDAO;
use App\DAO\ButtonDAO;

class Panel extends Component
{
    protected $listeners = ['updateLink','createButton'];

    use WithFileUploads;

    public $buttonRenderizados = [];
    public $paineisRenderizados = [];
    public $painel;
    public $texto;
    public $midia;
    public $link;

    public function mount($painel)
    {
        $this->painel = $painel;
        $this->buttonRenderizados = ButtonDAO::getByOriginId($painel->id);

        // Garante que esteja decodificado (caso venha string do banco)
        $json = is_string($painel->panel) ? json_decode($painel->panel, true) : $painel->panel;

        $this->texto = $json['txt'] ?? '';
        $this->link = $json['link'] ?? '';
    }

    public function updatedTexto()
    {
        $painelDAO = new PainelDAO();

        $json = json_decode($this->painel->panel);
        $json->txt = $this->texto;

        $painelDAO->updateById($this->painel->id,[
            'panel'=> json_encode($json)
        ]);

        $this->emitSelf('$refresh');
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
        $possuiLinkYoutube = !empty($this->link);

        if ($arquivoRecebido) {
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

            if($midiaExtension == "mp4"){
                $json['midiaType'] = 'video'; // Ou detecta dinamicamente com base na extensão, se preferir
            }else{
                $json['midiaType'] = 'image'; // Ou detecta dinamicamente com base na extensão, se preferir
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
    }

    public function updateLink($payload){
        if ($payload['id'] != $this->painel->id) return;
        // só roda se for o painel certo
        $this->link = $payload['link'];
        $this->updatedMidia(true);
    }

    public function createButton($payload){
        if ($payload['id'] != $this->painel->id) return;

        $buttonDAO = new ButtonDAO();

        $novo = $buttonDAO->create([
            'origin_id'=>$this->painel->id,
            'destination_id'=>null,
            'configurations'=>'{"color":"#833B8D","text":"","type":"linhas","transition":""}'
        ]);

        $novo->configurations = json_decode('{"color":"#833B8D","text":"","type":"linhas","transition":""}',true);

        $this->buttonRenderizados = ButtonDAO::getByOriginId($this->painel->id);

        $this->emit("buttonCriado",$novo->id);
    }

    public function render()
    {
        return view('livewire.panel');
    }
}
