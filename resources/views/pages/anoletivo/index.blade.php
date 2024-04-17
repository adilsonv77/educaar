@extends('layouts.app')

@php
    $pageName = 'Anos Letivos';
@endphp

@section('page-name', $pageName)

@section('content')

    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </head>

    <form action="{{ route('anoletivo.index') }}" method="GET">
        <div class="form-inline">
            <input class="form-control" type="text" name="titulo" id="titulo" value="{{ $anoLetivo }}"
                list="historico" />
            <section class="itens-group">
                <button class="btn btn-primary "type="submit">Pesquisar</button>
            </section>
        </div>
        <datalist id="historico">
            @foreach ($anosletivos as $anoLetivo)
                <option value="{{ $anoLetivo->name }}">{{ $anoLetivo->name }}</option>
            @endforeach
        </datalist>
    </form>
    <br>

    <div class="card">
        <div class="card-body">
            @if (!empty($anosletivos))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Editar</th>
                                <th>Excluir</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($anosletivos as $item)
                                <tr>

                                    <td>{{ $item->name }}</td>


                                    <td>
                                        <form action="{{ route('anoletivo.edit', $item->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-warning">
                                            <i class="bi bi-pencil-square"></i></button>
                                        </form>
                                    </td>


                                    <td>
                                        <button type="button" class="btn btn-danger" data-toggle="modal"
                                            data-target="#modal{{ $item->id }}">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </td>
                                </tr>
                                <div class="modal fade" id="modal{{ $item->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <h3>Você tem certeza que deseja excluir o ano letivo
                                                    <b>{{ $item->name }}?</b>
                                                </h3>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Cancelar</button>
                                                <form action="{{ route('anoletivo.destroy', $item->id) }}" method="POST">
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
                        {{ $anosletivos->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            @else
                <div>
                    <h2>Nenhum ano letivo cadastrado</h2>
                </div>
            @endif
        </div>
    </div>
@endsection
