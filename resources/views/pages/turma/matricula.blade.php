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
            <form method="POST" action="{{ route('user.createMatricula') }}" enctype="multipart/form-data" file="true">
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

    @if(isset($students))
        <div class="modal fade" id="matriculasModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">{{ __('Students to be registrated') }}</h3>
              </div>
              <div class="modal-body">
                
                <table class="table table-layout-fixed">
                    <thead>
                        <tr>
                            <th>{{ __('Registration') }}</th>
                            <th>{{ __('Name') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td>{{ $student['username'] }}</td>
                                <td>{{ $student['name'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                
                <form action="{{ route('user.storeMatricula') }}" method="post"> @csrf
                    <button type="submit" class="btn btn-primary">{{ __('Confirm') }}</button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                $('#matriculasModal').modal('show');
            })
        </script>
    @endif

@endsection
