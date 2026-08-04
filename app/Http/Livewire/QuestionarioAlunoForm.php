<?php

namespace App\Http\Livewire;

use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Livewire\Component;
use App\Models\StudentAnswer;
use App\DAO\QuestionDAO;
use App\DAO\ActivityDAO;
use App\DAO\SalaDAO;
use App\DAO\JogoDAO;
use App\Models\ArProgress;
use App\Models\Content;
use Exception;

enum QuestionaireStatus: int {
    case COMPLETO = 1;
    case INCOMPLETO = -1;
    case NAO_RESPONDIDO = 0;
}

class QuestionarioAlunoForm extends Component {
    protected $listeners =['openQuestions'];

    public Content $content;
    public Activity $activity;

    public bool $isJogo;
    public int $proximaPosicaoCalculada;
    public string $hint = '';

    public bool $jaRespondeu;
    public int $nrquestao;
    public array $alternativas;
    public QuestionaireStatus $respondida;
    public int $qtasquestoes;
    public array $feedback = [];
    public Collection $questions;
    public bool $incorreta = false;

    public function mount() {
        $this->content = Content::find(session()->get('content_id'));
        $this->isJogo = $this->content->is_jogo;
    }

    /* Esse método sempre será executado ao final da chamada de execução dos outros métodos */
    public function render() {
        $this->dispatchBrowserEvent('checkAllPost');
        return view('livewire.questionario-aluno-form');
    }

    public function openQuestions(int $value): void {
        $this->activity = Activity::find((int)$value);
        $this->jaRespondeu = QuestionDAO::jaRespondeuAlguma($this->activity->id);

        if(session()->has('livewire_nrquestao') && session()->get('livewire_activity_id') === $value) {
            $this->nrquestao = session()->pull('livewire_nrquestao');
            $this->alternativas = session()->pull('livewire_alternativas');
            $questions = session()->get('livewire_questoes');
        } else {
            session()->put('livewire_activity_id', $this->activity->id);

            $questions = QuestionDAO::buscarQuestoesDaAtividade($this->activity->id, Auth::id(), ($this->isJogo && $this->jaRespondeu));
            $questions = $questions->shuffle();

            $this->shuffleAlternatives($questions);

            session()->put('livewire_questoes', $questions);
            $this->nrquestao = 0;
        }

        $this->respondida = $this->answeredQuestionaire();
        $this->questions = $questions;
        $this->qtasquestoes = count($questions);

        $this->dispatchBrowserEvent('openQuestionsModal');
    }

    private function shuffleAlternatives(Collection $questions): void {
        $this->alternativas = array();

        foreach($questions as $item) {
            $options = [$item->a, $item->b, $item->c, $item->d];
            shuffle($options);
            $item->options = $options;

            if($item->alternative_answered != null) {
                $key = array_search($item->alternative_answered, $options);
                $this->alternativas[$item->id] = $key;
            }
        }
    }

    private function answeredQuestionaire(): QuestionaireStatus {
        $questions = session()->get('livewire_questoes');
        $qntQuestoes = count($questions);
        $qntRespondidas = 0;

        foreach($questions as $question) {
            if($question->alternative_answered != null) {
                $qntRespondidas += 1;
            }
        }

        return ($qntRespondidas == $qntQuestoes)
            ? QuestionaireStatus::COMPLETO
            : ($qntRespondidas == 0
                ? QuestionaireStatus::NAO_RESPONDIDO
                : QuestionaireStatus::INCOMPLETO);
    }

    public function cancel(): void {
        session()->put('livewire_alternativas', $this->alternativas);
        session()->put('livewire_nrquestao', $this->nrquestao);
    }

    public function anterior(): void {
        if($this->nrquestao > 0) {
            $this->nrquestao -= 1;
        }
    }

    public function salvar() {
        if($this->nrquestao < $this->qtasquestoes - 1) {
            $this->nrquestao++;
            return;
        }

        $questions = session()->get('livewire_questoes');
        $this->selectQuestions($questions);
        
        $tentativa = ActivityDAO::getTentativa($this->activity->id, Auth::id());
        if(Content::where('id', $this->content->id)->value('refeito')) {
            $this->jaRespondeu = QuestionDAO::jaRespondeuTodas($this->activity->id);
        }

        try {
            DB::beginTransaction();

            $this->saveAnswers($questions, $tentativa);
            $this->verifySavedAnswers();

            $this->updateGameProgress();

            DB::commit();
            
            session()->forget(['livewire_questoes', 'livewire_alternativas', 'livewire_nrquestao']);

            $this->hint = $this->incorreta ? '' : $this->hint;

            if(!$this->incorreta && $this->verifyContentFinished()) {
                return redirect()->route('home');
            }

            if($this->hint) {
                $this->dispatchBrowserEvent('openHintModal');
            } else {
                $this->dispatchBrowserEvent('closeQuestionarioModal');
            }

            $this->emitTo('hint-button', 'updateHint', $this->hint);
        } catch(Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            DB::rollback();
            $this->dispatchBrowserEvent('showError');
        }
    }

    private function selectQuestions(Collection $questions) {
        foreach($questions as $question) {
            $questionId = $question->id;

            if(isset($this->alternativas[$questionId])) {
                $selectedIndex = $this->alternativas[$questionId];

                if(isset($question->options[$selectedIndex])) {
                    $selectedOption = $question->options[$selectedIndex];
                    $question->alternative_answered = $selectedOption;
                }
            }
        }
    }

    private function saveAnswers(Collection $questions, int $tentativa): void {
        foreach($questions as $question) {
            if(!$this->isJogo && $this->jaRespondeu) {
                continue;
            }

            $option = $question->alternative_answered;
            $isCorrect = $option === $question->a;

            $data = [
                'question_id' => $question->id,
                'user_id' => Auth::id(),
                'alternative_answered' => $option,
                'correct' => $isCorrect,
                'activity_id' => $question->activity_id,
                'tentativas' => $tentativa,
            ];

            $this->feedback[] = [
                'question' => QuestionDAO::getTextoQuestao($question->id),
                'alternative_answered' => $option,
                'correct' => $isCorrect,
            ];

            StudentAnswer::create($data);
        }
    }

    private function verifySavedAnswers(): void {
        foreach($this->feedback as $item) {
            if(!$item['correct']) {
                $this->hint = '';
                $this->incorreta = true;
                break;
            }
        }
    }

    private function updateGameProgress(): void {
        if($this->incorreta || !$this->isJogo) {
            $progress = ['next_position' => $this->proximaPosicaoCalculada ?? 1];
            return;
        }

        $progress = ArProgress::firstOrCreate(
            ['student_id' => Auth::id(), 'content_id' => $this->content->id],
            ['next_position' => 1]
        );
        $progress->next_position++;
        $progress->save();

        $this->dispatchBrowserEvent('atividade-concluida', [
            'position' => $progress->next_position,
            'activity_id' => $this->activity->id
        ]);

        $this->proximaPosicaoCalculada = $progress->next_position;

        $jogo = JogoDAO::getJogoByContentId($this->content->id);
        $salaId = SalaDAO::getSalaIDByJogo($jogo->id);

        $this->emitTo('monitor-jogo', 'atividadeConcluida', Auth::id(), $salaId);
    }

    private function verifyContentFinished(): bool {
        $posicaoAtual = ArProgress::where('content_id', $this->content->id)
            ->where('student_id', Auth::id())
            ->value('next_position');

        $totalAtividades = ActivityDAO::buscarActivitiesPorConteudo($this->content->id)->count();

        return ($posicaoAtual > $totalAtividades);
    }

    public function close(): void {
        $this->dispatchBrowserEvent('closeFeedbackModal');
        $this->dispatchBrowserEvent('closeHintModal');
        
        session()->put('activity', $this->activity);
        session()->put('position', $this->activity->position);
        session()->put('id', $this->content->id);

        $this->feedback = [];
    }

    public function hint() {
        $this->dispatchBrowserEvent('openHintModal');
    }
}