@extends('layouts.app')

@php
    $pageName =  $activity->name;
    $qntCompletas= $result['alunos_fizeram_completo'];
    $qntIncompletas= $result['alunos_fizeram_incompleto'];
    $qntNaoFizeram= $result['alunos_nao_fizeram'];
    $questions_results= $questions; 
@endphp

@section('page-name', $pageName)

@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>

  <div id="formTurma">
  <form action="{{ route('activity.results') }}" method="GET ">
          @csrf
          <label for="">Informe a turma:</label>
          <div class="form-inline">
              <select class="form-control" name="turma_id">
                  @foreach ($turmas as $item)
                      <option value="{{ $item->id }}" @if ($item->id === $turma->id) selected="selected" @endif>
                          {{ $item->nome }}</option>
                  @endforeach
              </select>
              <section class="itens-group">
                  <button class="btn btn-primary "type="submit">Pesquisar</button>
              </section>
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
    var questoes_resultados= <?php echo $questions_results; ?>;

    google.charts.load('current', {'packages':['bar', 'corechart']});
    google.charts.setOnLoadCallback(drawStuff);

    function drawStuff() {
        drawBarChart();
        drawPieChart();
    }

    function drawBarChart() {

      const map1 = new Map();

          var data = new google.visualization.DataTable();
          data.addColumn('string','Questões');
          data.addColumn('number', 'Respostas Corretas');
          data.addColumn('number', 'Questões Respondidas');
          data.addColumn({type: 'string', role: 'annotation'}); // Coluna de descrição sem ser exibida no gráfico

          var count = 1;
          questoes_resultados.forEach((question)=>{
            var value = "Q"+count++; 
            map1.set(value, [question.quntRespondCerto, question.qntRespondida, question.questao]);
            data.addRow([value, question.quntRespondCerto, question.qntRespondida, question.questao ]);
          });

          var options = {
              chart: {
                title: 'Questoes Corretas',
                subtitle: 'Questoes Respondidas',
              },
              vAxis: {
                gridlines:{
                  count:1
                }
              },
              series: {
            3: {visibleInLegend: false} // Define a coluna da descrição para não ser exibida no gráfico
              }
            };

        var chart = new google.charts.Bar(document.getElementById('barras'));
        chart.draw(data, google.charts.Bar.convertOptions(options));

       // Adicionando eventos de mouse às legendas após o gráfico ser desenhado
      google.visualization.events.addListener(chart, 'ready', function() {
            $('#barras text').each(function(index) {

              $(this).on('mouseover', function() {
                  var question= map1.get($(this).text());
                  if(question !== undefined){
                    var tooltip = $('#tooltip');
                    tooltip.text(question[2]);
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
          ['Questionário', 'Alunos'],
          ['Completo',   qntCompletas],
          ['Incompleto',  qntIncompletas],
          ['Não fizeram', qntNaoFizeram]
        ]);

        var options = {
          title: 'Questionário completo/incompleto',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('rosca'));
        chart.draw(data, options);


        google.visualization.events.addListener(chart, 'select', selectHandler);

        function selectHandler() {
        var selectedItem = chart.getSelection()[0];
        var type= " ";
        if (selectedItem) {
        type = data.getValue(selectedItem.row, 0);
        var url = "{{ route('activity.listStudents', ['type' => 'type_selection']) }}".replace('type_selection', type);
        window.location.href = url;
      }
}
    }
</script>
</body>
</html>





@endsection
