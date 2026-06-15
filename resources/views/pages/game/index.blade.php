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
                                <th>{{ __('Class') }}</th>
                                <th>{{ __('Parties') }}</th>
                                <th>{{ __('Create Party') }}</th>
                                <th>{{ __('Delete') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($jogos as $jogo)
                                
                                
                                <tr style="text-align: center;">

                                    <td style="text-align: left;">{{ $jogo->content->name }}</td>

                                    <td>{{ $jogo->content->turma_name }}</td>

                                    <td>
                                        <form action="{{ route('sala.index') }}">
                                            @csrf
                                            <input type="hidden" name="content" value="{{ $jogo->id }}">
                                            <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle"></i></button>
                                        </form>
                                    </td>

                                    <td>

                                    <form action="{{ route('sala.create') }}">
                                        @csrf
                                        <input type="hidden" name="content" value="{{ $jogo->id }}">
                                        <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle"></i></button>
                                    </form>
                                    </td>

                                    @if (session('type') == 'teacher')
                                        <td>
                                            <button type="button"
                                                class="btn btn-danger"  @if ($jogo->content->qtasatividades > 0) disabled @endif
                                                data-toggle="modal" data-target="#modal{{ $jogo->id }}"
                                                title="{{ __('Delete') }}">
                                                <i class="bi bi-trash3 h2" style = "color : #ffffff;"></i>
                                            </button>
                                        </td>
                                    @endif
                                </tr>

                                <div class="modal fade" id="modal{{ $jogo->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <h3>{{ __('Delete the content :content', ["content" => $jogo->content_name]) }} </h3>
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
    <form action="{{ route('game.create') }}">
        @csrf
        <button class="btn btn-smaller, btn-primary " id="novo" title="{{ __('New') }}">
            <i class="bi bi-plus-circle-dotted h1" style = "color : #ffffff;"></i>
        </button>
    </form>
</div>

@endsection