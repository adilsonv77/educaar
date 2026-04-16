@extends('layouts.app')

@section('page-name', $titulo)

@section('content')
 
    <div class="card">
        <div class="card-body">
            
             
                <h3>{{ trans_choice('entities.content', 1) }}</h3>
                <body>
            <div id="content_id" style="display:none">{{$content->id}}</div>
                <div id="receivemind" style="display:none">/receivemind</div>
                <table class="table">
                    <tbody>
                        <tr>
                            <td>{{ __('ui.input.name') }} : <b>{{ $content->name }}</b></td>
                        </tr>
                        <tr>
                            <td>{{ trans_choice('entities.discipline', 1) }} ({{ trans_choice('entities.model_class', 1) }}) : <b> {{ $content->dname }} ( {{ $content->tserie }} )</b></td>
                        </tr>

                    </tbody>
                </table>
              
                <h3>{{ trans_choice('entities.activity', 2) }}</h3>
                
                <table class="table">
                    <tbody>
                        @if(isset($id) && $content->sort_activities)
                        <tr>
                            <td>
                                <div class="mt-4">
                                    @livewire('content-activities-order', ['contentId' => $content->id])
                                </div>
                            </td>
                        </tr>    
                        @else    
                            @foreach ($activities as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        <img src="/marcadores/{{ $item->marcador }}" alt=""width="200"
                                                height="200">
                                        <li style="display:none" class="imagens_compilar">/marcadores/{{ $item->marcador }}</li>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                        
                        

                    </tbody>
                </table>


                <div class="form-group mt-4" style="width: 100%">

                    <p style="text-align: center; font-size: 70%; margin: 0px 0px 5px; visibility: hidden" id="aguarde" >{{ __('ui.prompt.wait') }}...</p>
                    <div style="width: 50%; display: block;  margin-left: auto; margin-right: auto;">
                        <div class="progress" style="height: 20px">
                            <div id="progressbar" class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                            {{ __('ui.prompt.compile') }}</div>
                        </div>
                        <br/>
                        <input type="button" value="{{ __('ui.action.close') }}" class="btn btn-success button_pre_compiler" style="width: 30%; display: block;  margin-left: auto; margin-right: auto;" id="fechar">
                    </div>
                    <div class="click_compilar" style="display:none">{{ __('ui.prompt.click') }}</div>

                    <div class="button_compiler" style="display:none">{{ __('ui.prompt.click') }}</div>

                </div>

 
      
        </div>
    </div>
@endsection

@section('script')

    <script>
        var mostrarAvanco = function(percent) {
            var pb = document.getElementById("progressbar");
            pb.style = "width: " + percent + "%";
        }

        document.mostrarAvanco = mostrarAvanco;

        var botao = document.getElementsByClassName("button_pre_compiler");
        botao[0].addEventListener('click', function() {
           this.disabled = "disabled";
           this.cursor = "not-allowed";
           document.getElementById("aguarde").style.visibility = "visible";

           var botaoX = document.getElementsByClassName("button_compiler");
           botaoX[0].click();
        });
    </script>

    <script src="./js/compilarimagens.js" type="module">
      
    </script>
   
    <script>
        var botao = document.getElementsByClassName("click_compilar");
        botao[0].addEventListener('click', function() {
            window.location = '{{ route("fecharconteudo.store") }}';
        });
    
    </script>
    
@endsection

 