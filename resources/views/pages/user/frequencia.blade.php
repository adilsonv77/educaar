@extends('layouts.app')

@php
    $pageName= 'Frequência de acesso ao sistema';

@endphp

@section('page-name', $pageName)

@section('script-head')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">

    google.charts.load('current', {'packages':['bar', 'corechart']});
    google.charts.setOnLoadCallback(drawStuff);

    function drawStuff() {
        drawLineChart();
     }

    function drawLineChart() {
      var data = new google.visualization.DataTable();
        data.addColumn('date', 'Data ');
        data.addColumn('number', 'Acessos');

        data.addRows([
            @foreach ($freq as $item) 
              [new Date('{{$item->momento}}'), {{$item->quantos}}], 
            @endforeach
        ]);
        
        var options = {
            title: 'Acessos por dia',
            hAxis: {
              title: 'Data',
              format: 'dd/MM',
              ticks: [
                @foreach ($freq as $item) 
                  new Date('{{$item->momento}}'), 
                @endforeach
              ]

            },
            vAxis: {
                title: 'Quantos',
                ticks: [
                 @foreach ($ticksquantos as $item) 
                  {{$item}}, 
                 @endforeach
                ]
            },
            legend: 'none',
            pointSize: 5
        }

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
</script>

@endsection

@section('content')

  <div id="formTurma">
    <form action="{{ route('teacher.frequencia') }}" method="GET ">
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
  
  <div id="curve_chart" style="width: 1000px; height: 800px;"></div>
  <div id="tooltip" style="display: none; position: absolute; background-color: #fff; border: 1px solid #ccc; padding: 5px; border-radius: 3px; z-index: 100;"></div>






@endsection