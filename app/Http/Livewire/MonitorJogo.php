<?php

namespace App\Http\Livewire;

use App\DAO\ActivityDAO;
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
    public $isProfessor = false;
    public $qntAcitivites;
    
    protected $listeners = ['tempoAcabou' => 'verificarFimDeJogo', 'atividadeConcluida' => 'concluirAtividade', 'calcularPontuacao' => 'calcularPontuacao', 'alunoFinalizou' => 'alunoFinalizou'];

    public function mount() {
        $sala = $this->getSala();
        $this->tempoMaximo = Regras::find($sala->regra_id)->tempo;
        $this->pontuacaoMaxima = Regras::find($sala->regra_id)->pontMax;
        $this->qntAcitivites = ActivityDAO::getActivitiesBySala($sala->id)->count();

        /* Talvez isso aqui possa resolver o problema do regra_id no futuro, mas pode quebrar o resto do sistema também, preciso estudar essa possibilidade
        if ($sala && $sala->regra) {
            $this->tempoMaximo = $sala->regra->tempo;
            $this->pontuacaoMaxima = $sala->regra->pontMax;
        } else {
            $this->tempoMaximo = 0;
            $this->pontuacaoMaxima = 0;
        }
        */
    }

    private function getSala()
    {
        $jogo = Jogo::where('content_id', $this->contentId)->first();
        if ($jogo) {
            return Sala::with('regra')
                       ->where('jogo_id', $jogo->id)
                       ->where('turma_id', $this->turmaId)
                       ->orderByRaw('CASE WHEN started_at IS NULL THEN 0 ELSE 1 END')
                       ->orderBy('started_at', 'desc')
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
        elseif ($sala->started_at && $sala->regra) {
            $horaFim = \Carbon\Carbon::parse($sala->started_at)->addSeconds($sala->regra->tempo);
            
            if (now()->greaterThanOrEqualTo($horaFim)) {
                $acabou = true;
                $sala->aberta = false;
                $sala->save();
            }
        }

        if ($acabou) {
            $urlDestino = $this->isProfessor 
                ? route('sala.results', $sala->id) 
                : route('home'); 

            $this->dispatchBrowserEvent('forcar-redirecionamento', ['url' => $urlDestino]);
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
        $this->calcularPontuacao();
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

        $pontuacao = (1 - (($tempoGasto / $this->tempoMaximo) / 2)) * ($this->pontuacaoMaxima / $this->qntAcitivites);
        $registro = PontuacaoSala::firstOrNew([
            'aluno_id' => $this->userId,
            'sala_id'  => $this->salaId,
        ]);

        $registro->pontuacao = ($registro->pontuacao ?? 0) + $pontuacao;
        $registro->save();
    }

}