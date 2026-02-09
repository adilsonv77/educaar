@extends('layouts.app')

@section('page-name', "Ranking de uma turma")

@section('content')

    <style> 
        .table thead th {
            background-color: #7e3789 !important; 
            color: #000;
        }
    </style>

    <div class="container mr-0 ml-0">
        <form action="{{ route('ranking.create') }}" method="GET"> @csrf
            <div class="form-inline d-flex gap-2 justify-content-start">
                <label>Informe a atividade:</label>
                <select class="form-control ml-2 w-60" name="activity_id">
                    <option value="" selected disabled > Selecione uma atividade </option>
                    @foreach($atividades as $atividade)
                        <option value="{{ $atividade->id }}" @selected(request('activity_id') == $atividade->id)>
                            {{ $atividade->name }}
                        </option>
                    @endforeach
                </select>
                <section class="itens-group" >
                    <button class="btn btn-primary btn-lg" type="submit">Pesquisar</button>
                </section>
            </div>
        </form>
    </div>
    <br>

    @if($ranking === null)
        <hr>
        <div class="mt-4"">
            <h1>Não há respostas</h1>
        </div>
    @else
        <div style="overflow: hidden; border-radius:10px">
            <table class="table table-bordered">
                <thead class="thead-info">
                    <tr>
                        <th>Posição</th>
                        <th>Nome</th>
                        <th>Pontuação</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 0; $i < count($ranking['nome']); $i++)
                        <tr>
                            <th>{{ $i + 1 }}º</th>
                            <th>{{ $ranking['nome'][$i] }}</th>
                            <th>{{ $ranking['pontuacao'][$i] ?? 0}} pontos</th>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    @endif

@endsection