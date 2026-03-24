<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\DAO\RankingDAO;
use App\DAO\ActivityDAO;
use App\DAO\ContentDAO;
use Illuminate\Support\Facades\DB;

class RankingController extends Controller
{
    public function create(Request $request): \Illuminate\View\View {
        $type = $request->type;
        $content_id = $request->id;
        $content_name = ContentDAO::getNameById($content_id);
        $activity_id = request('activity_id');

        if($type) {
            $layout = 'mobile';
            $atividades = null;

            $ranking = RankingDAO::somaDasPontuacoesDeUmConteudo($content_id);
        } else {
            $layout = 'app';
            $atividades = ActivityDAO::getAtividadesPontuadasPorProf(Auth::id());

            $ranking = $activity_id == 0 || !ActivityDAO::hasAnswers($activity_id)
                ? null
                : RankingDAO::buscarRankingPorAtividade($activity_id);
        }

        return view('pages.activity.ranking', compact('atividades', 'ranking', 'content_id', 'type', 'layout', 'content_name'));
    }

}
 