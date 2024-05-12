<?php

namespace App\DAO;

use App\Models\AnoLetivo;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResultContentDAO
{
    protected $totalQuestions;
    protected $alunos_fizeram;
    protected $alunos_fizeram_completo= array();
    protected $alunos_fizeram_incompleto= array();
    protected $alunos_nao_fizeram;
    protected $question_base;


    public static function buscarQntFizeramAsTarefas($content_id, $turma_id)
    {
        session()->put('turma_id', $turma_id);
        session()->put('content_id', $content_id);
        $totalAlunos = DB::table('alunos_turmas')->where('turma_id', $turma_id)->count();
 
            // SELECT sub1.Conteudo as Conteudo, COUNT(sub2.user_id) AS QntCompletaram from (SELECT aq.content_id as Conteudo, SUM(QntQuestions) as QntQuestions FROM 
            // vw_Activities_qntQuestions as aq
            // group by Conteudo) sub1
            // JOIN (SELECT user_id, name, sum(QntRespondida) AS QntRespondida, content_id FROM `vw_aluno_QntResposta` WHERE content_id= 9 and turma_id=5
            // GROUP BY user_id, name
            // ) AS sub2
            // ON sub1.Conteudo= sub2.content_id
            // WHERE sub2.QntRespondida = sub1.QntQuestions 
            // GROUP BY Conteudo

        $resultadoCompleto = DB::table(DB::raw('(SELECT aq.content_id as Conteudo, SUM(QntQuestions) as QntQuestions FROM vw_Activities_qntQuestions as aq group by Conteudo) sub1'))
        ->select('sub1.Conteudo as Conteudo', DB::raw('COUNT(sub2.user_id) AS QntCompletaram'))
        ->join(DB::raw("(SELECT user_id, name, sum(QntRespondida) AS QntRespondida, content_id FROM vw_aluno_QntResposta WHERE content_id= $content_id and turma_id=$turma_id GROUP BY user_id, name) AS sub2"), function ($join) {
            $join->on('sub1.Conteudo', '=', 'sub2.content_id');
        })
        ->whereRaw('sub2.QntRespondida = sub1.QntQuestions')
        ->groupBy('Conteudo')
        ->first();
        

        $resultadoIncompleto=DB::table(DB::raw('(SELECT aq.content_id as Conteudo, SUM(QntQuestions) as QntQuestions FROM vw_Activities_qntQuestions as aq group by Conteudo) sub1'))
        ->select('sub1.Conteudo as Conteudo', DB::raw('COUNT(sub2.user_id) AS QntNCompletaram'))
        ->join(DB::raw("(SELECT user_id, name, sum(QntRespondida) AS QntRespondida, content_id FROM vw_aluno_QntResposta WHERE content_id= $content_id and turma_id=$turma_id GROUP BY user_id, name) AS sub2"), function ($join) {
            $join->on('sub1.Conteudo', '=', 'sub2.content_id');
        })
        ->whereRaw('sub2.QntRespondida < sub1.QntQuestions')
        ->groupBy('Conteudo')
        ->first(); 

        // se todos os alunos completaram entao resultadoIncompleto == null. O mesmo acontece quando nenhum aluno completou com resultadoCompleto
        $completo= ($resultadoCompleto == null?0:$resultadoCompleto->QntCompletaram);
        $incompleto= ($resultadoIncompleto == null?0:$resultadoIncompleto->QntNCompletaram);
        $naoFizeram= $totalAlunos - ($completo + $incompleto);

        $result= [
                'conteudo_completo' => $completo,
                'conteudo_incompleto' => $incompleto,
                'conteudo_nao_fizeram' => $naoFizeram
            ];

        return $result;
    }
    public static function atividadesFeitas($content_id, $turma_id)
    {


//         SELECT aq.activity_id, count(*) as qntFizeram FROM `vw_aluno_QntResposta` as ar
//   JOIN vw_Activities_qntQuestions as aq
//   on aq.activity_id = ar.activity_id
//   where aq.content_id=9
// group by aq.activity_id

        $result = DB::table('vw_aluno_QntResposta as ar')
        ->select('aq.activity_id','aq.name', DB::raw('count(*) as qntFizeram'))
        ->join('vw_Activities_qntQuestions as aq', 'aq.activity_id', '=', 'ar.activity_id')
        ->where([['aq.content_id', $content_id],
                ['ar.turma_id', $turma_id]])
        ->groupBy('aq.activity_id')
        ->get();

        
            return $result;
    }

    public static function getStudentDidActivities(){
        // SELECT sub2.name as nome, sub2.user_id as id from (SELECT aq.content_id as Conteudo, SUM(QntQuestions) as QntQuestions FROM 
        //     vw_Activities_qntQuestions as aq
        //     group by Conteudo) sub1
        //     JOIN (SELECT user_id, name, sum(QntRespondida) AS QntRespondida, content_id FROM `vw_aluno_QntResposta` WHERE content_id= 9 and turma_id=5
        //     GROUP BY user_id, name
        //     ) AS sub2
        //     ON sub1.Conteudo= sub2.content_id
        //     WHERE sub2.QntRespondida = sub1.QntQuestions 
        //     GROUP BY 
        
        $content_id= session()->get('content_id');
        $turma_id= session()->get('turma_id');

        $resultadoCompleto = DB::table(DB::raw('(SELECT aq.content_id as Conteudo, SUM(QntQuestions) as QntQuestions FROM vw_Activities_qntQuestions as aq group by Conteudo) sub1'))
        ->select('sub2.name as nome', 'sub2.user_id as id')
        ->join(DB::raw("(SELECT user_id, name, sum(QntRespondida) AS QntRespondida, content_id FROM vw_aluno_QntResposta WHERE content_id= $content_id and turma_id=$turma_id GROUP BY user_id, name) AS sub2"), function ($join) {
            $join->on('sub1.Conteudo', '=', 'sub2.content_id');
        })
        ->whereRaw('sub2.QntRespondida = sub1.QntQuestions')
        ->groupBy('id','nome')
        ->get();

        return $resultadoCompleto;
    }
    public static function getStudentsUnfinishActivities(){
        $content_id= session()->get('content_id');
        $turma_id= session()->get('turma_id');

        $resultadoIncompleto=DB::table(DB::raw('(SELECT aq.content_id as Conteudo, SUM(QntQuestions) as QntQuestions FROM vw_Activities_qntQuestions as aq group by Conteudo) sub1'))
        ->select('sub2.name as nome', 'sub2.user_id as id')
        ->join(DB::raw("(SELECT user_id, name, sum(QntRespondida) AS QntRespondida, content_id FROM vw_aluno_QntResposta WHERE content_id= $content_id and turma_id=$turma_id GROUP BY user_id, name) AS sub2"), function ($join) {
            $join->on('sub1.Conteudo', '=', 'sub2.content_id');
        })
        ->whereRaw('sub2.QntRespondida < sub1.QntQuestions')
        ->groupBy('id','nome')
        ->get();

        return $resultadoIncompleto;
    }

    public static function getStudentDidNotActivities(){
        
        
        $content_id= session()->get('content_id');


        // SELECT q.id, q.question FROM questions as q 
		// join activities as a
        // on q.activity_id= a.id
        // JOIN contents as c
        // on a.content_id= c.id
        // WHERE c.id=9
        $questao= DB::table('questions as q')
        ->select('q.id as id','q.question as questao')
        ->join('activities as a', 'q.activity_id', '=', 'a.id')
        ->join('contents as c', 'a.content_id', '=', 'c.id')
        ->where('c.id', '=', $content_id)
        ->first();
        
        $turma_id= session()->get('turma_id');
        
        $result = DB::table('users as u')
        ->select('u.id as id','u.name as nome')
        ->join('alunos_turmas as alunt', 'alunt.aluno_id', '=', 'u.id')
        ->where('alunt.turma_id', '=', $turma_id)
        ->whereNotExists(function($query) use ($questao)
        {
            $query->select(DB::raw(1))
                    ->from('student_answers as sta')
                    ->whereRaw('sta.user_id = u.id')
                    ->whereRaw('sta.question_id = '. $questao->id);
        })
            ->get();
    
    
    return $result;
    }
}
