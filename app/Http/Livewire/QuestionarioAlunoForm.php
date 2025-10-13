<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\StudentAnswer;
use Exception;

class QuestionarioAlunoForm extends Component
{

    protected $listeners = ['openQuestions'];
    
    private $activity_id;

    public $questions;
    public $respondida;
    public $activequestion;

    public $nrquestao;
    public $qtasquestoes;

    public $alternativas;

    /*
- PRECISO TESTAR COMO FUNCIONA COM FORMULÁRIOS JÁ RESPONDIDOS
*/
    public function openQuestions($value)
    {
        $this->activity_id = $value;


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
            ->where("activity_id", $this->activity_id)->addSelect([
                    'alternative_answered' => DB::table('student_answers')
                        ->select('student_answers.alternative_answered')
                        ->whereColumn('student_answers.question_id', '=', 'questions.id')
                        ->whereColumn('student_answers.activity_id', '=', 'questions.activity_id')
                        ->where('student_answers.user_id', '=', Auth::user()->id)
                ]);

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
            $questoes = [];

            foreach ($questions as $q) {
                array_push($questoes, $q->id);
            }
    
            /*
            $respondida = DB::table('student_answers')
                ->whereIn('question_id', $questoes)
                ->where('user_id', Auth::user()->id)
                ->exists();
            */
            $respondida = false;

            if (!$respondida) {

                //$datareq = $request->all();
                foreach ($questions as $questao) {
                    $data = ['question_id', 'user_id', 'alternative_answered', 'correct'];

                    try {

                        $jaRespondeu = StudentAnswer::where('question_id', $questao->id)
                            ->where('user_id', Auth::id())
                            ->exists();
                        
                        if ($jaRespondeu) {
                            continue;
                        }

                        //$respop = $datareq["questao" . $questao->id];
                        $respop = $this->alternativas[$questao->id];
                        $data['question_id'] = $questao->id;
                        $data['user_id'] = Auth::user()->id;
                        $data['activity_id'] = $questao->activity_id;
                        $opcao = $questao->options[$respop];
                        //dd($respop . " - " . $opcao . " - " . $questao->a . " - " . implode(" ; ", $questao->options));

                        $data['alternative_answered'] = $opcao; // havia um erro na estrutura do banco que esse campo era de somente 1!!!
                        // $s = $s . " " . $opcao . "-" .  $questao->a . " <br/> ";

                        if ($opcao == $questao->a) {
                            $data['correct'] = true;
                        } else {
                            $data['correct'] = false;
                        }

                        //dd($data);
                        // gravar no banco uma linha da resposta
                        StudentAnswer::create($data);

                    } catch (Exception $e) {
                        continue;
                    }
                }

                DB::commit();

                unset($_SESSION['livewire_questoes']);
                unset($_SESSION['livewire_alternativas']);
                unset($_SESSION['livewire_nrquestao']);

                $this->questions = null;
                
                $this->dispatchBrowserEvent('closeQuestionsModal');

                return $this->redirectRoute('student.showActivity', ['id' => session()->get("content_id")]);
                
            } else {
                DB::rollback();

                $this->dispatchBrowserEvent('showError');
            }
        }
    }

}