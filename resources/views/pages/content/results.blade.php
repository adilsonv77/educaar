@extends('layouts.app')

@php
    $pageName= 'Resultados de '. $content->name;
@endphp

@section('page-name', $pageName)

@section('script-head')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    var qntCompletas= {{$totais['qtos_fizeram']}}; 
    var qntIncompletas= {{$totais['qtos_incompletos']}};
    var qntNaoFizeram= {{$totais['qtos_nao_fizeram']}};

    google.charts.load('current', {'packages':['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawStuff);

    function drawStuff() {
        drawBarChart();
        drawPieChart();
    }

    function drawBarChart() {

      const map1 = new Map();

          var data = new google.visualization.DataTable();
          data.addColumn('string','Atividades');
          data.addColumn('number', 'Responderam');
          data.addColumn('number', 'Não respond.');
          data.addColumn({type: 'string', role: 'annotation'});
          var value;

          @foreach ($results as $item) 
            value = "A"+{{ $loop->index+1 }};
            data.addRow([value, {{ $item['atividade_completa'] }}, {{ ($item['atividade_incompleta'] + $item['atividade_nao_fizeram']) }}, "{{ $item['nome'] }}"]); 
            map1.set(value, [value, {{ $item['atividade_completa'] }}, "{{ $item['nome'] }}"]);
          @endforeach

          var _ticks = [];
          for (var i = 0; i <= (qntCompletas+qntIncompletas+qntNaoFizeram); i++) {
            _ticks.push(i);
          }

         var options = {
          chart: {
            title: 'Atividades respondidas',
          },
          isStacked: true,
          legend: 'bottom',

          vAxis: {
            minValue: 0,
            ticks: _ticks
          },
          bar: { groupWidth: '45%' }
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('barras'));
        chart.draw(data, options);
/*
        var chart = new google.charts.Bar(document.getElementById('barras'));
        chart.draw(data, google.charts.Bar.convertOptions(options));
  */     

        
       // Adicionando eventos de mouse às legendas após o gráfico ser desenhado
      google.visualization.events.addListener(chart, 'ready', function() {
            $('#barras text').each(function(index) {

              $(this).on('mouseover', function() {
                  var activity= map1.get($(this).text());
                  if(activity !== undefined){
                    var tooltip = $('#tooltip');
                    tooltip.text(activity[2]);
                    tooltip.css({
                        display: 'block',
                        left: event.pageX + 'px',
                        top: (event.pageY - tooltip.outerHeight() - 10) + 'px' // Posiciona a tooltip acima do cursor do mouse
                    });
                  }
                });

                $(this).on('mouseout', function() {
                    $('#tooltip').css('display', 'none');
                });
            });
        });
    }
    function drawPieChart() {
        var data = google.visualization.arrayToDataTable([
          ['Atividades', 'Alunos'],
          ['Completo',   qntCompletas],
          ['Incompleto',  qntIncompletas],
          ['Não fizeram', qntNaoFizeram]
        ]);

        var options = {
          title: 'Atividades completas/incompletas/não fizeram',
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
          var type= " ";
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
    <form action="{{ route('content.resultsContents') }}" method="POST ">
          @csrf
          <label for="turmasel">Informe a turma:</label>
          <div class="form-inline">
              <select class="form-control" name="turma_id" id="turmasel">
                  @foreach ($turmas as $item)
                      <option value="{{ $item->id }}" @if ($item->id === $turma->id) selected="selected" @endif>
                          {{ $item->nome }}</option>
                  @endforeach
              </select>
              <button class="btn btn-primary btn-lg" type="submit">Pesquisar</button>
          </div>
    </form>

    <br>
  </div>
  
  <div id="barras" style="width: 1000px; height: 500px;"></div>
  <div id="rosca" style="width: 900px; height: 500px;"></div>
  <div id="tooltipX" style="display: none; position: absolute; background-color: #fff; border: 1px solid #ccc; padding: 5px; border-radius: 3px; z-index: 100;"></div>

@endsection