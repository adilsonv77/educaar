<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Disciplina;
use App\Models\User;
use App\Models\School;
use App\Models\Matricula;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class DisciplinaController extends Controller
{
    // o cadastrar disciplina está sendo feito pelo livewire em DisciplinaForm


      // os métodos abaixo não foram revisados !!!
    public function viewCadastrarAlunoDisciplina()
    {
        $disciplinas = Disciplina::all();
        $schools = School::all()->where('id', Auth::user()->school_id)->first();


        $students = User::where('type', 'student')->orderBy('name')->get();


        return view('pages.class.registerStudentDiscipline', compact('disciplinas', 'students', 'schools'));
    }


    public function storeAlunoDisciplina(Request $request)
    {
        $data = $request->all();


        $user = User::all()->where('username', $data['student'])->first();


        if (!isset($data['discipline'])) {
            return redirect('registerStudentDiscipline')
                ->withErrors('Selecione uma disciplina.');
        } else if ($data['student'] == null) {
            return redirect('registerStudentDiscipline')
                ->withErrors('Selecione um aluno.');
        } else {
            $discipline_id = Disciplina::all()->where('name', $data['discipline'])->first()->id;
        }


        $matricula = Matricula::all()->where('user_id', $user->id);


        $matricula_existente = false;


        foreach ($matricula as $m) {
            if ($m->disciplina_id == $discipline_id) {
                $matricula_existente = true;
            }
        }


        if ($matricula_existente) {
            return redirect('registerStudentDiscipline')
                ->withErrors('O usuário selecionado já está matriculado nessa disciplina.');
        } else {
            Matricula::create([
                'user_id' => $user->id,
                'disciplina_id' => $discipline_id
            ]);


            return redirect('/');
        }
    }
}
