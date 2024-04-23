@extends('layouts.app')

@php
    $pageName = 'Conteúdos';
@endphp

@section('page-name', $pageName)

@section('content')

    <head>
        <link rel="stylesheet" href="/css/list_content.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </head>

    <form action="{{ route('content.index') }}" method="GET">
        <div class="form-inline">
            <input class="form-control" type="text" name="titulo" id="titulo" value="{{ $content }}" list="historico" />
            <button class="btn btn-primary btn-lg" type="submit">Pesquisar</button>
        </div>


        <datalist id="historico">
            @foreach ($contents as $content)
                <option value="{{ $content->pesq_name }}">{{ $content->pesq_name }}</option>
            @endforeach
        </datalist>
    </form>
    <br>

    <div class="card">
        <div class="card-body">
            @if (!empty($contents))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Disciplina</th>
                                <th>Série</th>
                                <th>Fechar</th> 
                                <th>Selecionar Devs</th>
                                <th>Resultados</th>
                                <th>Editar</th>
                                <th>Excluir</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($contents as $item)
                                <tr>

                                    <td>{{ $item->content_name }}</td>
                                    <td>{{ $item->disc_name }}</td>
                                    <td>{{ $item->turma_name }}</td>

                                    
                                         <!-- Açoes -->
                                    <td>
                                        <form action="{{ route('fechar.index') }}">
                                            @csrf
                                            <input type="hidden" name="content" value="{{ $item->id }}">
                                            <button type="submit" id="FecharConteudo" class="btn btn-info"
                                                @if ($item->qtasatividades == 0 or $item->fechado) disabled @endif>
                                                <i class="bi bi-lock-fill h2 "  style = "color : #ffffff;"></i> ({{ $item->qtasatividades }})

                                            </button>
                                        </form>
                                    </td>
                                     

                                    <td>
                                        <form action="{{ route('dev.listDevs') }}">
                                            @csrf
                                            <input type="hidden" name="content" value="{{ $item->id }}">
                                            <button type="submit" class="btn btn-warning">
                                                <i class="bi bi-person-fill-gear h2"  style = "color : #ffffff;"></i>
                                            </button>
                                        </form>
                                    </td>

                                    <!-- Resultados -->
                                    <td>
                                        <form action="{{ route('content.resultsContents') }}">
                                            @csrf
                                            <input type="hidden" name="content_id" value="{{ $item->id }}">
                                            <button type="submit"  class="btn btn-success">
                                            <i class="bi bi-journal-bookmark h2"  style = "color : #ffffff;"></i>
                                            </button>
                                        </form>
                                    </td>

                                    <td>
                                     <!-- Editar -->
                                    <form action="{{ route('content.edit', $item->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-warning ">
                                                <i class="bi bi-pencil-square h2" style = "color : #ffffff;"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                         <!-- Excluir -->
                                        <button type="button"
                                                class="btn btn-danger  @if ($item->qtasatividades > 0) disabled @endif"
                                                data-toggle="modal" data-target="#modal{{ $item->id }}">
                                                <i class="bi bi-trash3 h2"  style = "color : #ffffff;"></i>
                                        </button>
                                    </td>
                                </tr>
                                <div class="modal fade" id="modal{{ $item->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <h3>Você tem certeza que deseja excluir o conteúdo
                                                    {{ $item->content_name }}?</h3>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Cancelar</button>
                                                <form action="{{ route('content.destroy', $item->id) }}" method="POST">
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
                    <div class="d-flex justify-content-center">
                        {{ $contents->links('vendor.pagination.bootstrap-4') }}
                    </div>

                </div>
            @else
                <div>
                    <h2>Nenhum conteúdo cadastrado</h2>
                </div>
            @endif
        </div>
    </div>



@endsection
