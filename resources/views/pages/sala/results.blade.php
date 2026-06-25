@extends('layouts.app')

@section('page-name', 'Resultados da Sala')

@section('content')
    <style>
        table.sortable th:not(.sorttable_sorted):not(.sorttable_sorted_reverse):not(.sorttable_nosort):after { 
            content: " \25B4\25BE" 
        }
    </style>

    <div class="card">
        <div class="card-body">
            @if (!empty($results))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm sortable" id="table">
                        <thead>
                            <tr style="text-align: center;">
                                <th style="text-align: left; cursor:pointer; user-select:none;">{{ __('Name') }}</th>
                                <th style="cursor:pointer; user-select:none;">{{ __('Score') }}</th>
                                <th class="sorttable_nosort">{{ __('Delete') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($results as $result)
                                
                                <tr style="text-align: center;">

                                    <td style="text-align: left;">{{ $result->nome_aluno }}</td>

                                    <td>{{ $result->pontuacao }}</td>

                                    @if (session('type') == 'teacher')
                                        <td>
                                            <button type="button"
                                                class="btn btn-danger" 
                                                data-toggle="modal" data-target="#modal{{ $result->id }}"
                                                title="{{ __('Delete') }}">
                                                <i class="bi bi-trash3 h2" style = "color : #ffffff;"></i>
                                            </button>
                                        </td>
                                    @endif
                                </tr>

                                <div class="modal fade" id="modal{{ $result->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <h3>{{ __('Deletar os resultados de :content ?', ["content" => $result->nome_aluno]) }} </h3>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">{{ __('Cancel') }}</button>
                                                <form action="{{ route('sala.results.destroy', $result->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="jogo_id" value="{{ $result->jogo_id }}">
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
                    <h2>{{ __('No Results') }}</h2>
                </div>
            @endif
        </div>
    </div>

    <!--
    <div>
        <form action="{{ route('sala.index') }}">
            @csrf
            <input type="hidden" name="jogo_id" value="{{ $jogo_id }}">
            <button class="btn btn-smaller, btn-primary " id="novo" title="{{ __('New') }}">
                <i class="bi bi-collection"></i>
            </button>
        </form>
    </div>
    -->
    
@endsection