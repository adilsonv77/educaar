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
            let tipoAtividade = document.getElementById("selectSceneType").value;

            let alt1 = document.getElementById("alterar3D");
            let alt2 = document.getElementById("alterarPainel")

            let alt1checked = (alt1 == null) || (alt1.checked);
            let alt2checked = (alt2 == null) || (alt2.checked);

            if (tipoAtividade === "Modelo3D") {
                document.getElementById("glb").disabled = !alt1checked;
                document.getElementById("glb").required = alt1checked;
                document.getElementById("selectScene").required = false;
            } else {
                document.getElementById("selectScene").disabled = !alt2checked;
                document.getElementById("selectScene").required = alt2checked;
                document.getElementById("glb").required = false;
            }

/*

            var alt = document.getElementById("alterar3D");
            var alt2 = document.getElementById("alterarPainel")
            let cenaSelecionada = document.getElementById("selectSceneType").value;
            let cenaAtual = document.getElementById("scene_id").value;
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
                    document.getElementById("selectScene").disabled = (!alt2.checked || cenaSelecionada== "Modelo3D")
                    document.getElementById("panelId").required = (alt2.checked && cenaSelecionada == "Painel")
                } catch (error) {
                    document.getElementById("selectScene").disabled = !(cenaSelecionada == "Painel" && cenaAtual == "modelo3D");
                    document.getElementById("panelId").required = (cenaSelecionada == "Painel" && cenaAtual == "modelo3D");
                }  
            }else{

            }
 */ 
        }

         function HabilitarDesabilitarImagemMarcador() {
            var alt = document.getElementById("alterarMarcador");

            document.getElementById("marcador").disabled = !alt.checked;
            document.getElementById("marcador").required = alt.checked;
/*
            if (!alt.checked) {
                var upl = document.getElementById("marcador");
                upl.value = "";
            }
                */
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
            <form method="POST" action="{{ route('activity.store') }}" enctype="multipart/form-data" files="true" onsubmit="desativarBotao(this)">

           


                <h3>Atividade</h3>
                @csrf
                <input name="id" type="hidden" value="{{$id}}"/>
                <input name="acao" type="hidden" value="{{$acao}}"/>
                <input name="scene_id" type="hidden" value="{{ $scene_id ?? ''}}" id="scene_id"/>

                <!------------NOME DA ATIVIDADE-------------->
                <div class="form-group">
                    <label for="">Nome da Atividade*</label>
                    <input id="name" type="text" maxlength="100"class="form-control @error('name') is-invalid @enderror" name="name"
                            value="{{ old('name', $name) }}" required autocomplete="name" autofocus/>
                </div>

                <!-----------SELECIONAR CONTEÚDO------------->
                <div class="form-group">
                    <label for="">Conteúdo*</label>
                    <select class="form-control" name="content_id" aria-label="">
                        @foreach ($contents as $item)
                            <option value="{{ $item->id }}" @if ($item->id === $content) selected="selected" @endif>{{ $item->total_name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-------SELECIONAR TIPO DE ATIVIDADE-------->
                
                <div class="form-group">
                    <label for="">Selecione o tipo de atividade*</label>
                    <select class="form-control" id="selectSceneType" name="sceneType" aria-label="">             
                        <option value="Modelo3D" @if($scene_id === 'modelo3D') selected @endif>Modelo 3D</option>
                        <option value="Cena" @if($scene_id != 'modelo3D') selected @endif>Cena</option>
                    </select>
                </div>

                <!-------------ENVIAR MODELO3D--------------->
                <div class="form-group" id="3DmodelOption"  @if($scene_id != 'modelo3D')  style="display: none" @endif >
                        @if ($acao == 'edit'  && $scene_id === 'modelo3D' ) 
                            <input type="checkbox" id="alterar3D" name="alterar3D" value="S" onclick="HabilitarDesabilitar3D()"/>
                        @endif
                        
                        <label for="alterar3D">Modelo 3D (GLB ou GLTF->ZIP)*</label>
                        <span class="alert-danger">Tamanho máximo: 40MB</span>
                        <input type="file" @if($acao === 'insert') required @endif style="border:none" class="form-control" name="glb"
                            id="glb" accept=".glb, .zip" onchange="upload_check()" @if($acao === 'edit' && $scene_id == "modelo3D") disabled  @endif/>
                </div>

                <!------------SELECIONAR CENA--------------->
                <div class="form-group" id="panelOption"  @if($scene_id === 'modelo3D')  style="display: none" @endif>
                        @if ($acao == 'edit' && $scene_id != 'modelo3D' ) 
                            <input type="checkbox" id="alterarPainel" name="alterarPainel" value="S" onclick="HabilitarDesabilitar3D()"/>
                        @endif

                        <label for="alterarPainel">Cena*</label><br>
                        <select class="form-control" id="selectScene" name="scene" aria-label="" @if($acao === 'edit') disabled @endif
                            @if ($acao === 'edit' && $scene_id != "modelo3D") value = {{ $scene_id }} @endif>
                            @foreach ($scenes as $scene)
                                <option value="{{$scene->id}}" @if ($acao == "edit" && $scene->id == $scene_id) selected @endif>{{$scene->name}}</option>
                            @endforeach             
                        </select>
                </div>

                <!----------------MARCADOR----------------->
                <div class="form-group">
                        @if ($acao == 'edit') 
                            <input type="checkbox" id="alterarMarcador" name="alterarMarcador" value="S" onclick="HabilitarDesabilitarImagemMarcador()"/>
                        @endif
                        <label for="alterarMarcador">Marcador (PNG ou JPEG ou JPG)*</label>
                        <input type="file" @if($acao === 'insert') required @endif style="border:none" class="form-control" name="marcador"
                            id="marcador" accept=".png, .jpeg, .jpg"  @if($acao === 'edit') disabled @endif/>
                </div>



               
                <input id="panelId" name="panelId" type="hidden" @if($acao === 'edit') value="{{$scene_id}}" @endif>

                <!-----------------SUBMIT------------------>
                <div class="form-group mt-4" onsubmit="desativarBotao(this)">
                    <button type="submit" id="btnSalvar" class="btn btn-success">
                       Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById("selectSceneType").onchange = ()=>{
            let valor = document.getElementById("selectSceneType").value;
            if (valor == "Modelo3D") {
                document.getElementById("3DmodelOption").style.display = "block"
                document.getElementById("panelOption").style.display = "none"
            }else{
                document.getElementById("panelOption").style.display = "block"
                document.getElementById("3DmodelOption").style.display = "none"
            }

            HabilitarDesabilitar3D();
        }
        
        function desativarBotao(form) {
            let botao = form.querySelector("#btnSalvar");
            botao.disabled = true; 
        }
    </script>
@endsection
 