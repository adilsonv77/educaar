@extends('layouts.app')

@section('page-name', __('Teacher Settings'))

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
                                <th>{{ __('Discipline') }}</th>
                                <th>{{ __('Class') }}</th>
                                <th>{{ __('Deadline to answer') }}</th>
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

                    
                        <div class="col-md-6 offset-md-4" >
                            <button type="submit" style="width: 50%; display: inline;" class="btn btn-primary">
                                {{ __('Save') }}
                            </button>
                        </div>
                    
                </form>
            </div>
        @endif

    </div>


   
</div>
@endsection
