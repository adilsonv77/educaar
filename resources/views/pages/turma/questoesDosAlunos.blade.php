@extends('layouts.app')

@php
    $pageName = $aluno->name;
@endphp

@section('page-name', $pageName)

@section('content')

<div id="jorje">
    <div class="card">
        <div class="card-body">
           @if (!empty($questoes))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Questão</th>
                                <th>Atividade</th>
                                <th>Conteúdo</th>
                                <th>Resposta</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($questoes as $questao)
                        <tr>
                            <td>{{ $questao['question'] }}</td>
                            <td>{{ $questao['activity_name'] }}</td>
                            <td>{{ $questao['content_name'] }}</td>
                            <td>{{ $questao['alternative_answered']}}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>

                 </div>
            @else
                <div>
                    <h2>Nenhuma resposta</h2>
                </div>
            @endif
        </div>
    </div>



@endsection