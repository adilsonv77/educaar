<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\StudentAnswer;
use Illuminate\Support\Facades\DB;
use App\Models\AnoLetivo;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Auth;

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

        $id = $request->id;
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
        $content_id = $request->id;
        $activities = DB::table('activities')
            ->where("content_id", $content_id)
            ->orderBy("id")
            ->get();

        //return view('student.questionAnswer', compact('activities'));

        return view('student.ar', compact('activities', 'content_id'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $datasess = session()->get('questoes');

        $datareq = $request->all();

        $s  = "";
        foreach ($datasess as $questao) {

            $respop = $datareq["questao" . $questao->id];


            $opcao = $questao->options[$respop];
            $s = $s . " " . $opcao . "-" .  $questao->a . " <br/> ";
            // gravar no banco uma linha da resposta
            StudentAnswer::create(['question_id' => $questao->id, 'user_id' => Auth::user()->id, 'alternative_answered' => $opcao, 'correct' => $questao->a]);


            // $opcao == $questao->a
        }
        dd($s);


        //dd($opcao == $datasess[0]->a);

        // session()->get("");
        // session()->remove("");
        // session()->remove()->all();
        // $ = $request->all();
        // $id = session()->get('activity_id');
        // # dd($activity);
        // #return view('pages.questions.index', $id);
        // return redirect(route('questions.index', 'activity=' . $id));


    }





    public function questoes(Request $request)
    {
        $activity_id = $request->id;
        $questions = DB::table('questions')
            ->where("activity_id", $activity_id)
            ->get();

        $count = 0;
        foreach ($questions as $item) {
            $options = [$item->a, $item->b, $item->c, $item->d];
            shuffle($options);
            $item->options = $options;
        }
        session()->put('questoes', $questions);


        // dd($questions);

        return view('student.atividadeAr', compact('questions'));
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
