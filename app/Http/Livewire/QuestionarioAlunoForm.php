<?php

namespace App\Http\Livewire;

use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\StudentAnswer;
use App\DAO\QuestionDAO;
use App\Models\ArProgress;
use App\Models\Content;
use Exception;

class QuestionarioAlunoForm extends Component
{

    protected $listeners = ['openQuestions'];
    
    public $activity_id;

    public $refeita;
    public $jaRespondeu;

    public $feedback = [];

    public $questions;
    public $respondida;
    public $activequestion;

    public $activity;

    public $nrquestao;
    public $qtasquestoes;


    public $questionarioRespondido;
    public $alternativas;

    /*
- PRECISO TESTAR COMO FUNCIONA COM FORMULÁRIOS JÁ RESPONDIDOS
*/
    public function openQuestions($value)
    {
        $this->activity_id = (int)$value;

        $this->refeita = QuestionDAO::refeita($this->activity_id);
        $this->jaRespondeu = QuestionDAO::jaRespondeuAlguma($this->activity_id);

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
    }



    public function anterior() {
        if ($this->nrquestao > 0) {
            $this->nrquestao = $this->nrquestao - 1;
        } 
    }

    public function salvar() {
        if ($this->nrquestao < $this->qtasquestoes-1) {
            $this->nrquestao = $this->nrquestao + 1;
        } else {
            DB::beginTransaction();

            $questions = session()->get('livewire_questoes');
            $tentativa = QuestionDAO::getTentativa((int)session()->get('livewire_activity_id'), Auth::id());

            if(DB::table('activities')
                ->where('id', (int)session()->get('livewire_activity_id'))
                ->value('refeita') == 0) {
                $this->jaRespondeu = QuestionDAO::jaRespondeuTodas((int)session()->get('livewire_activity_id'));
            }

            try {
                foreach ($questions as $questao) {
                    $data = [];

                    if(!$this->refeita && $this->jaRespondeu) {
                        continue;
                    }

                    $respop = $this->alternativas[$questao->id];
                    $opcao = $questao->options[$respop];

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

                DB::commit();

                session()->forget(['livewire_questoes', 'livewire_alternativas', 'livewire_nrquestao']);
                $this->questions = null;

                $this->dispatchBrowserEvent('openFeedbackModal');
            } catch (Exception $e) {
                DB::rollback();
                $this->dispatchBrowserEvent('showError');
            }
        }
    }

    public function close() {
        $this->dispatchBrowserEvent('closeFeedbackModal');
        $this->feedback = [];
        $content = Content::find(session()->get('content_id'));
        $activity = Activity::find($this->activity_id);

        session()->put('activity', $activity);
        session()->put('position', $activity->position);

        // sinaliza atividade concluída (payload que main-mindar espera)
        session()->put('atividade_concluida', [
            'position' => $activity->position,
            'content_id' => session()->get('content_id'),
        ]);

        $progress = [
            'next_position' => 1
        ];

        if($content->is_sort){
            $progress = ArProgress::where('student_id', Auth::id())
            ->where('content_id', session()->get('content_id'))
            ->first();
        }

        if($content->is_sort){
            $progress->next_position ++;
            $progress->save();
        }
        

        return $this->redirectRoute('student.showActivity', [
            'id' => session()->get('content_id'), 'progress' => $progress
        ]);

    }

}