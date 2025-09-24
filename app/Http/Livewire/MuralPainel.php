<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class MuralPainel extends Component
{
    use WithFileUploads;

    public $painel;

    public $btnFormat;

    public $buttons;

    public function mount($painel) {

        $this->painel = $painel;

        // Garante que esteja decodificado (caso venha string do banco)
        $json = is_string($painel->panel) ? json_decode($painel->panel, true) : $painel->panel;

        $this->link = $json['link'] ?? '';
        $this->btnFormat = $json['btnFormat'] ?? '';
        $this->buttons = $json['buttons'] ?? [];
        
    }
}
?>