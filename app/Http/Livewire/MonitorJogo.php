<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Sala;
use App\Models\Jogo;
use Carbon\Carbon;

class MonitorJogo extends Component
{
    public $contentId;
    public $turmaId;

    // Função auxiliar para achar a sala facilmente
    private function getSala()
    {
        $jogo = Jogo::where('content_id', $this->contentId)->first();
        if ($jogo) {
            return Sala::with('regra')
                       ->where('jogo_id', $jogo->id)
                       ->where('turma_id', $this->turmaId)
                       ->first();
        }
        return null;
    }

    public function verificarFimDeJogo()
    {
        $sala = $this->getSala();

        if (!$sala) return;

        $acabou = false;

        // 1. O professor clicou em Terminar
        if (!$sala->aberta) {
            $acabou = true;
        } 
        // 2. O tempo expirou
        elseif ($sala->started_at && $sala->regras) {
            // Usando addSeconds porque seu tempo no banco é em segundos!
            $horaFim = Carbon::parse($sala->started_at)->addSeconds($sala->regras->tempo);
            
            if (now()->greaterThanOrEqualTo($horaFim)) {
                $acabou = true;
                $sala->aberta = false;
                $sala->save();
            }
        }

        if ($acabou) {
            // Jogo finalizado!
            return redirect()->route('student.conteudos');
        }
    }

    public function render()
    {
        // Precisamos passar a sala para a view para o Javascript ler os segundos
        return view('livewire.monitor-jogo', [
            'sala' => $this->getSala()
        ]);
    }
}