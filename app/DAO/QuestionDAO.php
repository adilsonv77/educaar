<?php

namespace App\DAO;

use App\Models\Turma;

use App\Models\AnoLetivo;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuestionDAO
{
    public static function getByActivityId($id){
        return DB::table('questions')
                    ->where("activity_id", $id)->get();
    }

    public static function getQuestesRespondidasPorAluno($turmaid, $contentid, $alunoId)
    {
        $sql = DB::table('questions as q')
            ->leftJoin('student_answers as sta', function ($join) use ($alunoId) {
                $join->on('sta.question_id', '=', 'q.id')
                    ->where('sta.user_id', '=', $alunoId);
            })
            ->select(
                'q.id as question_id',
                DB::raw('CASE WHEN sta.id IS NOT NULL THEN 1 ELSE 0 END as respondida')
            )
            ->where('q.activity_id', $turmaid) // Use $turmaid aqui
            ->where('q.content_id', $contentid) // Use $contentid se for relevante
            ->get();

        return $sql;
    }


    public static function todasQuestoesRespondidas($activityId, $alunoId)
    {
        // Conta o total de questões na atividade
        $totalQuestoes = DB::table('questions')
            ->where('activity_id', $activityId)
            ->count();

        // Conta quantas questões o aluno respondeu
        $respondidas = DB::table('student_answers as sa')
            ->join('questions as q', 'sa.question_id', '=', 'q.id')
            ->where('q.activity_id', $activityId)
            ->where('sa.user_id', $alunoId)
            ->distinct('sa.question_id') // Garante que uma resposta duplicada não conte duas vezes
            ->count('sa.question_id');

        // Verifica se todas as questões foram respondidas
        return $respondidas === $totalQuestoes;
    }

    public static function conteudoCompleto($contentId, $alunoId)
    {
        $totalQuestoes = DB::table('questions as q')
            ->join('activities as a', 'q.activity_id', '=', 'a.id')
            ->where('a.content_id', $contentId)
            ->count();

        $respondidas = DB::table('student_answers as sa')
            ->join('questions as q', 'sa.question_id', '=', 'q.id')
            ->join('activities as a', 'q.activity_id', '=', 'a.id')
            ->where('a.content_id', $contentId)
            ->where('sa.user_id', $alunoId)
            ->distinct('sa.question_id')
            ->count('sa.question_id');

        dd($totalQuestoes, $respondidas);

        Log::info("Total de questões: $totalQuestoes, Questões respondidas: $respondidas");

        return $respondidas === $totalQuestoes;
    }

    /**
     * Retorna se o aluno autenticado já respondeu alguma questão da atividade correspondente
    */
    public static function jaRespondeu($activity_id) {
        $user_id = Auth::id();

        return DB::table('student_answers')
            ->where('user_id', $user_id)
            ->where('activity_id', $activity_id)
            ->exists();
    }

    /**
     * Retorna se a atividade pode ser refeita ou não 
    */
    public static function refeita($activity_id) {
        return DB::table('activities')
            ->where('id', $activity_id)
            ->value('refeita');
    }

    /**
     * Retorna a tentativa atual do usuário autenticado (Última tentativa + 1).
    */
    public static function getTentativa() {
        $tentativa = 0;
        if(QuestionDAO::jaRespondeu((int)session()->get('livewire_activity_id'))) {
            $tentativa = DB::table('student_answers as sa')
                ->select('sa.tentativas')
                ->where('sa.activity_id', (int)session()->get('livewire_activity_id'))
                ->where('sa.user_id', Auth::id())
                ->orderBy('sa.created_at', 'desc')
                ->value('tentativas');
        }
        return ++$tentativa;
    }

    public static function getTextoQuestao($question_id) {
        return DB::table('questions')
            ->where('id', $question_id)
            ->value('question');
    }

}