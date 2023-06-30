@extends('layouts.app')

@php
    $pageName = 'Questões';
@endphp

@section('page-name', $pageName)

@section('content')

    <head>
        <link rel="stylesheet" href="/css/list_content.css">
        <link rel="stylesheet" href="/css/list_questions.css">
    </head>
    {{-- <a style="display:block;width:100px" style= "padding-right: -100px" href="{{ route('questions.create') }}" class="btn btn-success">Criar</a> --}}

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
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($questions as $item)
                                <tr>
                                    {{-- !<td>{{ $item->name }}</td>
                                    <td><a href="/question/create{{ $activity->id }}" class="btn btn-primary1">Editar</a></td>
                                    <td>
                                        <button type="button" class="btn btn-danger" data-toggle="modal"
                                            data-target="#modal{{ $item->id }}">
                                            Deletar
                                        </button>
                                    </td> --}}

                                    <td>{{ $item->question }}</td>
                                    <td>
                                        <form action="{{ route('questions.edit', $item->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-warning">Editar</button>
                                        </form>
                                    </td>

                                    <td>
                                        <button type="button" class="btn btn-danger" data-toggle="modal"
                                            data-target="#modal{{ $item->id }}">
                                            Excluir
                                        </button>
                                    </td>
                                </tr>
                                <div class="modal fade" id="modal{{ $item->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <h3>Você tem certeza que deseja excluir o questions {{ $item->question }}?
                                                </h3>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Cancelar</button>
                                                <form action="{{ route('questions.destroy', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Excluir</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
