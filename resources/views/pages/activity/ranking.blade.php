@extends('layouts.app')

@section('page-name', "Ranking de uma turma")

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
    </style>

    <div class="container mr-0 ml-0">
        <form action="{{ route('ranking.create') }}" method="GET"> @csrf
            <div class="form-inline d-flex gap-2 justify-content-start">
                <label>Informe a atividade:</label>
                <select class="form-control ml-2 w-60" name="activity_id">
                    <option value="" selected disabled > Selecione uma atividade </option>
                    @foreach($atividades as $atividade)
                        <option value="{{ $atividade->id }}" @selected(request('activity_id') == $atividade->id)>
                            {{ $atividade->name }}
                        </option>
                    @endforeach
                </select>
                <section class="itens-group" >
                    <button class="btn btn-primary btn-lg" type="submit">Pesquisar</button>
                </section>
            </div>
        </form>
    </div>
    <br>

    @if($ranking === null)
        <hr>
        <div class="mt-4"">
            <h1>Não há respostas</h1>
        </div>
    @else
        <div class="overflow-hidden" style="border-radius: 10px;"> 
            <!-- Usando sorttable.js para a ordenação da tabela -->
            <table class="table table-bordered sortable table-layout-fixed" id="table">
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