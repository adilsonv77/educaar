@extends('layouts.app')

@php
    $pageName = 'Disciplinas da Turma';
@endphp

@section('page-name', $pageName)

@section('content')
    <div class="card">
        <div class="card-body">
            @if (!empty($disciplinas))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Ação</th>

                            </tr>
                        </thead>
                        <tbody>

                            <form method="POST" action="{{ route('turmas.storeDisciplinaProf') }}">
                                @csrf
                                @foreach ($disciplinas as $disc)
                                    <tr>

                                        <td>{{ $disc->dname }}</td>


                                        <td>
                                            <div class="form-group">
                                                <label for="">Escolha o professor</label>
                                                <select class="form-control" name="cbx_{{ $disc->did }}">
                                                    @foreach ($professores as $item)
                                                        <option value="{{ $item->id }}"
                                                            @if ($item->id === $disc->pid) selected="selected" @endif>
                                                            {{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                @endforeach
                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            Salvar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </tbody>
                    </table>

                </div>
            @else
                <div>
                    <h2>Nenhuma turma modelo cadastrada</h2>
                </div>
            @endif
        </div>
    </div>
@endsection
