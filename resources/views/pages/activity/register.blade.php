@extends('layouts.app')
@section('script')
@endsection
@section('page-name', $titulo)

@php
    $orderedContentString = __('Activities in an ordered content are redone by default.');
    $noOrderedContentString = __('If this option is checked, students will be able to redo the question if they did not get it completely right.');
    $sceneTypeString = __('Option unavailable for scenes.');
@endphp

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

                
                <h3>{{ __('Activity') }}</h3>
                @csrf
                <input name="id" type="hidden" value="{{$id}}"/>
                <input name="acao" type="hidden" value="{{$acao}}"/>
                <input name="mural_id" type="hidden" value="{{ $mural_id ?? ''}}" id="mural_id"/>

                <!------------NOME DA ATIVIDADE-------------->
                <div class="form-group">
                    <label for="">{{ __('Name') }}*</label>
                    <input id="name" type="text" maxlength="100"class="form-control @error('name') is-invalid @enderror" name="name"
                            value="{{ old('name', $name) }}" required autofocus/>
                </div>

                <!-----------SELECIONAR CONTEÚDO------------->
                <div class="form-group">
                    <label for="">{{ __('Content') }}*</label>
                    <select class="form-control" name="content_id" aria-label="">
                        @foreach ($contents as $item)
                            <option value="{{ $item->id }}" data-check="{{ $item->is_jogo }}" 
                            @if ($item->id === ($content ?? $content->first()->id)) selected="selected" @endif>{{ $item->total_name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-------SELECIONAR TIPO DE ATIVIDADE-------->
                

                

                <div class="form-group">
                    @if (session('type') == 'teacher')
                    <label for="selectActivityType">{{ __('Select the type of activity') }}*</label>
                    <select class="form-control" id="selectActivityType" name="activityType" aria-label="">             
                        <option value="Modelo3D" @if($mural_id === 'modelo3D') selected @endif>{{ __('3D Model') }}</option>
                        <option value="Cena" @if($mural_id != 'modelo3D') selected @endif>{{ __('Scene') }}</option>
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
                        
                        <label for="alterar3D">{{ __('3D Model') }} (GLB ou GLTF->ZIP)*</label>
                        <span class="alert-danger">{{ __('Max size: :size', ['size' => '40MB']) }}</span>
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
                            <label for="alterarMarcador">{{ __('Marker') }} (PNG, JPEG ou JPG)*</label>
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
                                <h3>{{ __('Marker guidelnes') }}</h3>
                                <p>{{ __('For better detection by the system, it is recommended to use images/photos that contain details, and that do not have symmetrical elements, as these factors make it difficult to correctly identify the markers. If it is necessary to use markers that contain geometric shapes, for example, it is recommended to capture more details of the book page, as in the example below:') }}</p> 
                                <img src="/images/marcador_editado.png" alt="" style="max-width: 100%">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>

                    <!-----------------PISTA------------------>
                    <div class="extras collapse" id="hint">
                        <div class="hint mt-4">
                            <label for="hintLabel" >{{ __('Customized Hint') }}</label>
                            <input type="text" name="pista_customizada" id="pista_customizada" class="form-control" placeholder="{{ __('Leave it empty to not add a custom hint') }}" value=@if(!empty($hint)) "{{ $hint }}" @endif>
                        </div>
                    </div>
                    
                    <!-----------------SUBMIT------------------>
                    <div class="form-group mt-4" onsubmit="desativarBotao(this)">
                        <button type="submit" id="btnSalvar" class="btn btn-success">
                            {{ __('Save') }}
                        </button>
                    </div>
                </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const el = {
                selectActivity: document.getElementById("selectActivityType"),
                selectContent: document.querySelector('select[name="content_id"]'),
                model3D: document.getElementById("3DmodelOption"),
                panelOption: document.getElementById("panelOption"),
                hint: document.getElementById('hint')
            };

            const getContentType = () => {
              return el.selectContent.options[el.selectContent.selectedIndex]?.dataset.check;
            }

            const atualizarInterface = () => {
                const isModelo3D = el.selectActivity.value === "Modelo3D";
                const contentType = getContentType();

                if (isModelo3D) {
                    el.model3D.style.display = "block";
                    el.panelOption.style.display = "none";

                    if (contentType > 0)
                        $(el.hint).collapse('show');
                    else 
                        $(el.hint).collapse('hide');
                    
                } else {
                    el.model3D.style.display = "none";
                    el.panelOption.style.display = "block";

                    $(el.hint).collapse('hide');
                }
            };

            el.selectActivity.addEventListener('change', atualizarInterface);
            el.selectContent.addEventListener('change', atualizarInterface);

            atualizarInterface();
        });
        
        function desativarBotao(form) {
            const botao = form.querySelector("#btnSalvar");
            botao.disabled = true;
        }
        </script>
@endsection