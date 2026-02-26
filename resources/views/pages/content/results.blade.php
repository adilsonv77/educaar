@extends('layouts.app')

@php
    $pageName = __('global.pageName.results_of') . ' ' . $content->name;

    /* Variáveis de tradução para o JS */
    $titleChart1 = __('global.statistics.answered_activities');
    $activity = trans_choice('global.views.activity', 1);
    $activities = trans_choice('global.views.activity', 2);
    $column2 =__('global.statistics.has_answered');
    $column3 =__('global.statistics.hasnt_answered');
    $titleChart2 = __('global.statistics.titleChart2');
    $complete = __('global.statistics.complete');
    $incomplete = __('global.statistics.incomplete');
    $noRespond = __('global.statistics.no_respond')
@endphp

@section('page-name', $pageName)

@section('script-head')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="{{asset('css/resultsContents1.css')}}">
    <style>
        .form-inline {
            display: flex;
            justify-content: flex-start;
        }

        .form-inline label {

            margin-right: 10px;
        }
    </style>
    <script>
        /* Variáveis de tradução pegas do PHP */
        var titleChar1 = "<?php print $titleChart1; ?>";
        var titleChart2 = "<?php print $titleChart2; ?>";
        var activity = "<?php print $activity; ?>";
        var activities = "<?php print $activities; ?>";
        var column2 = "<?php print $column2; ?>";
        var column3 = "<?php print $column3; ?>";
        var complete = "<?php print $complete ?>"
        var incomplete = "<?php print $incomplete ?>"
        var noRespond = "<?php print $noRespond ?>"

        var qntCompletas = {{$totais['qtos_fizeram']}};
        var qntIncompletas = {{$totais['qtos_incompletos']}};
        var qntNaoFizeram = {{$totais['qtos_nao_fizeram']}};

        google.charts.load('current', { 'packages': ['corechart', 'bar'] });
        google.charts.setOnLoadCallback(drawStuff);

        function drawStuff() {
            drawBarChart();
            drawPieChart();
        }

        function drawBarChart() {

            const map1 = new Map();

            var data = new google.visualization.DataTable();
            data.addColumn('string', activity);
            data.addColumn('number', column2);
            data.addColumn('number', column3);
            data.addColumn({ type: 'string', role: 'tooltip' });
            var value;

            @foreach ($results as $item)
                value = "A" + {{ $loop->index + 1 }};
                data.addRow([{ f: value + ": " + "{{ $item['nome'] ?? '' }}", v: value }, {{ $item['atividade_completa'] }}, {{ ($item['atividade_incompleta'] + $item['atividade_nao_fizeram']) }}, "{{ $item['nome'] ?? '' }}"]);
            @endforeach

            var _ticks = [];
            for (var i = 0; i <= (qntCompletas + qntIncompletas + qntNaoFizeram); i++) {
                _ticks.push(i);
            }

            var options = {
                chart: {
                    title: titleChar1,
                },
                isStacked: true,
                colors: ['#5A2D66', '#9C6FA8'],
                legend: 'bottom',
                vAxis: {
                    minValue: 0,
                    ticks: _ticks
                },
                bar: { groupWidth: '45%' }
            };

            var chart = new google.charts.Bar(document.getElementById('barras'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
        }

        function drawPieChart() {
            var data = google.visualization.arrayToDataTable([
                [activities, 'Alunos'],
                [complete, qntCompletas],
                [incomplete, qntIncompletas],
                [noRespond, qntNaoFizeram]
            ]);

            var options = {
                title: titleChart2,
                pieHole: 0.4,
            };

            var chart = new google.visualization.PieChart(document.getElementById('rosca'));
            chart.draw(data, options);

            google.visualization.events.addListener(chart, 'select', selectHandler);

            google.visualization.events.addListener(chart, 'onmouseover', mouseOverHandler);
            google.visualization.events.addListener(chart, 'onmouseout', mouseOutHandler);

            function mouseOverHandler() {
                document.getElementById('rosca').style.cursor = 'pointer';
            }

            function mouseOutHandler() {
                document.getElementById('rosca').style.cursor = 'default';
            }

            function selectHandler() {
                var selectedItem = chart.getSelection()[0];
                var type = " ";
                if (selectedItem) {
                    type = data.getValue(selectedItem.row, 0);
                    var url = "{{ route('content.listStudents', ['type' => 'type_selection']) }}".replace('type_selection', type);
                    window.location.href = url;
                }
            }
        }
    </script>
@endsection

@section('content')

    <div id="formTurma">
        <form action="{{ route('content.resultsContents') }}" method="GET ">
            @csrf
            <div class="form-inline">
                <label for="">{{ __('global.label.enter_class') }}:</label>
                <select class="form-control" name="turma_id">
                    @foreach ($turmas as $item)
                        <option value="{{ $item->id }}" @if ($item->id === $turma->id) selected="selected" @endif>
                            {{ $item->nome }}
                        </option>
                    @endforeach
                </select>
                <button class="btn btn-primary btn-lg" type="submit">{{ __('global.button.save') }}</button>
            </div>
        </form>
        <br>
    </div>

    @if (!empty($results))
        <div style="background-color: white">
            <div id="barras" style="width: 1000px; height: 500px;"></div>
            <div id="rosca" style="width: 900px; height: 500px;"></div>
        </div>

    
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th class="fixedCol" rowspan="2" style="border-bottom: 0px solid black"></th>
                        @foreach ($results as $result) 
                            <th class="atividade" colspan="{{ count($result['questions']) * 4 }}">
                                {{ $result['nome'] ?? 'Nome não disponível' }}
                            </th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($results as $result)
                            @foreach ($result['questions'] as $questao)
                                <th class="questions" colspan="4">{{ $questao['question'] }}</th>
                            @endforeach
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ([ __('global.statistics.options') , __('global.statistics.correct'), __('global.statistics.incorrect'), __('global.statistics.not_answered')] as $rowLabel)
                        <tr>
                            <td>{{ $rowLabel }}</td>
                            @foreach ($results as $result) 
                                @foreach ($result['questions'] as $questao)
                                    @if ($rowLabel === 'Não Respondeu')
                                        <td colspan="4">
                                            {{ $result['atividade_nao_fizeram'] > 0 ? $result['atividade_nao_fizeram'] : '-' }}
                                        </td>
                                    @else
                                        @foreach (['A', 'B', 'C', 'D'] as $alt)
                                            <td data-toggle="tooltip" title="{{ $questao['alternatives'][$alt] ?? 'Descrição não disponível' }}">
                                                @if ($rowLabel ===  __('global.statistics.options'))
                                                    {{ $alt }}
                                                @elseif ($rowLabel === __('global.statistics.correct'))
                                                    @if ($alt === $questao['correct_alternative'])
                                                        {{ $questao['alternatives_count'][$alt] }}
                                                    @else
                                                        -
                                                    @endif
                                                @elseif ($rowLabel === __('global.statistics.incorrect'))
                                                    @if ($alt !== $questao['correct_alternative'])
                                                        {{ $questao['alternatives_count'][$alt] > 0 ? $questao['alternatives_count'][$alt] : '-' }}
                                                    @else
                                                        -
                                                    @endif
                                                @endif
                                            </td>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="background-color: white; padding: 20px; border-radius: 20px;">
            <h2>{{ __('global.statistics.no_results') }}</h2>
        </div>
    @endif

@endsection
