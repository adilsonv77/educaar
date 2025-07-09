<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class QuestionarioAlunoForm extends Component
{

    protected $listeners = ['openQuestions'];
    
    private $activity_id;

    public $questions;
    public $respondida;
    
    public function openQuestions($value)
    {
        $this->activity_id = $value;
    }

    // esse método sempre será executado ao final da chamada da execução dos outros métodos
    public function render()
    {
      
        if ($this->activity_id  != null) {

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

            foreach ($questions as $item) {
                $options = [$item->a, $item->b, $item->c, $item->d];
                shuffle($options);
                $item->options = $options;
            }
            session()->put('questoes', $questions);

            $this->respondida = $this->questionarioRespondido();
            $this->questions = $questions;

            $this->dispatchBrowserEvent('openQuestionsModal');

        }

        return view('livewire.questionario-aluno-form');
    }

 
    private function questionarioRespondido()
    {
        $questions = session()->get('questoes');

        $reposta = 0;
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

    public function salvar() {

        DB::beginTransaction();

        $questions = session()->get('questoes');
        $questoes = [];

        foreach ($questions as $q) {
            array_push($questoes, $q->id);
        }
      

        $respondida = DB::table('student_answers')
            ->whereIn('question_id', $questoes)
            ->where('user_id', Auth::user()->id)
            ->exists();

        if (!$respondida) {

            $datareq = $request->all();
            foreach ($questions as $questao) {
                $data = ['question_id', 'user_id', 'alternative_answered', 'correct'];

                try {
                    $respop = $datareq["questao" . $questao->id];
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
        } else {
            DB::commit();

            return redirect()->back()
                ->withInput()
                ->withErrors([
                        'respondida' => 'Questionário já respondido por outro login. Você somente consegue retornar.'
                    ]);

        }

    }
}