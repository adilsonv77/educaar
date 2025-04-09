<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\DAO\PainelDAO;

class Panel extends Component
{
    public $painel;
    public $texto;
    public $midia;
    public $link;

    public function mount($painel)
    {
        $this->painel = $painel;
        $this->texto = $painel->txt;
    }

    public function updateText()
    {
        $painelDAO = new PainelDAO();

        $json = json_decode($this->painel->panel);
        $json->txt = $this->texto;

        $painelDAO->updateById($this->painel->id,[
            'panel'=> json_encode($json)
        ]);

        $this->emitSelf('$refresh');
    }

    public function updateMidia()
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
            $this->midia->storeAs('midiasPainel', $nomeTemporario, 'public');
            rename($publicPath . '/' . $nomeTemporario, $publicPath . '/' . $nomeReal);

            // Atualiza o JSON
            $json['arquivoMidia'] = $nomeReal;
            $json['midiaExtension'] = $midiaExtension;
            $json['midiaType'] = 'image'; // Ou detecta dinamicamente com base na extensão, se preferir
            $json['link'] = '';
        } elseif ($possuiLinkYoutube) {
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

    public function render()
    {
        return view('livewire.panel');
    }
}
