@extends('layouts.app')



@section('content')
    <div class="">
        {{-- <div class="h-100"> --}}
        {{-- <div class="h-100 align-items-center"> --}}
        {{-- <div class="row justify-content-center"> --}}
        {{-- <div class="col-md-4"> --}}
        <div class="img-wrapper">

            <img src="{{ asset('images/GameLAB.png') }}" alt="Imagem" class="img-fluid">

            <img src="{{ asset('images/Fapesc.png') }}" alt="Imagem" class="img-fluid">


        </div>
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
                            {{-- <div class="col-9"> --}}


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
                            {{-- </div> --}}
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="item-row password">

                            {{-- <div class="col-9"> --}}

                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="current-password" placeholder="Senha">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            {{-- </div> --}}
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
        </div>
        {{-- </div> --}}
        {{-- </div> --}}
        {{-- </div> --}}
        {{-- </div> --}}
    </div>


@section('script')
    <link rel="stylesheet" href="{{ asset('css/login.css?v=' . filemtime(public_path('css/login.css'))) }}" />
@endsection




{{-- @section('style')
    <style>
        h-100 {

            background-color: #fff;
        }


        .card {
            display: flex;
            box-shadow: 5px 5px 5px darkgrey;

        }


        .card-body {
            padding-left: 30%;
            box-shadow: 5px 5px 5px darkgrey;


        }



        .item-row.login {
            display: flex;

            width: 90%;

            margin-left: 20%;



        }

        .item-row.password {
            display: flex;

            width: 90%;

            margin-left: 20%;

        }

        .btn.btn-primary {

            width: 150px;


        }



        @media screen and (max-width: 600px) {


            .btn.btn-primary {

                width: 100px;
                margin-left: 125px;
            }
        }


        @media screen and (min-width: 768px) and (max-width: 992px) {
            .content-body {


                padding-right: 20%;
            }


        }


        @media screen and (min-width: 992px) {
            .content-body {
                text-align: center;

                padding-right: 30%;
                padding-left: 12%;
            }




        }
    </style>
@endsection --}}
@endsection
