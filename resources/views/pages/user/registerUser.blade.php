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
            <form method="POST" action="{{ route('user.store') }}" enctype="multipart/form-data" file="true">
                @csrf

                <input name="id" type="hidden" value="{{ $id }}" />
                <input name="acao" type="hidden" value="{{ $acao }}" />
                <input name="tipo" type="hidden" value="{{ $type }}" />


                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">Nome completo : </label>

                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                            name="name" value="{{ old('name', $name) }}" />

                    </div>
                </div>

                <div class="form-group row">
                    <label for="username" class="col-md-4 col-form-label text-md-right">Login : </label>

                    <div class="col-md-6">
                        <input id="email" type="text" class="form-control @error('username') is-invalid @enderror"
                            name="username" value="{{ old('username', $username) }}">

                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">E-mail : </label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email', $email) }}">

                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }} : </label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password">

                    </div>
                </div>

                <div class="form-group row">
                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}
                        : </label>

                    <div class="col-md-6">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                    </div>
                </div>

                <div class="form-group row">
                        <label for="" class="col-md-4 col-form-label text-md-right">Escolha a Turma:* (Ano Letivo: {{$anoletivo->name}} )</label>
                        <div class= "col-md-6">
                            <select class="form-control" name="turma">
                            @foreach ($turmas as $item)
                                <option value="{{$item->id}}">
                                    {{ $item->nome }} </option>
                            @endforeach
                        </select>
                        </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            Salvar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
