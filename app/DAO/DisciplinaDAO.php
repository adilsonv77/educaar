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

    public function getDisciplinasDoProfessor($professorId)
    {
        return DB::table('turmas_disciplinas')
            ->join('disciplinas', 'turmas_disciplinas.disciplina_id', '=', 'disciplinas.id')
            ->where('turmas_disciplinas.professor_id', $professorId)
            ->select('disciplinas.id', 'disciplinas.name')
            ->distinct()
            ->get();
    }

    public function updateDisciplinaScene($sceneId, $disciplinaId)
    {
        return DB::table('scenes')
            ->where('id', $sceneId)
            ->update(['disciplina_id' => $disciplinaId]);
    }
}