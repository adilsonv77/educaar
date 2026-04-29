@extends('layouts.app')

@section('page-name', $titulo)
@section('content')
<div class="">
    {{-- <div class="">
        <button class="btn text-white btn-success" wire:click.prevent="addQuestion({{ $id }})">Adicionar
    </button>
</div> --}}
<style>
    input[type="text"] {
        width: 480px;
    }
</style>

<div class="mt-5 mb-5">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    {{-- @if ($key !== 0)
            <button class="btn text-white btn-danger " wire:click.prevent="removeQuestion({{ $key }})">Deletar questão</button>
    @endif --}}
</div>
<form id="questionForm"  method="POST" action="{{ route('questions.store') }}" enctype="multipart/form-data" files="true"  autocomplete="off">
    <h3>{{ __('Questions') }}</h3>
    @csrf
    <input name="id" type="hidden" value="{{ $id }}" />
    <input name="acao" type="hidden" value="{{ $acao }}" />
    <input name="answer" type="hidden" value="{{ $answer }}" />
    <input name="activity_id" type="hidden" value="{{ $activity_id }}" />

    <div class="col-md-9 col-sm-12">
        <div class="form-group">
            <label for="">{{ __('Statement') }}</label>
            <textarea rows="2" cols="150" type="text" name="question" maxlength="98" class="form-control @error('question') is-invalid @enderror" id="question" required autofocus>{{ old('question', $question) }}</textarea>
        </div>
    </div>

    </div>

    <div class="col-md-3 col-sm-12">
        <div class="form-group">
            <label for="">{{ __('Correct Answer') }}</label>
            <input type="text" name="A" maxlength="50" class="form-control @error('A') is-invalid @enderror" id="A" style="border-width: 2px;border-color: #77dd77;" value="{{ old('A', $A) }}" required autofocus />
        </div>
    </div>
    <div class="col-md-3 col-sm-12">
        <div class="form-group">
            <label for="">{{ __('Wrong Answer') }}</label>
            <input type="text" name="B" maxlength="50" class="form-control @error('B') is-invalid @enderror" id="B" style="border-width: 2px;border-color: #ff6961; color: black;" value="{{ old('B', $B) }}" required autofocus />
        </div>
    </div>
    <div class="col-md-3 col-sm-12">
        <div class="form-group">
            <label for="">{{ __('Wrong Answer') }}</label>
            <input type="text" name="C" maxlength="50" class="form-control @error('C') is-invalid @enderror" id="C" style="border-width: 2px;border-color: #ff6961; color: black;" value="{{ old('C', $C) }}" required  autofocus />
        </div>
    </div>
    <div class="col-md-3 col-sm-12">
        <div class="form-group">
            <label for="">{{ __('Wrong Answer') }}</label>
            <input type="text" name="D" maxlength="50" class="form-control @error('D') is-invalid @enderror" id="D" style="border-width: 2px;border-color: #ff6961; color: black;" value="{{ old('D', $D) }}" required autofocus />
        </div>
    </div>
    <div class="form-group mt-4 text-center justfy-content-center">
        @if(Route::currentRouteName() == 'questions.edit')
        <!-- Se estiver editando, o botão abre o modal -->
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#confirmModal">{{ __('Save') }}</button>
        @else
        <!-- Se estiver criando, o botão salva diretamente -->
        <button type="submit" class="btn btn-success">{{ __('Save') }}</button>
        @endif
    </div>
</form>

<!-- Modal de Confirmação de Exclusão -->
@if(Route::currentRouteName() == 'questions.edit')

<div class="modal fade" id="confirmModal"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 600px">
            <div class="modal-body">
                <h3>{{ __('How do you want to save your changes?') }}?</h3>
            </div>
            <div class="modal-footer" justify-content-center>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>

                <button type="button" class="btn btn-danger" onclick="excluirESalvar()">{{ __('Delete answers and save') }}</button>
                 
                <button type="button" class="btn btn-success" onclick="document.getElementById('questionForm').submit();">{{ __('Save') }}</button>
            </div>
        </div>
    </div>
</div>


@endif

<hr>
</div>
</div>

<script>
    function excluirESalvar() {
        let form = document.getElementById('questionForm');

        let input = document.createElement('input');

        input.type = 'hidden';
        input.name = 'delete_answers';

        input.value = '1';
        form.appendChild(input);
        form.submit();
    }
</script>
@endsection