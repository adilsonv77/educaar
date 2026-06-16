<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sala; // Adapte
use App\Models\Jogo; // Adapte

class SalaEspera extends Component
{
    public int $contentId;
    public int $turmaId;
    public bool $jogoIniciado = false;
    public string $mensagemStatus = 'Aguardando o professor abrir a sala...';

    public function verificarStatus()
    {
        $jogo = Jogo::where('content_id', $this->contentId)->first();

        if ($jogo) {
            $sala = Sala::where('jogo_id', $jogo->id)
                        ->where('turma_id', $this->turmaId)
                        ->first();

            if (!$sala) {
                $this->mensagemStatus = 'Aguarde o professor criar a sala...';
            } elseif (!$sala->aberta) {
                $this->mensagemStatus = 'Sala pronta! Aguardando o professor iniciar o jogo...';
            } elseif ($sala->aberta && !$this->jogoIniciado) {
                $this->jogoIniciado = true;
                $this->dispatch('jogo-comecou');
            }
        }
    }

    public function render()
    {
        return view('livewire.sala-espera');
    }
}
