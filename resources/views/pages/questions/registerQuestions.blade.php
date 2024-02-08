@extends('layouts.app')

@section('page-name', $titulo)
@section('content')
    <div class="">
        {{-- <div class="">
        <button class="btn text-white btn-success" wire:click.prevent="addQuestion({{ $id }})">Adicionar
        </button>
    </div> --}}
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
                    <input type="text" name="question" class="form-control @error('question') is-invalid @enderror"
                        id="question" value="{{ old('question', $question) }}" required autocomplete="question"
                        autofocus />
                </div>
            </div>
            {{-- <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="">Resposta</label>
                        <select class="form-control name= question[{{$id}}][answer]" aria-label="">
                            <option selected>Selecione a Resposta</option>
                            <option value="a">Alternativa A</option>
                            <option value="b">Alternativa B</option>
                            <option value="c">Alternativa C</option>
                            <option value="d">Alternativa D</option>
                        </select>
                    </div>
                </div>  --}}
    </div>
    <div class="row mt-3">
        <div class="col-md-3 col-sm-12">
            <div class="form-group">
                <label for="">Resposta Correta</label>
                <input type="text" name="A" class="form-control @error('A') is-invalid @enderror" id="A"
                    value="{{ old('A', $A) }}" required autocomplete="A" autofocus />
            </div>
        </div>
        <div class="col-md-3 col-sm-12">
            <div class="form-group">
                <label for="">Resposta Errada</label>
                <input type="text" name="B" class="form-control @error('B') is-invalid @enderror" id="B"
                    value="{{ old('B', $B) }}" required autocomplete="B" autofocus />
            </div>
        </div>
        <div class="col-md-3 col-sm-12">
            <div class="form-group">
                <label for="">Resposta Errada</label>
                <input type="text" name="C" class="form-control @error('C') is-invalid @enderror" id="C"
                    value="{{ old('C', $C) }}" required autocomplete="C" autofocus />
            </div>
        </div>
        <div class="col-md-3 col-sm-12">
            <div class="form-group">
                <label for="">Resposta Errada</label>
                <input type="text" name="D" class="form-control @error('D') is-invalid @enderror" id="D"
                    value="{{ old('D', $D) }}" required autocomplete="D" autofocus />
            </div>
        </div>
        <div class="form-group mt-4 text-center justfy-content-center">
            <input type="submit" value="Salvar" class="btn btn-success">
        </div>
    </div>
    </form>
    <hr>
    </div>
    </div>
@endsection

