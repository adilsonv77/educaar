<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AnoLetivo;
use App\Models\Activity;
use App\DAO\TurmaDAO;
use App\DAO\RankingDAO;
use App\DAO\ActivityDAO;

class RankingController extends Controller
{
    public function create(Request $request) {
        $atividades = ActivityDAO::getAtividadesPontuadas(Auth::id());

        $activityId = request('activity_id'); 

        $ranking = $activityId == 0
            ? null
            : RankingDAO::buscarRankingPorAtividade($activityId);

        return view('pages.activity.ranking', compact('atividades' ,'ranking'));
    }

}
 