<?php

namespace App\DAO;

use App\Models\AnoLetivo;
use App\Models\Activity;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActivityDAO
{

    public static function buscarPorPainel($id){
        return Activity::where('mural_id',$id);
    }

    public static function buscarActivitiesDoProf(int $profId, int $anoLetivoId) {
        return DB::table('activities')
            ->whereExists(function ($query) use ($profId, $anoLetivoId) {
                $query->select(DB::raw(1))
                    ->from('turmas_disciplinas as td')
                    ->join('turmas', 'td.turma_id', '=', 'turmas.id')
                    ->join('contents', function ($join) {
                        $join->on('turmas.turma_modelo_id', '=', 'contents.turma_modelo_id');
                        $join->on('td.disciplina_id', '=', 'contents.disciplina_id');
                    })
                    ->whereColumn('contents.id', '=', 'activities.content_id')
                    ->where('td.professor_id', '=', $profId)
                    ->where('turmas.ano_id', '=', $anoLetivoId);
            })
            ->join('contents', 'contents.id', '=', 'activities.content_id')
            ->join('disciplinas', 'disciplinas.id', '=', 'contents.disciplina_id')
            ->select([
                'activities.*',
                'contents.name AS nome_conteudo',
                DB::raw('concat(activities.name, " - ", disciplinas.name, " (", contents.name, ")") AS pesq_name'),
            ])
            ->groupBy('activities.id');
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
     * Retorna a ordem de um conteúdo baseado na sequência particular de um aluno
    */
    public static function buscarRandomOrderedActivitiesPorConteudo(int $content_id): Collection {
        $activities = self::buscarActivitiesPorConteudo($content_id);

        $studentSort = DB::table('random_sorts')
            ->where('content_id', $content_id)
            ->where('user_id', Auth::id())
            ->value('sort');

        $activityDic = collect($activities)->keyBy('position');

        $i = 1;
        foreach(explode(',', $studentSort) as $position) {
            /**
            * $activityDic é indexado por 'position' das activities do $content_id.
            * $studentSort contém exatamente essas posições (gerado pelo sistema).
            * O null da assinatura TValue|null de ->get() é impossível neste contexto.
            * @var Activity $activityDic
            */
            $activity = $activityDic->get((int)$position);
            $activity->position = $i++;
        }

        return $activityDic;
    }

    public static function getTentativa(int $id_activity, int $id_user): int {
        $tentativa = DB::table('student_answers as sa')
            ->select('sa.tentativas')
            ->where('sa.activity_id', $id_activity)
            ->where('sa.user_id', $id_user)
            ->orderBy('sa.created_at', 'desc')
            ->value('tentativas') ?? 0;

        return ++$tentativa;
    }

    /**
     * Retorna as atividades pontuadas de um conteúdo
    */
    public static function getAtividadesPontuadasPorConteudo($content_id) {
        return DB::table('activities')
            ->where('content_id', $content_id)
            ->where('duration', '>', 0)
            ->select([
                'id',
                'name'
            ])
            ->get();
    }

    /**
     * Retorna '' caso hint, no banco de dados, for nulo.
    */
    public static function getNextHint(int $contentId, int $activityId) : string {
        $position = DB::table('activities')
            ->where('id', $activityId)
            ->value('position');

         return DB::table('contents')
            ->join('activities', 'activities.content_id', '=', 'contents.id')
            ->where('contents.id', $contentId)
            ->where('activities.position', $position + 1)
            ->value('hint') ?? '';
    }

    /**
     * Retorna '' caso hint, no banco de dados, for nulo.
    */
    public static function getNextHintRandom(int $contentId, int $activityId) : string {
        $sortRaw = DB::table('random_sorts')
            ->where('user_id', Auth::id())
            ->where('content_id', $contentId)
            ->value('sort');

        if($sortRaw === null) return '';
            
        $sort = explode(',', $sortRaw);

        $positionBase = DB::table('activities')
            ->where('id', $activityId)
            ->value('position');

        $positionSorted = array_search($positionBase, $sort);
        if($positionSorted === false || $positionSorted >= count($sort) - 1) return '';

        return DB::table('contents')
            ->join('activities', 'activities.content_id', '=', 'contents.id')
            ->where('contents.id', $contentId)
            ->where('activities.position', $sort[$positionSorted + 1])
            ->value('hint') ?? '';
    }

    public static function getHint(int $contentId, int $activityId) : string {
        $position = DB::table('activities')
            ->where('id', $activityId)
            ->value('position');

         return DB::table('contents')
            ->join('activities', 'activities.content_id', '=', 'contents.id')
            ->where('contents.id', $contentId)
            ->where('activities.position', $position)
            ->value('hint') ?? '';
    }

    public static function getActivitiesBySala(int $salaId) {
        $result = DB::table('salas')
            ->join('jogos', 'jogos.id', '=', 'salas.jogo_id')
            ->join('contents', 'contents.id', '=', 'jogos.content_id')
            ->join('activities', 'activities.content_id', '=', 'contents.id')
            ->select('activities.*')
            ->get();

        return $result;
    }
}

