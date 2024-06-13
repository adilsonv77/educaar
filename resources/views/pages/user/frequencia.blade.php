@extends('layouts.app')

@php
    $pageName= 'Frequência de acesso ao sistema';

@endphp

@section('page-name', $pageName)

@section('script-head')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
  
    @if($acesso !== 'ultacesso') 

    google.charts.load('current', {'packages':['bar', 'corechart']});
    google.charts.setOnLoadCallback(drawStuff);

    @endif
    
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
            title: '{{ $titgrafico }}',
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
          
          <div class="mb-3">
              <label  class="form-label" for="">Informe a turma : </label>
              <div class="form-inline">
                <select class="form-control" name="turma_id">
                    @foreach ($turmas as $item)
                        <option value="{{ $item->id }}" @if ($item->id === $turma->id) selected="selected" @endif>
                            {{ $item->nome }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary btn-lg" type="submit">Pesquisar</button>
              </div>
          </div>

          <div class="mb-3">
              <label  class="form-label" >Filtro : </label>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="pordia" name="acessos" value="pordia" onchange="this.form.submit();" @if($acesso === 'pordia') checked @endif>
                <label class="form-check-label" for="pordia">Acessos por dia</label><br>
              </div>
              <div class="form-check form-check-inline">  
                <input class="form-check-input" type="radio" id="poralunos" name="acessos" value="poralunos" onchange="this.form.submit();" @if($acesso === 'poralunos') checked @endif>
                <label class="form-check-label" for="poralunos">Alunos que acessaram por dia</label><br>
              </div>
              <div class="form-check form-check-inline">  
                <input class="form-check-input" type="radio" id="ultacesso" name="acessos" value="ultacesso" onchange="this.form.submit();" @if($acesso === 'ultacesso') checked @endif>
                <label class="form-check-label" for="ultacesso">Último acesso de cada aluno</label><br>
              </div>

          </div>
          <div class="mb-3">
            <div id="curve_chart" style="width: 1000px; height: 800px;"></div>
         </div>
   </form>

  </div>
  






@endsection