<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\StudentAnswer;
use Illuminate\Support\Facades\DB;
use App\Models\AnoLetivo;
use Exception;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Auth;
use App\DAO\ContentDAO;
use App\DAO\StudentAppDAO;
use App\Models\Painei;



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
                ->select('contents.name', 'contents.id')
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
        return view('student.indexContentStudent', compact('conteudos', 'rota', 'conteudosRespondidos'));
}


    /**
     * Após clicar no conteúdo, entra na página de RA da aplicação
     */
    public function showActivity(Request $request)
    {
        $data = $request->all();
        $content_id = $request->id;
        if ($content_id == null) {
            $content_id = session()->get("content_id");
        }
        $activities = DB::table('activities')
            ->where("content_id", $content_id)
            ->orderBy('scene_id', 'desc')
            ->get();
            //dd($activities);
            
        $anoAtual = AnoLetivo::where('school_id', Auth::user()->school_id)
            ->where('bool_atual', 1)->first();
            
        $dataCorte = null;
        $bloquearPorData = 0;
        // verificar quais atividades já foram respondidas        
        foreach ($activities as $activity) {
            if ($dataCorte == null) {
                $dataCorte = StudentAppDAO::buscarDataCorte($activity->id, Auth::user()->id, $anoAtual->id)->first();

                if ($dataCorte != null) {
                    $dataHoje = now();
                    $diff = date_diff($dataHoje, new \DateTime($dataCorte->dt_corte));
                    
                    if ($diff->format("%R%a") <= 0) {
                        $bloquearPorData = 1;
                    } 
                }
                
            }
            
            //Pega o json do painel da atividade se for um painel.
            $idPainelInicial = $activity->scene_id;
            if($idPainelInicial != null){
                //Possui um painel inicial
                $activity->json = Painei::where('id',$idPainelInicial)->first()->panel;
            }
            $activity->bloquearPorData = $bloquearPorData;
            if ($bloquearPorData == 0) {
                $questions = DB::table('questions')
                    ->where("activity_id", $activity->id)->get();
                // uma questao respondida ou nao jah diz tudo da atividade
                $respondida = DB::table('student_answers as st')
                    ->join('questions as q', 'st.question_id', '=', 'q.id')
                    ->where([
                        ['st.activity_id', '=', $activity->id],
                        ['st.user_id', '=', Auth::user()->id],
                    ])->exists();
                $activity->respondido = ($respondida ? 1 : 0);
            }

        }

        session(["content_id" => $content_id]);
        $disciplina = session()->get("disciplina");
        $rota = route("student.conteudos") . "?id=" . $disciplina;
        return view('student.ar', compact('activities', 'rota',));
    }
    
    /**
     * Entra na página de questionario de uma atividade
     */
    public function questoes(Request $request)
    {

        // buscar da tabela student_answers uma questao respondida da activity_id, question_id, user_id
        $activity_id = $request->id;

        // $respondida = DB::table('student_answers as st')
        //     ->join('questions as q', 'st.question_id', '=', 'q.id')
        //     ->where([
        //         ['st.activity_id', '=', $activity_id],
        //         ['st.user_id', '=', Auth::user()->id]
        //     ])->exists();

        $where = DB::table('questions')
            ->where("activity_id", $activity_id)->addSelect([
                'alternative_answered' => DB::table('student_answers')
                    ->select('student_answers.alternative_answered')
                    ->whereColumn('student_answers.question_id', '=', 'questions.id')
                    ->whereColumn('student_answers.activity_id', '=', 'questions.activity_id')
                    ->where('student_answers.user_id', '=', Auth::user()->id)
            ]);
        $questions = $where->get();

        foreach ($questions as $item) {
            $options = [$item->a, $item->b, $item->c, $item->d];
            shuffle($options);
            $item->options = $options;
        }
        session()->put('questoes', $questions);
        $respondida =$this->respondida();

        $rota = route("student.showActivity") ;

        return view('student.atividadeAr', compact('questions', 'respondida', 'rota'));
    }

    /**
     * Botão salvar as respostas do questionario
     */
    public function store(Request $request)
    {
        /* 
            precisa verificar se a questão já foi respondida...
           isso pode acontecer quando o mesmo usuário se loga simultaneamente em mais equipamentos,
           e entra na atividade. 
        */ 
        // dd("Salvando");

        DB::beginTransaction();

        $questions = session()->get('questoes');
        $questoes = [];

        foreach ($questions as $q)
        {
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
                
                try{
                    $respop = $datareq["questao" . $questao->id];
                    $data['question_id'] = $questao->id;
                    $data['user_id'] =  Auth::user()->id;
                    $data['activity_id'] =  $questao->activity_id;
                    $opcao = $questao->options[$respop];
                    //dd($respop . " - " . $opcao . " - " . $questao->a . " - " . implode(" ; ", $questao->options));
                    
                    $data['alternative_answered'] =  $opcao; // havia um erro na estrutura do banco que esse campo era de somente 1!!!
                    // $s = $s . " " . $opcao . "-" .  $questao->a . " <br/> ";

                    if ($opcao == $questao->a) {
                        $data['correct'] = true;
                    } else {
                        $data['correct'] = false;
                    }
                    
                    //dd($data);
                    // gravar no banco uma linha da resposta
                    StudentAnswer::create($data);

                    }catch(Exception $e){
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

        return redirect('/students/activity');
    }

    private function respondida() {
        $questions= session()->get('questoes');

        $reposta=0;
        // vou fazer o sistema de resposta assim: 
        // 1- retorna que o questionário foi respondido completo
        // -1 -retorna que o questionário não foi respondido completo
        // 0 - retorna que o questionário não foi respondido 
        // dd($questions);
        $qntQuestoes = count($questions);
        $qntRespondidas = 0;
        foreach($questions as $question){
            if($question->alternative_answered != null){
                $qntRespondidas +=1 ;
            }
        }

        if($qntRespondidas == $qntQuestoes){
            $reposta = 1;
        }else{
            if($qntRespondidas == 0){
                $reposta = 0;
            }else{
                $reposta = -1;
            }
        }

        return $reposta;

    }

    // Também acredito nao estar mais sendo utilizada
    public function alternatives(Request $request)
    {

        // $letter = $request->all();
        // dd($letter);

        // $alternatives = DB::table('questions') 
        //     ->where("a", $letter)
        //     ->get();
        return view('student.atividadeAr', compact('alternatives'));
    }

    // Excluir.... provavelmente nao mais utilizadas

    public function novasAtividades()
    {
        $user_id = auth()->user()->id;
        $activities = Activity::all();
        $student_answers = StudentAnswer::all()->where('user_id', $user_id);

        return view('student.novas', compact('activities', 'student_answers', 'user_id'));
    }

    public function atividadesRealizadas()
{
    $user_id = auth()->user()->id;
    $conteudos = Content::all(); // Ou a lógica que você usa para recuperar os conteúdos
    $conteudosRespondidos = [];

    

    // Verifica para cada conteúdo se o aluno completou
    foreach ($conteudos as $item) {
        $conteudosRespondidos[$item->id] = Conteudo::conteudoCompleto($item->id, $user_id);
    }

    dd($conteudosRespondidos);

    return view('student.realizadas', compact('conteudos', 'conteudosRespondidos'));
}



}

