@extends('layouts.app')

@section('page-name', $titulo)

@section('content')

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-warning">
        {{ session('error') }}
    </div>
@endif

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/pt.js"></script>

<div class="main">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('sala.store') }}" method="post"> @csrf
                <input type="hidden" name="jogo_id" value={{ $jogoId }}>

                <div class="form-group row">
                    <label for="name">{{ __('Name') }}</label>
                    <input type="text" name="nome" id="nome" class="form-control @error('name') is-invalid @enderror" required autofocus>
                </div>

                <div class="form-group row">
                    <label for="class">{{ __('Class') }}</label>
                    <select name="turma_id" id="turma_id" class="form-control" required>
                        @foreach($classes as $class)
                            <option value={{ $class->id }}>
                                {{ $class->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group row">
                    <label for="rule">{{ __('Rules') }}</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#rule-create">
                                + {{ __('New Rule') }}
                            </button>
                        </div>
                        <select name="regra_id" id="regra_id" class="form-control" required>
                            @foreach($rules as $rule)
                                <option value="{{ $rule->id }}"> 
                                    {{ $rule->pontMax }} pontos | 
                                    @if(empty($rule->data_inicio))
                                        {{ $rule->tempo }} segundos
                                    @else
                                        De {{ \Carbon\Carbon::parse($rule->data_inicio)->format('d/m/Y H:i') }} até {{ \Carbon\Carbon::parse($rule->data_limite)->format('d/m/Y H:i') }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row mt-4">
                    <button type="submit" class="btn btn-success">{{ __('Save') }}</button>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="rule-create" tabindex="-1" role="dialog" data-backdrop="true" aria-labelledby="rule-create" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h1>Criar nova regra</h1>

                <form action="{{ route('regra.store') }}" method="post" id="form-regra" class="p-3"> 
                    @csrf
                    <input type="hidden" name="jogo_id" value="{{ $jogoId }}">

                    <div class="form-group row">
                        <label for="tempo">{{ __('Time Limit') }}</label>
                        <input type="number" class="form-control" name="tempo" id="tempo" min="0">
                    </div>

                    <div class="form-group row">
                        <label for="data_inicio">{{ __('Starting Date') }}</label>
                        <input type="text" class="form-control seletor-data" name="data_inicio" id="data_inicio" placeholder="Selecione a data e hora...">
                    </div>

                    <div class="form-group row">
                        <label for="data_limite">{{ __('Deadline') }}</label>
                        <input type="text" class="form-control seletor-data" name="data_limite" id="data_limite" placeholder="Selecione a data e hora...">
                    </div>

                    <div class="form-group row">
                        <label for="score">{{ __('Score') }}</label>
                        <input type="number" class="form-control" name="pontMax" id="pontMax" min="0" required>
                    </div>

                    <div class="form-group row">
                        <button type="submit" class="btn btn-primary mt-4 w-100">{{ __('Save') }}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        flatpickr(".seletor-data", {
            enableTime: true, 
            dateFormat: "Y-m-d H:i", 
            altInput: true,
            altFormat: "d/m/Y H:i", 
            locale: "pt" 
        });

        document.getElementById('form-regra').addEventListener('submit', function(event) {
            const dataInicio = document.getElementById('data_inicio').value;
            const dataLimite = document.getElementById('data_limite').value;

            if (dataInicio && !dataLimite) {
                event.preventDefault();
                alert('A Data Limite também precisa ser selecionada se a Data de Início for preenchida.');
            } else if (!dataInicio && dataLimite) {
                event.preventDefault();
                alert('A Data de Início também precisa ser selecionada se a Data Limite for preenchida.');
            }
        });
    });
</script>

@endsection
