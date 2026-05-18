@extends('layouts.app')

@php
    $pageName = __('Model Classes');
@endphp

@section('page-name', $pageName)

@section('content')

    <div>
        <form action="{{ route('turmasmodelos.create') }}">
            @csrf
            <button class="btn btn-sm btn-primary " id="novo" title="Novo"><i class="bi bi-plus-circle-dotted h1" style = "color : #ffffff;"></i></button>
        </form>
    </div>

    <form action="{{ route('turmasmodelos.index') }}" method="GET">
    
        <div class="form-inline">
        <label for="">{{ __('Enter the Model Class') }}: </label>
            <input class="form-control" type="text" name="titulo" id="titulo" value="{{ $turmas }}"
                list="historico" />
            <section class="itens-group">
                <button class="btn btn-primary btn-lg" type="submit">{{ __('Search') }}</button>
            </section>
        </div>
    </form>
    <br/>
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
                                <th>{{ __('Edit') }}</th>
                                <th>{{ __('Delete') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($turmas as $item)
                                <tr>

                                    <td>{{ $item->serie }}</td>


                                    <td>
                                        <form action="{{ route('turmasmodelos.edit', $item->id) }}">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-warning"@if ($item->qntTurmas > 0 || $item->conteudos > 0) disabled @endif title={{ __('Edit') }}>
                                                <i class="bi bi-pencil-square h2" style = "color : #ffffff;"></i>
                                                </button>
                                        </form>
                                    </td>
                                    <td>
                                        <button type="button"
                                            class="btn btn-danger"@if ($item->qntTurmas > 0 || $item->conteudos > 0) disabled @endif
                                            data-toggle="modal" data-target="#modal{{ $item->id }}" title={{ __('Delete') }}>
                                            <i class="bi bi-trash3 h2" style = "color : #ffffff;"></i>
                                        </button>
                                    </td>
                                    <div class="modal fade" id="modal{{ $item->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <h3>{{ __('Confirm delete the model class :model_class', ["model_class" => $item->serie]) }}</h3>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">{{ __('Cancel') }}</button>
                                                    <form action="{{ route('content.destroy', $item->id) }}" method="POST">
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
                    <h2>{{ __('No model class registered') }}</h2>
                </div>
            @endif
        </div>
    </div>
@endsection
