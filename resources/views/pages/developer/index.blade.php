@extends('layouts.app')

@php
    $pageName = 'Atividades';
@endphp

@section('style')
    <link rel="stylesheet" href="/css/list_content.css">
@endsection
@section('page-name', $pageName)

@section('content')

    <div>
        <form action="{{ route('dev.createActivity') }}">
            @csrf
            <button class="btn btn-sm btn-primary " id="novo"><i class="bi bi-plus-circle-dotted h1" style = "color : #ffffff;"></i></button>
        </form>
    </div>

    <div class="card">
        <div class="card-body">
            @if (!empty($activities))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Marcador</th>
                                <th>Visualizar</th>
                                <th>Questionários</th>
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activities as $activity)
                                <tr>
                                <td style="width: 60%;">{{ $activity->name }}</td>
                                <td style="width: 25%;"><img src="/marcadores/{{ $activity->marcador }}?v={{ random_int(0,10000) }}"
                                            alt=""width="200" height="200"></td>
                                    <td style="width: 10%;"><a href="/activity/{{ $activity->id }}"
                                            class="btn btn-primary"><i class="bi bi-eye-fill h2" style = "color : #ffffff;"></i></a>
                                </td>
                                <td style="width: 10%;">
                                        <form action="{{ route('questions.index', $activity->id) }}">
                                            @csrf
                                            <input type="hidden" name="activity" value="{{ $activity->id }}">
                                            <button type="submit" class="btn btn-info" text-align:
                                                center><i class="bi bi-file-earmark-medical-fill h2" style = "color : #ffffff;"></i></button>
                                        </form>
                                </td>
                                    <td style="width: 70px;">
                                        <form action="{{ route('dev.editActivity', $activity->id) }}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $activity->id }}">
                                            <button type="submit" class="btn btn-warning" text-align:
                                                center><i class="bi bi-pencil-square h2"  style = "color : #ffffff;"></i></button>
                                        </form>
                                    </td>
                            @endforeach

                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center">
                        {{ $activities->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            @else
                <div>
                    <h2>Nenhuma atividade cadastrada</h2>
                </div>
            @endif
        </div>
    </div>
@endsection
