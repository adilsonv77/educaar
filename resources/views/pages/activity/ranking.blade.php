@extends('layouts.'.$layout)

@section('page-name', __('Ranking of'))

@php
    $position = ['🥇', '🥈', '🥉'];
@endphp

@section('content')
    <link rel="stylesheet" href="{{ asset('css/ranking.css') }}">

    @if($layout == 'app')
        <div class="container mr-0 ml-0">
            <form action="{{ route('ranking.create') }}" method="GET"> @csrf
                <input type="hidden" name="id" value="{{ $content_id }}">
                <input type="hidden" name="type" value="{{ $type }}">

                <div class="form-inline d-flex gap-2 justify-content-start">
                    <label for="activityinput" class="mr-2">{{ __('Enter the activity') }}:</label>

                    <select name="activity_id" class="form-control" style="height: 55px;">
                        <option selected disabled>{{ __('Select the activity') }}</option>
                            @foreach($atividades as $atividade)
                                <option value="{{ $atividade->id  }}" @selected(request('activity_id') == $atividade->id)>
                                    {{ $atividade->name }}
                                </option>        
                            @endforeach
                    </select>

                    <button type="submit" class="btn btn-primary btn-lg @if($layout == "mobile") btn-block mt-2 mb-4 @endif ">{{ __('Search') }}</button>
                </div>
            </form>
        </div>
    @endif

    @if($ranking === null)
        <hr>
        <div class="mt-4"">
            <h1>{{ __('No Results') }}</h1>
        </div>
    @elseif($layout == 'app')
        <!-- Usando sorttable.js para a ordenação da tabelas --> 
        <div class="overflow-hidden" style="border-radius: 10px;"> 
            <table class="table table-bordered sortable table-layout-fixed mt-4" id="table">
                <thead class="thead-info">
                    <tr>
                        <th class="sorttable_nosort">{{ __('Position') }}</th>
                        <th style="cursor:pointer; user-select:none;">
                            {{ __('Name') }}
                        </th>
                        <th style="cursor:pointer; user-select:none;">
                            {{ __('Score') }}
                        </th>
                        <th style="cursor:pointer; user-select:none;">
                            {{ __('Attempt') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ranking as $i => $aluno)
                        <tr class="item">
                            <td>{{ $i + 1 }}º</th>
                            <td>{{ $aluno->name }}</th>
                            <td>{{ $aluno->pontuacao ?? 0 }} @if($aluno->pontuacao <= 0) {{ __('Point') }} @else {{ __('Points') }} @endif</th>
                            <td>{{ $aluno->tentativas ?? 0 }}</th>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div> 
    @else
        <div class="overflow-hidden px-2 mt-4" style="border-radius: 12px">

            <div class="d-flex align-items-center justify-content-between mb-3 px-1">
                <div>
                    <h3 class="mb-0 font-weight-bold">🏆 {{ __('Ranking of') }} {{$content_name}}</h5>
                    <small class="text-muted">
                        {{ $studentCount }} 
                        @if($studentCount === 1)  {{__('Participant')}}
                        @else {{__('Participants')}}
                        @endif
                    </small>
                </div>
            </div>

            <hr class="mt-0">

            <div class="overflow-hidden" style="border-radius: 10px">
                <table class="table sortable table-layout-fixed" id="table">
                    <tbody>
                        @foreach($ranking as $i => $aluno)
                            <tr class="item" >
                                <td style="width: 20%;">
                                    @if($i <= 2) {{ $position[$i] }}
                                    @else {{ $aluno->posicao++ }}º
                                    @endif
                                </td>
                                <!-- <td style="width: 20%;">avatar</td> -->
                                <td style="width: 60%;">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            {{ $aluno->name }} <br> {{ $aluno->pontuacao ?? 0 }}
                                            {{ ($aluno->pontuacao ?? 0) !== 1 ? __('Points') : __('Point') }}
                                        </div>
                                        @if($aluno->user_id === auth()->id())
                                            <div>
                                                <i class="bi bi-person" style="font-size: 1.5rem"></i>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
        </div>
    @endif

    <script>
        /* Isso sempre manterá a ordem de 1º a Nº no coluna posição */
        document.addEventListener('DOMContentLoaded', function() {
            const table = document.querySelector('#table tbody');

            function reorder() {
                const linhas = table.querySelectorAll('tr');

                linhas.forEach((tr, index) => {
                    tr.cells[0].textContent = (index + 1) + 'º';
                });
            }

            const obs = new MutationObserver(reorder);
            obs.observe(table, {childList: true});
        });
    </script>
@endsection 