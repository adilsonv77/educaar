<?php

namespace App\DAO;

use Illuminate\Support\Facades\DB;

class DisciplinaDAO
{
    public static function buscarDisciplinasAluno($alunoid, $anoletivoid)
    {
        $sql = DB::table('disciplinas as d')
            ->select('d.id', 'd.name')
            ->join('turmas_disciplinas as td', 'd.id', '=', 'td.disciplina_id')
            ->join('turmas as t', 'td.turma_id', '=', 't.id')
            ->join('alunos_turmas as at', 't.id', '=', 'at.turma_id')
            ->where('at.aluno_id', $alunoid)
            ->where('t.ano_id', $anoletivoid)
            ->distinct();

        return $sql;

    }
}