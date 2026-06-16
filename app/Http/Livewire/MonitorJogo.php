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
    
    protected $listeners = ['tempoAcabou' => 'verificarFimDeJogo'];

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

        if (!$sala->aberta) {
            $acabou = true;
        } 

        elseif ($sala->started_at && $sala->regras) {

            $horaFim = Carbon::parse($sala->started_at)->addSeconds($sala->regras->tempo);
            
            if (now()->greaterThanOrEqualTo($horaFim)) {
                $acabou = true;
                $sala->aberta = false;
                $sala->save();
            }
        }

        if ($acabou) {
            return redirect()->route('student.conteudos');
        }
    }

    public function render()
    {

        return view('livewire.monitor-jogo', [
            'sala' => $this->getSala()
        ]);
    }
}