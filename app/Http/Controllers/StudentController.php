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
use App\DAO\JogoDAO;
use App\Models\ArProgress;
use App\Models\RandomSort;
use App\Models\Sala;

class StudentController extends Controller
{

    /**
     * Página de home da aplicação... mostra todos os conteúdos do aluno.
     * 
     */
    public function indexContentStudent(Request $request)
    {
        if ($request->has('id')) {
            session()->put('disciplina', $request->id);
        }

        $id = session()->get("disciplina");
        $userId = Auth::id(); 

        if (Auth::user()->type != session('type')) {
            $conteudosPadrao = ContentDAO::buscarConteudosDeveloper($userId)
                ->where('disciplinas.id', '=', $id)
                ->where('contents.fechado', '=', 1)
                ->where('contents.is_jogo', '=', 0) 
                ->select('contents.name', 'contents.id', 'contents.fechado', 'contents.is_jogo')
                ->get();
        } else {
            $anoAtual = AnoLetivo::where('school_id', Auth::user()->school_id)
                ->where('bool_atual', 1)
                ->first();

            $conteudosPadrao = DB::table('alunos_turmas as at')
                ->select('c.name', 'c.id', 'c.is_jogo')
                ->join('turmas as t', 't.id', '=', 'at.turma_id')
                ->join('disciplinas_turmas_modelos as dtm', 'dtm.turma_modelo_id', '=', 't.turma_modelo_id')
                ->join('contents as c', function ($join) {
                    $join->on('dtm.turma_modelo_id', '=', 'c.turma_modelo_id')
                        ->on('c.disciplina_id', '=', 'dtm.disciplina_id');
                })
                ->where([
                    ['at.aluno_id', '=', $userId],
                    ['t.ano_id', '=', $anoAtual->id],
                    ['c.disciplina_id', '=', $id],
                    ['c.fechado', '=', 1],
                    ['c.is_jogo', '=', 0] 
                ])
                ->distinct()
                ->get();
        }

        $turmasIds = DB::table('alunos_turmas')
            ->where('aluno_id', $userId)
            ->pluck('turma_id');

        $salas = Sala::with('jogo.content') 
            ->whereIn('turma_id', $turmasIds) 
            ->where('aberta', true) 
            ->whereNull('started_at')        
            ->get();

        $conteudosJogo = $salas->pluck('jogo.content')
            ->filter()       
            ->values();

        $conteudos = $conteudosPadrao->concat($conteudosJogo)->unique('id')->values();


        $conteudosRespondidos = [];
        foreach ($conteudos as $conteudo) {
            
            $respondidas = DB::table('student_answers as sa')
                ->join('questions as q', 'sa.question_id', '=', 'q.id')
                ->join('activities as a', 'q.activity_id', '=', 'a.id')
                ->where('a.content_id', $conteudo->id)
                ->where('sa.user_id', $userId)
                ->distinct('sa.question_id')
                ->count('sa.question_id');

            $totalQuestoes = DB::table('questions as q')
                ->join('activities as a', 'q.activity_id', '=', 'a.id')
                ->where('a.content_id', $conteudo->id)
                ->count();

            if ($totalQuestoes > 0) {
                $conteudosRespondidos[$conteudo->id] = ($respondidas === $totalQuestoes);
            } else {
                $conteudosRespondidos[$conteudo->id] = false; 
            }
        }

        $conteudos = $conteudos->reject(function ($conteudo) use ($conteudosRespondidos) {
            return $conteudosRespondidos[$conteudo->id] === true;
        });

        $rota = route("home");

        session()->forget(['livewire_nrquestao', 'livewire_alternativas', 'livewire_questoes', 'livewire_activity_id']);

        return view('student.indexContentStudent', compact('conteudos', 'rota', 'conteudosRespondidos'));
    }

    /**
     * Após clicar no conteúdo, entra na página de RA da aplicação
     */

    public function showActivity(Request $request)
    {
        session()->put('primeira_entrada', 1);

        $data = $request->all();
        $content_id = $request->id == null ? session()->get("content_id") : $request->id;
        $content = Content::find($content_id);

        $turmaAluno = StudentAppDAO::buscarTurmaAluno(Auth::user()->id);

        $jogo = JogoDAO::buscarJogoConteudoESalaAberta($content_id);
        $isJogo = false;

        if($jogo){
            $isJogo = true;
        }

        if($content->is_jogo) {
            $this->createRandomSort($content_id);
        }

        if($content->is_jogo){
            $activities = ActivityDAO::buscarRandomOrderedActivitiesPorConteudo($content_id);
        } else{
            $activities = ActivityDAO::buscarActivitiesPorConteudo($content_id);
        }

        $anoAtual = AnoLetivo::where('school_id', Auth::user()->school_id)
            ->where('bool_atual', 1)->first();
        $dataCorte = null;
        $bloquearPorData = 0;
        $murais = [];
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
                $respondida = StudentAppDAO::verificaAtividadeRespondida($activity->id, Auth::user()->id, 
                    $content->is_jogo);

                $activity->respondido = $respondida ? 1 : 0;
            }

            //Possui um painel inicial
            $mural_id = $activity->mural_id;
            if ($mural_id != null) {
                //Pegar paineis e botões e atribui para uma array de paineis
                $idPainelInicial = MuralDAO::getById($mural_id)->start_panel_id;
                $activity->json = PainelDAO::getById($mural_id)->panel;
                $murais[] = MuralDAO::getById($mural_id);
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

        if($content->is_jogo){
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
        return view('student.ar', compact('activities', 'rota','murais','panels','buttons', 'content', 'progress', 'turmaAluno', 'isJogo'));
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

            Log::info('ROTA progresso', ['novo' => $newPosition, 'aluno' => $studentId]);

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

    /**
     * Cria uma ordem aleatória em um conteúdo para o aluno autenticado.
     * 
     * @param int $content_id
    */
    private function createRandomSort(int $content_id) : void {
        $activities = Activity::where('content_id', $content_id)->count();

        if($activities <= 1) {
            return;
        }

        $oldSort = count(explode(',', RandomSort::where('content_id', $content_id)->where('user_id', Auth::id())->value('sort')));

        $sort = range(1, $activities);
        shuffle($sort);
        
        if ($oldSort == $activities) {
            RandomSort::firstOrCreate([
                'user_id' => Auth::id(),
                'content_id' => $content_id,
            ],[
                'sort' => implode(',', $sort)
            ]);
        } else {
            RandomSort::updateOrInsert([
                'user_id' => Auth::id(),
                'content_id' => $content_id,
            ], [
                'sort' => implode(',', $sort)
            ]);
        }
    }

    public function profile()
    {
        $student = Auth::user();
        $student->turma = StudentAppDAO::buscarTurmaAluno($student->id);
        $student->escola = StudentAppDAO::buscarEscolaAluno($student->id);
        $urlAvatar = $student->avatar;
        $rota = route("home");
        return view('student.profile', compact('student', 'rota', 'urlAvatar'));

    }

    public function studentAvatar(){
        $student = Auth::user();
        $rota = route("home");
        $urlAvatar = $student->avatar;
        session()->put('avatar', $urlAvatar);
        return view('student.student-avatar', compact('student', 'rota', 'urlAvatar'));
    }

    public function updateStudentAvatar(Request $request){

        $request = request();
        $avatar = $request->input('avatar');
        $student = Auth::user();
        $student->avatar = $avatar;
        $student->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Avatar atualizado com sucesso!'
            ]);
        }

        return redirect()->route('student.avatar', $student->id)->with('success', 'Avatar atualizado com sucesso!');
    }

}

