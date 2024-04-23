<?php

namespace App\DAO;

use App\Models\AnoLetivo;
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

        $completo= $resultadoCompleto->QntCompletaram;
        $incompleto= $resultadoIncompleto->QntNCompletaram;
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
}
