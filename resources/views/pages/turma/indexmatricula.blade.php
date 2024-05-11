@extends('layouts.app')

@php
    $pageName = 'Alunos da turma ' . $turma->nome;
@endphp

@section('page-name', $pageName)

@section('content')

  
    <div>
        <form action="{{ route('turmas.novoAlunoTurma') }}">
            @csrf
            <button class="btn btn-sm btn-primary " id="novo"><i class="bi bi-plus-circle-dotted h1" style = "color : #ffffff;"></i></button>
        </form>
    </div>
   <form action="{{ route('turmas.indexmatricula') }}" method="GET ">
        @csrf
        <label for="">Informe a turma: (Ano letivo atual {{ $anoletivo->name }})</label>
        <div class="form-inline">
            <select class="form-control" name="turma_id">
                @foreach ($turmas as $item)
                    <option value="{{ $item->id }}" @if ($item->id === $turma->id) selected="selected" @endif>
                        {{ $item->nome }}</option>
                @endforeach
            </select>
            <section class="itens-group">
                <button class="btn btn-primary btn-lg "type="submit">Pesquisar</button>
            </section>
        </div>
    </form>
    <br>


    <div class="card">
        <div class="card-body">
            @if (!empty($alunos))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Desmatricular</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($alunos as $aluno)
                                <tr>
                                    <td>{{ $aluno->name }}</td>

                                    <td>
                                        <button type="button" class="btn btn-danger"data-toggle="modal"
                                            data-target="#modal{{ $aluno->id }}">
                                            <i class="bi bi-person-fill-dash h2" style = "color : #ffffff;"></i>
                                        </button>
                                    </td>
                                    <div class="modal fade" id="modal{{ $aluno->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <h3>Você tem certeza que deseja desmatricular o aluno
                                                        {{ $aluno->name }}?
                                                    </h3>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Cancelar</button>
                                                    <form action="{{ route('turmas.desmatricular') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="aluno_id" value="{{ $aluno->id }}">
                                                        <input type="hidden" name="turma_id" value="{{ $turma->id }}">
                                                        <button type="submit" class="btn btn-danger">Desmatricular</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                </tr>

                </div>
            @endforeach

            </tbody>
            </table>

        </div>
    @else
        <div>
            <h2>Nenhum aluno matriculado nesta turma </h2>
        </div>
        @endif
    </div>
    </div>
@endsection
