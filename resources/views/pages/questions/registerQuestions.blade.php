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
            width: 650px; 
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
        <form method="POST" action="{{ route('questions.store') }}" enctype="multipart/form-data" files="true">
            <h3>Questão</h3>
            @csrf
            <input name="id" type="hidden" value="{{ $id }}" />
            <input name="acao" type="hidden" value="{{ $acao }}" />
            <input name="answer" type="hidden" value="{{ $answer }}" />
            <input name="activity_id" type="hidden" value="{{ $activity_id }}" />

            <div class="col-md-9 col-sm-12">
                <div class="form-group">
                    <label for="">Enunciado</label>
                    <textarea rows="2" cols="75" type="text" name="question" maxlength="150" class="form-control @error('question') is-invalid @enderror"
                        id="question" value="{{ old('question', $question) }}" required autocomplete="question"
                        autofocus ></textarea>
                </div>
            </div>
           
    </div>
   
        <div class="col-md-3 col-sm-12">
            <div class="form-group">
                <label for="">Resposta Correta</label>
                <input  type="text" name="A"  maxlength="75" class="form-control @error('A') is-invalid @enderror" id="A"
                    value="{{ old('A', $A) }}" required autocomplete="A" autofocus />
            </div>
        </div>
        <div class="col-md-3 col-sm-12">
            <div class="form-group">
                <label for="">Resposta Errada</label>
                <input type="text" name="B" maxlength="75" class="form-control @error('B') is-invalid @enderror" id="B"
                    value="{{ old('B', $B) }}" required autocomplete="B" autofocus />
            </div>
        </div>
        <div class="col-md-3 col-sm-12">
            <div class="form-group">
                <label for="">Resposta Errada</label>
                <input type="text" name="C" maxlength="75" class="form-control @error('C') is-invalid @enderror" id="C"
                    value="{{ old('C', $C) }}" required autocomplete="C" autofocus />
            </div>
        </div>
        <div class="col-md-3 col-sm-12">
            <div class="form-group">
                <label for="">Resposta Errada</label>
                <input type="text" name="D" maxlength="75" class="form-control @error('D') is-invalid @enderror" id="D"
                    value="{{ old('D', $D) }}" required autocomplete="D" autofocus />
            </div>
        </div>
        <div class="form-group mt-4 text-center justfy-content-center">
            <input type="submit" value="Salvar" class="btn btn-success">
        </div>
    
    </form>
    <hr>
    </div>
    </div>
@endsection

