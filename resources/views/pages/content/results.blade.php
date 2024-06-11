@extends('layouts.app')

@php
    $pageName= 'Resultados de '. $content->name;
    $qntCompletas= $results['conteudo_completo'];
    $qntIncompletas= $results['conteudo_incompleto'];
    $qntNaoFizeram= $results['conteudo_nao_fizeram'];
    $activities= $activities;
@endphp

@section('page-name', $pageName)

@section('script-head')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
@endsection

@section('content')

  <div id="formTurma">
    <form action="{{ route('content.resultsContents') }}" method="GET ">
          @csrf
          <label for="">Informe a turma:</label>
          <div class="form-inline">
              <select class="form-control" name="turma_id">
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
  
  <div id="barras" style="width: 1000px; height: 800px;"></div>
  <div id="tooltip" style="display: none; position: absolute; background-color: #fff; border: 1px solid #ccc; padding: 5px; border-radius: 3px; z-index: 100;"></div>
  <div id="rosca" style="width: 900px; height: 500px;"></div>

<script type="text/javascript">

    var qntCompletas= <?php echo $qntCompletas; ?>;
    var qntIncompletas= <?php echo $qntIncompletas; ?>;
    var qntNaoFizeram= <?php echo $qntNaoFizeram; ?>;
    var activities= <?php echo $activities; ?>;

    google.charts.load('current', {'packages':['bar', 'corechart']});
    google.charts.setOnLoadCallback(drawStuff);

    function drawStuff() {
        drawBarChart();
        drawPieChart();
    }

    function drawBarChart() {

      const map1 = new Map();

          var data = new google.visualization.DataTable();
          data.addColumn('string','Atividades');
          data.addColumn('number', 'Quantos Responderam');
          data.addColumn({type: 'string', role: 'annotation'});
          var count = 1;
          activities.forEach((activity)=>{
            var value = "A"+count++;
            data.addRow([value, activity.qntFizeram, activity.name]);
            map1.set(value, [value, activity.qntFizeram, activity.name]);
          });


        var options = {
          chart: {
            title: 'Atividades respondidas',
          },
          height: 500,
          series: {
            2: {visibleInLegend: false} // Define a coluna da descrição para não ser exibida no gráfico
              }
        };

        var chart = new google.charts.Bar(document.getElementById('barras'));
        chart.draw(data, google.charts.Bar.convertOptions(options));

        
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