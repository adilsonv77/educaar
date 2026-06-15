@extends('layouts.app')

@section('page-name', $titulo)

@section('content')

    <div class="card">
        <div class="card-body">
            @if (!empty($salas))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr style="text-align: center;">
                                <th style="text-align: left;">{{ __('Name') }}</th>
                                <th>{{ __('Class') }}</th>
                                <th>{{ __('Enter') }}</th>
                                <th>{{ __('Results') }}</th>
                                <th>{{ __('Edit') }}</th>
                                <th>{{ __('Delete') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($salas as $sala)
                                <tr style="text-align: center;">

                                    <td style="text-align: left;">{{ $sala->nome }}</td>

                                    <td>
                                        {{ $sala->nome_turma }}
                                    </td>

                                    <td>
                                        <form action="{{ route('sala.enter', $sala->id) }}">
                                            @csrf
                                            <input type="hidden" name="sala_id" value="{{ $sala->id }}">
                                            <button type="submit" class="btn btn-primary"><i class="bi bi-collection"></i></button>
                                        </form>
                                    </td>

                                    <td>
                                        <form action="{{ route('sala.results', $sala->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary"><i class="bi bi-bar-chart"></i></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{ route('sala.edit', $sala->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-warning"><i class="bi bi-pencil"></i></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{ route('sala.destroy', $sala->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>

                                <div class="modal fade" id="modal{{ $jogo->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <h3>{{ __('Delete the content :content', ["content" => $jogo->content->name]) }} </h3>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">{{ __('Cancel') }}</button>
                                                <form action="{{ route('game.destroy', $jogo->id) }}" method="POST">
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
                </div>
            @else
                <div>
                    <h2>{{ __('No Games') }}</h2>
                </div>
            @endif
        </div>
    </div>

    <div>
        <form action="{{ route('sala.create') }}">
            @csrf
            <input type="hidden" name="jogo_id" value="{{ $jogo->id }}">
            <button class="btn btn-smaller, btn-primary " id="novo" title="{{ __('New') }}">
                <i class="bi bi-plus-circle-dotted h1" style = "color : #ffffff;"></i>
            </button>
        </form>
    </div>
@endsection