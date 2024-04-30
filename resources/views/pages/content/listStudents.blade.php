@extends('layouts.app')

@php
    $pageName =  $content->name;
@endphp

@section('page-name', $pageName)

@section('content')

    <div class="card">
        <div class="card-body">
            @if (!empty($results))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Nome</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($results as $item)
                                <tr>
                                    <td>{{ $item->nome }}</td>
                                </tr>
                </div>
            @endforeach

            </tbody>
            </table>

        </div>
    @else
        <div>
            <h2>Nenhum aluno matriculado nesta turma </h2>
        </div>
        @endif
    </div>
    </div>
@endsection
