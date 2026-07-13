@extends('layouts.app')

@section('page-name', $titulo)

@section('content')

<div class="card">
        <div class="card-body">
            @if (!empty($jogos))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr style="text-align: center;">
                                <th style="text-align: left;">{{ __('Name') }}</th>
                                <th>{{ __('Parties') }}</th>
                                <th>{{ __('Create Party') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($jogos as $jogo)
                                
                                <tr style="text-align: center;">

                                    <td style="text-align: left;">{{ $jogo->content->name }}</td>

                                    <td>
                                        <form action="{{ route('sala.index') }}">
                                            @csrf
                                            <input type="hidden" name="jogo_id" value="{{ $jogo->id }}">
                                            <button type="submit" class="btn btn-primary"><i class="bi bi-collection"></i></button>
                                        </form>
                                    </td>

                                    <td>
                                    <form action="{{ route('sala.create') }}">
                                        @csrf
                                        <input type="hidden" name="jogo_id" value="{{ $jogo->id }}">
                                        <button type="submit" class="btn btn-primary" @if(!$jogo->podeCriarSala) disabled title="Sala não inicializada já criada" @endif><i class="bi bi-plus-circle"></i></button>
                                    </form>
                                    </td>
                                </tr>
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

@endsection