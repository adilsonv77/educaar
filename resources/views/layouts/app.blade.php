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
    <link href="/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/questions.css?v=' . filemtime(public_path('css/questions.css'))) }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link href="{{ asset('css/app.css?v=' . filemtime(public_path('css/app.css'))) }}" rel="stylesheet">
    <link rel="stylesheet" href="/css/telainicial.css">
    
    <script src="{{ asset('js/mudancaLogo.js?v=' . filemtime(public_path('js/mudancaLogo.js'))) }}" type="module"></script>
    
    @include('sweetalert::alert')
    @livewireStyles
</head>

<script>
    const caminhoHorizontal = "{{ asset('images/LOGO_HORIZONTAL.png')}}" ;
    const caminhoVertical = "{{ asset('images/LOGO_VERTICAL.png')}}";
</script>

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
            <div class="nav-header" id="logo">
                <img src="{{ asset('images/LOGO_HORIZONTAL.png') }}" width="120" style="margin-left:23%;margin-top:0.5%"
                    alt="Imagem" class="img-fluid" id="imagem"> 

                <div class="nav-control" id = "menu">
                    <div class="hamburger">
                        <span class="line"></span><span class="line"></span><span class="line"></span>
                    </div>
                </div>
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

                            <ul class="navbar-nav header-right">
                                <li class="nav-item dropdown header-profile">
                                    <a class="nav-link" href="javascript:void()" role="button" data-toggle="dropdown">
                                        <div class="header-info">
                                            <i class="bi bi-person icon-purple"></i>
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
                                                @if (Auth::user()->type == 'developer')
                                                    Desenvolvedor
                                                @endif
                                            </p>

                                        </div>
                                        @can('admin')
                                            <a href="{{ route('config.index') }}" title="Configurar";
                                                class="dropdown-item ai-icon">
                                                <img src="/gear.png" width="10" alt="" />
                                            </a>
                                        @endcan

                                        <div class="saida">
                                            <a href="{{ route('logout') }}" title="Sair";
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                                class="dropdown-item ai-icon">

                                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                    class="d-none">
                                                    @csrf
                                                </form>

                                                <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger"
                                                    width="30" height="30" viewBox="0 0 24 24" fill="none"
                                                    stroke="black" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                                    <polyline points="16 17 21 12 16 7"></polyline>
                                                    <line x1="21" y1="12" x2="9" y2="12">
                                                    </line>
                                                </svg>
                                            </a>
                                    </a>
                        </div>
                        {{-- </div> --}}

                        </a>

                        </li>
                        </ul>
                </div>
                </nav>
            </div>
        </div>

        <div class="deznav">
            <div class="deznav-scroll">
                <ul class="metismenu" id="menu">

                    @can('student')
                        <li><a class="ai-icon" href="/students/novas">
                                <i class="fa fa-external-link-square" aria-hidden="true"></i>
                                <span class="nav-text">Novas</span>
                            </a>
                        </li>
                        <li><a class="ai-icon" href="/students/realizadas">
                                <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                <span class="nav-text">Realizadas</span>
                            </a>
                        </li>
                    @endcan

                    @can('developer')
                        <li><a class="ai-icon" href="{{ route('developer.index') }}" aria-expanded="false">
                                <i class="flaticon-381-notepad"></i>
                                <span class="nav-text">Atividades</span>
                            </a>
                        </li>
                    @endcan

                    @can('teacher')
                       
                        <li><a class="ai-icon" href="{{ route('content.index') }}" aria-expanded="false">
                                <i class="flaticon-381-smartphone-5"></i>
                                <span class="nav-text">Conteúdos</span>
                            </a>
                        </li>

                        <li><a class="ai-icon" href="{{ route('activity.index') }}" aria-expanded="false">
                                <i class="flaticon-381-notepad"></i>
                                <span class="nav-text">Atividades</span>
                            </a>
                        </li>
                    @endcan
                    @can('admin')
                        
                        <li><a class="ai-icon" href="{{ route('user.indexAluno') }}" aria-expanded="false">
                                <i class="flaticon-381-user-9"></i>
                                <span class="nav-text">Alunos</span>
                            </a>
                        </li>

                        <li><a class="ai-icon" href="{{ route('user.indexProf') }}" aria-expanded="false">
                                <i class="bi-person-hearts"></i>
                                <span class="nav-text">Professores</span>
                            </a>
                        </li>

                        <li><a class="ai-icon" href="{{ route('user.indexDev') }}" aria-expanded="false">
                                <i class="bi-person-fill-gear"></i>
                                <span class="nav-text">Desenvolvedores</span>
                            </a>
                        </li>

                        <li><a class="ai-icon" href="{{ route('class.index') }}" aria-expanded="false">
                                <i class="flaticon-381-book"></i>
                                <span class="nav-text">Disciplinas</span>
                            </a>
                        </li>

                        <li><a class="ai-icon" href="{{ route('anoletivo') }}" aria-expanded="false">
                                <i class="flaticon-381-calendar-2"></i>
                                <span class="nav-text">Anos Letivos</span>
                            </a>
                        </li>

                        <li><a class="ai-icon" href="{{ route('turmasmodelos.index') }}" aria-expanded="false">
                                <i class="flaticon-381-notebook-4"></i>
                                <span class="nav-text">Turmas Modelos</span>
                            </a>
                        </li>
                        <li><a class="ai-icon" href="{{ route('turmas.index') }}" aria-expanded="false">
                                <i class="flaticon-381-notebook-3"></i>
                                <span class="nav-text">Turmas</span>
                            </a>
                        </li>

                        <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                                <i class="flaticon-381-id-card-1"></i>
                                <span class="nav-text">Matrículas</span>
                            </a>
                            <ul aria-expanded="false">
                                <li><a href="{{ route('user.matricula') }}">Importar arquivo</a></li>
                                 <li><a href="{{ route('turmas.indexmatricula') }}">Listar</a></li>
                            </ul>
                        </li>
                        <li><a class="ai-icon" href="{{ route('content.index') }}" aria-expanded="false">
                                <i class="flaticon-381-smartphone-5"></i>
                                <span class="nav-text">Conteúdos</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </div>
        </div>

    @endauth

    @section('style')
        <style>
         </style>
    @endsection




    <div class="content-body">
        <div class="container-fluid">
            @yield('content')

        </div>
    </div>

    <div class="footer">

    </div>

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

</html>
