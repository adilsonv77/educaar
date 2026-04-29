@extends('layouts.app')

@php
    $pageName= __('System access frequency');

    /* Variáveis de tradução para o JS */
    $date = __('Date');
    $access = __('Access');
    $howMany = __('How many');
@endphp

@section('page-name', $pageName)

@section('script-head')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
  
    var labelDate = <?php echo json_encode($date); ?>;
    var labelAccess = <?php echo json_encode($access); ?>;
    var labelHowMany = <?php echo json_encode($howMany); ?>;


    @if($acesso !== 'ultacesso') 

    google.charts.load('current', {'packages':['bar', 'corechart']});
    google.charts.setOnLoadCallback(drawStuff);
    function drawStuff() {
        drawLineChart();
     }

    function drawLineChart() {
      var data = new google.visualization.DataTable();
        data.addColumn('date', labelDate);
        data.addColumn('number', labelAccess);

        data.addRows([
            @foreach ($freq as $item) 
              [new Date('{{$item->momento}} 00:00:00'), {{$item->quantos}}], 
            @endforeach
        ]);
        
        var options = {
            title: '{{ $titgrafico }}',
            hAxis: {
              title: labelDate,
              format: 'dd/MM',
              ticks: [
                @foreach ($freq as $item) 
                  new Date('{{$item->momento}} 00:00:00'), 
                @endforeach
              ]

            },
            vAxis: {
                title: labelHowMany,
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

    @endif
    
</script>

@endsection

@section('content')

  <div id="formTurma">
    <form action="{{ route('teacher.frequencia.filter') }}" method="POST">
          @csrf
          
          <div class="col-md-6">
              <label>{{ __('Enter the class') }}: </label>

                <select class="form-control" name="turma_id" id="turma_id">
                    @foreach ($turmas as $item)
                        <option value="{{ $item->id }}" @if ($item->id === $turma->id) selected="selected" @endif>
                            {{ $item->nome }}</option>
                    @endforeach
                </select>
                
         </div>

          @if($acesso !== 'ultacesso')
          <div class="row pb-3">
            <div class="col-md-3">
              <label>{{ __('Start date') }}:</label>
              <input type="date" name="start_date" class="form-control"/>
            </div>
            <div class="col-md-3">
              <label>{{ __('Final date') }}:</label>
              <input type="date" name="end_date" class="form-control"/>
            </div>
            
          </div>
          @endif
          
          <div class="col-md-3">
              <button class="btn btn-primary" type="submit">{{ __('Search') }}</button>
          </div>
 
          <div class="mb-3">
              <label  class="form-label" for="pordia">{{ __('Filter') }}: </label>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="pordia" name="acessos" value="pordia" onchange="this.form.submit();" @if($acesso === 'pordia') checked @endif>
                <label class="form-check-label" for="pordia">{{ __('Accesses per day') }}</label><br>
              </div>
              <div class="form-check form-check-inline">  
                <input class="form-check-input" type="radio" id="poralunos" name="acessos" value="poralunos" onchange="this.form.submit();" @if($acesso === 'poralunos') checked @endif>
                <label class="form-check-label" for="poralunos">{{ __('Student who acessed per day') }}</label><br>
              </div>
              <div class="form-check form-check-inline">  
                <input class="form-check-input" type="radio" id="ultacesso" name="acessos" value="ultacesso" onchange="this.form.submit();" @if($acesso === 'ultacesso') checked @endif>
                <label class="form-check-label" for="ultacesso">{{ __('Last acess for each student') }}</label><br>
              </div>

          </div>
         
         @if($acesso === 'ultacesso')
         <div class="card">
            <div class="card-body">
               <div class="table-responsive">
                  <table class="table table-hover table-responsive-sm">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Acess') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($alunos as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>@if(!$item->acesso) {{ __('Never') }} @else {{ $item->acesso }} @endif  </td>
                            </tr>
                        @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
         @else
           <div class="mb-3">
            <div id="curve_chart" style="width: 1000px; height: 800px;"></div>
          </div>
 
         @endif
   </form>

  </div>
  






@endsection