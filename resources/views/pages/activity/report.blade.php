@extends('layouts.app')

@php
$pageName = 'Relatório do aluno';
@endphp

@section('page-name', $pageName)


@section('content')
    <div class="card">
        <div class="card-body">
            <a href="/activity/{{ $activityId }}" class="btn btn-primary mb-4">Voltar</a>
            @foreach ($users as $user)
                <h2>Nome do aluno: {{ $user->name }}</h2>
            @endforeach

            <table class="table mt-4">
                <thead>
                    <tr>
                        <th scope="col">Número de questões</th>
                        <th scope="col">Acertos</th>
                        <th scope="col">Erros</th>
                        <th scope="col">Tempo</th>
                        <th scope="col">Número de acessos na atividade</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach ($activityGradeUser as $grade)
                            <td>{{ $grade->numberQuestions }}</td>
                            <td>{{ $grade->correctQuestions }}</td>
                            <td>{{ $grade->wrongQuestions }}</td>
                        @endforeach

                        @foreach ($activityUserTime as $userAccessTime)
                            <td>{{ $userAccessTime->timeGeneral }}</td>
                        @endforeach

                        @foreach ($activityAccessUser as $userActivityAccess)
                            <td>{{ $userActivityAccess->timesAccessActivity }}</td>
                        @endforeach
                    </tr>

                </tbody>
            </table>


            <h2>Questões respondidas</h2>
            <div class="mt-4">
                @foreach ($activity->questions as $question)
                    <h4>{{ $question->question }}</h4>
                    @foreach ($activityQuestionsAnswered as $answer)
                        @if ($answer->question_id == $question->id)
                        <p>Resposta do aluno: {{$answer->alternative_answered}}</p>
                        @endif
                    @endforeach
                    <p>Resposta do correta: {{$question->answer}}</p>

                @endforeach
            </div>
        </div>
    </div>
@endsection
