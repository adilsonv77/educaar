@extends('layouts.app')
@section('script')
@endsection
@section('page-name', $titulo)

@section('style')
    <style>
        #selectPanel{
            border: 1px solid #b3b3b3;
            font-size: 14px;
            width: 130px;
            padding: 2px;
            height: fit-content;
            margin: 6px 12px;
            margin-bottom: 23px;
            background-image: linear-gradient(#e9e9e9,#d9d9d9);
        }

        #selectPanel:hover{
            background-color: #a7f2fe;
            background-image: none;
            border: 1px solid #319dd7;
        }
    </style>
@endsection

@section('content') 
    <script>
       function upload_check()
        {
            var upl = document.getElementById("glb");
            var max = 40*1024*1024; // 40MB

            var alert = document.getElementById("alertaGLB");
            if (alert !== null) {
                alert.remove();
            }
            
            if(upl.files[0].size > max)
            {
                const div = document.createElement("div");
                
                upl.parentNode.insertBefore(div, upl.nextSibling);

                div.id = "alertaGLB";
                div.className = "alert alert-danger";

                const ul = document.createElement("ul");
                div.appendChild(ul);

                const li = document.createElement("li");
                li.innerHTML = "Tamanho máximo excedido (~"+ Math.round(upl.files[0].size/1024/1024) + "MB > 40MB)";
                ul.appendChild(li);

                upl.value = "";
            } 
        };

        function HabilitarDesabilitar3D() {
            var alt = document.getElementById("alterar3D");
            var alt2 = document.getElementById("alterarPainel")
            let cenaSelecionada = document.getElementById("selectSceneType").value;
            let cenaAtual = document.getElementById("painel_inicial_id").value;
            //Cena atualmente seleciona = "", significa que é o cadastro, e não há cena atual.
            if(cenaAtual != "" && cenaAtual != "modelo3D")
                cenaAtual= "Painel";

            //Só usa o input se for habilitado a opção e se o modelo3d for selecionado para a atividade.
            if(cenaAtual!=""){
                try {
                    document.getElementById("glb").disabled = (!alt.checked || cenaSelecionada== "Painel")
                    document.getElementById("glb").required = (alt.checked && cenaSelecionada== "Modelo3D")
                    if (!alt.checked) {
                        var upl = document.getElementById("glb");
                        upl.value = "";
                    }
                } catch (error) {
                    document.getElementById("glb").disabled = !(cenaSelecionada == "Modelo3D" && cenaAtual == "Painel");
                    document.getElementById("glb").required = (cenaSelecionada == "Modelo3D" && cenaAtual == "Painel"); 
                    var upl = document.getElementById("glb");
                    upl.value = "";
                } 
                //Só usa o input se for habilitado a opção e se o painel for selecionado para a atividade.
                try {
                    document.getElementById("selectPanel").disabled = (!alt2.checked || cenaSelecionada== "Modelo3D")
                    document.getElementById("panelId").required = (alt2.checked && cenaSelecionada == "Painel")
                } catch (error) {
                    document.getElementById("selectPanel").disabled = !(cenaSelecionada == "Painel" && cenaAtual == "modelo3D");
                    document.getElementById("panelId").required = (cenaSelecionada == "Painel" && cenaAtual == "modelo3D");
                }  
            }else{

            }
        }

         function HabilitarDesabilitarImagemMarcador() {
            var alt = document.getElementById("alterarMarcador");

            document.getElementById("marcador").disabled = !alt.checked;
            document.getElementById("marcador").required = alt.checked;

            if (!alt.checked) {
                var upl = document.getElementById("marcador");
                upl.value = "";
            }
         }

         function habilitarBotoesEscolhaDeCena() {
            
         }
    </script>

    <div class="card">
        <div class="card-body">
         @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('activity.store') }}" enctype="multipart/form-data" files="true" onsubmit="desativarBotao(this)">>
                <h3>Atividade</h3>
                @csrf
                    <input name="id" type="hidden" value="{{$id}}"/>
                    <input name="acao" type="hidden" value="{{$acao}}"/>
                    <input name="painel_inicial_id" type="hidden" value="{{ $painel_inicial_id ?? ''}}" id="painel_inicial_id"/>

                <div class="form-group">
                    <label for="">Nome da Atividade*</label>
                    <input id="name" type="text" maxlength="100"class="form-control @error('name') is-invalid @enderror" name="name"
                            value="{{ old('name', $name) }}" required autocomplete="name" autofocus/>
                </div>

                <div class="form-group">
                    <label for="">Conteúdo*</label>
                    <select class="form-control" name="content_id" aria-label="">
                        @foreach ($contents as $item)
                            <option value="{{ $item->id }}" @if ($item->id === $content) selected="selected" @endif>{{ $item->total_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="">Selecione o tipo da cena*</label>
                    <select class="form-control" id="selectSceneType" name="sceneType" aria-label="">             
                        <option value="Modelo3D" selected>Modelo 3D</option>
                        <option value="Painel">Painel</option>
                    </select>
                </div>

                <div class="form-group" id="3DmodelOption">
                        @if ($acao == 'edit' && $painel_inicial_id == 'modelo3D') 
                            <input type="checkbox" id="alterar3D" name="alterar3D" value="S" onclick="HabilitarDesabilitar3D()"/>
                        @endif
                        
                        <label for="alterar3D">Modelo 3D (GLB ou GLTF->ZIP)*</label>
                        <span class="alert-danger">Tamanho máximo: 40MB</span>
                        <input type="file" style="border:none" class="form-control" name="glb"
                            id="glb" accept=".glb, .zip" onchange="upload_check()" @if($acao === 'edit' && $painel_inicial_id == "modelo3D") disabled @elseif($acao == 'edit') required @endif/>
                </div>

                <div class="form-group" id="panelOption" style="display: none">
                        @if ($acao == 'edit' && $painel_inicial_id != 'modelo3D') 
                            <input type="checkbox" id="alterarPainel" name="alterarPainel" value="S" onclick="HabilitarDesabilitar3D()"/>
                        @endif
                        
                        <label for="alterarPainel">Painel*</label><br>
                        <input type="button" id="selectPanel" value="Escolher painel" @if($acao === 'edit') disabled @endif/>
                </div>

                <div class="form-group">
                        @if ($acao == 'edit') 
                            <input type="checkbox" id="alterarMarcador" name="alterarMarcador" value="S" onclick="HabilitarDesabilitarImagemMarcador()"/>
                        @endif
                        <label for="alterarMarcador">Marcador (PNG ou JPEG ou JPG)*</label>
                        <input type="file" @if($acao === 'insert') required @endif style="border:none" class="form-control" name="marcador"
                            id="marcador" accept=".png, .jpeg, .jpg"  @if($acao === 'edit') disabled @endif/>
                </div>

                <input id="panelId" name="panelId" type="hidden" @if($acao === 'edit') value="{{$painel_inicial_id}}" @endif>


                <div class="form-group mt-4" onsubmit="desativarBotao(this)">
                    <button type="submit" id="btnSalvar" class="btn btn-success">
                        <i >Salvar</i> 
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById("selectPanel").onclick = ()=>{
            document.getElementById("panelId").value = prompt("Insira o ID do painel.")
        }

        document.getElementById("selectSceneType").onchange = ()=>{
            let valor = document.getElementById("selectSceneType").value;
            if (valor == "Modelo3D") {
                document.getElementById("3DmodelOption").style.display = "block"
                document.getElementById("panelOption").style.display = "none"
            }else{
                document.getElementById("panelOption").style.display = "block"
                document.getElementById("3DmodelOption").style.display = "none"
            }

            HabilitarDesabilitar3D()
        }
        function desativarBotao(form) {
        let botao = form.querySelector("#btnSalvar");
        botao.disabled = true; 
        
    }

    </script>
@endsection
 