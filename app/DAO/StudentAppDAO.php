<?php

namespace App\DAO;


use Illuminate\Support\Facades\DB;

class StudentAppDAO
{
    public static function buscarDataCorte($activity_id, $aluno_id, $ano_id) {
        /*
SELECT pc.* FROM activities a
join contents c on c.id = a.content_id
join alunos_turmas atu on c.turma_modelo_id = atu.turma_id
join turmas t on t.id = atu.turma_id
join prof_config pc on pc.anoletivo_id = t.ano_id and pc.disciplina_id = c.disciplina_id and pc.turma_id = atu.turma_id
where a.id = 7 and atu.aluno_id = 261 and t.ano_id = 4

AQUI AINDA EXISTE UM ERRO NESSE JOIN alunos_turmas atu on c.turma_modelo_id = atu.turma_id

*/
        $sql = DB::table('activities as a')
            ->select("pc.dt_corte")
            ->join("contents as c", "c.id", "=", "a.content_id")
            ->join("alunos_turmas as atu", "c.turma_modelo_id", "=", "atu.turma_id")
            ->join("turmas as t", "t.id", "=", "atu.turma_id")
            ->join("prof_config as pc", function ($join) {
                $join->on('pc.anoletivo_id', '=', 't.ano_id')
                    ->on('pc.disciplina_id', '=', 'c.disciplina_id')
                    ->on('pc.turma_id', '=', 'atu.turma_id');
                })
            ->where("a.id", "=",  $activity_id)
            ->where("atu.aluno_id", "=",  $aluno_id)
            ->where("t.ano_id", "=",  $ano_id);
        return $sql;
    }
}

?>
