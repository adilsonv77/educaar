@extends('layouts.app')

@php
    $pageName = 'Disciplinas';
@endphp

@section('page-name', $pageName)

@section('content')

    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </head>

    <form action="{{ route('class.index') }}" method="GET">
        <div class="form-inline">
            <input class="form-control" type="text" name="titulo" id="titulo" value="{{ $disciplina }}"
                list="historico" />
            <section class="itens-group">
                <button class="btn btn-primary "type="submit">Pesquisar</button>
            </section>
        </div>
        <datalist id="historico">
            @foreach ($disciplinas as $disciplina)
                <option value="{{ $disciplina->name }}">{{ $disciplina->name }}</option>
            @endforeach
        </datalist>
    </form>
    <br>

    <div class="card">
        <div class="card-body">
            @if (!empty($disciplinas))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($disciplinas as $item)
                                <tr>

                                    <td>{{ $item->name }}</td>


                                    <td>
                                        <form action="{{ route('class.edit', $item->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-warning"><i class="bi bi-pencil-square"></i></button>
                                        </form>
                                    </td>

                                    {{-- @if ($item->is_checked)
                                <td>
                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                        data-target="#modal{{ $item->id }}">
                                        Excluir
                                    </button>
                                </td>
                                @endif --}}
                                </tr>
                                <div class="modal fade" id="modal{{ $item->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <h3>Você tem certeza que deseja excluir a disciplina
                                                    <b>{{ $item->name }}?</b>
                                                </h3>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Cancelar</button>
                                                <form action="{{ route('class.destroy', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Sim</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </tbody>
                    </table>


                    <div class="d-flex justify-content-center">
                        {{ $disciplinas->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            @else
                <div>
                    <h2>Nenhuma disciplina cadastrada</h2>
                </div>
            @endif
        </div>
    </div>
@endsection
