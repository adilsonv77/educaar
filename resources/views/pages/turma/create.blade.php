@extends('layouts.app')

@section('page-name', $titulo)

@section('content')
    <div class="">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('turmas.store') }}">
                    @csrf

                    <input name="id" type="hidden" value="{{ $id }}" />
                    <input name="acao" type="hidden" value="{{ $acao }}" />


                    <div class="form-group">
                        <label for="name">Nome Da Turma* </label>
                        <div class="col-md-10">
                            <input id="nome" type="text" class="form-control @error('nome') is-invalid @enderror"
                                name="nome" value="{{ old('nome', $nome) }}" required autocomplete="nome" autofocus 
                                maxlength="100"/>
                        </div>

                        <br>

                        <div class="form-group">
                            <label for="">Escolha o Ano*</label>
                            <select class="form-control" name="ano_id">
                                @foreach ($anosletivos as $item)
                                    <option value="{{ $item->id }}"
                                        @if ($item->id === $ano_id) selected="selected" @endif>
                                        {{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Escolha a Turma Modelo*</label>
                            <select class="form-control" @if ($turma_modelo_id != 0) delete @endif
                                name="turma_modelo_id">
                                @if ($turma_modelo_id != 0)
                                    <style>

                                    </style>
                                @endif
                                @foreach ($turmasModelo as $item)
                                    <option value="{{ $item->id }}"
                                        @if ($item->id === $turma_modelo_id) selected="selected" @endif>
                                        {{ $item->serie }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-4">
                            <input type="submit" value="Salvar" class="btn btn-success">
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection
