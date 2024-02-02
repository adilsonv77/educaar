<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class DeveloperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $activities= DB::table('activities')
                    ->join('contents', 'activities.content_id', '=', 'contents.id')
                    ->join('content_developer','content_developer.content_id','=','contents.id')
                    ->where('content_developer.developer_id',Auth::user()->id)
                    ->select('activities.*');


        $activities = $activities->distinct()->paginate(20);

        return view('developer.index', compact('activities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->type !== 'developer') {
            return redirect('/');
        }
        $titulo = 'Atividade Nova';
        $acao = 'insert';
        $Type = Auth::user()->type;
        $contents = DB::table('contents')
            ->join('disciplinas', 'contents.disciplina_id', '=', 'disciplinas.id')
            ->join('turmas_modelos', 'contents.turma_id', '=', 'turmas_modelos.id')
            ->join('content_developer', 'content_developer.content_id', '=', 'contents.id')
            ->where('content_developer.developer_id', Auth::user()->id);
        
            $contents = $contents
            ->select('contents.id as id', 
                    DB::raw('concat(contents.name, " - ", disciplinas.name, " (" , turmas_modelos.serie, ")") AS total_name'))
            ->get();
        $content = 0;
        $params = [
            'titulo' => $titulo,
            'acao' => $acao,
            'name' => '',
            'id' => 0,
            'contents' => $contents,
            'content' => $content,
            'Type' => $Type
        ];

        return view('pages.activity.register', $params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    
    public function listDevs(Request $request){

        $data = $request->input('content');

        session()->put('content_id', $data);

        $devs= DB::table('users')->where('type', 'developer')
                    ->select('users.*')
                    ->addSelect(['selected_dev'=> DB::Raw('exists (select * from content_developer 
                    where content_developer.developer_id = users.id
                    and content_developer.content_id = '.$data.') as selected_dev')])
                    ->get();

        
        return view('developer.selectDevelopers', compact('devs'));
    }

    public function store(Request $request)
    {
        $data = $request->all();

    // dd($data);
        $content_id = session()->get('content_id');

        $devs= DB::table('users')
                    ->where('type', 'developer')
                    ->get();
        
        foreach($devs as $dev){

            try{
            if($data['dev'.$dev->id]==$dev->id){
                DB::table('content_developer')->insert([
                    'content_id' => $content_id,
                    'developer_id' => $dev->id
                ]);
            }
        }catch(Exception $e){
            continue;
        }
        }
        return redirect()->route('content.index');
    }

    public function edit()
    {
        //
    }

    public function destroy()
    {
        //
    }
}
