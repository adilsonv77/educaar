
@extends('layouts.'.$layout)

@section('page-name', "Ranking de uma turma")

@php
    $position = ['🥇', '🥈', '🥉'];
@endphp

@section('content')

    <style> 
        #table {
            table-layout: fixed;
        }
        #table th:nth-child(1) {
            width: 10px; 
        }
        #table th:nth-child(2) { 
            width: 40px;
        }
        #table th:nth-child(3) { 
            width: 30px; 
        }
        #table th:nth-child(4) { 
            width: 20px;
        }

        .table thead th {
            background-color: #7e3789 !important; 
            color: #000;
        }

        table.sortable th:not(.sorttable_sorted):not(.sorttable_sorted_reverse):not(.sorttable_nosort):after { 
            content: " \25B4\25BE" 
        }

        /* ANIMAÇÃO DE BRILHO NO PÓDIO */
        @media only screen and (max-width: 767px) {
            tbody tr:nth-child(1) { --base: #fff8e1; --shine: rgba(255, 215, 0, 0.55); }
            tbody tr:nth-child(2) { --base: #f0f0f0; --shine: rgba(200, 200, 200, 0.55); }
            tbody tr:nth-child(3) { --base: #fdecea; --shine: rgba(205, 127, 50, 0.45); }

            tbody tr:nth-child(1),
            tbody tr:nth-child(2),
            tbody tr:nth-child(3) {
                background: linear-gradient(
                    90deg,
                    var(--base) 30%,
                    var(--shine) 50%,
                    var(--base) 70%
                );
                background-size: 200% 100%;
                background-position: 200% center;
                animation: row-shimmer 1.8s infinite alternate ease-in-out;
            }

            tbody tr:nth-child(1) td,
            tbody tr:nth-child(2) td,
            tbody tr:nth-child(3) td {
                background: transparent !important;
            }

            @keyframes row-shimmer {
                0%   { background-position: -50% center; }
                100% { background-position: 150% center; }
            }
        }
    </style>

    @if($layout == 'app')
        <div class="container mr-0 ml-0">
            <form action="{{ route('ranking.create') }}" method="GET"> @csrf
                <input type="hidden" name="id" value="{{ $content_id }}">
                <input type="hidden" name="type" value="{{ $type }}">

                <div class="form-inline d-flex gap-2 justify-content-start">
                    <label for="activityinput" class="mr-2">Informe a atividade:</label>

                    <select name="activity_id" class="form-control" style="height: 55px;">
                        <option selected disabled>Selecione uma atividade</option>
                            @foreach($atividades as $atividade)
                                <option value="{{ $atividade->id  }}" @selected(request('activity_id') == $atividade->id)>
                                    {{ $atividade->name }}
                                </option>        
                            @endforeach
                    </select>

                    <button type="submit" class="btn btn-primary btn-lg @if($layout == "mobile") btn-block mt-2 mb-4 @endif ">Pesquisar</button>
                </div>
            </form>
        </div>
    @endif

    @if($ranking === null)
        <hr>
        <div class="mt-4"">
            <h1>Não há respostas</h1>
        </div>
    @elseif($layout == 'app')
        <!-- Usando sorttable.js para a ordenação da tabelas --> 
        <div class="overflow-hidden" style="border-radius: 10px;"> 
            <table class="table table-bordered sortable table-layout-fixed mt-4" id="table">
                <thead class="thead-info">
                    <tr>
                        <th class="sorttable_nosort">Posição</th>
                        <th style="cursor:pointer; user-select:none;">
                            Nome
                        </th>
                        <th style="cursor:pointer; user-select:none;">
                            Pontuação
                        </th>
                        <th style="cursor:pointer; user-select:none;">
                            Tentativas
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ranking as $i => $aluno)
                        <tr class="item">
                            <td>{{ $i + 1 }}º</th>
                            <td>{{ $aluno->name }}</th>
                            <td>{{ $aluno->pontuacao ?? 0 }} pontos</th>
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
                    <h3 class="mb-0 font-weight-bold">🏆 Ranking de {{$content_name}}</h5>
                    <small class="text-muted">{{ $studentCount }} participantes</small>
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
                                            {{ ($aluno->pontuacao ?? 0) > 1 ? 'pontos' : 'ponto' }}
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
        /* Isso sempre manterá a ordem de 1º à Xº no coluna posição */
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