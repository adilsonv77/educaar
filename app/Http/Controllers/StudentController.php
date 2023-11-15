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
use phpDocumentor\Reflection\DocBlock\Tags\See;

class StudentController extends Controller
{


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
        $activities = Activity::all();
        $student_answers = StudentAnswer::all()->where('user_id', $user_id);

        return view('student.realizadas', compact('activities', 'student_answers', 'user_id'));
    }



    public function indexContentStudent(Request $request)
    {

        session()->put('disciplina', $request->id);
        $id = session()->get("disciplina");

        $anoAtual = AnoLetivo::where('school_id', Auth::user()->school_id)
            ->where('bool_atual', 1)->first();

        $conteudos = DB::table('alunos_turmas as at')
            ->select('c.name', 'c.id')
            ->join('turmas as t', 't.id', '=', 'at.turma_id')
            ->join('disciplinas_turmas_modelos as dtm', 'dtm.turma_modelo_id', '=', 't.turma_modelo_id')
            ->join('contents as c', function (JoinClause $join) {
                $join->on('dtm.turma_modelo_id', '=', 'c.turma_id')->on('c.disciplina_id', '=', 'dtm.disciplina_id');
            })
            ->where([
                ['at.aluno_id', '=', Auth::user()->id],
                ['t.ano_id', '=', $anoAtual->id],
                ['c.disciplina_id', '=', $id],
                ['c.fechado', '=', 1]
            ])
            ->distinct()
            ->get();

        return view('student.indexContentStudent', compact('conteudos'));
    }
    public function showActivity(Request $request)
    {
        //$request->session()->reflash();
        $data = $request->all();
        $content_id = $request->id;
        if ($content_id == null) {
            $content_id = session()->get("content_id");
        }
        $activities = DB::table('activities')
            ->where("content_id", $content_id)
            ->orderBy("id")
            ->get();

        // verificar quais atividades já foram respondidas        
        foreach ($activities as $activity) {
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

        session(["content_id" => $content_id]);
        $disciplina = session()->get("disciplina");

        return view('student.ar', compact('activities', 'disciplina'));
    }

    public function store(Request $request)
    {

        // dd($request->all());
        if ($request->get("return") == null) {
            $questions = session()->get('questoes');
            //  dd($questions);
            $datareq = $request->all();
            // dd($datareq);
            // $s  = "";
            foreach ($questions as $questao) {
                $data = ['question_id', 'user_id', 'alternative_answered', 'correct'];
                
                try{
                $respop = $datareq["questao" . $questao->id];
                $data['question_id'] = $questao->id;
                $data['user_id'] =  Auth::user()->id;
                $data['activity_id'] =  $questao->activity_id;
                $opcao = $questao->options[$respop];

                $data['alternative_answered'] =  $opcao;
                // $s = $s . " " . $opcao . "-" .  $questao->a . " <br/> ";

                if ($opcao == $questao->a) {
                    $data['correct'] = true;
                } else {
                    $data['correct'] = false;
                }
                // dd($data);
                // gravar no banco uma linha da resposta
                StudentAnswer::create($data);

                }catch(Exception $e){
                    continue;
                }
                

                // $opcao == $questao->a
            }
        }


        //dd($opcao == $datasess[0]->a);

        // session()->get("");
        // session()->forget("");
        // session()->flush(); // remover tudo !!! - perigoso. nunca usar
        // $ = $request->all();
        // $id = session()->get('activity_id');
        // # dd($activity);
        // #return view('pages.questions.index', $id);

        return redirect('/students/activity');
    }

    public function questoes(Request $request)
    {

        // buscar da tabela student_answers uma questao respondida da activity_id, question_id, user_id

        $activity_id = $request->id;

        $respondida =$this->respondida();
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

        // dd($questions);

        foreach ($questions as $item) {
            $options = [$item->a, $item->b, $item->c, $item->d];
            shuffle($options);
            $item->options = $options;
        }
        session()->put('questoes', $questions);

        // dd($questions);

        return view('student.atividadeAr', compact('questions', 'respondida'));
    }

    protected function respondida(){
        $questions= session()->get('questoes');

        $reposta=0;
        // vou fazer o sistema de resposta assim: 
        // 1- retorna que o questionário foi respondido completo
        // -1 -retorna que o questionário não foi respondido completo
        // 0 - retorna que o questionário não foi respondido 
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

    public function alternatives(Request $request)
    {

        // $letter = $request->all();
        // dd($letter);

        // $alternatives = DB::table('questions')
        //     ->where("a", $letter)
        //     ->get();
        return view('student.atividadeAr', compact('alternatives'));
    }
}
