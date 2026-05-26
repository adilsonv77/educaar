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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flag-icons@7.2.3/css/flag-icons.min.css"/>
    <link rel="stylesheet" href="{{ asset('css/login.css?v=' . filemtime(public_path('css/login.css'))) }}" />
    <link rel="stylesheet" href="{{ asset('css/locale-update.css') }}">
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
    
</head>



<body> 
    <div class="prision">
    <img src="{{ asset('images/gif/gif01.gif') }}" alt="Animação" class="img-fundo"/>
    

    <div class="locale-update">
        <a href="{{ route('locale.update', 'pt_BR') }}" class="lang-btn {{ app()->getLocale() === 'pt_BR' ? 'active' : '' }}" title="Português">
            <span class="fi fi-br"></span>
            <span class="d-none">PT</span>
        </a>
        <span class="linha-vertical">|</span>
        <a href="{{ route('locale.update', 'en') }}" class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}" title="English">
            <span class="fi fi-us"></span>
            <span class="d-none">EN</span>
        </a>
        <span class="linha-vertical">|</span>
        <a href="{{ route('locale.update', 'es') }}" class="lang-btn {{ app()->getLocale() === 'es' ? 'active' : '' }}" title="Español">
            <span class="fi fi-es"></span>
            <span class="d-none">ES</span>
        </a>
    </div>
    
        @if ($errors->any())
            <div class="alert alert-danger" id="alerta">
                <ul>
                    <h3>{{ __('Error') }}!</h3>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success" id="alerta-sucesso">
                <ul>
                    <h3>{{ __('Sucess') }}!</h3>
                    <p>{{ session('success') }}</p>
                </ul>
            </div>
        @endif
        

        <div class="main-container">           
            <form method="POST" action="{{ route('login') }}" autocomplete="off">     
                @csrf
                <div class="elements">
                
                    <div class="logo">
                            <img src="{{ asset('images/LOGO_HORIZONTAL.png') }}"  alt="Imagem" class="img-fluid">
                    </div>
                        
                    <input id="login" type="text"
                        class="form-control{{ $errors->has('username') || $errors->has('email') ? ' is-invalid' : '' }}"
                        name="login" value="{{ old('username') ?: old('email') }}" required autofocus
                        placeholder="{{ __('User') }}">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror                
                
                    <input id="password" type="password"
                        class="form-control @error('password') is-invalid @enderror" name="password" required
                        placeholder="{{ __('Password') }}">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    
                    <div class="mial">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Login') }}
                            </button>
                    </div>
                    
                    <!--

                    <a href="{{ route('register.create') }}">
                        {{ __('Register') }}
                    </a>
                    <a href="{{ route('password.create') }}">
                        {{ __('Forgot Password?') }}
                    </a>

                    -->
                        
                </div>
                        
                <div class="data">
                    20260318
                </div>
            </div>   
        </form>
        
                    
        <footer>   
                <img src="{{ asset('images/GameLAB.png') }}" alt="Imagem" class="img-fluid">
                <img src="{{ asset('images/Fapesc.png') }}" alt="Imagem" class="img-fluid">
        </footer>

    </div>                    
</body>

<script>
    /* Fallback para caso as bandeiras não carreguem */
    function checkFlags() {
      const flags = document.querySelectorAll('.locale-update span.fi[class*="fi-"]');

        for (const span of flags) {
          const bgImage = getComputedStyle(span).backgroundImage;
          const fallback = span.nextElementSibling;

          if (!fallback) continue;

          if (!bgImage || bgImage === 'none') {
            fallback.classList.remove('d-none');
          }
        }
    }

    document.addEventListener('DOMContentLoaded', checkFlags);
</script>

</html>
