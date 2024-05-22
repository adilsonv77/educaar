@extends('layouts.app')

@section('content')
    <div class="">
        @can('teacher')
            <div class="col">
                <div class="row">
                    <div class="col-xl-4 col-xxl-6 col-lg-12 col-sm-6">
                        <div class="card border-card">
                            <div class="card-body">
                                <a href="{{ route('content.index') }}">
                                    <div class="media">
                                   
                                        <div class="media-body mr-3">
                                            <h2 class="text-secondary">{{ $contentCount }}</h2>
                                            <span class="position">Conteúdos</span>
                                        </div>
                                        <span class="cd-icon bgl-secondary">
                                            <i class="flaticon-381-smartphone-5"></i>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <span class="line bg-secondary"></span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-xxl-6 col-lg-12 col-sm-6">
                        <div class="card border-card">
                            <div class="card-body">
                                <a href="{{ route('activity.index') }}">
                                    <div class="media">
                                    
                                        <div class="media-body mr-3">
                                            <h2 class="text-success">{{ $activitiesCount }}</h2>
                                            <span class="position">Atividades</span>
                                        </div>
                                        <span class="cd-icon bgl-success">
                                            <i class="flaticon-381-notepad"></i>
                                        </span>

                                    </div>
                                </a>
                            </div>
                            <span class="line bg-success"></span>
                        </div>
                    </div>

                    <div class="col-xl-4 col-xxl-6 col-lg-12 col-sm-6">
                        <div class="card border-card">
                            <div class="card-body">
                                <a href="{{ route('content.index') }}">
                                    <div class="media">
                                        <div class="media-body mr-3">
                                            <h2 class="text-success">{{ $activitiesCount }}</h2>
                                            <span class="position">Fechados</span>
                                        </div>
                                        <span class="cd-icon bgl-success">
                                            <i class="flaticon-381-notepad"></i>
                                        </span>

                                    </div>
                                </a>
                            </div>
                            <span class="line bg-success"></span>
                        </div>
                    </div>
                   
                   

                </div>
            </div>
        @endcan
        
        @can('student')
            <div class="card">
                <div class="card-body">
                    <model-viewer src="/uploads/welcome.glb" ios-src="/uploads/welcome.usdz" poster="" alt="Welcome"
                        shadow-intensity="1" camera-controls auto-rotate ar>
                    </model-viewer>
                </div>
            </div>
            @section('script')
                <script type="module" src="/js/app.js"></script>
            @endsection

            @section('style')
                <style>
                    body {
                        margin: 1em;
                        padding: 0;
                        font-family: Google Sans, Noto, Roboto, Helvetica Neue, sans-serif;
                        color: #244376;
                    }

                    #card {
                        margin: 3em auto;
                        display: flex;
                        flex-direction: column;
                        max-width: 600px;
                        border-radius: 6px;
                        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.25);
                        overflow: hidden;
                    }

                    model-viewer {
                        width: 100%;
                        height: 550px;
                        background-color: rgb(65, 64, 64);
                        --poster-color: #ffffff00;
                    }

                    .attribution {
                        display: flex;
                        flex-direction: row;
                        justify-content: space-between;
                        margin: 1em;
                    }

                    .attribution h1 {
                        margin: 0 0 0.25em;
                    }

                    .attribution img {
                        opacity: 0.5;
                        height: 2em;
                    }

                    .attribution .cc {
                        flex-shrink: 0;
                        text-decoration: none;
                    }

                    footer {
                        display: flex;
                        flex-direction: column;
                        max-width: 600px;
                        margin: auto;
                        text-align: center;
                        font-style: italic;
                        line-height: 1.5em;
                    }
                </style>
            @endsection
        @endcan
    </div>
@endsection

{{-- @section('page-name', $schools->name) --}}
