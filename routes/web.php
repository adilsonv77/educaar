<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\School;

//Painéis - Renan
//Talvez alguns possam ser substituidos por o tal do resource
Route::get('teste', function () {
    return view('pages.painel.panelConnection', [
        'titulo' => 'Criação de painéis',
        'action' => 'create',
        'midiaExtension' => ''
    ]);
});

use App\Http\Controllers\PainelController;
Route::prefix('paineis')->group(function () {
    Route::get('/', [PainelController::class, 'index'])->name('paineis.index');
    Route::get('/create', [PainelController::class, 'create'])->name('paineis.create');
    Route::post('/', [PainelController::class, 'store'])->name('paineis.store');
    Route::get('/{id}/edit', [PainelController::class, 'edit'])->name('paineis.edit');
    Route::put('/{id}', [PainelController::class, 'update'])->name('paineis.update');
    Route::delete('/{id}', [PainelController::class, 'destroy'])->name('paineis.destroy');
    Route::get('/conexoes', [PainelController::class, 'conexoes'])->name('paineis.conexoes');
});

// use Hash;
// Route::get('/', function () { return redirect('/home');});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

    //config
    Route::resource('config', App\Http\Controllers\ConfigController::class);

    //config do prof
    Route::resource('profconfig', App\Http\Controllers\ProfConfigController::class);

    //users
    Route::resource('user', App\Http\Controllers\UserController::class);
    Route::get('/indexAluno', [App\Http\Controllers\UserController::class, 'indexAluno'])->name('user.indexAluno');  
    Route::get('/indexProf', [App\Http\Controllers\UserController::class, 'indexProf'])->name('user.indexProf');
    Route::get('/indexDev', [App\Http\Controllers\UserController::class, 'indexDev'])->name('user.indexDev');
    Route::get('/createStudent', [App\Http\Controllers\UserController::class, 'createStudent'])->name('user.createStudent');
    Route::get('/createTeacher', [App\Http\Controllers\UserController::class, 'createTeacher'])->name('user.createTeacher');
    Route::get('/createDeveloper', [App\Http\Controllers\UserController::class, 'createDeveloper'])->name('user.createDeveloper');
    Route::get('/matricula', [App\Http\Controllers\UserController::class, 'matricula'])->name('user.matricula');
    Route::post('/storeMatricula', [App\Http\Controllers\UserController::class, 'storeMatricula'])->name('user.storeMatricula');    //class

    //Route::get('/disciplinas', DisciplinaForm::class)->name('disciplinas');
    //Route::resource('class', App\Http\Controllers\DisciplinaController::class);
    Route::get('/class', function () {
        return view('pages.disciplina.list');
    })->name('class.index');

    //ano letivo
    //Route::resource('anoletivo', App\Http\Controllers\AnoLetivoController::class);
    Route::get('/anoletivo', function () {
        return view('pages.anoletivo.list');
    })->name('anoletivo');

    //turmas modelos
    Route::resource('turmasmodelos', App\Http\Controllers\TurmasModelosController::class);

    //turmas 
    Route::resource('turmas', App\Http\Controllers\TurmaController::class);
    Route::get('/turmasAlunosIndex', [App\Http\Controllers\TurmaController::class, 'turmasAlunosIndex'])->name('turmas.turmasAlunosIndex');
    Route::post('/desmatricular', [App\Http\Controllers\TurmaController::class, 'desmatricular'])->name('turmas.desmatricular');
    Route::get('/disciplinasIndex', [App\Http\Controllers\TurmaController::class, 'disciplinasIndex'])->name('turmas.disciplinasIndex');
    Route::post('/storeDisciplinaProf', [App\Http\Controllers\TurmaController::class, 'storeDisciplinaProf'])->name('turmas.storeDisciplinaProf');
    Route::get('/novoAlunoTurma', [App\Http\Controllers\TurmaController::class, 'novoAlunoTurma'])->name('turmas.novoAlunoTurma');
    Route::post('/novoAlunoTurmaStore', [App\Http\Controllers\TurmaController::class, 'novoAlunoTurmaStore'])->name('turmas.novoAlunoTurmaStore');
    Route::get('/indexmatricula', [App\Http\Controllers\TurmaController::class, 'indexmatricula'])->name('turmas.indexmatricula');
    Route::get('/listarTurmasAlunosProf', [App\Http\Controllers\TurmaController::class, 'listarTurmasAlunosProf'])->name('turmas.listarTurmasAlunosProf');

    Route::get('/resultadosTurma', [App\Http\Controllers\ResultadosController::class, 'index'])->name('turma.resultados');


    //content
    Route::resource('content', App\Http\Controllers\ContentController::class);
    Route::get('resultsContents', [App\Http\Controllers\ContentController::class, 'resultsContents'])->name('content.resultsContents');
    Route::get('/listStudents/content/{type}', [App\Http\Controllers\ContentController::class, 'resultsListStudents'])->name('content.listStudents');
    Route::get('/content/list', [App\Http\Controllers\ContentController::class, 'listOfContents'])->name('content.list');


    //school
    Route::get('/registerStudentDiscipline', [App\Http\Controllers\DisciplinaController::class, 'viewCadastrarAlunoDisciplina'])->name('registerStudentDiscipline');
    Route::post('/storeAlunoDisciplina', [App\Http\Controllers\DisciplinaController::class, 'storeAlunoDisciplina'])->name('disciplina.storeAlunoDisciplina');

    //question
    Route::resource('/questions', App\Http\Controllers\QuestionController::class);
    Route::get('results', [App\Http\Controllers\QuestionController::class, 'results'])->name('activity.results');
    Route::get('/listStudents/{type}', [App\Http\Controllers\QuestionController::class, 'resultsListStudents'])->name('activity.listStudents');
    // Route::get('/question/index', [App\Http\Controllers\QuestionController::class, 'index'])->name('question.index');
    // Route::get('/question/create', [App\Http\Controllers\QuestionController::class, 'create'])->name('question.create');
    // Route::post('/question/store', [App\Http\Controllers\QuestionController::class, 'store'])->name('question.store');

    //activity
    Route::resource('activity', App\Http\Controllers\ActivityController::class);

    //fechar
    Route::resource('fechar', App\Http\Controllers\FecharController::class);
    Route::get('/fecharstore', [App\Http\Controllers\FecharController::class, 'store'])->name('fecharconteudo.store');

    //developer
    Route::get('/developer', [App\Http\Controllers\DeveloperController::class, 'index'])->name('developer.index');
    Route::get('/developer/createActivity', [App\Http\Controllers\DeveloperController::class, 'create'])->name('dev.createActivity');
    Route::get('/developer/editActivity', [App\Http\Controllers\DeveloperController::class, 'edit'])->name('dev.editActivity');
    Route::get('/developer/listDevs', [App\Http\Controllers\DeveloperController::class, 'listDevs'])->name('dev.listDevs');
    Route::post('/developer/store', [App\Http\Controllers\DeveloperController::class, 'store'])->name('dev.store');

    //students
    Route::get('/conteudos', [App\Http\Controllers\StudentController::class, 'indexContentStudent'])->name('student.conteudos');
    Route::get('/students/activity', [App\Http\Controllers\StudentController::class, 'showActivity'])->name('student.showActivity');
    Route::get('/students/store', [App\Http\Controllers\StudentController::class, 'store'])->name('student.store');
    Route::get('/students/questoes', [App\Http\Controllers\StudentController::class, 'questoes'])->name('student.questoes');

    //teacher
    Route::get('/frequencia', [App\Http\Controllers\FrequenciaController::class, 'index'])->name('teacher.frequencia');
    Route::post('/frequencia', [App\Http\Controllers\FrequenciaController::class, 'index'])->name('teacher.frequencia.filter');

    //Lista De alunos em preofessor
    Route::get('/turma/questoesDosAlunos', [App\Http\Controllers\QuestoesAcertadasController::class, 'index'])->name('student.results');
    Route::get('/turma/questoesNaoRespondidasTodosAlunos', [App\Http\Controllers\QuestoesAcertadasController::class, 'todos'])->name('student.naorespondidas');

});

//webservice para receber o arquivo mind
Route::post('/receivemind', 'App\Http\Controllers\ReceiveMindController@receive');


// nao sao mais usadas.... provavelmente excluir !!!
Route::get('/students/novas', [App\Http\Controllers\StudentController::class, 'novasAtividades']);
Route::get('/students/realizadas', [App\Http\Controllers\StudentController::class, 'atividadesRealizadas']);




Route::get('/alunoteste', function () {
    if (DB::table('schools')->count() == 0) {
        DB::table('schools')->insert([
            [
                'name' => 'Escola X'
            ]
        ]);
    }


    if (DB::table('anos_letivos')->count() == 0) {
        DB::table('anos_letivos')->insert([
            [
                'name' => '2021',
                'bool_atual' => 0,
                'school_id' => 1
            ],
            [
                'name' => '2022',
                'bool_atual' => 0,
                'school_id' => 1
            ],
            [
                'name' => '2023',
                'bool_atual' => 0,
                'school_id' => 1
            ],
            [
                'name' => '2024',
                'bool_atual' => 1,
                'school_id' => 1
            ],
        ]);
    }
    DB::table('turmas_modelos')->insert([
        ['serie' => '1a Serie', 'school_id' => 1],
        ['serie' => '2a Serie', 'school_id' => 1],
        ['serie' => '3a Serie', 'school_id' => 1],
        ['serie' => '1a Serie Integral', 'school_id' => 1],
    ]);

    if (DB::table('turmas')->count() == 0) {
        DB::table('turmas')->insert([

            [
                'nome' => '1a Serie 1',
                'ano_id' => 1,
                'school_id' => 1,
                'turma_modelo_id' => 1
            ],

            [
                'nome' => '1a Serie 2',
                'ano_id' => 2,
                'school_id' => 1,
                'turma_modelo_id' => 1
            ],

            [
                'nome' => '2a Serie 1',
                'ano_id' => 3,
                'school_id' => 1,
                'turma_modelo_id' => 2
            ],

            [
                'nome' => '1a Serie 1',
                'ano_id' => 4,
                'school_id' => 1,
                'turma_modelo_id' => 1
            ],
            [
                'nome' => '2a Serie 1',
                'ano_id' => 4,
                'school_id' => 1,
                'turma_modelo_id' => 2
            ],

        ]);
    }

    if (DB::table('users')->count() == 0) {
        DB::table('users')->insert([

            [
                'name' => 'Cauê',
                'username' => 'prof',
                'email' => 'caue@gmail.com',
                'school_id' => 1,
                'password' => Hash::make('123'),
                'type' => 'teacher'
            ],

            [
                'name' => 'Joao',
                'username' => 'aluno',
                'email' => 'joao@gmail.com',
                'school_id' => 1,
                'password' => Hash::make('123'),
                'type' => 'student'
            ],

            [
                'name' => 'Pedro',
                'username' => 'admin',
                'email' => 'pedro@gmail.com',
                'school_id' => 1,
                'password' => Hash::make('123'),
                'type' => 'admin'
            ],

            [
                'name' => 'prof01',
                'username' => 'prof01',
                'email' => 'prof01@gmail.com',
                'school_id' => 1,
                'password' => Hash::make('123'),
                'type' => 'teacher'
            ],

            [
                'name' => 'prof02',
                'username' => 'prof02',
                'email' => 'prof02@gmail.com',
                'school_id' => 1,
                'password' => Hash::make('123'),
                'type' => 'teacher'
            ],

            [
                'name' => 'prof03',
                'username' => 'prof03',
                'email' => 'prof03@gmail.com',
                'school_id' => 1,
                'password' => Hash::make('123'),
                'type' => 'teacher'
            ],

            [
                'name' => 'xico01',
                'username' => 'xico01',
                'email' => 'xico01@gmail.com',
                'school_id' => 1,
                'password' => Hash::make('123'),
                'type' => 'student'
            ],
            [
                'name' => 'xico02',
                'username' => 'xico02',
                'email' => 'xico02@gmail.com',
                'school_id' => 1,
                'password' => Hash::make('123'),
                'type' => 'student'
            ],
            [
                'name' => 'xico03',
                'username' => 'xico03',
                'email' => 'xico03@gmail.com',
                'school_id' => 1,
                'password' => Hash::make('123'),
                'type' => 'student'
            ],
            [
                'name' => 'xico04',
                'username' => 'xico04',
                'email' => 'xico04@gmail.com',
                'school_id' => 1,
                'password' => Hash::make('123'),
                'type' => 'student'
            ]
        ]);
    }

    if (DB::table('disciplinas')->count() == 0) {
        DB::table('disciplinas')->insert([
            ['name' => 'Fisica', 'school_id' => 1],
            ['name' => 'Quimica', 'school_id' => 1],
            ['name' => 'Biologia', 'school_id' => 1],
            ['name' => 'Filosofia', 'school_id' => 1],
            ['name' => 'Matemática', 'school_id' => 1],
            ['name' => 'Comunicação e Sociedade', 'school_id' => 1],
        ]);
    }

    DB::table('disciplinas_turmas_modelos')->insert([
        ['disciplina_id' => 1, 'turma_modelo_id' => 1],
        ['disciplina_id' => 2, 'turma_modelo_id' => 1],
        ['disciplina_id' => 3, 'turma_modelo_id' => 1],

        ['disciplina_id' => 1, 'turma_modelo_id' => 2],
        ['disciplina_id' => 2, 'turma_modelo_id' => 2],

        ['disciplina_id' => 1, 'turma_modelo_id' => 3],
        ['disciplina_id' => 2, 'turma_modelo_id' => 3],

        ['disciplina_id' => 1, 'turma_modelo_id' => 4],
        ['disciplina_id' => 2, 'turma_modelo_id' => 4]
    ]);

    DB::table('turmas_disciplinas')->insert([
        ['turma_id' => 1, 'disciplina_id' => 1, 'professor_id' => 1],
        ['turma_id' => 1, 'disciplina_id' => 2, 'professor_id' => 4],
        ['turma_id' => 1, 'disciplina_id' => 3, 'professor_id' => 5],

        ['turma_id' => 2, 'disciplina_id' => 1, 'professor_id' => 1],
        ['turma_id' => 2, 'disciplina_id' => 2, 'professor_id' => 4],
        ['turma_id' => 2, 'disciplina_id' => 3, 'professor_id' => 5],

        ['turma_id' => 4, 'disciplina_id' => 1, 'professor_id' => 1],
        ['turma_id' => 4, 'disciplina_id' => 2, 'professor_id' => 4],
        ['turma_id' => 4, 'disciplina_id' => 3, 'professor_id' => 5],

        ['turma_id' => 3, 'disciplina_id' => 1, 'professor_id' => 1],
        ['turma_id' => 3, 'disciplina_id' => 2, 'professor_id' => 4],

        ['turma_id' => 5, 'disciplina_id' => 1, 'professor_id' => 1],
        ['turma_id' => 5, 'disciplina_id' => 2, 'professor_id' => 4]
    ]);


    DB::table('alunos_turmas')->insert([
        ['aluno_id' => 1, 'turma_id' => 1],
        ['aluno_id' => 3, 'turma_id' => 1],
        ['aluno_id' => 2, 'turma_id' => 2],
        ['aluno_id' => 1, 'turma_id' => 2],
        ['aluno_id' => 5, 'turma_id' => 5],
        ['aluno_id' => 4, 'turma_id' => 4],
        ['aluno_id' => 2, 'turma_id' => 4],
        ['aluno_id' => 3, 'turma_id' => 4],
        ['aluno_id' => 1, 'turma_id' => 5],
    ]);

    return redirect('/home');
});

Route::get('/arteste', function () {

    if (DB::table('contents')->count() == 0) {

        DB::table('contents')->insert([
            [
                'name' => 'calorimetria',
                'user_id' => 1,
                'disciplina_id' => 1,
                'turma_id' => 1,
            ],

            [
                'name' => 'balanceamento quimico',
                'user_id' => 1,
                'disciplina_id' => 1,
                'turma_id' => 2,
            ],

            [
                'name' => 'reino animalia',
                'user_id' => 1,
                'disciplina_id' => 3,
                'turma_id' => 1,
            ],

            [
                'name' => 'ser ou nao ser',
                'user_id' => 1,
                'disciplina_id' => 1,
                'turma_id' => 4,
            ],

            [
                'name' => 'geometria',
                'user_id' => 1,
                'disciplina_id' => 1,
                'turma_id' => 4,
            ],

            [
                'name' => 'diferentes variaçoes linguisticas do norte',
                'user_id' => 1,
                'disciplina_id' => 1,
                'turma_id' => 1,
            ],
        ]);
    };
    return redirect('/home');
});
