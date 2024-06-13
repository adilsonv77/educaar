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
                    <form method="POST" action="{{ route('turmas.storeDisciplinaProf') }}">
                        <table class="table table-hover table-responsive-sm">
                            <thead>
                                <tr>
                                    <th>Disciplina</th>
                                    <th>Escolha o professor
                                    </th>

                                </tr>
                            </thead>
                            <tbody>

                                @csrf
                                @foreach ($disciplinas as $disc)
                                    <tr>

                                        <td>{{ $disc->dname }}</td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control" name="cbx_{{ $disc->did }}">
                                                    @foreach ($professores as $item)
                                                        <option value="{{ $item->id }}"
                                                            @if ($item->id === $disc->pid) selected="selected" @endif>
                                                            {{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                    
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">
                                Salvar
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div>
                    <h2>Nenhuma turma modelo cadastrada</h2>
                </div>
            @endif
        </div>
    </div>
@endsection
