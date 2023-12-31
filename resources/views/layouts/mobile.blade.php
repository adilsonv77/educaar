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
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon.png">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700;900&display=swap"
        rel="stylesheet">
    <link href="{{ asset('css/mobile.css') }}" rel="stylesheet">
    {{-- <link href="/css/mobile.css" rel="stylesheet"> --}}
    <link href="/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    {{-- <link rel="stylesheet" href="/css/app.css"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('/css/login.css') }}"rel="stylesheet"> --}}
    {{-- <link rel="stylesheet" href="/css/questions.css"> --}}



    {{-- <link rel="stylesheet" href="/css/telainicial.css"> --}}

    @include('sweetalert::alert')
    @livewireStyles
</head>

<body class="h-100">
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <div id="main-wrapper">
        @auth
            <div class="nav-header">
                <a href="/" class="brand-logo">
                    <img src="{{ asset('images/LOGO VERTICAL.png') }}" width="70" alt="Imagem"
                        class="img-fluid">{{-- So pra teste --}}
                </a>


            </div>
            <div class="header">
                <div class="header-content">
                    <nav class="navbar navbar-expand">
                        <div class="collapse navbar-collapse justify-content-between">
                            <div class="header-left">
                                <div class="dashboard_bar">
                                    @yield('page-name')
                                </div>
                            </div>
                            <div class="aux">
                                <ul class="navbar-nav header-right">
                                    <a class="nav-link" href="javascript:void(0)" role="button" data-toggle="dropdown">
                                        <div class="header-info">
                                            <span class="text-black"><strong>{{ Auth::user()->name }}</strong></span>
                                            <p class="fs-12 mb-0">
                                                @if (Auth::user()->type == 'student')
                                                    Estudante
                                                @endif

                                                @if (Auth::user()->type == 'teacher')
                                                    Professor
                                                @endif

                                                @if (Auth::user()->type == 'admin')
                                                    Administrator
                                                @endif
                                            </p>

                                        </div>
                                        @can('admin')
                                            <a href="{{ route('config.index') }}" title="Configurar";
                                                class="dropdown-item ai-icon">
                                                <img src="/gear.png" width="10" alt="" />
                                            </a>
                                        @endcan
                            </div>
                            <div class="saida">
                                <a href="{{ route('logout') }}" title="Sair";
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                    class="dropdown-item ai-icon">

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>

                                    <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger"
                                        width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="black"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                        <polyline points="16 17 21 12 16 7"></polyline>
                                        <line x1="21" y1="12" x2="9" y2="12">
                                        </line>
                                    </svg>
                                </a>
                                </a>
                            </div>
                        </div>

                        </a>
                        </ul>
                </div>
                </nav>
            </div>
        </div>

    @endauth



    <div class="content-body">




        @yield('content')


    </div>




    {{-- <div class="footer">

    </div> --}}

    </div>
    @livewireScripts
    <script src="/vendor/global/global.min.js"></script>
    <script src="/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
    <script src="/vendor/deznav/deznav.min.js"></script>
    <script src="/js/custom.min.js"></script>
    <script src="/js/deznav-init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    @yield('script')



</body>

<footer>

    <div class='footer'>
        @if ($showBack)
            <div id='footerBF'>
                <a href="{{ $back }}"><button type="button" class="btn btn-warning"> <span><i
                                style = "color:#83368A;" class="bi bi-arrow-return-left h2"></i></span></button></a>
            </div>
        @endif
        @if ($showOthers)
            <div id='footerBF'>
                <button type="button" class="btn btn-warning">
                    <spam><i style = "color:#83368A;" class="bi bi-book h2"></i></spam>
                </button>
            </div>

            <div id='footerBF'>
                <button type="button" class="btn btn-warning"><span><i style = "color:#83368A;"
                            class="bi bi-arrows-move h2"></i></span></button>
            </div>

            <div id='footerBA'>
                <button type="button" class="btn btn-warning" id="mais"><span><i style = "color:#83368A;"
                            class="bi bi-zoom-in h5"></i></span></button>
            </div>

            <div id='footerBL'>
                <button type="button" class="btn btn-warning" id="menos"><span><i style = "color:#83368A;"
                            class="bi bi-zoom-out h5"></i></span></button>
            </div>
        @endif
    </div>

</footer>


</html>
