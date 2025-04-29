<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\Activity;
use App\DAO\ContentDAO;

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

        return view('pages.developer.index', compact('activities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (session('type') !== 'developer') {
            return redirect('/');
        }

        $titulo = 'Atividade Nova';
        $acao = 'insert';
        $contents = ContentDAO::buscarConteudosDeveloper(Auth::user()->id);
        
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
        ];

        return view('pages.activity.register', $params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // public function show(){

    // }
    
    public function listDevs(Request $request){

        $data = $request->input('content');

        session()->put('content_id', $data);

        $devs= DB::table('users')->where('type', 'developer')
                    ->select('users.*')
                    ->addSelect(['selected_dev'=> DB::Raw('exists (select * from content_developer 
                    where content_developer.developer_id = users.id
                    and content_developer.content_id = '.$data.') as selected_dev')])
                    ->get();

        
        return view('pages.developer.selectDevelopers', compact('devs'));
    }

    public function store(Request $request)
    {
        $data = $request->all();

        // dd($data);
        
        $content_id = session()->get('content_id');

        $devs= DB::table('users')
                    ->where('type', 'developer')
                    ->get();
        
        
        $content_exists= DB::table('content_developer')
                    ->where('content_id', $content_id)
                    ->exists();

        if($content_exists){
            DB::table('content_developer')
            ->where('content_id', $content_id)
            ->delete();
        }

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

    public function edit(Request $request)
    {
        $id = $request->id;
        $activity = Activity::find($id);
        $titulo = 'Editar Atividades';
        $acao = 'edit';
        $contents = DB::table('contents')
        ->join('disciplinas', 'contents.disciplina_id', '=', 'disciplinas.id')
        ->join('turmas_modelos', 'contents.turma_modelo_id', '=', 'turmas_modelos.id')
        ->join('content_developer', 'content_developer.content_id', '=', 'contents.id')
        ->where('content_developer.developer_id', Auth::user()->id);
    
        $contents = $contents
            ->select ('contents.id as id', 
                    DB::raw('concat(contents.name, " - ", disciplinas.name, " (" , turmas_modelos.serie, ")") AS total_name')
                    )
            ->get();

        if (empty($activity->scene_id))
            $activity->scene_id = "modelo3D";
        $scenes = collect();

        $params = [
            'titulo' => $titulo,
            'acao' => $acao,
            'id' => $activity->id,
            'name' => $activity->name,
            'contents' => $contents,
            'content' => $activity->content_id,
            'scene_id' => $activity->scene_id,
            'scenes' => $scenes
        ];
        return view('pages.activity.register', $params);
    }

    public function destroy($id)
    {
        $content_id= session()->get('content_id');
        $content_developer = DB::table('content_developer')
        ->where([
            ['content_id', '=', $content_id],
            ['developer_id', '=', $id]
        ])->delete();  
        
        return redirect(route('developer.selectDevelopers'));
    }
}
