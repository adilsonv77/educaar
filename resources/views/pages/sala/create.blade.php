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
                                        De {{ \Carbon\Carbon::parse($rule->data_inicio)->format('d/m/Y') }} até {{ \Carbon\Carbon::parse($rule->data_limite)->format('d/m/Y') }}
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

                <form action="{{ route('regra.store') }}" method="post" class="p-3"> @csrf
                    <input type="hidden" name="jogo_id" value="{{ $jogoId }}">

                    <div class="mb-4">
                        <div class="custom-control custom-switch switch">
                            <input type="hidden" name="time_limit" value="0">
                            <input type="checkbox" name="time_limit" id="time_limit" class="custom-control-input" value="1" onchange="toggleTimeLimit(this)">
                            <label for="time_limit" class="custom-control-label">{{ __('Switch Time Limit') }}</label>
                        </div>
                    </div>

                    <div class="form-group row" id="block-duration" style="display: none;">
                        <label for="tempo">{{ __('Time Limit') }}</label>
                        <input type="number" class="form-control" name="tempo" id="tempo" min=0>
                    </div>

                    <div id="block-dates">
                        <div class="form-group row">
                            <label for="data_inicio">{{ __('Starting Date') }}</label>
                            <input type="date" class="form-control" name="data_inicio" id="data_inicio" required>
                        </div>

                        <div class="form-group row">
                            <label for="data_limite">{{ __('Deadline') }}</label>
                            <input type="date" class="form-control" name="data_limite" id="data_limite" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="score">{{ __('Score') }}</label>
                        <input type="number" class="form-control" name="pontMax" id="pontMax" min=0 required>
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
    function toggleTimeLimit(checkbox) {
        const blockDuration = document.getElementById('block-duration');
        const blockDates = document.getElementById('block-dates');
        const inputTempo = document.getElementById('tempo');
        
       
        const inputStarting = document.getElementById('data_inicio'); 
        const inputDeadline = document.getElementById('data_limite'); 

        if (checkbox.checked) {
            blockDuration.style.display = 'flex'; 
            blockDates.style.display = 'none';
            
            inputTempo.required = true;
            inputStarting.required = false;
            inputDeadline.required = false;
            
            inputStarting.value = '';
            inputDeadline.value = '';
        } else {
            blockDuration.style.display = 'none';
            blockDates.style.display = 'block';
            
            inputTempo.required = false;
            inputStarting.required = true;
            inputDeadline.required = true;
            
            inputTempo.value = '';
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        const switchBtn = document.getElementById('time_limit');
        if(switchBtn) {
            toggleTimeLimit(switchBtn);
        }
    });
</script>

@endsection
