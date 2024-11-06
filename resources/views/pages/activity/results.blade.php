@extends('layouts.app')

@php
    $pageName =  $activity->name;
    $qntCompletas= $result['alunos_fizeram_completo'];
    $qntIncompletas= $result['alunos_fizeram_incompleto'];
    $qntNaoFizeram= $result['alunos_nao_fizeram'];
    $questions_results= $questions; 
@endphp

@section('script-head')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<!-- Bootstrap CSS -->
<!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet"> -->
<!-- <link href="{{ asset('css/telainicial.css?v=' . filemtime(public_path('css/telainicial.css'))) }}" rel="stylesheet">  -->
@endsection 

@section('page-name', $pageName)

@section('content')

  <div id="formTurma">
  <form action="{{ route('activity.results') }}" method="GET ">
          @csrf
          
          <div class="form-inline" >
          
          <label for="" >Informe a turma: </label>
              <select class="form-control" name="turma_id">
                  @foreach ($turmas as $item)
                      <option value="{{ $item->id }}" @if ($item->id === $turma->id) selected="selected" @endif>
                          {{ $item->nome }}</option>
                  @endforeach
              </select>
              <section class="itens-group" >
                  <button class="btn btn-primary btn-lg" type="submit">Pesquisar</button>
              </section>
          </div>
      </form>
      <br>
    
  </div>
  <style>
    .form-inline {
        display: flex;
        justify-content: flex-start; 
    }

    .form-inline label {
      
      margin-right: 10px;
    }
</style>
  
  @if ($qntCompletas + $qntIncompletas == 0)
       <b>Nenhum aluno ainda acessou.</b>
  @else

    <div id="barras" style="width: 1000px; height: 800px;"></div>
    <div id="tooltip" style="display: none; position: absolute; background-color: #fff; border: 1px solid #ccc; padding: 5px; border-radius: 3px; z-index: 100;"></div>
    <div id="rosca" style="width: 900px; height: 500px;"></div>

    <div class="alert alert-primary">
      <b>Veja mais detalhes ao passar o mouse sobre o título da questão ou a resposta do aluno.</b>
      <ul>
          <li style="list-style:square"> &#9989; : O aluno acertou a questão</li>
          <li style="list-style:square"> &#10060; : O aluno errou a questão</li>
          <li style="list-style:square">&#128993; : O aluno não fez a questão</li>
      </ul>
    </div>

   <div style="overflow: hidden; border-radius: 10px;">  <!--isso aqui é pa arredondar as bordas superiores da tabela -->
    <table id="table" class="table table-bordered">
      <thead class="thead-info">
        <tr>
        <th scope="col">Nome</th>
          @php
              $count=1;
          @endphp
          @foreach ($questions as $question)
            <th id="Q{{ $count }}" data-bs-toggle="tooltip" title="Q{{ $count }}" scope="col">{{ "Q".$count++ }}</th>
          @endforeach
        </tr>
      </thead>
      <tbody>

            @foreach($respostasSelecionadas as $item)
                  <tr class="table-light">
                    <td>{{$item['name']}}</td>
                    @foreach($questions as $question)
                      @if(isset($item['q'.$question->id]))
                        <td data-bs-toggle="tooltip" title="{{ $item['q'.$question->id] }}"
                        @if($item['q'.$question->id.'correta']==1)class="table-success"
                        @elseif($item['q'.$question->id.'correta']==0) class="table-danger"@endif >
                        @if($item['q'.$question->id.'correta']==1) &#9989; @elseif($item['q'.$question->id.'correta']==0) &#10060; @endif</td>
                      @else
                      <td class="table-warning">&#128993;</td>
                      @endif
                      @endforeach
                  </tr>
            @endforeach
      </tbody>
<!-- isso aqui é um estilo pra corzinha bunitinha, coloquei aqui msm -->
      <style> 
        .table thead th {
            background-color: #7e3789 !important; 
            color: #000; 
        }
      </style>
    </table>

  </div>


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
        const map1 = new Map();

        function drawBarChart() {

              var data = new google.visualization.DataTable();
              data.addColumn('string','Questões');
              data.addColumn('number', 'Respostas Incorretas');
              data.addColumn('number', 'Respostas Corretas '); 
              //data.addColumn({type: 'string', role: 'tooltip'}); 


              var count = 1;
              var titleTable=null;
              questoes_resultados.forEach((question)=>{
                var value = "Q"+count++; 
                var respostasIncorretas = question.qntRespondida - question.quntRespondCerto; 
                // console.log("Questão: " + question.questao + " | Respostas Incorretas: " + respostasIncorretas); 
                // map1.set(value [question.qntRespondida ,question.quntRespondCerto , question.questao ]);
                titleTable= document.getElementById(value);
                titleTable.setAttribute('title', question.questao);
                //console.log(question)
                //data.addRow([value ,respostasIncorretas , question.quntRespondCerto,"minehu"]);
                 data.addRow([{f:value + ": " + question.questao, v:value}, respostasIncorretas,question.quntRespondCerto]);
              });


              var options = {
                chart: {
                  title: 'Respostas Corretas e Incorretas'
                },
                isStacked: true,
                colors: ['#5A2D66','#9C6FA8'] 
                // tooltip: {isHtml: true},
                // legend: 'none'            
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
              var url = "{{ route('activity.listStudents', ['type' => 'type_selection']) }}".replace('type_selection', type);
              window.location.href = url;
            }
        }
    }

    // Inicialização dos tooltips após o carregamento da página
    document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

    <!-- jQuery, Popper.js, and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
@endif
@endsection
