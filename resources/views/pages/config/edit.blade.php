@extends('layouts.app')

@section('page-name', 'Configurar a escola')

@section('content')
    <div class="card">

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('config.store') }}">
                @csrf

                <input type="hidden" name="beforeId" value="{{ $beforeId }}" />

                <div class="form-group row">
                    <label for="schoolName" class="col-md-4 col-form-label text-md-right">Nome da Escola : </label>

                    <div class="col-md-6">
                        <input id="schoolName" type="text" class="form-control @error('name') is-invalid @enderror"
                            name="schoolName" value="{{ old('schoolName', $school->name) }}" required
                            autocomplete="schoolName" autofocus>

                    </div>
                </div>

                <div class="form-group row">
                    <label for="anoLetivoAtual" class="col-md-4 col-form-label text-md-right">Ano letivo atual : </label>
                    <div class="col-md-6">
                        <select id="anoLetivoAtual" name="anoLetivoAtual">
                            @foreach ($anosletivos as $item)
                                <option value="{{ $item->id }}"
                                    @if ($item->bool_atual) selected="selected" @endif>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="prof_Atual" class="col-md-4 col-form-label text-md-right">Professor padrão : </label>
                    <div class="col-md-6">
                        <select id="prof_atual" name="prof_atual">
                            @foreach ($professores as $prof)
                                <option value="{{ $prof->id }}"
                                    @if ($prof->id == $school->prof_atual_id) selected="selected" @endif>
                                    {{ $prof->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            Salvar
                        </button>
                    </div>
                </div>
            </form>


        </div>
    </div>
@endsection
