<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Content;
use App\Models\Question;
use App\Models\StudentAccessActivity;
use App\Models\StudentTimeActivity;
use App\Models\StudentAnswer;
use App\Models\AnoLetivo;

use App\Models\User;
use App\Models\StudentGrade;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\DAO\ContentDAO;
use App\DAO\ActivityDAO;
use App\DAO\DisciplinaDAO;
use App\DAO\SceneDAO;

use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (session('type') == 'student') {
            return redirect('/');
        }

        $anoletivoAtual = AnoLetivo::where('school_id', Auth::user()->school_id)
            ->where('bool_atual', 1)->first();
        $anoletivo_id = $anoletivoAtual->id;


       

        $nomesConteudo = ActivityDAO::buscarActivitiesDoProf(Auth::user()->id, $anoletivo_id)
            ->select('contents.name AS nome_conteudo')
            ->distinct()
            ->pluck('nome_conteudo');

        
        $activities = ActivityDAO::buscarActivitiesDoProf(Auth::user()->id, $anoletivo_id)
            ->select(
                'activities.*',
                'contents.name AS nome_conteudo',
                DB::raw('concat(activities.name, " - ", disciplinas.name, " (", contents.name, ")") AS pesq_name')
            );

        $activities = $activities->addSelect(['qtnQuest' => Question::selectRaw('count(*)')->whereColumn('activities.id', '=', 'activity_id')
        ]);

        $act = $request->titulo;
        if ($act) {
            $r = '%' . $act . '%';
            $activities = $activities->where(DB::raw('concat(activities.name, " - ", disciplinas.name, " (", contents.name, ")") '), 'like', $r);
        }

        $conteudo = $request->conteudo;
        if ($conteudo) {
            $activities = $activities->where('contents.name', $conteudo);
        }

        $activities = $activities->distinct()->paginate(20);

        $activity = $request->titulo;

       



        
        return view('pages.activity.index', compact('activities', 'activity', 'nomesConteudo'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (session('type') !== 'teacher') {
            return redirect('/');
        }
        $titulo = 'Atividade Nova';
        $acao = 'insert';

        $anoletivoAtual = AnoLetivo::where('school_id', Auth::user()->school_id)
            ->where('bool_atual', 1)->first();
        $anoletivo_id = $anoletivoAtual->id;

        $contents = ContentDAO::buscarContentsDoProf(Auth::user()->id, $anoletivo_id);
        $contents = $contents
            ->select(
                'contents.id as id',
                DB::raw('concat(contents.name, " - ", disciplinas.name, " (" , turmas_modelos.serie, ")") AS total_name')
            )
            ->get();
      
        $disciplinas = DisciplinaDAO::getDisciplinasDoProfessor(Auth::user()->id);

        $scenes = collect();

        foreach ($disciplinas as $disciplina) {
            $scenes = $scenes->merge(SceneDAO::getByDisciplinaId($disciplina->id));
        }

        $content = 0;
        $params = [
            'titulo' => $titulo,
            'acao' => $acao,
            'name' => '',
            'id' => 0,
            'contents' => $contents,
            'content' => $content,
            'scenes' => $scenes,
        ];

        return view('pages.activity.register', $params);
    }

    public static function deleteDir($dirPath)
    {
        if (!is_dir($dirPath)) {
            return;
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
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
        $tipoAtividade = $data['sceneType'];

        unset($data['panelId']);
        unset($data['sceneType']);

        //Verifica se é cadastro de glb ou de painel para poder tratar cada cadastro de forma diferente 
        $usarPainel = false;
        if ($tipoAtividade == "Cena") {
            $data['glb'] = '';
            $usarPainel = true;
            $idScene = $data['scene'];
        } else {
            $data['scene_id'] = null;
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'glb' => [Rule::requiredIf($request['acao'] == 'insert' && !$usarPainel), 'extensao_invalida:glb,zip', 'max:40960000'],
            'marcador' => [Rule::requiredIf($request['acao'] == 'insert'), 'extensao_invalida:png,jpeg,jpg']
        ]);

      if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        /*
        Foi alterada a lógica de armazenar marcadores e modelos3d para reduzir o consumo do espaço em disco nas alterações.
        Existem algumas situações que podem levar a um pouco de desperdício:
        + o marcador anterior era de um tipo e o novo é de outro tipo
        + o modelo anterior era de um tipo e o novo é de outro tipo

        Como são situações mais raras, melhorou imenso essa nova solução.
        */

        $baseFileName = time();
        if ($data['acao'] == 'edit') {
            $baseFileName = $data['id'];
        }

        $zipdir = "";
        $achou = "";
        //Se possuir um glb, e o cadastro de GLB for selecionado.
        if (array_key_exists('glb', $data) && !$usarPainel) {
            $filename = time();

            if ($request->glb->getClientOriginalExtension() == "zip") {
                $zipArchive = new \ZipArchive();
                $result = $zipArchive->open($request->glb);
                if ($result === TRUE) {
                    $zipArchive->extractTo(public_path('modelos3d/' . $filename));
                    $zipArchive->close();

                    $files = scandir(public_path('modelos3d/' . $filename));

                    foreach ($files as $file) {
                        if (str_ends_with($file, ".gltf")) {
                            $achou = $file;
                            break;
                        }
                    }

                    $zipdir = $filename;
                    if ($achou == "") {
                        self::deleteDir(public_path('modelos3d/' . $filename));
                        return redirect()->back()->withErrors(['msg' => 'Arquivo GLTF incompatível.']);
                    }

                    // normalmente é scene.gltf mas estou me garantindo para exceções
                    $data['glb'] = $filename . "/" . $achou;

                } else {
                    return redirect()->back()->withErrors(['msg' => 'Problemas ao descompactar o ZIP.']);
                }
            } else {
                $glbFile = $baseFileName . '.' . $request->glb->getClientOriginalExtension();
                $request->glb->move(public_path('modelos3d'), $glbFile);

                $data['glb'] = $glbFile;
            }

        }

        if (array_key_exists('marcador', $data)) {
            $imgFile = $baseFileName . '.' . $request->marcador->getClientOriginalExtension();
            $request->marcador->move(public_path('marcadores'), $imgFile);
            $data['marcador'] = $imgFile;
        }

        if ($data['acao'] == 'insert') {
            //Insere
            $data['professor_id'] = Auth::user()->id;
            $activity = Activity::create($data);

            $data['marcador'] = $activity->id . '.' . $request->marcador->getClientOriginalExtension();
            $public_path = public_path('marcadores');
            rename($public_path . '/' . $imgFile, $public_path . '/' . $data['marcador']);

            $public_path = public_path('modelos3d');

            if ($zipdir === "" && !$usarPainel) {
                //Se for um arquivo .zip e for para usar o glb
                $data['glb'] = $activity->id . '.' . $request->glb->getClientOriginalExtension();
                rename($public_path . '/' . $glbFile, $public_path . '/' . $data['glb']);
            } else if (!$usarPainel) {
                //Se for um arquivo glb e é para usar o glb
                $data['glb'] = $activity->id . "/" . $achou;
                rename($public_path . '/' . $zipdir, $public_path . '/' . $activity->id);
            } else {
                //Se não for para usar um glb, e usar um painel
                $data['scene_id'] = $idScene;
            }
            $activity->update($data);
        } else if (!$usarPainel) {
            //Edita modelo 3D
            $activity = Activity::find($data['id']);

            if ($zipdir !== "") {
                self::deleteDir(public_path('modelos3d/' . $activity->id));
                $data['glb'] = $activity->id . "/" . $achou;
                $public_path = public_path('modelos3d');
                rename($public_path . '/' . $zipdir, $public_path . '/' . $activity->id);
            }

            @unlink(public_path('mind') . "/" . $activity->content_id . ".mind");

            $content = Content::find($activity->content_id);
            $content->update(['fechado' => 0]);

            $activity->update($data);
        } else {
            //Editar
            $activity = Activity::find($data['id']);
            $data['scene_id'] = $idScene;

            //Deleta o arquivo GLB
            if(!empty($activity->glb)){
                unlink(public_path('modelos3d/' . $activity->glb));
            }
            
            @unlink(public_path('mind') . "/" . $activity->content_id . ".mind");

            $content = Content::find($activity->content_id);
            $content->update(['fechado' => 0]);

            $activity->update($data);
        }


        @unlink(public_path('mind') . "/" . $data["content_id"] . ".mind");

        $content = Content::find($data["content_id"]);
        $content->update(['fechado' => 0]);
        if (session('type') == 'teacher') {
            return redirect(route('activity.index'));
        } else {
            return redirect(route('developer.index'));
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $activity = Activity::find($id);

        $params = [
            'activity' => $activity->glb,
            'name' => $activity->name
        ];

        return view('pages.activity.showcontent', $params);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $activity = Activity::find($id);
        $titulo = 'Editar Atividades';
        $acao = 'edit';

        $anoletivoAtual = AnoLetivo::where('school_id', Auth::user()->school_id)
            ->where('bool_atual', 1)->first();
        $anoletivo_id = $anoletivoAtual->id;

        $contents = ContentDAO::buscarContentsDoProf(Auth::user()->id, $anoletivo_id);
        $contents = $contents
            ->select(
                'contents.id as id',
                DB::raw('concat(contents.name, " - ", disciplinas.name, " (" , turmas_modelos.serie, ")") AS total_name')
            )
            ->get();

        
        if (empty($activity->scene_id))
            $activity->scene_id = "modelo3D";

        $disciplinas = DisciplinaDAO::getDisciplinasDoProfessor(Auth::user()->id);
        $scenes = collect();

        foreach ($disciplinas as $disciplina) {
            $scenes = $scenes->merge(SceneDAO::getByDisciplinaId($disciplina->id));
        }

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $activity = Activity::find($id);

        if ($activity != null) {
            @unlink(public_path('marcadores') . "/" . $activity->marcador);

            if (str_contains($activity->glb, "/")) {
                $posbarra = strpos($activity->glb, "/");
                $dirname = substr($activity->glb, 0, $posbarra);
                self::deleteDir(public_path('modelos3d') . "/" . $dirname);

            } else {
                @unlink(public_path('modelos3d') . "/" . $activity->glb);
            }

            $activity->delete();


            @unlink(public_path('mind') . "/" . $activity->content_id . ".mind");

            $content = Content::find($activity->content_id);
            $content->update(['fechado' => 0]);

        }

        return redirect(route('activity.index'));
    }

}
