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

        .custom-switch .custom-control-label::before {
            border-width: 1.2px;
        }

        .custom-switch.switch .custom-control-label::after {
            border-width: 1.2px;
            top: 25%!important;
        }

        .custom-control {
            line-height: 1.5em!important;
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
            
            let campoAtividade = document.querySelector('[name="activityType"]');
            let tipoAtividade = campoAtividade ? campoAtividade.value : null;

            let alt1 = document.getElementById("alterar3D");
            let alt2 = document.getElementById("alterarPainel")

            let alt1checked = (alt1 == null) || (alt1.checked);
            let alt2checked = (alt2 == null) || (alt2.checked);

            if (tipoAtividade === "Modelo3D") {
                document.getElementById("glb").disabled = !alt1checked;
                document.getElementById("glb").required = alt1checked;
                document.getElementById("selectMural").required = false;
            } else {
                document.getElementById("selectMural").disabled = !alt2checked;
                document.getElementById("selectMural").required = alt2checked;
                document.getElementById("glb").required = false;
            }

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
            <form method="POST" action="{{ route('activity.store') }}" enctype="multipart/form-data" files="true" onsubmit="desativarBotao(this)" autocomplete="off">

           


                <h3>Atividade</h3>
                @csrf
                <input name="id" type="hidden" value="{{$id}}"/>
                <input name="acao" type="hidden" value="{{$acao}}"/>
                <input name="mural_id" type="hidden" value="{{ $mural_id ?? ''}}" id="mural_id"/>

                <!------------NOME DA ATIVIDADE-------------->
                <div class="form-group">
                    <label for="">Nome da Atividade*</label>
                    <input id="name" type="text" maxlength="100"class="form-control @error('name') is-invalid @enderror" name="name"
                            value="{{ old('name', $name) }}" required autofocus/>
                </div>

                <!-----------SELECIONAR CONTEÚDO------------->
                <div class="form-group">
                    <label for="">Conteúdo*</label>
                    <select class="form-control" name="content_id" aria-label="">
                        @foreach ($contents as $item)
                            <option value="{{ $item->id }}" data-check="{{ $item->sort }}" 
                            @if ($item->id === ($content ?? $content->first()->id)) selected="selected" @endif>{{ $item->total_name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-------SELECIONAR TIPO DE ATIVIDADE-------->
                
                
                

                <div class="form-group">
                    @if (session('type') == 'teacher')
                    <label for="selectActivityType">Selecione o tipo de atividade*</label>
                    <select class="form-control" id="selectActivityType" name="activityType" aria-label="">             
                        <option value="Modelo3D" @if($mural_id === 'modelo3D') selected @endif>Modelo 3D</option>
                        <option value="Cena" @if($mural_id != 'modelo3D') selected @endif>Cena</option>
                    </select>
                </div>
                @endif

                @if (session('type') == 'developer')
                <input type="hidden" id="selectActivityType" name="activityType" value="Modelo3D"/>
                @endif

                <!-------------ENVIAR MODELO3D--------------->
                <div class="form-group" id="3DmodelOption"  @if($mural_id != 'modelo3D')  style="display: none" @endif >
                        @if ($acao == 'edit'  && $mural_id === 'modelo3D' ) 
                            <input type="checkbox" id="alterar3D" name="alterar3D" value="S" onclick="HabilitarDesabilitar3D()"/>
                        @endif
                        
                        <label for="alterar3D">Modelo 3D (GLB ou GLTF->ZIP)*</label>
                        <span class="alert-danger">Tamanho máximo: 40MB</span>
                        <input type="file" @if($acao === 'insert') required @endif style="border:none" class="form-control" name="glb"
                            id="glb" accept=".glb, .zip" onchange="upload_check()" @if($acao === 'edit' && $mural_id == "modelo3D") disabled  @endif/>
                </div>

                <!------------SELECIONAR MURAL--------------->
                <div class="form-group" id="panelOption"  @if($mural_id === 'modelo3D')  style="display: none" @endif>
                        @if ($acao == 'edit' && $mural_id != 'modelo3D' ) 
                            <input type="checkbox" id="alterarPainel" name="alterarPainel" value="S" onclick="HabilitarDesabilitar3D()"/>
                        @endif

                        <label for="alterarPainel">Mural*</label><br>
                        <select class="form-control" id="selectMural" name="mural" aria-label="" @if($acao === 'edit') disabled @endif
                            @if ($acao === 'edit' && $mural_id != "modelo3D") value = {{ $mural_id }} @endif>
                            @foreach ($murais as $mural)
                                <option value="{{$mural->id}}" @if ($acao == "edit" && $mural->id == $mural_id) selected @endif>{{$mural->name}}</option>
                            @endforeach             
                        </select>
                </div>

                <!----------------MARCADOR----------------->
                <div class="form-group">
                        <div class="grupo-marcador">
                            @if ($acao == 'edit') 
                                <input type="checkbox" id="alterarMarcador" name="alterarMarcador" value="S" onclick="HabilitarDesabilitarImagemMarcador()"/>
                            @endif
                            <label for="alterarMarcador">Marcador (PNG ou JPEG ou JPG)*</label>
                            <button type="button" id="btnHelpMarcador" data-toggle="modal" data-target="#confirmModal" class="btn btn-link p-0 m-0 align-baseline">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-lg" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4.475 5.458c-.284 0-.514-.237-.47-.517C4.28 3.24 5.576 2 7.825 2c2.25 0 3.767 1.36 3.767 3.215 0 1.344-.665 2.288-1.79 2.973-1.1.659-1.414 1.118-1.414 2.01v.03a.5.5 0 0 1-.5.5h-.77a.5.5 0 0 1-.5-.495l-.003-.2c-.043-1.221.477-2.001 1.645-2.712 1.03-.632 1.397-1.135 1.397-2.028 0-.979-.758-1.698-1.926-1.698-1.009 0-1.71.529-1.938 1.402-.066.254-.278.461-.54.461h-.777ZM7.496 14c.622 0 1.095-.474 1.095-1.09 0-.618-.473-1.092-1.095-1.092-.606 0-1.087.474-1.087 1.091S6.89 14 7.496 14"/>
                                </svg>
                            </button>
                            <input type="file" @if($acao === 'insert') required @endif style="border:none" class="form-control" name="marcador"
                            id="marcador" accept=".png, .jpeg, .jpg"  @if($acao === 'edit') disabled @endif/>
                        </div>
                </div>
                <input id="panelId" name="panelId" type="hidden" @if($acao === 'edit') value="{{$mural_id}}" @endif>

                <!---modal que aparece quando clica na interrogação---->
                <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content" >
                            <div class="modal-body">
                                <h3>Orientações sobre os marcadores</h3>
                                <p>Para uma melhor detecção por parte do sistema, recomenda-se o uso de imagens/fotos que contenham detalhes, e que não possuam elementos simétricos, pois esses fatores dificultam a identificação correta dos marcadores. Caso seja necessário utilizar marcadores que contenham formas geométricas, por exemplo, recomenda-se capturar mais detalhes da página do livro, como no exemplo abaixo:</p> 
                                <img src="/images/marcador_editado.png" alt="" style="max-width: 100%">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
                

                <!----------------REFEITA----------------->
                @if($acao != 'edit')
                <div class="mb-4">
                    <div class="custom-control custom-switch switch">
                        <input type="hidden" name="refeitaMarcador" value="0">
                        <input type="checkbox" class="custom-control-input" id="refeitaMarcador" name="refeitaMarcador" value="1">
                        <label class="custom-control-label" for="refeitaMarcador">Refeita</label>
                        <div class="form-text alert-danger d-inline-block small ml-1 p-0" id="refeitaAlerta" role="alert"><!-- Texto controlado pelo JS --></div>
                    </div>
                </div>
                @endif

                <!----------------PONTUADA----------------->
                @if($acao != 'edit')
                    <div class="custom-control custom-switch switch pontuada mb-2 mt-3">
                        <input type="hidden" name="pontuadaMarcador" value="0">
                        <input type="checkbox" class="custom-control-input" id="switchPontuada" name="pontuadaMarcador" value="1">
                        <label class="custom-control-label" for="switchPontuada">Pontuada</label>
                        <div class="form-text alert-danger d-inline-block small ml-1 p-0" id="pontuadaAlerta" role="alert"><!-- Texto controlado pelo JS --></div>
                    </div>
                
                    <div class="extras collapse" id="extras">
                        <div class="nota">
                            <label for="nota">Nota da Atividade</label>
                            <div class="form-text alert-danger d-inline-block small ml-1 p-0" role="alert">
                                A nota máxima de uma atividade.
                            </div>
                            <input type="number" class="form-control mb-2" name="nota" id="nota" value=100>
                        </div>
                        <div class="tempo">
                            <label for="tempo">Tempo Limite</label>
                            <div class="form-text alert-danger d-inline-block small ml-1 p-0" role="alert">
                                Tempo limite para realizar uma atividade completa.
                            </div>
                            <input type="number" name="tempo" id="tempo" class="form-control" value=30>
                        </div>
                    </div>
                @endif
                    
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
        const el = {
            selectActivity: document.getElementById("selectActivityType"),
            selectContent: document.querySelector('select[name="content_id"]'),
            model3D: document.getElementById("3DmodelOption"),
            panelOption: document.getElementById("panelOption"),
            switchRefeita: document.getElementById('refeitaMarcador'),
            switchPontuada: document.getElementById('switchPontuada'),
            camposExtras: document.getElementById('extras'),
            refAle: document.getElementById('refeitaAlerta'),
            ponAle: document.getElementById('pontuadaAlerta'),
            nota: document.getElementById('nota'),
            tempo: document.getElementById('tempo')
        };

        const getContentType = () => {
          return el.selectContent.options[el.selectContent.selectedIndex]?.dataset.check;
        }

        const atualizarInterface = () => {
            const isModelo3D = el.selectActivity.value === "Modelo3D";
            const contentType = getContentType();

            el.model3D.style.display = isModelo3D ? "block" : "none";
            el.panelOption.style.display = isModelo3D ? "none" : "block";

            if (isModelo3D) {
                el.switchPontuada.disabled = false;
                el.ponAle.textContent = "";

                if (contentType == "1") {
                    el.switchRefeita.disabled = true;
                    el.switchRefeita.checked = false;
                    el.refAle.textContent = "Atividades de um conteúdo ordenado são refeitas por padrão.";
                    
                } else {
                    el.switchRefeita.disabled = false;
                    el.refAle.textContent = "Se esta opção estiver marcada os alunos poderão refazer a questão caso não a tenham acertada completamente.";
                }
            } else {
                el.switchRefeita.checked = false;
                el.switchRefeita.disabled = true;
                el.switchPontuada.checked = false;
                el.switchPontuada.disabled = true;
                el.refAle.textContent = "Opção indisponível para cenas.";
                el.ponAle.textContent = "Opção indisponível para cenas.";
                $(el.camposExtras).collapse('hide');
            }
        };

        el.selectActivity.addEventListener('change', atualizarInterface);
        el.selectContent.addEventListener('change', atualizarInterface);

        el.switchPontuada.addEventListener('change', function() {
            const isChecked = this.checked;
            $(el.camposExtras).collapse(isChecked ? 'show' : 'hide');
            el.nota.required = isChecked;
            el.tempo.required = isChecked;

            if (isChecked) {
                el.switchRefeita.disabled = true;
                el.switchRefeita.parentElement.style.display = 'block';
            } else if (getContentType() != "1") {
                el.switchRefeita.disabled = false;
                el.switchRefeita.parentElement.style.display = '';
            }
        });

        el.switchRefeita.addEventListener('change', function() {
            el.switchPontuada.disabled = this.checked;
            el.switchPontuada.parentElement.style.display = this.checked ? 'block' : '';
        });

        function desativarBotao(form) {
            const botao = form.querySelector("#btnSalvar");
            botao.disabled = true;
        }

        document.addEventListener('DOMContentLoaded', () => {
            el.switchPontuada.checked = false;
            atualizarInterface();
        });
    </script>
@endsection