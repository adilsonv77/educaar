<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\User;
use App\Models\AnoLetivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfigController extends Controller
{

    public function index(Request $request)
    {

        if (session('type') != 'admin') {
            return redirect('/');
        }

        $school = School::find(Auth::user()->school_id);
        // dd($school);
        $anosletivos = AnoLetivo::where('school_id', $school->id)->get();

        $profs = User::where('type', '=', 'teacher')
            ->where('school_id', '=', $school->id)
            ->get();

        $beforeId = 0;
        foreach ($anosletivos as $anoletivo) {
            if ($anoletivo->bool_atual == TRUE) {
                $beforeId = $anoletivo->id;
                break;
            }
        }

        $params = [
            'school' => $school,
            'beforeId' => $beforeId,
            'anosletivos' => $anosletivos,
            'professores' => $profs

        ];

        return view('pages.config.edit', $params);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        if ($data['beforeId'] <> $data['anoLetivoAtual']) {
            $anoletivo = AnoLetivo::find($data['beforeId']);
            $anoletivo->bool_atual = 0;
            $anoletivo->update();

            $anoletivo = AnoLetivo::find($data['anoLetivoAtual']);
            $anoletivo->bool_atual = 1;
            $anoletivo->update();
        }

        $school = School::find(Auth::user()->school_id);
        $school->name = $data['schoolName'];
        $school->prof_atual_id = $data['prof_atual'];
        $school->update();

        return redirect('/');
    }
}
