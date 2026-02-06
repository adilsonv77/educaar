<?php

namespace App\Http\Livewire;

use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\StudentAnswer;
use App\DAO\QuestionDAO;
use App\DAO\ActivityDAO;
use App\Models\ArProgress;
use App\Models\Content;
use App\Models\Pontuacao;
use Exception;
use Illuminate\Notifications\Action;

class QuestionarioAlunoForm extends Component
{

    protected $listeners = ['openQuestions', 'addTempo' => 'addTempo'];
    
    public $activity_id;

    public $refeita;
    public $jaRespondeu;

    public $tempoMaximo;
    public $pontuacaoMaxima;
    public $pontuacaoAtual;
    public $tempoResposta = [];
    public $tempoRestante;

    public $feedback = [];

    public $questions;
    public $respondida;
    public $activequestion;

    public $incorreta;

    public $activity;

    public $nrquestao;
    public $qtasquestoes;

    public $proximaPosicaoCalculada;
    public $questionarioRespondido;
    public $alternativas;

    /*
- PRECISO TESTAR COMO FUNCIONA COM FORMULÁRIOS JÁ RESPONDIDOS
*/
    public function openQuestions($value)
    {
        $this->activity_id = (int)$value;

        $this->refeita = ActivityDAO::refeita($this->activity_id);
        $this->jaRespondeu = QuestionDAO::jaRespondeuAlguma($this->activity_id);
        $this->tempoMaximo = QuestionDAO::getDuration($this->activity_id);
        $this->tempoRestante = ActivityDAO::getTempoRestante(Auth::id(), $this->activity_id);
        $this->pontuacaoMaxima = ActivityDAO::getPontuacao($this->activity_id);

        $this->feedback = [];

        if (session()->has('livewire_nrquestao') && session()->get('livewire_activity_id') == $value) {
            // pull jah busca e exclui
            $this->nrquestao = session()->pull('livewire_nrquestao');
            $this->alternativas = session()->pull('livewire_alternativas');
            $questions = session()->get('livewire_questoes');
            $this->activity_id = session()->get('livewire_activity_id');

        } else {

            session()->put('livewire_activity_id', $this->activity_id);
            // buscar da tabela student_answers uma questao respondida da activity_id, question_id, user_id

            $where = DB::table('questions')
                ->where('activity_id', $this->activity_id);
            
            /* when() usado para caso a atividade possa ser refeita, então só irá ser pego as questões que o usuário autenticado acertou */
            $subwhere = DB::table('student_answers as sa')
                ->select('sa.alternative_answered')
                ->whereColumn('sa.question_id', '=', 'questions.id')
                ->whereColumn('sa.activity_id', '=', 'questions.activity_id')
                ->where('sa.user_id', '=', Auth::id())
                ->orderBy('sa.created_at', 'desc')
                ->limit(1)
                ->when($this->refeita && $this->jaRespondeu, function($subwhen) {
                    $subwhen->where('sa.correct', 1);
                });

            $where->addSelect(['alternative_answered' => $subwhere]);

            $questions = $where->get();
            $questions = $questions->shuffle();

            $this->alternativas = array();
            foreach ($questions as $item) {

                $options = [$item->a, $item->b, $item->c, $item->d];
                shuffle($options);
                $item->options = $options;
                if ($item->alternative_answered != null) {
                    $key = array_search($item->alternative_answered, $options);
                    $this->alternativas[$item->id] = $key;
                }
            }
            session()->put('livewire_questoes', $questions);
            //dd($questions);
            $this->nrquestao = 0;
        }

        $this->respondida = $this->questionarioRespondido();
        $this->questions = $questions;

        $this->qtasquestoes = count($questions);

        $this->dispatchBrowserEvent('openQuestionsModal');

        $pontuacao = Pontuacao::where('user_id', Auth::id())->where('activity_id', $this->activity_id);
        if($this->tempoMaximo !== null && $pontuacao->doesntExist() && session('primeira_entrada') === 1) {
            $tempo = ($this->tempoRestante !== 0 && $this->tempoRestante !== null)
                ? $pontuacao->value('tempo_restante')
                : $this->tempoMaximo;
            
            $this->dispatchBrowserEvent('startTimer', [
                'tempoMaximo' => $tempo,
                'qtaQuestoes' => $this->qtasquestoes
            ]);
        }
    }

    private function questionarioRespondido()
    {
        $questions = session()->get('livewire_questoes');

        

        $resposta = 0;
        // vou fazer o sistema de resposta assim: 
        // 1- retorna que o questionário foi respondido completo
        // -1 -retorna que o questionário não foi respondido completo
        // 0 - retorna que o questionário não foi respondido 
        // dd($questions);
        $qntQuestoes = count($questions);
        $qntRespondidas = 0;
        foreach ($questions as $question) {
            if ($question->alternative_answered != null) {
                $qntRespondidas += 1;
            }
        }

        if ($qntRespondidas == $qntQuestoes) {
            $resposta = 1;
        } else {
            if ($qntRespondidas == 0) {
                $resposta = 0;
            } else {
                $resposta = -1;
            }
        }

        return $resposta;

    }

     // esse método sempre será executado ao final da chamada da execução dos outros métodos
    public function render()
    {
        $this->dispatchBrowserEvent('checkAllPost');
        return view('livewire.questionario-aluno-form');
    }

    public function cancel() {
        // as alternativas escolhidas salvar na sessão, assim como o numero da questao que estava observando
        session()->put("livewire_alternativas", $this->alternativas);
        session()->put("livewire_nrquestao", $this->nrquestao);
        session()->put('primeira_entrada', 0);
    }



    public function anterior() {
        if ($this->nrquestao > 0) {
            $this->nrquestao = $this->nrquestao - 1;
        } 
    }

    public function salvar($timeout, $tempoRestante) {
        $this->incorreta = false;

        if($timeout == true) {
            $this->salvarRespostas(true, $tempoRestante);
        } else {
            if($this->nrquestao < $this->qtasquestoes-1) {
                $this->nrquestao = $this->nrquestao + 1;
            } else {
                $this->salvarRespostas(false, $tempoRestante);
            }
        }
    }

    public function salvarRespostas($timeout, $tempoRestante) {
        DB::beginTransaction();
        $questions = session()->get('livewire_questoes');
        foreach($questions as $questao) {
            $questionId = $questao->id;
            if(isset($this->alternativas[$questionId])) {
                $selectedIndex = $this->alternativas[$questionId];
                if(isset($questao->options[$selectedIndex])) {
                    $selectedOption = $questao->options[$selectedIndex];
                    $questao->alternative_answered = $selectedOption;
                }
            }
        }
    
        $tentativa = ActivityDAO::getTentativa((int)session()->get('livewire_activity_id'), Auth::id());
        if(DB::table('activities')
            ->where('id', (int)session()->get('livewire_activity_id'))
            ->value('refeita') == 0) {
            $this->jaRespondeu = QuestionDAO::jaRespondeuTodas((int)session()->get('livewire_activity_id'));
        }
        try {
            $corretas = [];
            foreach ($questions as $questao) {
                $data = [];
                
                if(!$this->refeita && $this->jaRespondeu) {
                    continue;
                }
                
                $opcao = $questao->alternative_answered;
                $corretas[] = $opcao === $questao->a ? 1 : 0;
                
                $data = [
                    'question_id' => $questao->id,
                    'user_id' => Auth::user()->id,
                    'alternative_answered' => $opcao,
                    'correct' => ($opcao === $questao->a),
                    'activity_id' => $questao->activity_id,
                    'tentativas' => $tentativa,
                ];
                
                $this->feedback[] = [
                    'question' => QuestionDAO::getTextoQuestao($questao->id),
                    'alternative_answered' => $opcao,
                    'correct' => ($opcao === $questao->a),
                ];
                StudentAnswer::create($data);
            }
            
            $tempoRestanteAtual = ActivityDAO::getTempoRestante(Auth::id(), $this->activity_id);

            if($tempoRestanteAtual === null && $this->tempoMaximo !== null) {
                $this->pontuacaoAtual = $this->calcularPontuacao($corretas);
                Pontuacao::create([
                    'user_id' => Auth::id(),
                    'activity_id' => $this->activity_id,
                    'pontuacao' => $this->pontuacaoAtual,
                    'tempo_restante' => $timeout ? 0 : $tempoRestante
                ]);
            } else if($tempoRestanteAtual > 0) {
                Pontuacao::where('user_id', Auth::id())->where('activity_id', $this->activity_id)
                    ->update(['tempo_restante' => $tempoRestante]);
            }

            foreach($this->feedback as $item) {
                
                if(!$item['correct']) {
                    $this->incorreta = true;
                    break;
                }
            }
            $content = Content::find(session()->get('content_id'));
            
            if(!$this->incorreta && $content->sort_activities){
                $progress = ArProgress::updateOrCreate(
                    ['student_id' => Auth::id(), 'content_id' => session()->get('content_id')],
                    ['next_position' => $this->proximaPosicaoCalculada ?? 1]
                );
                $this->proximaPosicaoCalculada = $progress->next_position + 1;
            } else{
                $progress = ['next_position' => $this->proximaPosicaoCalculada ?? 1 ];
            }
            
            DB::commit();
            session()->forget(['livewire_questoes', 'livewire_alternativas', 'livewire_nrquestao']);
            $this->questions = null;
            if(!$this->incorreta && $content->sort_activities){
                $this->dispatchBrowserEvent('atividade-concluida', [
                    'position' => $progress->next_position,
                    'activity_id' => $this->activity_id
                ]);
            }
            
            if($timeout == false) {
                $this->dispatchBrowserEvent('openFeedbackModal');
            }
        } catch (Exception $e) {
            DB::rollback();
            dd($e);
            $this->dispatchBrowserEvent('showError');
        }
    }

    public function close() {

        $this->dispatchBrowserEvent('closeFeedbackModal');
        $content_id = session()->get('content_id');
        $content = Content::find($content_id);
        
        $activity = Activity::find($this->activity_id);

        session()->put('activity', $activity);
        session()->put('position', $activity->position);

        //padronizei a posição do progresso em 1 para que atividades não ordenadas não sejam afetadas pelo sistema de ordenação
        $progress = [
            'next_position' => 1
        ];

        //caso o conteúdo seja de atividades ordenadas, atualiza a posição permitida para o aluno realizar a atividade
        if($content->sort_activities && $this->incorreta == false){
            $progress = ArProgress::where('student_id', Auth::id())
            ->where('content_id', session()->get('content_id'))
            ->first();
            $progress->next_position = $activity->position + 1;
            $progress->save();
            $this->emitTo('ar-progress-state', 'updatePosition', $progress->next_position, $progress->content_id);
        }

        $this->feedback = [];
        
        session()->put([
            'id' => session()->get('content_id'),
        ]);

    }

    /* Essa função não está sendo mais utilizada
    public function closeNotAllowedModal() {
        $this->dispatchBrowserEvent('closeNotAllowedModal');
    }
     */   

    public function addTempo($tempo) {
        $valor = is_numeric($tempo) ? floatval($tempo) : 0.0;
        $this->tempoResposta[] = $valor;
    }

    public function calcularPontuacao($corretas) {
        $pontos = [];

        if (empty($this->tempoResposta)) {
            return 0;
        }

        $pontos = collect($corretas)->map(function ($correta, $i) {
            if($correta !== 1) return 0;

            $tempoGasto = $i === 0
                ? $this->tempoResposta[0]
                : $this->tempoResposta[$i] - $this->tempoResposta[$i-1];

            return round((1-($tempoGasto/$this->tempoMaximo)/2) * ($this->pontuacaoMaxima/$this->qtasquestoes));
        })->filter()->values()->toArray();

        return array_sum($pontos);
    }

    public function timeOut() {
        Pontuacao::create([
            'user_id' => Auth::id(),
            'activity_id' => $this->activity_id,
            'pontuacao' => 0
        ]);
    }
}