<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Sala;
use App\Models\Jogo; 

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
                        ->where('aberta', 1)
                        ->first();

            if($sala){
                $sala->alunosPresentes()->syncWithoutDetaching([auth()->id()]);
            }

            if (!$sala) {
                $this->mensagemStatus = 'Aguarde o professor criar a sala...';
            } elseif (!$sala->aberta) {
                $this->mensagemStatus = 'Sala pronta! Aguardando o professor iniciar o jogo...';
            } elseif ($sala->aberta && !$this->jogoIniciado) {
                $this->jogoIniciado = true;
                $this->dispatchBrowserEvent('jogo-comecou');
            }
        }
    }

    public function render()
    {
        return view('livewire.sala-espera');
    }
}
