<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\DAO\RankingDAO;
use App\DAO\ActivityDAO;
use Illuminate\Support\Facades\DB;

class RankingController extends Controller
{
    public function create(Request $request) {
        $atividades = ActivityDAO::getAtividadesPontuadasPorProf(Auth::id());

        $activityId = request('activity_id'); 

        $hasAnswer = DB::table('pontuacoes')->where('activity_id', $activityId)->exists();

        $ranking = $activityId == 0 || !$hasAnswer
            ? null
            : RankingDAO::buscarRankingPorAtividade($activityId);

        return view('pages.activity.ranking', compact('atividades', 'ranking'));
    }

}
 