<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\DAO\ButtonDAO;

class Button extends Component
{
    use WithFileUploads;

    public $listeners = ["updateTexto"];
    public $button;
    public $texto;
    public $cor;
    public $painelOrigem;
    public $transicao;
    public $painelDestino;

    public function mount($button)
    {
        $this->button = $button;

        $json = is_string($button->configurations) ? json_decode($button->configurations, true) : $button->configurations;

        $this->texto = $json['text'] ?? '';
        $this->cor = $json['color'] ?? '';
        $this->painelOrigem = $button->origin_id;
        $this->painelDestino = $button->destination_id;
        $this->transicao = $json['type'] ?? '';
    }

    public function updateTexto($payload)
    {
        if ($payload['id'] != $this->button->id) return;

        $buttonDAO = new ButtonDAO();

        $json = json_decode($this->button->configurations);
        $this->texto = $payload['text'];
        $json->text = $payload['text'];

        $buttonDAO->updateById($this->button->id, [
            'configurations' => json_encode($json)
        ]);

        $this->emitSelf('$refresh');
    }

    public function render()
    {
        return view('livewire.button');
    }
}
