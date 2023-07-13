@extends('layouts.app')

@php
    $pageName = $turma;
@endphp

@section('page-name', $pageName)

@section('content')

    <div class="card">
        <div class="card-body">
            @if (!empty($alunos))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Nome</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($alunos as $item)
                            <tr>
                                    <td>{{ $item->name }}</td>
                            
                            <td>
                                <button type="button"
                                    class="btn btn-danger"data-toggle="modal" data-target="#modal{{ $item->id }}">
                                    Desmatricular
                                </button>
                            </td>
                            <div class="modal fade" id="modal{{ $item->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <h3>Você tem certeza que deseja desmatricular o aluno {{ $item->name }}?
                                            </h3>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cancelar</button>
                                            <form action="{{ route('turmas.desmatricular') }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="aluno_id" value="{{ $item->id }}">
                                                <button type="submit" class="btn btn-danger">Desmatircular</button>
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