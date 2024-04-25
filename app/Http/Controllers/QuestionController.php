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
        if (Auth::user()->type == 'student') {
            return redirect('/');
        }
        #dd($request->query());

        $data = $request->input('activity');

        session()->put('activity_id', $data);


        $activity = Activity::find($data);

        $aname = mb_substr($activity->name, 0, 10);
        if (strlen($aname) != strlen($activity->name)) {
            $activity->name = $aname . "...";
        } else {
            $activity->name = $aname;
        }




        $questions = DB::table("questions")->where('questions.activity_id', $data)->get();

        return view('pages.questions.index', compact('questions', 'activity'));
    }

    public function listOfQuestions()
    {
        if (Auth::user()->type == 'student') {
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
            'question' => 'required|max:500',
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
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, Question $question)
    // {
    //     //
    // }

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
        return redirect(route('questions.index', $activity_id));
    }


    public function results(Request $request){
        $activity= $request->input('activity_id');

        if($activity){
            session()->put('activity', $activity); 
        }
        $turma_id = $request->input('turma_id');

        $anoletivo = AnoLetivo::where('school_id', Auth::user()->school_id)
        ->where('bool_atual', 1)->first();
        $activity_id = session()->get('activity');

        $where = DB::table('turmas as t')
            ->select('t.id as id','t.nome as nome')
            ->join('turmas_disciplinas as td','td.turma_id', '=', 't.id')
            ->join('turmas_modelos as tm', 't.turma_modelo_id','=','tm.id')
            ->join('contents as c', 'c.turma_id', '=', 'tm.id')
            ->join('activities as a', 'a.content_id', '=', 'c.id')
            ->where([
                ['td.professor_id','=', Auth::user()->id],
                ['t.ano_id','=', $anoletivo->id],
                ['a.id', '=', $activity_id]
            ])
            ->distinct();

            
        if ($turma_id) {
            $turma = Turma::find($turma_id);
            
        } else {
            $turma = $where->first();
        }

        $turmas= $where->get();

        $activity = Activity::find($activity_id);


        $result= ResultActivityDAO::buscarQntFizeramATarefas($activity->id, $turma->id);
        $questions=ResultActivityDAO::questoesQntAcertos($activity->id, $turma->id);

        return view('pages.activity.results', compact('result','questions', 'turmas', 'turma'));
    }

    function resultsListStudents($type){

        return view('pages.content.listStudents');
    }
}
//         SELECT DISTINCT t.id, t.nome FROM `turmas` as t 
// 			INNER JOIN turmas_disciplinas as td 
//             on td.turma_id= t.id
//             INNER JOIN turmas_modelos as tm
//             on t.turma_modelo_id= tm.id
//             inner join contents as c 
//             ON c.turma_id= tm.id
//             INNER join activities as a 
//             on a.content_id= c.id
// WHERE td.professor_id= 4 and t.ano_id= 4 and a.id= 15


// $wrongQuestions = [];
// $correctQuestions = [];
// $numberQuestions = count($request->question);

// $timeLeaveActivity = date('H:i:s');

// $questionTime = date_diff(date_create($timeLeaveActivity), date_create($timeEnterActivity));

// $questionTimeFormated = "{$questionTime->h}:{$questionTime->i}:{$questionTime->s}";

// $studentTimeActivity = new StudentTimeActivity();
// $studentTimeActivity->activity_id = $activityId;
// $studentTimeActivity->user_id = Auth::user()->id;
// $studentTimeActivity->timeEnterActivity = $timeEnterActivity;
// $studentTimeActivity->timeLeaveActivity = $timeLeaveActivity;
// $studentTimeActivity->timeGeneral = $questionTimeFormated;
// $studentTimeActivity->save();

// foreach ($request->question as $key => $question){

//     $studentUser = new StudentAnswer;
//     $studentUser->activity_id = $activityId;
//     $studentUser->user_id = Auth::user()->id;

//     foreach ($question as $j=> $answer){
//         $studentUser->alternative_answered = $answer;
//         $studentUser->question_id = $j;

//         $currentQuestion = Question::find($j);
//         $correctQuestion = $currentQuestion->answer;

//         if($answer == $correctQuestion){
//             $studentUser->correct = true;
//             $studentUser->wrong = false;
//             array_push($correctQuestions, $j);

//         }

//         if($answer !== $correctQuestion){
//             $studentUser->correct = false;
//             $studentUser->wrong = true;
//             array_push($wrongQuestions, $j);
//         }

//     }


//     $studentUser->save();
// }

// $studentGrade = new StudentGrade();
// $studentGrade->correctQuestions = count($correctQuestions);
// $studentGrade->wrongQuestions = count($wrongQuestions);
// $studentGrade->numberQuestions = $numberQuestions;
// $studentGrade->user_id = Auth::user()->id;
// $studentGrade->activity_id = $activityId;
// $studentGrade->save();
