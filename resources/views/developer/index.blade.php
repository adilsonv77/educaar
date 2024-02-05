@extends('layouts.app')

@php
    $pageName = 'Atividades';
@endphp

@section('page-name', $pageName)

@section('content')

    <head>
        <link rel="stylesheet" href="/css/list_content.css">
    </head>

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
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activities as $item)
                                <tr>
                                    <td style="width: 60%;">{{ $item->name }}</td>
                                    <td style="width: 25%;"><img src="/marcadores/{{ $item->marcador }}"
                                            alt=""width="200" height="200"></td>
                                    <td style="width: 10%;"><a href="/activity/{{ $item->id }}"
                                            class="btn btn-primary">Visualizar</a></td>

                                    <td style="width: 70px;">
                                        <form action="{{ route('dev.editActivity', $item->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-warning" text-align:
                                                center>Editar</button>
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
