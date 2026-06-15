@extends('layouts.app')

@section('page-name', $sala->nome)

@section('content')

    <div class="ms-2">
        <button type="button" class="btn btn-info"><i class="bi bi-info-circle-fill"></i></button>
    </div>

    <div class="mx-auto">
        <h3>Jogo {{ $sala->nome_conteudo }}</h3>
    </div>

    <div class="ms-2">
        <button type="button" class="btn btn-primary">Começar</button>
    </div>
    <div class="ms-2">
        <button type="button" class="btn btn-primary">Terminar</button>
    </div>
    

    <div class="mx-auto">

        
        <span>Alunos</span>
        <!-- Isso aqui vai dar trabalho porque depende do aluno entrar na sala pra aparecer o ícone do avatar dele
        <div class="card">
            <div class="card-body">
                <img src="" class="card-img-top rounded-circle mx-auto" style="width: 100px; height: 100px;" alt="avatarAluno">
                <span>Aqui vai o nome do aluno</span>
            </div>
        </div>
        -->
    </div>
    

@endsection
