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
                        ->orderBy('id', 'desc')
                        ->first();

            if (!$sala) {
                $this->mensagemStatus = 'Nenhuma sala foi criada para este jogo ainda.';
                return; 
            }

            if (!$sala->aberta && is_null($sala->started_at)) {
                $this->mensagemStatus = 'Aguardando o professor abrir a sala...';
            }

            elseif ($sala->aberta && is_null($sala->started_at)) {
                
                $sala->alunosPresentes()->syncWithoutDetaching([auth()->id()]);
                $this->mensagemStatus = 'Você entrou na sala! Aguardando o professor iniciar o jogo...';
            }
            
            elseif (!is_null($sala->started_at)) {
                
                $sala->alunosPresentes()->syncWithoutDetaching([auth()->id()]);
                
                if (!$this->jogoIniciado) {
                    $this->jogoIniciado = true;
                    $this->dispatchBrowserEvent('jogo-comecou'); 
                }
            }
        }
    }
}
