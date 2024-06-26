@extends('layouts.app')

@section('page-name', $titulo)

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
            <form method="POST" action="{{ route('user.storeMatricula') }}" enctype="multipart/form-data" file="true">
                @csrf

                <div class="form-group">
                    <label for="">Escolha a turma*</label>
                    <select class="form-control" name="turma_id">
                        @foreach ($turmas as $item)
                            <option value="{{ $item->id }}"
                                @if ($item->id === $turma_id) selected="selected" @endif>
                                {{ $item->nome }}</option>
                        @endforeach
                    </select>
                </div>



                <div class="form-group">
                    <label for="">Arquivo CSV*</label>
                    <input type="file" style="border:none" class="form-control" name="csv" id="csv"
                        accept=".csv" required>

                    <div class="alert alert-primary">
                        <b>Instruções para o arquivo CSV</b>
                        <ul>
                            <li style="list-style:square">Separado por ponto e vírgula (;)</li>
                            <li style="list-style:square">A primeira linha com cabeçalho. A segunda linha em diante com dados</li>
                            <li style="list-style:square">Três colunas: a primeira é ignorada, a segunda com a matrícula e a terceira com o nome completo</li>
                            <li style="list-style:square">A matrícula será usada como nome do usuário e senha</li>
                        </ul>
                    </div>
                </div>

               

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            Importar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
