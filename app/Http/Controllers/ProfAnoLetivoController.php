<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnoLetivo;
use App\Models\Disciplina;
use App\Models\DisciplinaAnoLetivo;
use App\Models\DisciplinaProfessor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfAnoLetivoController extends Controller
{

   public function index()
    {
         
        if(Auth::user()->type != 'admin') {
            return redirect('/');
        }

        $anoletivo = AnoLetivo::where('school_id', Auth::user()->school_id)
            ->where('bool_atual', 1)->first();

        /*
            select d.name, u.name from disciplinas_anos_letivos dal join disciplinas d on (dal.disciplina_id=d.id) 
                left outer join disciplinas_professores dp on(dp.disciplina_id = dal.disciplina_id and dp.ano_letivo_id = dal.anoletivo_id)
                left outer join users u on (u.id = dp.professor_id)
                where anoletivo_id = 6
        */
        $disciplinas = DB::table('disciplinas_anos_letivos')
                ->select('disciplinas.id as d_id', 'disciplinas.name as d_name', 'users.name as u_name')
                ->join('disciplinas', 'disciplinas.id', '=', 'disciplinas_anos_letivos.disciplina_id')
                ->leftJoin('disciplinas_professores', function($join) {
                        $join
                            ->on('disciplinas_anos_letivos.disciplina_id', '=', 'disciplinas_professores.disciplina_id')
                            ->on('disciplinas_anos_letivos.anoletivo_id', '=', 'disciplinas_professores.anoletivo_id')
                            ->where('disciplinas_professores.bool_ativo', 1);
                    })
                ->leftJoin('users', 'users.id', '=', 'disciplinas_professores.professor_id')
                ->where('disciplinas_anos_letivos.anoletivo_id', $anoletivo->id)
                ->where('disciplinas_anos_letivos.bool_ativo', 1)
                ->orderBy('disciplinas.name')->get();
  
        $discanoletivo = array();
        $ultdisc = "";
        $dal = NULL;
        foreach ($disciplinas as $d) 
        {
            if ($ultdisc <> $d->d_name) 
            {
                if ($dal <> NULL)
                {
                    array_push($discanoletivo, $dal);
                }
                $dal = [
                    'id' => $d->d_id,
                    'name' => $d->d_name,
                    'professores' => [$d->u_name]
                ];
                $ultdisc = $d->d_name;

            } else {
                array_push($dal['professores'], ", ".$d->u_name);
            }
        }
        array_push($discanoletivo, $dal);
 
         $params = [
            "titulo" => "Professores do ano letivo ".$anoletivo->name,
            "anoletivo" => $anoletivo,
            "discanoletivo" => $discanoletivo
        ];

        return view('pages.anoletivo.prof', $params);
    }

    public function edit(Request $request, $iddisc)
    {
        $disc = Disciplina::find($iddisc);
        $data = $request->all();

        /*
        select u.id, u.name, dp.professor_id from users u
        left join (disciplinas_professores dp inner join disciplinas_anos_letivos dal on dp.disciplina_id = dal.disciplina_id and dal.anoletivo_id = 1)
           on u.id = dp.professor_id and dp.disciplina_id = 1
        where u.school_id = 1 and u.type = "teacher"
        */
        $dp = DB::table("disciplinas_anos_letivos")
            ->select('disciplinas_professores.professor_id as p_id', 'disciplinas_professores.disciplina_id as d_id')
            ->join("disciplinas_professores", "disciplinas_professores.disciplina_id", "=", "disciplinas_anos_letivos.disciplina_id")
            ->where('disciplinas_anos_letivos.anoletivo_id',  $data['anoletivoid'])
            ->where('disciplinas_professores.disciplina_id', $disc->id)
            ->where('disciplinas_professores.bool_ativo', 1);

        $professores = DB::table('users')
            ->select('users.id as u_id', 'users.name as u_name', 'd_id')
            ->leftJoinSub($dp, 'dp', function($join){
                $join->on('users.id', '=', 'p_id');
            })
           ->where('school_id', Auth::user()->school_id)
           ->where('type', 'teacher')->get();

        // https://www.virtuosoft.eu/code/bootstrap-duallistbox/

        $params = [
            "titulo" => "Professores da disciplina ".$disc->name,
            "professores" => $professores,
            "iddisc" => $iddisc,
            "anoletivoid" => $data['anoletivoid']
         ];
        return view('pages.anoletivo.profEdit', $params);

        // https://codepen.io/jaredbell/pen/OejgMe
    }

    public function store(Request $request)
    {
        $profs = $request->duallistbox_prof;
        if ($profs == null) {
            $profs = [];
        }
     
        // colocar bool_ativo = 0 para todos aqueles que não estão mais na lista
        $discs = DisciplinaProfessor::where('anoletivo_id', $request->anoletivoid)
                                    ->where('disciplina_id', $request->iddisc)
                                    ->get();
 
        // Desabilitar aqueles professores que não estão na lista
        foreach ($discs as $disc) {
            if (array_search($disc->professor_id, $profs) === false) {
                $disc->bool_ativo = false;
                $disc->save();
            }
        }
        
        // Habilitar os professores que estão na lista
        foreach ($profs as $prof) {
            $prof_disc_anoletivo = DisciplinaProfessor::firstOrNew(['anoletivo_id'  => $request->anoletivoid, 
                                                                       'disciplina_id' => $request->iddisc,
                                                                       'professor_id'  => $prof
                                                                      ]);
           
            $prof_disc_anoletivo->bool_ativo = true;
            $prof_disc_anoletivo->save();
            /*
            $prof_disc_anoletivo = [
                'bool_ativo' => true,
                'professor_id' => $prof,
                'anoletivo_id' => $request->anoletivoid,
                'disciplina_id' => $request->iddisc
            ];

            DisciplinaProfessor::create($prof_disc_anoletivo);
            */
        }

        return redirect('/profanoletivo');
    }
 
}


 