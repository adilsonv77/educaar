<?php

namespace App\Http\Controllers;

use App\DAO\ResultActivityDAO;
use App\Models\Activity;
use App\Models\Turma;
use App\Models\Question;
use App\Models\StudentAnswer;
use App\Models\StudentTimeActivity;
use App\Models\AnoLetivo;
use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $activity;

    public function index(Request $request)
    {
        if (session('type') == 'student') {
            return redirect('/');
        }
        #dd($request->query());

        $data = $request->input('activity');

        session()->put('activity_id', $data);


        $activity = Activity::find($data);

        $aname = mb_substr($activity->name, 0, 30);
        if (strlen($aname) != strlen($activity->name)) {
            $activity->name = $aname . "...";
        } else {
            $activity->name = $aname;
        }

        $questions = DB::table("questions")->where('questions.activity_id', $data)->get();

        $alunosPorQuestao = [];

        foreach ($questions as $question) {
            $alunosPorQuestao[$question->id] = DB::table('student_answers')
                ->where('question_id', $question->id)
                ->distinct('user_id')
                ->count('user_id');
       
        }


        return view('pages.questions.index', compact('questions', 'activity','alunosPorQuestao'));
    }

    public function listOfQuestions()
    {
        if (session('type') == 'student') {
            return redirect('/');
        }
        $data = Question::select('*');
        return datatables()::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        #return var_dump($activity);
        $titulo = 'Criar';
        $acao = 'insert';
        $id = session()->get('activity_id');
        # retornar atividades do professor atual
        // $activities = DB::table("activities")
        //     ->select('activities.*')
        //     ->join("contents", "activities.content_id", "=", "content_id")
        //     ->where('user_id', Auth::user()->id) 
        //     ->distinct()
        //     ->get();
        // <!-- <div class="col-md-6 col-sm-12">
        //         <div class="form-group">
        //             <label for="">Atividade*</label>
        //             <select class="form-control" name="activity_id">
        //                 @foreach ($activities as $item)
        //                     <option value="{{ $item->id }}" @if ($item->id === $activity_id) selected="selected" @endif>{{ $item->name }}</option>
        //                 @endforeach
        //             </select>
        //         </div>  -->
        $answer = 'A';

        $params = [
            'titulo' => $titulo,
            'acao' => $acao,
            'question' => '',
            'id' => 0,
            // 'activities'=> $activities,
            'activity_id' => $id,
            'A' => '',
            'B' => '',
            'C' => '',
            'D' => '',
            'answer' => $answer
        ];


        return view('pages.questions.registerQuestions', $params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $request->all();
        $id = session()->get('activity_id');
        # dd($activity);
        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'A' => 'required',
            'B' => 'required',
            'C' => 'required',
            'D' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        if ($data['acao'] == 'insert') {
            Question::create($data);
        } else {
            $question = Question::find($data['id']);
            // return var_dump($question);
            $question->update($data);
        }




        #return view('pages.questions.index', $id);
        return redirect(route('questions.index', 'activity=' . $id));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {

        $question = Question::find($id);
        $titulo = 'Editar Questoes';
        $acao = 'edit';
        # retornar atividades do professor atual
        //  $activities = DB::table("activities")
        //  ->select('activities.*')
        //  ->join("contents", "activities.content_id", "=", "content_id")
        //  ->where('user_id', Auth::user()->id) 
        //  ->distinct()
        //  ->get();
        $params = [
            'titulo' => $titulo,
            'acao' => $acao,
            'question' => $question->question,
            'id' => $question->id,
            // 'activities'=> $activities,
            'activity_id' => $question->activity_id,
            'A' => $question->a,
            'B' => $question->b,
            'C' => $question->c,
            'D' => $question->d,
            'answer' => $question->answer
        ];




        return view('pages.questions.registerQuestions', $params);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $question = Question::find($id);
        $question->delete();
        $activity_id = session()->get('activity_id');
        return redirect(route('questions.index', ["activity" => $activity_id]));
    }

    public function destroyAnswers($id)
    {
        // Exclui apenas as respostas da questão, sem remover a questão
        StudentAnswer::where('question_id', $id)->delete();

        return redirect()->back()->with('success', 'Respostas excluídas com sucesso.');
    }


    public function results(Request $request)
    {
        $activity = $request->input('activity_id');

        if ($activity) {
            session()->put('activity', $activity);
        }
        $turma_id = $request->input('turma_id');

        $anoletivo = AnoLetivo::where('school_id', Auth::user()->school_id)
            ->where('bool_atual', 1)->first();
        $activity_id = session()->get('activity');

        $where = DB::table('turmas as t')
            ->select('t.id as id', 't.nome as nome')
            ->join('turmas_disciplinas as td', 'td.turma_id', '=', 't.id')
            ->join('turmas_modelos as tm', 't.turma_modelo_id', '=', 'tm.id')
            ->join('contents as c', 'c.turma_modelo_id', '=', 'tm.id')
            ->join('activities as a', 'a.content_id', '=', 'c.id')
            ->where([
                ['td.professor_id', '=', Auth::user()->id],
                ['t.ano_id', '=', $anoletivo->id],
                ['a.id', '=', $activity_id]
            ])
            ->distinct();

       // dd($where->toSql(), Auth::user()->id, $anoletivo->id, $activity_id);


        $turmas = $where->get();

        if ($turma_id) {
            $turma = Turma::find($turma_id);
        } else {
            $turma = $where->first();
            $turma_id = $turma->id;
        }

        $activity = Activity::find($activity_id);

        session()->put('turma_id', $turma_id);
        session()->put('activity_id', $activity->id);

        $result = ResultActivityDAO::buscarQntFizeramATarefas($activity->id, $turma->id);

        session()->put('alunos_fizeram_completo', $result['alunos_fizeram_completo']);
        session()->put('alunos_fizeram_incompleto', $result['alunos_fizeram_incompleto']);
        session()->put('question_base', $result['question_base']);


        $questions = ResultActivityDAO::questoesQntAcertosComNaoRespondidas($activity->id, $turma->id);
        $respostasSelecionadas = ResultActivityDAO::respostasDosAlunos($activity->id, $turma->id);

        foreach ($questions as $question) {
            $question->quntRespondCerto = (int)$question->quntRespondCerto;
        }

        //dd($respostasSelecionadas, $questions);
        return view('pages.activity.results', compact('result', 'questions', 'turmas', 'turma', 'activity', 'respostasSelecionadas'));
    }

    function resultsListStudents($type)
    {

        $activity_id = session()->get('activity');

        $activity = Activity::find($activity_id);

        if ($type == 'Completo') {
            $results = session()->get('alunos_fizeram_completo');
        } elseif ($type == 'Incompleto') {
            $results = session()->get('alunos_fizeram_incompleto');
        } elseif ($type == 'Não fizeram') {

            $turma_id = session()->get('turma_id');
            $questao =  session()->get('question_base');

            $results = ResultActivityDAO::getStudentDidNotQuestions($turma_id, $questao);
        }

        return view('pages.activity.listStudents', compact('results', 'activity', 'type'));
    }
}
