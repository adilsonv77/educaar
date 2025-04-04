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
<form id="questionForm"  method="POST" action="{{ route('questions.store') }}" enctype="multipart/form-data" files="true">
    <h3>Questão</h3>
    @csrf
    <input name="id" type="hidden" value="{{ $id }}" />
    <input name="acao" type="hidden" value="{{ $acao }}" />
    <input name="answer" type="hidden" value="{{ $answer }}" />
    <input name="activity_id" type="hidden" value="{{ $activity_id }}" />

    <div class="col-md-9 col-sm-12">
        <div class="form-group">
            <label for="">Enunciado</label>
            <textarea rows="2" cols="150" type="text" name="question" maxlength="98" class="form-control @error('question') is-invalid @enderror" id="question" required autocomplete="question" autofocus>{{ old('question', $question) }}</textarea>
        </div>
    </div>

    </div>

    <div class="col-md-3 col-sm-12">
        <div class="form-group">
            <label for="">Resposta Correta</label>
            <input type="text" name="A" maxlength="50" class="form-control @error('A') is-invalid @enderror" id="A" style="border-width: 2px;border-color: #77dd77;" value="{{ old('A', $A) }}" required autocomplete="A" autofocus />
        </div>
    </div>
    <div class="col-md-3 col-sm-12">
        <div class="form-group">
            <label for="">Resposta Errada</label>
            <input type="text" name="B" maxlength="50" class="form-control @error('B') is-invalid @enderror" id="B" style="border-width: 2px;border-color: #ff6961; color: black;" value="{{ old('B', $B) }}" required autocomplete="B" autofocus />
        </div>
    </div>
    <div class="col-md-3 col-sm-12">
        <div class="form-group">
            <label for="">Resposta Errada</label>
            <input type="text" name="C" maxlength="50" class="form-control @error('C') is-invalid @enderror" id="C" style="border-width: 2px;border-color: #ff6961; color: black;" value="{{ old('C', $C) }}" required autocomplete="C" autofocus />
        </div>
    </div>
    <div class="col-md-3 col-sm-12">
        <div class="form-group">
            <label for="">Resposta Errada</label>
            <input type="text" name="D" maxlength="50" class="form-control @error('D') is-invalid @enderror" id="D" style="border-width: 2px;border-color: #ff6961; color: black;" value="{{ old('D', $D) }}" required autocomplete="D" autofocus />
        </div>
    </div>
    <div class="form-group mt-4 text-center justfy-content-center">
        @if(Route::currentRouteName() == 'questions.edit')
        <!-- Se estiver editando, o botão abre o modal -->
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#confirmModal">Salvar</button>
        @else
        <!-- Se estiver criando, o botão salva diretamente -->
        <button type="submit" class="btn btn-success">Salvar</button>
        @endif
    </div>
</form>

<!-- Modal de Confirmação de Exclusão -->
@if(Route::currentRouteName() == 'questions.edit')

<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h3>Você tem certeza que deseja salvar as mudaças?</h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <!-- isso n ta prnto ainda, ele ta excluidno as resposntas mas n ta salvando -->
                <!-- <form action="{{ route('answers.destroy', $id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-success" onclick="document.getElementById('questionForm').submit();">Excluir Respostas e Salvar</button>
                </form> -->
                <button type="submit" class="btn btn-success" onclick="document.getElementById('questionForm').submit();">Salvar</button>
            </div>
        </div>
    </div>
</div>


@endif

<hr>
</div>
</div>
@endsection