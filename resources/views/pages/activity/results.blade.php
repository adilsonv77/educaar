@extends('layouts.app')

@php
    $pageName =  $activity->name;
    $refeita = \App\DAO\ActivityDAO::refeita($activity['id']);
    $pontuada = \App\DAO\ActivityDAO::getPontuacao($activity['id']);
    $qntCompletas= $result['qtd_alunos_fizeram_completo'];
    $qntIncompletas= $result['qtd_alunos_fizeram_incompleto'];
    $qntNaoFizeram= $result['qtd_alunos_nao_fizeram'];
    $questions_results= $questions; 

    /* Variáveis de tradução para o JS */
    $titleChart1 = __('statistics.questions_answereds');
    $corretAnswer = __('statistics.correct_answer');
    $incorretAnswer = __('statistics.incorrect_answer');
    $questionsChart = trans_choice('entities.question', 2);
    $titleChart2 = __(('statistics.titleChart2_activity'));
    $complete = __('ui.adjective.complete');
    $incomplete = __('ui.adjective.incomplete');
    $notAnswered = __('ui.adjective.not_answered');
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
            <label for="" >{{ __('ui.input.enter_class') }}</label>
            <div class="form-inline" >
                <select class="form-control" name="turma_id">
                    @foreach ($turmas as $item)
                        <option value="{{ $item->id }}" @if ($item->id === $turma->id) selected="selected" @endif>
                            {{ $item->nome }}</option>
                    @endforeach
                </select>
                <section class="itens-group" >
                    <button class="btn btn-primary btn-lg" type="submit">{{ __('ui.action.search') }}</button>
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
       <b>{{ __('statistics.no_results')}}</b>
  @else

  <div style="background-color: white">
    <div id="barras" style="width: 1000px; height: 800px;"></div>
    <div id="rosca" style="width: 900px; height: 500px;"></div>
  </div>

  <div id="tooltip" style="display: none; position: absolute; background-color: #fff; border: 1px solid #ccc; padding: 5px; border-radius: 3px; z-index: 100;"></div>

  <div class="alert alert-primary" style="border-top-left-radius: 0!important; border-top-right-radius: 0!important;">
      <b>{{ __('statistics.see_more') }}</b>
      <ul>
          <li style="list-style:square"> &#9989; : {{ __('statistics.stu_correct') }}</li>
          <li style="list-style:square"> &#10060; : {{ __('statistics.stu_wrong') }}</li>
          <li style="list-style:square">&#128993; : {{ __('statistics.stu_not_respond') }}</li>
      </ul>
    </div>

   <div style="overflow: hidden; border-radius: 10px;">  <!--isso aqui é pa arredondar as bordas superiores da tabela -->
    <table id="table" class="table table-bordered">
      <thead class="thead-info">
        <tr>
        <th scope="col">{{ __('ui.input.name') }}</th>
          @php
              $count=1;
          @endphp
          @foreach ($questions as $question)
            <th id="Q{{ $count }}" data-bs-toggle="tooltip" title="Q{{ $count }}" scope="col">{{ "Q".$count++ }}</th>
          @endforeach

          @if($refeita)
            <th scope="col1" style="width: 15%;">{{ trans_choice('entities.attempt', 2) }}</th>

            @if($pontuada != null)
              <th scope="col1" style="width: 15%;">{{ trans_choice('entities.score', 1) }}</th>
            @endif

          @endif
        </tr>
      </thead>
      <tbody>

            @foreach($respostasSelecionadas as $item) <!-- Itera sobre alunos -->
                  <tr class="table-light">
                    <td>{{$item['name']}}</td>
                    @foreach($questions as $question) <!-- Itera sobre questões -->

                      @if(isset($item['q'.$question->id])) <!-- Aluno respondeu? -->

                        @if($item['q'.$question->id.'correta']==1) <!-- Resposta correta? -->
                          <td data-bs-toggle="tooltip" title="{{ $item['q'.$question->id] }}" class="table-success"> &#9989 </td>
                        @else
                          <td data-bs-toggle="tooltip" title="{{ $item['q'.$question->id] }}" class="table-danger"> &#10060 </td>
                        @endif

                      @else
                        <td class="table-warning">&#128993;</td>
                      @endif

                    @endforeach

                    @if($refeita)
                      <td>{{$item['tentativa']}}</td>
                    @endif

                    @if($pontuada)
                      <td>{{$item['pontuacao']}}</td>
                    @endif
                    
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

        var qntCompletas = {{ $qntCompletas }};
        var qntIncompletas = {{ $qntIncompletas }};
        var qntNaoFizeram = {{ $qntNaoFizeram }};

        /* Variáveis de traduçãos pegas do PHP */
        var titleChart1 = "<?php print $titleChart1; ?>";
        var correctAnswer = "<?php print $corretAnswer ?>"
        var incorrectAnswer = "<?php print $incorretAnswer ?>"
        var questionsChart = "<?php print $questionsChart ?>"
        var titleChart2 = "<?php print $titleChart2 ?>";
        var complete = "<?php print $complete ?>"
        var incomplete = "<?php print $incomplete ?>"
        var notAnswered = "<?php print $notAnswered ?>"
        
        var questoes_resultados = @json($questions_results);

        google.charts.load('current', {'packages':['bar', 'corechart']});
        google.charts.setOnLoadCallback(drawStuff);

        function drawStuff() {
            drawBarChart();
            drawPieChart();
            
        }
        
        const map1 = new Map();

        function drawBarChart() {

              var data = new google.visualization.DataTable();
              data.addColumn('string', questionsChart);
              data.addColumn('number', incorrectAnswer);
              data.addColumn('number', correctAnswer); 
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
                  title: titleChart1
                },
                isStacked: true,
                colors: ['#5A2D66','#9C6FA8'], 
                // tooltip: {isHtml: true},
                // legend: 'none'            
              };

              var chart = new google.charts.Bar(document.getElementById('barras'));

              chart.draw(data, google.charts.Bar.convertOptions(options));

          }

        function drawPieChart() {
            var data = google.visualization.arrayToDataTable([
              ['Questionário', 'Alunos'],
              [complete,   qntCompletas],
              [incomplete,  qntIncompletas],
              [notAnswered, qntNaoFizeram]
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
