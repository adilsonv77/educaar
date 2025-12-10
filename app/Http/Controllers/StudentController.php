<?php

namespace App\Http\Controllers;

use App\DAO\ActivityDAO;
use App\DAO\ButtonDAO;
use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\StudentAnswer;
use Illuminate\Support\Facades\DB;
use App\Models\AnoLetivo;
use Exception;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Content;
use App\DAO\ContentDAO;
use App\DAO\StudentAppDAO;
use App\DAO\PainelDAO;
use App\DAO\MuralDAO;
use App\DAO\QuestionDAO;
use App\Models\ArProgress;

class StudentController extends Controller
{

    /**
     * Página de home da aplicação... mostra todos os conteúdos do aluno.
     * 
     */
    public function indexContentStudent(Request $request)
    {
        // Valida se o ID da disciplina foi enviado
        if ($request->has('id')) {
            session()->put('disciplina', $request->id);
        }

        $id = session()->get("disciplina");

        // Verifica se o usuário tem permissão correta
        if (Auth::user()->type != session('type')) {
            $conteudos = ContentDAO::buscarConteudosDeveloper(Auth::user()->id)
                ->where('disciplinas.id', '=', $id)
                ->where('contents.fechado', '=', 1)
                ->select('contents.name', 'contents.id', 'contents.fechado')
                ->get();
        } else {
            $anoAtual = AnoLetivo::where('school_id', Auth::user()->school_id)
                ->where('bool_atual', 1)
                ->first();

            $conteudos = DB::table('alunos_turmas as at')
                ->select('c.name', 'c.id')
                ->join('turmas as t', 't.id', '=', 'at.turma_id')
                ->join('disciplinas_turmas_modelos as dtm', 'dtm.turma_modelo_id', '=', 't.turma_modelo_id')
                ->join('contents as c', function (JoinClause $join) {
                    $join->on('dtm.turma_modelo_id', '=', 'c.turma_modelo_id')
                        ->on('c.disciplina_id', '=', 'dtm.disciplina_id');
                })
                ->where([
                    ['at.aluno_id', '=', Auth::user()->id],
                    ['t.ano_id', '=', $anoAtual->id],
                    ['c.disciplina_id', '=', $id],
                    ['c.fechado', '=', 1]
                ])
                ->distinct()
                ->get();
        }
        // Aqui você pode calcular o valor de 'conteudoRespondido' com base nas respostas do aluno
        $conteudosRespondidos = [];
        foreach ($conteudos as $conteudo) {
            // Conta questões respondidas dentro do conteúdo
            $respondidas = DB::table('student_answers as sa')
                ->join('questions as q', 'sa.question_id', '=', 'q.id')
                ->join('activities as a', 'q.activity_id', '=', 'a.id')
                ->where('a.content_id', $conteudo->id)
                ->where('sa.user_id', Auth::user()->id)
                ->distinct('sa.question_id')
                ->count('sa.question_id');

            // Conta total de questões no conteúdo
            $totalQuestoes = DB::table('questions as q')
                ->join('activities as a', 'q.activity_id', '=', 'a.id')
                ->where('a.content_id', $conteudo->id)
                ->count();

            // Se todas as questões foram respondidas, marca como concluído
            $conteudosRespondidos[$conteudo->id] = ($respondidas === $totalQuestoes);
        }

        // dd($totalQuestoes, $respondidas);

        // Log::info("Total de questões: $totalQuestoes, Questões respondidas: $respondidas");
        // dd($conteudosRespondidos);
        // dd($respondido);

        $rota = route("home");

        // limpar a sessao a ser usada nos questionarios
        session()->forget(['livewire_nrquestao', 'livewire_alternativas', 'livewire_questoes', 'livewire_activity_id']);


        return view('student.indexContentStudent', compact('conteudos', 'rota', 'conteudosRespondidos'));
    }


    /**
     * Após clicar no conteúdo, entra na página de RA da aplicação
     */
    public function showActivity(Request $request)
    {
        $data = $request->all();
        $content_id = $request->id == null ? session()->get("content_id") : $request->id;
        $content = Content::find($content_id);

       

        //esse if tá verificando se o conteúdo possui atividades ordenadas
        if($content->sort_activities){
            $activities = ActivityDAO::buscarOrderedActivitiesPorConteudo($content_id);
        } else{
            $activities = ActivityDAO::buscarActivitiesPorConteudo($content_id);
        }
        
        $anoAtual = AnoLetivo::where('school_id', Auth::user()->school_id)
            ->where('bool_atual', 1)->first();
        $dataCorte = null;
        $bloquearPorData = 0;
        $scenes = [];
        $panels = [];
        $buttons = [];

        foreach ($activities as $activity) {
            // verificar quais atividades já foram respondidas  
            if ($dataCorte == null) {
                $dataCorte = StudentAppDAO::buscarDataCorte($activity->id, Auth::user()->id, $anoAtual->id)->first();

                if ($dataCorte != null) {
                    $diff = date_diff(now(), new \DateTime($dataCorte->dt_corte));

                    if ($diff->format("%R%a") <= 0) {
                        $bloquearPorData = 1;
                    }
                }
            }

            $activity->bloquearPorData = $bloquearPorData;
            if ($bloquearPorData == 0) {
                // uma questao respondida ou nao jah diz tudo da atividade
                $respondida = StudentAppDAO::verificaAtividadeRespondida($activity->id, Auth::user()->id);
                $activity->respondido = $respondida ? 1 : 0;
            }

            //Possui um painel inicial
            $mural_id = $activity->mural_id;
            if ($mural_id != null) {
                //Pegar paineis e botões e atribui para uma array de paineis
                $idPainelInicial = MuralDAO::getById($mural_id)->start_panel_id;
                $activity->json = PainelDAO::getById($mural_id)->panel;
                $scenes[] = MuralDAO::getById($mural_id);
                $scenePanels = PainelDAO::getByMuralId($mural_id);
                foreach ($scenePanels as $panel) {
                    $panels[] = $panel;
                    $panelButtons = ButtonDAO::getByOriginId($panel->id);
                    foreach ($panelButtons as $button) {
                        $buttons[] = $button;
                    }
                }
            }
        }
        
        // guarda content_id na sessão
        session(["content_id" => $content_id]);

        $progress  = [
            'next_position' => 1,
        ];

        if($content->sort_activities){
            $progress = ArProgress::firstOrCreate(
                [
                    'student_id' => Auth::user()->id,
                    'content_id' => $content_id,
                ],
                [
                    'next_position' => 1,
                ]
            );
        }
    
        $disciplina = session()->get("disciplina");
        $rota = route("student.conteudos") . "?id=" . $disciplina;
        return view('student.ar', compact('activities', 'rota','scenes','panels','buttons', 'content', 'progress'));
    }

    public function atualizarProgressoConteudoOrdenado(Request $request){
        $request->validate([
            'content_id' => 'required|integer|exists:contents,id',
            'new_position' => 'required|integer|min:1',
        ]);

        $contentId = $request->input('content_id');
        $newPosition = $request->input('new_position');
        $studentId = Auth::id();

        try {

            $progress = ArProgress::updateOrCreate(
                ['student_id' => $studentId, 'content_id' => $contentId],
                ['next_position' => $newPosition]
            );

            return response()->json([
                'success' => true,
                'message' => 'Progresso atualizado no banco de dados com sucesso.',
                'new_position' => $progress->next_position
            ], 200);

        } catch (Exception $e) {
            Log::error('Erro ao atualizar progresso AR: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro interno ao salvar progresso.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}

