@extends('layouts.app')

@section('page-name', 'Configurações do professor')

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

        @if (!empty($turmas))
            <div class="table-responsive">
                <form method="POST" action="{{ route('profconfig.store') }}">
                @csrf
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Disciplina</th>
                                <th>Turma</th>
                                <th>Data limite para responder</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            @foreach ($turmas as $turma)
                                    <tr>
                                        <td>{{ $turma["disciplina"] }}</td>
                                        <td>{{ $turma["serie"] }}</td>

                                        <td>

                                            <input type="date"  id="dateCorte" class="form-control" style="width: 50%; display: inline;" required value="{{$turma['dataCorte']}}" name="{{ 'data_'.$turma['d_id'].'_'.$turma['t_id'] }}">

                                        </td>
                                    </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                Salvar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @endif

    </div>
</div>
@endsection
