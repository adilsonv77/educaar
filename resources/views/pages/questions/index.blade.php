@extends('layouts.app')

@php
$pageName = 'Questões';
@endphp

@section('style')
<link rel="stylesheet" href="/css/list_content.css">
<link rel="stylesheet" href="/css/list_questions.css">
@endsection

@section('page-name', $pageName)

@section('content')


<div class="card">
    <div class="card-header">
        <h4 class="card-title">Atividade: {{ $activity->name }} </h4>
        <form action="{{ route('questions.create') }}" style="display:block;width:100px">
            @csrf
            <button type="submit" class="btn btn-success" text-align: center>Criar questão</button>
        </form>

    </div>
    <div class="card-body">
        @if (!empty($questions))
        <div class="table-responsive">
            <table class="table table-hover table-responsive-sm">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Editar</th>
                        <th>Respostas</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($questions as $item)
                    <tr>
                        {{-- !<td>{{ $item->name }}</td>
                        <td><a href="/question/create{{ $activity->id }}" class="btn btn-primary1">Editar</a></td>
                        <td>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal{{ $item->id }}">
                                Deletar
                            </button>
                        </td> --}}

                        <td>{{ $item->question }}</td>
                        <td>
                            <form action="{{ route('questions.edit', $item->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-pencil-square h2" style="color : #ffffff;" title="Editar"></i>
                                </button>
                            </form>
                        </td>

                        <td>
                            @php
                                $qtdAlunos = $alunosPorQuestao[$item->id] ?? 0;
                                $qtdRespostasArray = $qtdRespostas[$item->id] ?? []; // Um array de respostas
                            @endphp

                            {{ count($qtdRespostasArray) }}
                        </td>


                        <td>

                            <button type="button" class="btn btn-danger" data-toggle="modal" @if (($alunosPorQuestao[$item->id] ?? 0) > 0) disabled @endif
                                data-target="#modal{{ $item->id }}"
                                title="Excluir">
                                <i class="bi bi-trash3 h2" style="color: #ffffff; margin-right: 8px;"></i>
                                <span style="color: #ffffff;">{{ $qtdAlunos }}</span>
                            </button>

                            <div class="modal fade" id="modal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <h3>Você tem certeza que deseja excluir a questão {{ $item->question }}?
                                            </h3>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            <form action="{{ route('questions.destroy', $item->id) }}" method="POST">

                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Excluir</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>


            {{-- <div class="d-flex justify-content-center">
                        {!! $contents->links('vendor.pagination.bootstrap-4') !!}
                    </div> --}}
        </div>
        @else
        <div></div>
        <h2>Nenhum conteúdo cadastrado</h2>
    </div>
    @endif
</div>
</div>
@endsection