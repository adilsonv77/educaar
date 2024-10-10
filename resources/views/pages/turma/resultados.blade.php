@extends('layouts.app')

@php
    $pageName= 'Resultados de uma turma';

@endphp

@section('page-name', $pageName)

@section('script-head')
@endsection

@section('content')

<div id="formTurma">
    <form action="" method="POST">
          @csrf
          
          <div class="col-md-6">
              <label>Informe a turma : </label>

                <select class="form-control" name="turma_id" id="turma_id">
                    @foreach ($turmas as $item)
                        <option value="{{ $item->id }}" @if ($item->id === $turma->id) selected="selected" @endif>
                            {{ $item->nome }}</option>
                    @endforeach
                </select>
                
         </div>
    </form>

    <table id="table" class="table table-bordered">
      <thead class="thead-info">
        <tr>
            <th scope="col">Nome</th>
            @foreach ($contents as $content)
                <th scope="col"> {{ $content['name'] }} </th>
            
            @endforeach
        </tr>
      </thead>
    </table>

</div>

@endsection