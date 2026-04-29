@extends('layouts.app')

@php
    $pageName = __('Students');
@endphp

@section('page-name', $pageName)

@section('content')

<div id="formTurma">
    <form action="{{ route('turmas.listarTurmasAlunosProf') }}" method="GET ">
          @csrf
          
          <div class="form-inline">
          <label for="">{{ __('Enter the class') }}</label>
              <select class="form-control" name="turma_id">
                  @foreach ($turmas as $item)
                      <option value="{{ $item->id }}" @if ($item->id === $turma->id) selected="selected" @endif>
                          {{ $item->nome }}</option>
                  @endforeach
              </select>
              <button class="btn btn-primary btn-lg" type="submit">{{ __('Search') }}</button>
          </div>
    </form>
    <br>
    

    </div>
    <div class="geral">
        <form  action="{{ route('student.naorespondidas') }}">
            <input type="hidden" name="turma_id" value="{{ $turma->id }}">
            <button type="submit" class="btn btn-success" >
                {{ __('Report of unanswered questions') }}
            </button>
        </form>
    </div>
    <br>

    <style>
    .form-inline{
        display: flex;
        justify-content: flex-start; 
    }
    .form-inline label {
      
      margin-right: 10px;
    }

    .geral{
    display: flex;
   
    justify-content: center;

    }

 
</style>

    <div class="card">
        <div class="card-body">
            @if (!empty($alunos))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Correct Questions') }}</th>
                                <th>{{ __('Incorrect Questions') }}</th>
                                <th>{{ __('Unfinished Questions') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($alunos as $item)
                                <tr>

                                    <td>{{ $item['name'] }}</td>


                                    <td style="width: 10%;">
                                        <form action="{{ route('student.results') }}">
                                        <input type="hidden" name="aluno_id" value="{{ $item['id'] }}">
                                        <input type="hidden" name="type_question" value="corretas">
                                        <input type="hidden" name="turma_id" value="{{ $turma->id }}">
                                            <button type="submit" class="btn btn-success" title="Resultados" >
                                                {{ $item['qntCorretas'] }}
                                            </button>
                                        </form>
                                    </td>
                                    <td style="width: 10%;">
                                        <form action="{{ route('student.results') }}">
                                        <input type="hidden" name="aluno_id" value="{{ $item['id'] }}">
                                        <input type="hidden" name="type_question" value="incorretas">
                                        <input type="hidden" name="turma_id" value="{{ $turma->id }}">
                                            <button type="submit" class="btn btn-success" title="Resultados" >
                                                {{ $item['qntIncorretas'] }}
                                            </button>
                                        </form>
                                    </td>
                                    <td style="width: 10%;">
                                        <form action="{{ route('student.results') }}">
                                        <input type="hidden" name="aluno_id" value="{{ $item['id'] }}">
                                        <input type="hidden" name="type_question" value="naofeitas">
                                        <input type="hidden" name="turma_id" value="{{ $turma->id }}">
                                            <button type="submit" class="btn btn-success" title="Resultados" >
                                                {{ $item['qntNaoRespondidas'] }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                 </div>
            @else
                <div>
                    <h2>{{ __('No Students') }}</h2>
                </div>
            @endif
        </div>
    </div>



@endsection
