@extends('layouts.app')

@php
    $pageName = 'Resultados';
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
  <form action="{{ route('turmas.index') }}" method="GET ">
          @csrf
          <label for="">Informe a turma:</label>
          <div class="form-inline">

              <select class="form-control" name="ano_id">
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

          var data = new google.visualization.DataTable();
          data.addColumn('string','Questões');
          data.addColumn('number', 'Respostas Corretas');
          data.addColumn('number', 'Questões Respondidas');
          questoes_resultados.forEach((question)=>data.addRow([question.questao, question.quntRespondCerto, question.qntRespondida ]));

        

        var options = {
          chart: {
            title: 'Questoes Corretas',
            subtitle: 'Questoes Respondidas',
          }
        };

        var chart = new google.charts.Bar(document.getElementById('barras'));
        chart.draw(data, google.charts.Bar.convertOptions(options));
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
    }
</script>
</body>
</html>





@endsection
