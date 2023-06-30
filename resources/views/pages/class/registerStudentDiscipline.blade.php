@extends('layouts.app')

@section('page-name', 'Cadastrar aluno na disciplina')

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
            <form method="POST" action="{{ route('disciplina.storeAlunoDisciplina') }}">
                @csrf

                <div class="form-group row">
                    <label for="discipline" class="col-md-4 col-form-label text-md-right">{{ __('Student') }}</label>
                    <div class="col-md-6">
                        <select name="student" id="student" class="form-control @error('student') is-invalid @enderror">
                            <option value=""></option>

                            @foreach ($students as $student)
                                <option value="{{ $student->username }}">{{ $student->username }}</option>
                            @endforeach
                        </select>

                        @error('student')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>
                </div>

                <div class="form-group row">
                    <label for="discipline" class="col-md-4 col-form-label text-md-right">{{ __('Disciplina') }}</label>
                    <div class="col-md-6">
                        @foreach ($disciplinas as $discipline)
                            <input style="margin-left: 10px;" type="checkbox" id="discipline" name="discipline"
                                value="{{ $discipline->name }}">
                            <label style="margin-left: 10px;" for="discipline">{{ $discipline->name }}</label>
                        @endforeach
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            Cadastrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
