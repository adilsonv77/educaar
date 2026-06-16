@extends('layouts.app')

@php
    $pageName = __('Classes');
@endphp

@section('page-name', $pageName)

@section('content')


    <div>
        <form action="{{ route('turmas.create') }}">
            @csrf
            <button class="btn btn-sm btn-primary " id="novo" title="Novo"><i class="bi bi-plus-circle-dotted h1" style = "color : #ffffff;"></i></button>
        </form>
    </div>
    <form action="{{ route('turmas.index') }}" method="GET ">
        @csrf
        
        <div class="form-inline">
        <label for="">{{ __('Enter the class') }} :</label>
            <select class="form-control" name="ano_id">
                @foreach ($anosletivos as $item)
                    <option value="{{ $item->id }}" @if ($item->id === $anoletivo->id) selected="selected" @endif>
                        {{ $item->name }}</option>
                @endforeach
            </select>
            <section class="itens-group">
                <button class="btn btn-primary btn-lg "type="submit">{{ __('Search') }}</button>
            </section>
        </div>
    </form>
    <br>
    <style>
    .form-inline{
        display: flex;
        justify-content: flex-start; 
    }

    .form-inline label {
      
      margin-right: 10px;
    }
</style>

    <div class="card">
        <div class="card-body">
            @if (!empty($turmas))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Model Class') }}</th>
                                <th>{{ __('Students') }}</th>
                                <th>{{__('Subjects')  }}</th>
                                <th>{{ __('Edit') }}</th>
                                <th>{{ __('Delete') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($turmas as $item)
                                <tr>

                                    <td>{{ $item->nome }}</td>
                                    <td>{{ $item->serie }}</td>
                                    
                                    <td>
                                        <form action="{{ route('turmas.turmasAlunosIndex', $item->id) }}">
                                            @csrf
                                            <input type="hidden" name="turma_id" value="{{ $item->id }}">
                                            <button type="submit" class="btn btn-primary" title="Alunos">
                                                <i class="bi bi-people-fill h2" style = "color : #ffffff;"></i>
                                                ({{ $item->qtosAlunos }})</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{ route('turmas.disciplinasIndex', $item->id) }}">
                                            @csrf
                                            <input type="hidden" name="turma_id" value="{{ $item->id }}">
                                            <button type="submit" class="btn btn-primary"  title="Disciplinas">
                                            <i class="bi bi-stack h2" style = "color : #ffffff;"></i>

                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{ route('turmas.edit', $item->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-warning"
                                                @if ($item->qtosAlunos > 0) disabled @endif  title={{ __('Edit') }}>
                                                <i class="bi bi-pencil-square h2" style = "color : #ffffff;"></i></button>
                                        </form>
                                    </td>
                                    <td>
                                        <button type="button"
                                            class="btn btn-danger"@if ($item->qtosAlunos > 0) disabled @endif
                                            data-toggle="modal" data-target="#modal{{ $item->id }}" title={{ __('Delete') }}>
                                            <i class="bi bi-trash3 h2" style = "color : #ffffff;"></i>
                                        </button>
                                    </td>
                                    <div class="modal fade" id="modal{{ $item->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <h3>{{ __('Confirm delete the class :class', ["class" => $item->nome]) }}</h3>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">{{ __('Cancel') }}</button>
                                                    <form action="{{ route('turmas.destroy', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
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
                    <h2>{{ __('No class registered') }}</h2>
                </div>
            @endif
        </div>
    </div>
@endsection
