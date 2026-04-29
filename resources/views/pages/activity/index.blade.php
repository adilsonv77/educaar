@extends('layouts.app')

@php
$pageName = __('Activities');
@endphp

@section('page-name', $pageName)

@section('content')


<div>
    <form action="{{ route('activity.create') }}">
        @csrf
        <button class="btn btn-sm btn-primary " id="novo" title="Novo"><i class="bi bi-plus-circle-dotted h1" style="color : #ffffff;"></i></button>
    </form>
</div>
<form action="{{ route('activity.index') }}" method="GET">

    <div class="form-inline ">
        <label for="">{{ __('Activities') }} :</label>
        <input maxlength="100" class="form-control " type="text" name="titulo" id="titulo" value="{{ $activity }}" list="historico" />
        <section class="itens-group">
            <button class="btn btn-primary btn-lg" type="submit">{{ __('Search') }}</button>
        </section>
    </div>
    <datalist id="historico">
    @foreach ($activities as $item)
        <option value="{{ $item->pesq_name }}">{{ $item->pesq_name }}</option>
    @endforeach
    </datalist>

    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{ __('Contents') }}
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        @foreach ($nomesConteudo as $nome)
        <a class="dropdown-item" href="{{ route('activity.index', ['conteudo' => $nome]) }}">{{ $nome }}</a>
        @endforeach
        </div>
    </div>


        
</form>
<br>
<style>
    .form-inline {
        display: flex;
        justify-content: flex-start;
    }

    .form-inline label {

        margin-right: 10px;
    }
</style>

<div class="card">
    {{-- <div class="card-header">
            <h4 class="card-title">{{ $pageName }}</h4>
</div> --}}
<div class="card-body">
    @if (!empty($activities))
    <div class="table-responsive">
        <table class="table table-hover table-responsive-sm">
            <thead>
                <tr style="text-align: center;">
                    <th style="text-align: left;">{{ __('Name') }}</th>
                    <th>{{ __('Marker') }}</th>
                    <th>{{ __('View') }}</th>
                    @if (session('type') == 'teacher')<th>{{ __('Results') }}</th>@endif
                   
                    <th>{{ __('Questions') }}</th>
                    <th>{{ __('Clone') }}</th>
                    <th>{{ __('Edit') }}</th>
                    <th>{{ __('Delete') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($activities as $item)
                <tr style="text-align: center;">
                    <td style="width: 60%;text-align: left;">{{ $item->name }}</td>
                    <td style="width: 25%;">

                        <img src="{{ asset('/marcadores/'.$item->marcador.'?v=' . random_int(0,10000)) }}" width="200" height="200">
                    </td>
                    <td style="width: 10%;">
                        <a href="/activity/{{ $item->id }}" class="btn btn-primary" title="{{ __('View') }}">
                            <i class="bi bi-eye-fill h2" style="color : #ffffff;"></i>
                        </a>
                    </td>

                    <td style="width: 10%;">
                        <form action="{{ route('activity.results', $item->id) }}">
                            @csrf
                            <input type="hidden" name="activity_id" value="{{ $item->id }}">
                            <button type="submit" class="btn btn-info" title="{{ __('Results') }}" @if ($item->qtnQuest == 0) disabled @endif
                                text-align:center>
                                <i class="bi bi-journal-bookmark h2" style="color : #ffffff;"></i>
                            </button>
                        </form>
                    </td>


                    
                    



                    <td style="width: 10%;">
                        <form action="{{ route('questions.index', $item->id) }}">
                            @csrf
                            <input type="hidden" name="activity" value="{{ $item->id }}">
                            <button type="submit" class="btn btn-info" text-align:center title="{{ __('Questions) }}">
                                <i class="bi bi-file-earmark-medical-fill h2" style="color : #ffffff;"></i>
                            </button>
                        </form>
                    </td>

                    <td style="width: 10%;">
                    <form action="{{ route('activity.clone', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-info" title="{{ __('Clone') }}">
                            <i class="bi bi-copy h2" style="color: #ffffff;"></i>
                        </button>
                    </form>
                    </td>


                    <td style="width: 70px;">
                        <form action="{{ route('activity.edit', $item->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-warning" text-align:center title="{{ __('Edit') }}">
                                <i class="bi bi-pencil-square h2" style="color : #ffffff;"></i>
                            </button>
                        </form>
                    </td>
                    @if (session('type') == 'teacher')
                    <td style="width: 70px;">
                        <button type="button" class="btn btn-danger" @if ($item->qtnQuest > 0) disabled @endif data-toggle="modal"
                            data-target="#modal{{ $item->id }}" title="{{ __('Delete') }}">
                            <i class="bi bi-trash3 h2" style="color : #ffffff;"></i>
                        </button>
                    </td>
                    @endif
                </tr>
                <div class="modal fade" id="modal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <h3>{{ __('Delete activity :attribute?', ['attribute' => $item->name]) }}
                                </h3>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                                <form action="{{ route('activity.destroy', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $activities->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
    @else
    <div>
        <h2>{{ __('No Activities') }}</h2>
    </div>
    @endif
</div>
</div>
@endsection