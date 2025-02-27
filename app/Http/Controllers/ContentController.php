<?php
namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Turma;
use App\Models\Content;
use App\Models\Activity;
use App\Models\AnoLetivo;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\DAO\ContentDAO;
use App\DAO\ResultContentDAO;
use App\DAO\UserDAO;
use App\DAO\TurmaDAO;

class ContentController extends Controller
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

        $where = null;
        if (session('type') == 'admin') {
            $where = DB::table('contents')
                ->join('disciplinas', 'disciplinas.id', '=', 'contents.disciplina_id')
                ->join('turmas_modelos', 'turmas_modelos.id', '=', 'contents.turma_modelo_id')
                ->where('turmas_modelos.school_id', '=', Auth::user()->school_id);
        } else {
            if (session('type') == 'teacher') {
                $anoletivoAtual = AnoLetivo::where('school_id', Auth::user()->school_id)
                    ->where('bool_atual', 1)->first();
                $anoletivo_id = $anoletivoAtual->id;
                $where = ContentDAO::buscarContentsDoProf(Auth::user()->id, $anoletivo_id);
            } else {
                $where = ContentDAO::buscarConteudosDeveloper(Auth::user()->id);
            }
        }

        $where = $where->select(
            'contents.id as id',
            'contents.name as content_name',
            'disciplinas.name as disc_name',
            'turmas_modelos.serie as turma_name',
            'contents.fechado',
            DB::raw('concat(contents.name, " - ", disciplinas.name, " (" , turmas_modelos.serie, ")") AS pesq_name')
        );


        $where = $where->addSelect([
            'qtasatividades' => Activity::selectRaw('count(*)')
                ->whereColumn('contents.id', '=', 'content_id')
        ]);
        $where = $where->addSelect([
            'qtasQuestoes' => Question::selectRaw('count(*)')
                ->join("activities as a", "a.id", "=", "activity_id")
                ->join("contents as c", "c.id", "=", "content_id")
                ->whereColumn('contents.id', '=', 'content_id')
        ]);
        $content = $request->titulo;

        if ($content) {
            $r = '%' . $content . '%';
            $where = $where->where(DB::raw('concat(contents.name, " - ", disciplinas.name, " (" , turmas_modelos.serie, ")")'), 'like', $r);
        }

        $contents = $where->paginate(20);
        //$turmasAlunos = UserDAO::quantosAlunosPorTurma(Auth::user()->id)->get();

        return view('pages.content.index', compact('contents', 'content'));
    }

    public function listOfContents()
    {
        if (session('type') == 'student') {
            return redirect('/');
        }
        $data = Content::select('*');
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
        if (session('type') == 'student') {
            return redirect('/');
        }
        $idprof = Auth::user()->id;
  
        $anoLetivo = AnoLetivo::where('bool_atual', 1)
                        ->where("school_id", Auth::user()->school_id)
                        ->first();

        //var_dump($anoLetivo->id);
        $titulo = 'Adicionar Conteúdo';
        $acao = 'insert';

        $params = [
            'titulo' => $titulo,
            'acao' => $acao,
            'name' => '',
            'id' => 0
        ];
        if (session('type') == 'teacher') {
            $disciplinas = ContentDAO::buscarDisciplinas($anoLetivo->id, $idprof);
            $params['disciplinas'] = $disciplinas;
            $params['disciplina'] = "";
        } else {
            $params['turma'] = "";
            $params['disciplina'] = "";
        }

        return view('pages.content.create', $params);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        if (session('type') == 'teacher') {
            $turma_disc = explode("_", $data['disciplina_id']);

            $data['turma_modelo_id'] = $turma_disc[0];
            $data['disciplina_id'] = $turma_disc[1];
        } else {
            $data['turma_modelo_id'] = $data['turma'];
            $data['disciplina_id'] = $data['disciplina'];
        }

        $data['fechado'] = 0;

        if ($data['acao'] == 'insert') {
            // usuário que cadastrou
            $data['user_id'] = Auth::user()->id;

            Content::create($data);
        } else {

            $content = Content::find($data['id']);
            $content->update($data);

        }

        return redirect('/content');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {

        $content = Content::find($id);
        $titulo = 'Editar Conteúdo';
        $acao = 'edit';
        $idprof = Auth::user()->id;
        $anoLetivo = AnoLetivo::where('bool_atual', 1)->first(); // errado

        $params = [
            'titulo' => $titulo,
            'acao' => $acao,
            'id' => $content->id,
            'name' => $content->name,
            'user_id' => $content->user_id
        ];

        if (session('type') == 'teacher') {
            $disciplinas = ContentDAO::buscarDisciplinas($anoLetivo->id, $idprof);
            $params['disciplinas'] = $disciplinas;
            $params['disciplina'] = $content->turma_modelo_id . "_" . $content->disciplina_id;
        } else {
            $params['turma'] = $content->turma_modelo_id;
            $params['disciplina'] = $content->disciplina_id;
        }


        return view('pages.content.create', $params);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (session('type') == 'student') {
            return redirect('/');
        }

        $content = Content::find($id);
        $content->delete();
        return redirect(route('content.index'));
    }

    private $atividade_completa = 0;
    private $atividade_incompleta = 0;
    private $atividade_nao_fizeram = 0;
    public $listaalunos = [];

    private function finalizarAtividade($temrespondida, $temnaorespondida, $idaluno)
    {

        if (array_key_exists($idaluno, $this->listaalunos)) {
            $aluno = $this->listaalunos[$idaluno];

        } else {
            $aluno = [
                'incompleto' => 0,
                'nao_fez' => 0,
                'fez' => 0
            ];

            $this->listaalunos[$idaluno] = $aluno;
        }

        if ($temrespondida > 0) {
            if ($temnaorespondida > 0) {
                $this->atividade_incompleta = $this->atividade_incompleta + 1;
                $aluno['incompleto'] = 1;
            } else {
                $this->atividade_completa = $this->atividade_completa + 1;
                $aluno['fez'] = 1;
            }
        } else {
            $this->atividade_nao_fizeram = $this->atividade_nao_fizeram + 1;
            $aluno['nao_fez'] = 1;
        }

        $aluno['id'] = $idaluno;
        $this->listaalunos[$idaluno] = $aluno;

    }

    public function resultsContents(Request $request)
    {
        $content = $request->input('content_id');

        if ($content) {
            session()->put('content', $content);
        }
        $turma_id = $request->input('turma_id');

        $anoletivo = AnoLetivo::where('school_id', Auth::user()->school_id)
            ->where('bool_atual', 1)->first();

        $content_id = session()->get('content');
        $prof_id = Auth::user()->id;

        // Busca as turmas do conteúdo para o professor
        $where = ContentDAO::buscarTurmasDoContentsDoProf($prof_id, $anoletivo->id, $content_id);
        $turmas = $where->get();

        if ($turma_id) {
            $turma = Turma::find($turma_id);
        } else {
            $turma = $where->first();
            $turma_id = $turma->id;
        }

        session()->put('turma_id', $turma_id);

        // Busca conteúdos específicos
        $content = Content::find($content_id);
        $activities = Activity::where('content_id', $content_id)->get();

        // Busca as questões para o conteúdo
        $questions = ContentDAO::buscarQuestoesPorConteudo($content_id, $turma_id);

        // Consulta para obter status das respostas das questões
        $resultsSQL = ResultContentDAO::getStatusQuestionPorConteudo($turma_id, $content_id);

        $this->listaalunos = [];

        // Variáveis de controle para atividades
        $this->atividade_completa = 0;
        $this->atividade_incompleta = 0;
        $this->atividade_nao_fizeram = 0;

        $results = [];

        $idaluno = 0;
        $idatividade = 0;
        $newd = null;
        $temrespondida = 0;
        $temnaorespondida = 0;

        // Processa as atividades e alunos
        foreach ($resultsSQL as $r) {
            if ($r->activity_id !== $idatividade) {
                if ($newd != null) {
                    $this->finalizarAtividade($temrespondida, $temnaorespondida, $idaluno);

                    $newd['atividade_completa'] = $this->atividade_completa;
                    $newd['atividade_incompleta'] = $this->atividade_incompleta;
                    $newd['atividade_nao_fizeram'] = $this->atividade_nao_fizeram;

                    array_push($results, $newd);
                }

                $newd = [
                    'nome' => $r->activity_name,
                    'questions' => []
                ];

                // Obtém as questões da atividade
                $questions = ResultContentDAO::getQuestoesAtividade($r->activity_id); 
                foreach ($questions as $question) {
                    // Conta as respostas para cada alternativa
                    $alternatives_count = [
                        'A' => 0,
                        'B' => 0,
                        'C' => 0,
                        'D' => 0
                    ];

                    $respostas_count = ResultContentDAO::getCountRespostas($question->id);
                   
                    $correct_alternative = null;

                    foreach ($respostas_count as $response) {
                        if (isset($response->alternative_answered)) {
                            // Converte a alternativa para maiúscula para garantir consistência na comparação
                            $alternative_answered = strtoupper($response->alternative_answered);

                            // Mapeia a alternativa para a chave correta
                            $alternative_key = null;
                            switch ($alternative_answered) {
                                case strtoupper($question->a):
                                    $alternative_key = 'A';
                                    break;
                                case strtoupper($question->b):
                                    $alternative_key = 'B';
                                    break;
                                case strtoupper($question->c):
                                    $alternative_key = 'C';
                                    break;
                                case strtoupper($question->d):
                                    $alternative_key = 'D';
                                    break;
                            }

                            // Se a chave da alternativa foi encontrada, incrementa o contador
                            if ($alternative_key !== null) {
                                $alternatives_count[$alternative_key] += $response->respostas_count;
                            }
                        }

                        // Atribui a alternativa correta
                        $correct_alternative = $response->answer;
                    }

                    // Adiciona a questão ao array de resultados da atividade
                    $newd['questions'][] = [
                        'id' => $question->id,
                        'question' => $question->question,
                        'alternatives_count' => $alternatives_count,
                        'correct_alternative' => $correct_alternative,
                        'alternatives' => [
                            'A' => $question->a, // Texto da alternativa A
                            'B' => $question->b, // Texto da alternativa B
                            'C' => $question->c, // Texto da alternativa C
                            'D' => $question->d, // Texto da alternativa D
                        ]
                    ];

                }

                

                // Zera os contadores para a próxima atividade
                $idaluno = 0;
                $this->atividade_completa = 0;
                $this->atividade_incompleta = 0;
                $this->atividade_nao_fizeram = 0;
                $idatividade = $r->activity_id; // Atualiza o ID da atividade
            }

            // Gerenciamento de alunos
            if ($r->aluno !== $idaluno) {
                if ($idaluno !== 0) {
                    $this->finalizarAtividade($temrespondida, $temnaorespondida, $idaluno);
                }

                $temrespondida = 0;
                $temnaorespondida = 0;
                $idaluno = $r->aluno;
            }

            if ($r->correct === 1 || $r->correct === 0) {
                $temrespondida = 1;
            } else {
                $temnaorespondida = 1;
            }
        }

        // Finaliza a última atividade, se necessário
        if ($idaluno !== 0) {
            $this->finalizarAtividade($temrespondida, $temnaorespondida, $idaluno);

            $newd['atividade_completa'] = $this->atividade_completa;
            $newd['atividade_incompleta'] = $this->atividade_incompleta;
            $newd['atividade_nao_fizeram'] = $this->atividade_nao_fizeram;

            array_push($results, $newd);
        }

        // Totais para exibição no relatório
        $totais = [
            'qtos_fizeram' => 0,
            'qtos_nao_fizeram' => 0,
            'qtos_incompletos' => 0
        ];
        foreach ($this->listaalunos as $a) {
            if ($a['nao_fez'] === 1 && $a['fez'] === 0 && $a['incompleto'] === 0) {
                $totais['qtos_nao_fizeram'] += 1;
            } else {
                if ($a['fez'] === 1 && $a['nao_fez'] === 0 && $a['incompleto'] === 0) {
                    $totais['qtos_fizeram'] += 1;
                } else {
                    $totais['qtos_incompletos'] += 1;
                }
            }
        }

        session()->put("listaalunos", $this->listaalunos);

        //dd($results);
        return view('pages.content.results', compact('results', 'totais', 'turmas', 'turma', 'content', 'activities'));
    }



    function resultsListStudents($type)
    {

        $listaalunos = session()->get("listaalunos");

        $tipoalunos = [];

        foreach ($listaalunos as $a) {
            if ($type == 'Completo') {
                if ($a['fez'] === 1 && $a['nao_fez'] === 0 && $a['incompleto'] === 0)
                    array_push($tipoalunos, $a['id']);
            } elseif ($type == 'Incompleto') {
                if ($a['incompleto'] === 1 || ($a['nao_fez'] === 1 && $a['fez'] === 1))
                    array_push($tipoalunos, $a['id']);
            } elseif ($type == 'Não fizeram') {
                if ($a['nao_fez'] === 1 && $a['fez'] === 0 && $a['incompleto'] === 0)
                    array_push($tipoalunos, $a['id']);
            }
        }

        $results = UserDAO::buscarAlunos($tipoalunos);

        $id = session()->get('content');
        $content = Content::find($id);

        return view('pages.content.listStudents', compact('results', 'content', 'type'));

    }
}