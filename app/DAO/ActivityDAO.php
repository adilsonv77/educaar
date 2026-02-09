<?php

namespace App\DAO;

use App\Models\AnoLetivo;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActivityDAO
{

    public static function buscarPorPainel($id){
        return Activity::where('mural_id',$id);
    }

    public static function buscarActivitiesDoProf($profid, $anoletivoid)
    {
        /*
        SELECT distinct activities.* FROM activities
            join contents on activities.content_id = contents.id
            join turmas_disciplinas on contents.disciplina_id = turmas_disciplinas.disciplina_id
            join turmas on turmas_disciplinas.turma_id = turmas.id
            join anos_letivos on turmas.ano_id = anos_letivos.id
            where anos_letivos.bool_atual = 1 and turmas_disciplinas.professor_id = 5
    */
    /* mudar para:
       select distinct activities.* from turmas_disciplinas 
                    join turmas on turmas_disciplinas.turma_id = turmas.id
                    join contents on turmas.turma_modelo_id = contents.turma_modelo_id and
                                    turmas_disciplinas.disciplina_id = contents.disciplina_id
                join activities on activities.content_id = contents.id 
                    where turmas_disciplinas.professor_id = 5/209 and ano_id = 4
    */

     $sql = DB::table('turmas_disciplinas')
            
            ->join('turmas','turmas_disciplinas.turma_id','=','turmas.id')
            ->join('contents', function($join) {
                        $join->on('turmas.turma_modelo_id', '=', 'contents.turma_modelo_id');
                        $join->on('turmas_disciplinas.disciplina_id', '=', 'contents.disciplina_id');
                    })
            ->join('activities', 'activities.content_id', '=', 'contents.id')
            ->join('disciplinas', 'disciplinas.id', '=', 'contents.disciplina_id')
            //->join('turmas_modelos', 'turmas_modelos.id', '=', 'contents.turma_modelo_id')
            ->where('turmas_disciplinas.professor_id', '=', $profid)
            ->where("ano_id", "=", $anoletivoid)
            ->distinct();

    /*    
    $sql = DB::table("activities")
            ->join("contents", "activities.content_id", "=", "contents.id")
            ->join('disciplinas', 'disciplinas.id', '=', 'contents.disciplina_id')
            ->join("turmas_disciplinas", "contents.disciplina_id", "=", "turmas_disciplinas.disciplina_id")
            ->join("turmas", "turmas_disciplinas.turma_id", "=", "turmas.id")
            ->join("anos_letivos", "turmas.ano_id", "=", "anos_letivos.id")
            
            ->where('turmas_disciplinas.professor_id', $profid)
            ->where("anos_letivos.bool_atual", 1);
*/

         //dd($sql);
        return $sql;
    }

    public static function buscarActivitiesDoDev($devid)
    {
        $sql = DB::table('activities')
        ->join('contents', 'activities.content_id', '=', 'contents.id')
        ->join('content_developer','content_developer.content_id','=','contents.id')
        ->where('content_developer.developer_id',$devid);

        return $sql;
    }
    
    public static function buscarActivitiesPorConteudo($content_id){
        return DB::table('activities')
            ->where("content_id", $content_id)
            ->orderBy('mural_id', 'desc')
            ->get();
    }

    public static function buscarOrderedActivitiesPorConteudo($content_id){
        return DB::table('activities')
            ->where("content_id", $content_id)
            ->orderBy('position', 'asc')
            ->get();
    }

    /**
     * Retorna o tempo restante que o usuário tem para responder a atividade em questão
    */
    public static function getTempoRestante($user_id, $activity_id) {
        return DB::table('pontuacoes')
            ->where('user_id', $user_id)
            ->where('activity_id', $activity_id)
            ->value('tempo_restante');
    }

    /**
     * Retorna se a atividade pode ser refeita ou não.
    */
    public static function refeita($activity_id) {
        return DB::table('activities')
            ->where('id', $activity_id)
            ->value('refeita');
    }

    /**
     * Retorna a tentativa atual do usuário (Última tentativa + 1).
    */
    public static function getTentativa($id_activity, $id_user) {
        $tentativa = DB::table('student_answers as sa')
            ->select('sa.tentativas')
            ->where('sa.activity_id', $id_activity)
            ->where('sa.user_id', $id_user)
            ->orderBy('sa.created_at', 'desc')
            ->value('tentativas') ?? 0;

        return ++$tentativa;
    }

    /**
     * Retorna a pontuação máxima de uma atividade.
    */
    public static function getPontuacao($activity_id) {
        return DB::table('activities')
            ->where('id', $activity_id)
            ->value('score');
    }

    /**
     * Retorna a pontuacao de um aluno em certa atividade.
    */
    public static function getPontuacaoAluno($activity_id, $aluno_id) {
        return DB::table('pontuacoes')
            ->where('activity_id', $activity_id)
            ->where('user_id', $aluno_id)
            ->value('pontuacao');
    }

    /**
     * Retorna as atividades pontuadas de um professor
    */
    public static function getAtividadesPontuadasPorProf($profId) {
        return DB::table('activities')
            ->where('professor_id', $profId)
            ->where('duration', '>', 0)
            ->select([
                'id',
                'name'
            ])
            ->get();
    }

}

