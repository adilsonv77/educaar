@extends('layouts.app')

@php
    $pageName = 'Turmas';
@endphp

@section('page-name', $pageName)

@section('content')

    <form action="{{ route('turmas.index') }}" method="GET ">
        @csrf
        <label for="">Informe o ano Letivo</label>
        <div class="form-inline">

            <select class="form-control" name="ano_id">
                @foreach ($anosletivos as $item)
                    <option value="{{ $item->id }}" @if ($item->id === $anoletivo->id) selected="selected" @endif>
                        {{ $item->name }}</option>
                @endforeach
            </select>
            <section class="itens-group">
                <button class="btn btn-primary "type="submit">Pesquisar</button>
            </section>
        </div>
    </form>
    <br>

    <div class="card">
        <div class="card-body">
            @if (!empty($turmas))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Ação</th>
                                <th>Alunos</th>
                                <th>Disciplinas</th>
                                <th>Excluir</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($turmas as $item)
                                <tr>

                                    <td>{{ $item->nome }}</td>
                                    <td>
                                        <form action="{{ route('turmas.edit', $item->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-warning"
                                                @if ($item->qtosAlunos > 0) disabled @endif>Editar</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{ route('turmas.turmasAlunosIndex', $item->id) }}">
                                            @csrf
                                            <input type="hidden" name="turma_id" value="{{ $item->id }}">
                                            <button type="submit" class="btn btn-primary">Alunos
                                                ({{ $item->qtosAlunos }})</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{ route('turmas.disciplinasIndex', $item->id) }}">
                                            @csrf
                                            <input type="hidden" name="turma_id" value="{{ $item->id }}">
                                            <button type="submit" class="btn btn-primary">Disciplinas</button>
                                        </form>
                                    </td>
                                    <td>
                                        <button type="button"
                                            class="btn btn-danger"@if ($item->qtosAlunos > 0) disabled @endif
                                            data-toggle="modal" data-target="#modal{{ $item->id }}">
                                            Excluir
                                        </button>
                                    </td>
                                    <div class="modal fade" id="modal{{ $item->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <h3>Você tem certeza que deseja excluir o conteúdo {{ $item->nome }}?
                                                    </h3>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Cancelar</button>
                                                    <form action="{{ route('turmas.destroy', $item->id) }}" method="POST">
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
                        {{ $turmas->links('vendor.pagination.bootstrap-4') }}
                    </div>

                </div>
            @else
                <div>
                    <h2>Nenhuma turma modelo cadastrada</h2>
                </div>
            @endif
        </div>
    </div>
@endsection
