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
    <link rel="stylesheet" href="/css/app.css">

    <link rel="stylesheet" href="/css/questions.css">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="/css/telainicial.css">

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
                {{-- <a href="/" class="brand-logo">
                    <i class="fa-brands fa-unity"></i>
                </a> --}}

                <img src="{{ asset('images/EDUCAAR.png') }}" width="170" alt="Imagem"
                    class="img-fluid">{{-- So pra teste --}}

                <div class="nav-control">
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

                    @can('teacher')
                        <li><a class=" ai-icon" href="/" aria-expanded="false">
                                <i class="flaticon-381-home-2"></i>
                                <span class="nav-text">Página Inicial</span>
                            </a>
                        </li>

                        <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                                <i class="flaticon-381-smartphone-5"></i>
                                <span class="nav-text">Conteúdos</span>
                            </a>
                            <ul aria-expanded="false">
                                <li><a href="{{ route('content.index') }}">Listar</a></li>
                                <li><a href="{{ route('content.create') }}">Adicionar</a></li>
                            </ul>
                        </li>

                        <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                                <i class="flaticon-381-notepad"></i>
                                <span class="nav-text">Atividades</span>
                            </a>
                            <ul aria-expanded="false">
                                <li><a href="{{ route('activity.index') }}">Listar</a></li>
                                <li><a href="{{ route('activity.create') }}">Adicionar</a></li>
                            </ul>
                        </li>
                    @endcan
                    @can('admin')
                        <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                                <i class="flaticon-381-user-9"></i>
                                <span class="nav-text">Usuários</span>
                            </a>
                            <ul aria-expanded="false">

                                <li><a href="{{ route('user.indexAluno') }}">Listar Alunos</a></li>
                                <li><a href="{{ route('user.createStudent') }}">Adicionar Aluno</a></li>
                                <li><a href="{{ route('user.indexProf') }}">Listar Profs/Admins</a></li>
                                <li><a href="{{ route('user.createTeacher') }}">Adicionar Professor</a></li>

                            </ul>
                        </li>

                        <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                                <i class="flaticon-381-book"></i>
                                <span class="nav-text">Disciplinas</span>
                            </a>
                            <ul aria-expanded="false">
                                <li><a href="{{ route('class.index') }}">Listar</a></li>
                                <li><a href="{{ route('class.create') }}">Adicionar</a></li>
                            </ul>
                        </li>

                        <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                                <i class="flaticon-381-calendar-2"></i>
                                <span class="nav-text">Anos Letivos</span>
                            </a>
                            <ul aria-expanded="false">
                                <li><a href="{{ route('anoletivo.index') }}">Listar</a></li>
                                <li><a href="{{ route('anoletivo.create') }}">Adicionar</a></li>

                            </ul>
                        </li>

                        <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                                <i class="flaticon-381-notebook-4"></i>
                                <span class="nav-text">Turmas Modelos</span>
                            </a>
                            <ul aria-expanded="false">
                                <li><a href="{{ route('turmasmodelos.index') }}">Listar</a></li>
                                <li><a href="{{ route('turmasmodelos.create') }}">Adicionar</a></li>
                            </ul>
                        </li>
                        <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                                <i class="flaticon-381-notebook-3"></i>
                                <span class="nav-text">Turmas</span>
                            </a>
                            <ul aria-expanded="false">
                                <li><a href="{{ route('turmas.index') }}">Listar</a></li>
                                <li><a href="{{ route('turmas.create') }}">Adicionar</a></li>
                            </ul>
                        </li>

                        <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                                <i class="flaticon-381-id-card-1"></i>
                                <span class="nav-text">Matrículas</span>
                            </a>
                            <ul aria-expanded="false">
                                <li><a href="{{ route('user.matricula') }}">Importar arquivo</a></li>
                                <li><a href="{{ route('turmas.novoAlunoTurma') }}">Aluno novo</a></li>
                            </ul>
                        </li>
                        <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                                <i class="flaticon-381-smartphone-5"></i>
                                <span class="nav-text">Conteúdos</span>
                            </a>
                            <ul aria-expanded="false">
                                <li><a href="{{ route('content.index') }}">Listar</a></li>
                                <li><a href="{{ route('content.create') }}">Adicionar</a></li>
                            </ul>
                        </li>
                    @endcan
                </ul>
            </div>
        </div>

    @endauth

    @section('style')
        <style>
            .nav-header {
                display: flex;
                justify-content: center;
                align-items: center;
            }
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
