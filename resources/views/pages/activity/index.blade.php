@extends('layouts.app')

@php
    $pageName = 'Atividades';
@endphp

@section('page-name', $pageName)

@section('content')

  
    <div>
        <form action="{{ route('activity.create') }}">
            @csrf
            <button class="btn btn-sm btn-primary " id="novo" title="Novo"><i class="bi bi-plus-circle-dotted h1" style = "color : #ffffff;"></i></button>
        </form>
    </div>
    <form action="{{ route('activity.index') }}" method="GET">
        {{-- <div class="form-inline">
            <input class="form-control" type="text" name="titulo" id="titulo" value="{{ $activity }}"
                list="historico" />
            <section class="itens-group">
                <button class="btn btn-primary "type="submit">Pesquisar</button>
            </section>
        </div> --}}
        <div class="form-inline ">
            <input class="form-control " type="text" name="titulo" id="titulo" value="{{ $activity }}"
                list="historico" />
            <section class="itens-group">
                <button class="btn btn-primary btn-lg" type="submit">Pesquisar</button>
            </section>
        </div>
        <datalist id="historico">
            @foreach ($activities as $activity)
                <option value="{{ $activity->pesq_name }}">{{ $activity->pesq_name }}</option>
            @endforeach
        </datalist>
    </form>
    <br>

    <div class="card">
        {{-- <div class="card-header">
            <h4 class="card-title">{{ $pageName }}</h4>
        </div> --}}
        <div class="card-body">
            @if (!empty($activities))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Marcador</th>
                                <th>Visualizar</th>
                                @can('teacher')<th>Resultados</th>@endcan
                                <th>Questões</th>
                                <th>Editar</th>
                                <th>Excluir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activities as $item)
                                <tr>
                                    <td style="width: 60%;">{{ $item->name }}</td>
                                    <td style="width: 25%;"><img src="/marcadores/{{ $item->marcador }}"
                                            alt=""width="200" height="200"></td>
                                    <td style="width: 10%;"><a href="/activity/{{ $item->id }}" class="btn btn-primary" title="Visualizar">
                                        <i class="bi bi-eye-fill h2" style = "color : #ffffff;"></i>
                                    </a></td>

                                    <td style="width: 10%;">
                                        <form action="{{ route('activity.results', $item->id) }}">
                                            @csrf
                                            <input type="hidden" name="activity_id" value="{{ $item->id }}">
                                            <button type="submit" class="btn btn-success" title="Resultados" 
                                                @if ($item->qtnQuest == 0) disabled @endif
                                                text-align:center>
                                                <i class="bi bi-journal-bookmark h2"  style = "color : #ffffff;"></i>
                                            </button>
                                        </form>
                                    </td>


                                    <td style="width: 10%;">
                                        <form action="{{ route('questions.index', $item->id) }}">
                                            @csrf
                                            <input type="hidden" name="activity" value="{{ $item->id }}">
                                            <button type="submit" class="btn btn-info" text-align:center title="Questões">
                                                <i class="bi bi-file-earmark-medical-fill h2" style = "color : #ffffff;"></i>
                                            </button>
                                        </form>
                                    </td>

                                    <td style="width: 70px;">
                                        <form action="{{ route('activity.edit', $item->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-warning" text-align:center title="Editar">
                                                <i class="bi bi-pencil-square h2" style = "color : #ffffff;"></i>
                                            </button>
                                        </form>
                                    </td>
                                    @can('teacher')
                                    <td style="width: 70px;">
                                        <button type="button" class="btn btn-danger"
                                            @if ($item->qtnQuest > 0) disabled @endif data-toggle="modal"
                                            data-target="#modal{{ $item->id }}" title="Excluir"> 
                                            <i class="bi bi-trash3 h2" style = "color : #ffffff;"></i>
                                        </button>
                                    </td>
                                    @endcan
                                </tr>
                                <div class="modal fade" id="modal{{ $item->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <h3>Você tem certeza que deseja excluir a atividade "{{ $item->name }}"?
                                                </h3>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Cancelar</button>
                                                <form action="{{ route('activity.destroy', $item->id) }}" method="POST">
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
                        {{ $activities->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            @else
                <div>
                    <h2>Nenhuma atividade cadastrada</h2>
                </div>
            @endif
        </div>
    </div>
@endsection
