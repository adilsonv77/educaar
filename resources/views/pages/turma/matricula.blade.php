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
                    <label for="">{{ __('Enter the class') }}*</label>
                    <select class="form-control" name="turma_id">
                        @foreach ($turmas as $item)
                            <option value="{{ $item->id }}"
                                @if ($item->id === $turma_id) selected="selected" @endif>
                                {{ $item->nome }}</option>
                        @endforeach
                    </select>
                </div>



                <div class="form-group">
                    <label for="">{{ __('CSV File') }}*</label>
                    <input type="file" style="border:none" class="form-control" name="csv" id="csv"
                        accept=".csv" required>

                    <div class="alert alert-primary">
                        <b>{{ __('CSV file instructions') }}</b>
                        <ul>
                            <li style="list-style:square">{{ __('Separated by semicolon (;)') }}</li>
                            <li style="list-style:square">{{ __('The first line with header. The second line onwards with the data') }}</li>
                            <li style="list-style:square">{{ __('Three columns: the first is ignored, the second with the registration number and the third with the full name') }}</li>
                            <li style="list-style:square">{{ __('The registration number will be used as the username and password') }}</li>
                        </ul>
                    </div>
                </div>

               

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Import') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
