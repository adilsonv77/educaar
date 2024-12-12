<?php
namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Turma;
use App\Models\AnoLetivo;
use App\DAO\TurmaDAO;
use App\Models\Question;
use App\DAO\ContentDAO;
use App\Models\StudentAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultadosController extends Controller
{
    public function index(Request $request)
    {
        $turma_id = $request->input('turma_id');
        $prof_id = Auth::user()->id;
        $anoletivo = AnoLetivo::where('school_id', Auth::user()->school_id)
            ->where('bool_atual', 1)->first();

        // Busca as turmas do professor
        $turmas = TurmaDAO::buscarTurmasProf($prof_id, $anoletivo->id)->get();

        // Se não foi passado um turma_id, seleciona a primeira turma disponível
        if ($turma_id) {
            $turma = Turma::find($turma_id);
        } else {
            $turma = $turmas->first();
            $turma_id = $turma->id;
        }
       
        $turma_modelo_id = $turma->turma_modelo_id;

        // Buscar conteúdos do professor para a turma selecionada
        $contentsprof = ContentDAO::buscarContentsDoProf($prof_id, $anoletivo->id)
            ->select('contents.id', 'contents.name', 'contents.turma_modelo_id')
            ->get();
        
        $contents = [];
        $contents_id = [];
        foreach ($contentsprof as $linha) {
            if ($linha->turma_id == $turma_modelo_id) {
                $contents[$linha->id] = [
                    'id' => $linha->id,
                    'name' => $linha->name,
                    'activities' => []
                ];
                $contents_id[] = $linha->id;
            }
        }

        // Buscar atividades relacionadas aos conteúdos
        $activities = Activity::whereIn('content_id', $contents_id)->get();

        foreach ($activities as $activity) {
            $activity_data = [
                'name' => $activity->name,
                'questions' => []
            ];

            $questions = Question::where('activity_id', $activity->id)->get();

            // Somente adiciona a atividade se tiver pelo menos uma questão
            if ($questions->isNotEmpty()) {
                foreach ($questions as $question) {
                    $activity_data['questions'][$question->id] = [
                        'question_text' => $question->question,  // texto da pergunta
                        'responses' => [] // coletar respostas
                    ];
                }

                // Armazenar a atividade 
                if (isset($contents[$activity->content_id])) {
                    $contents[$activity->content_id]['activities'][$activity->id] = $activity_data;
                }
            }
        }

        // Remove conteúdos sem atividades
        $contents = array_filter($contents, function ($content) {
            return !empty($content['activities']);
        });

        // Organizar as respostas dos alunos
        $studentsResponses = [];
        $students = TurmaDAO::buscarAlunosTurma($turma_id)->get();
        
        //  adiciona todos os alunos da turma
        foreach($students as $student) {
            $studentsResponses[$student->name] = array();
        }

        //dd($studentsResponses);

        $activity_questions = StudentAnswer::whereIn('activity_id', $activities->pluck('id'))
            ->with(['activity.contents', 'student'])
            ->get();

        foreach ($activity_questions as $student_answer) {
            $username = $student_answer->student->name ?? 'Desconhecido';

            // Verifica se a atividade e o conteúdo existem
            if (isset($student_answer->activity) && isset($student_answer->activity->content_id)) {
                $content_id = $student_answer->activity->content_id;
                $activity_id = $student_answer->activity_id;
                $question_id = $student_answer->question_id;

                // Preencher o array com as respostas
                $studentsResponses[$username][$content_id][$activity_id][$question_id] = [
                    'is_correct' => $student_answer->correct,
                    'status' => $student_answer->correct ? '✅' : '❌',
                    'answer' => $student_answer->alternative_answered,
                ];

                // Adiciona a resposta (pergunta + status) no array de conteúdos
                if (isset($contents[$content_id]['activities'][$activity_id]['questions'][$question_id])) {
                    $contents[$content_id]['activities'][$activity_id]['questions'][$question_id]['responses'][$username] = [
                        'is_correct' => $student_answer->correct,
                        'status' => $student_answer->correct ? '✅' : '❌',
                        'answer' => $student_answer->alternative_answered,
                    ];
                }
            }
        }
       
        // ordenar lista
        ksort($studentsResponses);
        
        return view('pages.turma.resultados', compact('turmas', 'turma', 'contents', 'studentsResponses'));
    }


}
