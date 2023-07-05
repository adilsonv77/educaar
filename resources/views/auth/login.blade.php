@extends('layouts.app')



@section('content')
    <div class="">
        {{-- <div class="h-100"> --}}
        {{-- <div class="h-100 align-items-center"> --}}
        {{-- <div class="row justify-content-center"> --}}
        {{-- <div class="col-md-4"> --}}
        <div class="card">
            <div class="card-header">{{ __('Login') }}</div>
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
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group row">
                        <div class="item-row login">
                            <div class="col-9">


                                <input id="login" type="text"
                                    class="form-control{{ $errors->has('username') || $errors->has('email') ? ' is-invalid' : '' }}"
                                    name="login" value="{{ old('username') ?: old('email') }}" required autofocus
                                    placeholder="Login">
                                </label>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="item-row password">

                            <div class="col-9">

                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="current-password" placeholder="Senha">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- <div class="form-group row mb-0 btn"> --}}
                    <!--   <div class="col-md-8 offset-md-4 lulu">-->
                    <button type="submit" class="btn btn-primary">
                        {{ __('Login') }}
                    </button>
                    <!--</div>-->
                    {{-- </div> --}}
                </form>
            </div>
            {{-- </div> --}}
        </div>
        {{-- </div> --}}
        {{-- </div> --}}
        {{-- </div> --}}
    </div>
@endsection


@section('style')
    {{-- <style>
        .content-body {
            text-align: center;


        }
    </style> --}}
    <link rel="stylesheet" href="/css/login.css">
@endsection
