<!DOCTYPE html>
<html lang="pt-br" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="refresh" content="{{ config('session.lifetime') * 60 }}">
    <title>EducaAR</title>
    @yield('style')

    <link rel="stylesheet" href="{{ asset('css/login.css?v=' . filemtime(public_path('css/login.css'))) }}" />
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"> -->
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon.png">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700;900&display=swap"
        rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="/css/app.css">

    <link rel="stylesheet" href="/css/questions.css">

    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="/css/telainicial.css"> --}} -->

    @include('sweetalert::alert')
    @livewireStyles
    
    <style>
        .content-body {
            margin-left: 0rem !important;
        }
    </style>
</head>

<body>       
    <div class="prision">

        <img src="{{ asset('images/gif/gif01.gif') }}" alt="Animação" class="img-fundo"/>

        @if ($errors->any())
            <div class="alert alert-danger" id="alerta">
                <ul>
                    <h3>Erro!</h3>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success" id="alerta-sucesso">
                <ul>
                    <h3>Sucesso!</h3>
                    <p>{{ session('success') }}</p>
                </ul>
            </div>
        @endif

        <div class="container">

            <div class="row justify-content-center">
                <div class="col-md-8">

                    <div class="card">
                        <div class="card-header">{{ __('Register') }}</div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('register.store') }}">
                                @csrf

                                <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="projeto" class="col-md-4 col-form-label text-md-right">Projeto</label>

                                    <div class="col-md-6">
                                        @if (isset($escolas))
                                            <select name="projeto" id="projeto" class="form-control dropdown-personalizado">

                                                @foreach($escolas as $escola)
                                                    <option value="{{ $escola }}"> {{ $escola }}</option>
                                                @endforeach
                                            
                                            </select>
                                        @endif
                                    </div>
                                </div>                        

                                
                                <div class="form-group row mb-0">
                                    <div class="mial">
                                        <button type="submit" class="btn btn-primary" style="width: unset">
                                            {{ __('Register') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer>   
                <img src="{{ asset('images/GameLAB.png') }}" alt="Imagem" class="img-fluid">
                <img src="{{ asset('images/Fapesc.png') }}" alt="Imagem" class="img-fluid">   
        </footer>

    </div>
    
</body>