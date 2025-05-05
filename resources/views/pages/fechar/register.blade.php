@extends('layouts.app')

@section('page-name', $titulo)

@section('content')
 
    <div class="card">
        <div class="card-body">
            
             
                <h3>Conteúdo</h3>
                <body>
                <div id="content_id" style="display:none">{{$content->id}}</div>
                <div id="receivemind" style="display:none">/receivemind</div>
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Nome : <b>{{ $content->name }}</b></td>
                        </tr>
                        <tr>
                            <td>Disciplina (Série) : <b> {{ $content->dname }} ( {{ $content->tserie }} )</b></td>
                        </tr>

                    </tbody>
                </table>
 
              
                <h3>Atividades</h3>
                
                <table class="table">
                    <tbody>
                        
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

                    </tbody>
                </table>


                <div class="form-group mt-4" style="width: 100%">

                    <p style="text-align: center; font-size: 70%; margin: 0px 0px 5px; visibility: hidden" id="aguarde" >Aguarde...</p>
                    <div style="width: 50%; display: block;  margin-left: auto; margin-right: auto;">
                        <div class="progress" style="height: 20px">
                            <div id="progressbar" class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                            Compilando</div>
                        </div>
                        <br/>
                        <input type="button" value="Fechar" class="btn btn-success button_pre_compiler" style="width: 30%; display: block;  margin-left: auto; margin-right: auto;" id="fechar">
                    </div>
                    <div class="click_compilar" style="display:none">Clique-me</div>

                    <div class="button_compiler" style="display:none">Clique-me</div>

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

 