@extends('layouts.app')

@php
    $pageName = 'Resultados';
@endphp

@section('page-name', $pageName)

@section('content')

<h1>Chegou!!!!!!!</h1>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>

<div id="barras" style="width: 800px; height: 600px;"></div>
<div id="rosca" style="width: 900px; height: 500px;"></div>

<script type="text/javascript">
    google.charts.load('current', {'packages':['bar', 'corechart']});
    google.charts.setOnLoadCallback(drawStuff);

    function drawStuff() {
        drawBarChart();
        drawPieChart();
    }

    function drawBarChart() {
        var data = google.visualization.arrayToDataTable([
          ['Questoes', 'Respostas Corretas', 'Questoes Respondidas'],
          ['Q1', 12, 12],
          ['Q2', 10, 14],
          ['Q3', 6, 13],
          ['Q4', 14, 14]
        ]);

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
          ['Completo',     11],
          ['Incompleto',      2],
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
