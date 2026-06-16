<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Sala;
use App\Models\Jogo;
use App\Models\Regras;
use App\Models\PontuacaoSala;
use Carbon\Carbon;

class MonitorJogo extends Component
{
    public $contentId;
    public $turmaId;
    public $ultimoCarbon;
    public $tempoMaximo;
    public $pontuacaoMaxima;
    public $userId;
    public $salaId;
    
    protected $listeners = ['tempoAcabou' => 'verificarFimDeJogo', 'atividadeConcluida' => 'concluirAtividade', 'calcularPontuacao' => 'calcularPontuacao'];

    public function mount() {
        $sala = $this->getSala();
        $this->tempoMaximo = Regras::find($sala->regra_id)->tempo;
        $this->pontuacaoMaxima = Regras::find($sala->regra_id)->pontMax;
    }

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
            $this->calcularPontuacao();

            return redirect()->route('student.conteudos');
        }
    }

    public function render()
    {

        return view('livewire.monitor-jogo', [
            'sala' => $this->getSala()
        ]);
    }

    public function concluirAtividade(int $userId, int $salaId) {
        $this->userId = $userId;
        $this->salaId = $salaId;
        $this->ultimoCarbon = Carbon::now()->toIso8601String();
    }

    private function calcularPontuacao() {
        $sala = $this->getSala();
        if (!$sala || !$this->ultimoCarbon || !$sala->started_at) {
            return 0;
        }

        $inicio = Carbon::parse($sala->started_at);
        $conclusao = Carbon::parse($this->ultimoCarbon);

        $tempoGasto = $conclusao->diffInSeconds($inicio);
        $tempoGasto = min($tempoGasto, $this->tempoMaximo);

        $pontuacao = (1 - (($tempoGasto / $this->tempoMaximo) / 2)) * $this->pontuacaoMaxima;
        PontuacaoSala::create([
            'aluno_id' => $this->userId,
            'sala_id' => $this->salaId,
            'pontuacao' => $pontuacao
        ]);
    }
}