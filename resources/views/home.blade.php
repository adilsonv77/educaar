@extends('layouts.app')

@section('content')
    <div class="">
        @if (session('type') == 'teacher')
             <div class="col">
                <div class="row">
                    <div class="col-xl-4 col-xxl-6 col-lg-12 col-sm-6">
                        <div class="card border-card">
                            <div class="card-body">
                                <a href="{{ route('content.index') }}">
                                    <div class="media">
                                   
                                        <div class="media-body p-0 ">
                                            <h2 class="text-secondary">{{ $contentCount }}</h2>
                                            <span class="position">Conteúdos</span>
                                        </div>
                                        @if($contentCount>0)
                                            <div class="align-self-center pl-2">
                                                <h3 class="text-warning">{{ $fechadoCount }}</h3>
                                                <span class="position">Fechados</span>
                                            </div>
                                        @endif
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
                    <div class="col-xl-4 col-xxl-12 col-lg-12 col-md-12">
                        <div class="card border-card">
                            <div class="card-body">
                                <div class="media">
                                    <div class="media-body mr-3">
                                        <h2 class="text-warning">{{ $usersCount }}</h2>
                                        <span class="position">Alunos</span>
                                    </div>
                                    <span class="cd-icon bgl-warning">
                                        <i class="flaticon-381-user-9"></i>
                                    </span>
                                </div>
                            </div>
                            <span class="line bg-warning"></span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
   </div>
@endsection

{{-- @section('page-name', $schools->name) --}}
