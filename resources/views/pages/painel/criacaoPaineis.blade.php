@extends('layouts.app')

@section('page-name', $titulo)

@section('script-head')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/telainicial.css?v=' . filemtime(public_path('css/telainicial.css'))) }}" rel="stylesheet">
    <link href="{{ asset('css/painel.css?v=' . filemtime(public_path('css/painel.css'))) }}" rel="stylesheet">
    <link rel="stylesheet"
        href="{{ asset('editor/dist/plugins/colors/ui/trumbowyg.colors.min.css?v=' . filemtime(public_path('editor/dist/plugins/colors/ui/trumbowyg.colors.min.css'))) }}">
    <link rel="stylesheet"
        href="{{ asset('editor/dist/ui/trumbowyg.min.css?v=' . filemtime(public_path('editor/dist/ui/trumbowyg.min.css'))) }}">
    <style>
        .trumbowyg-editor[contenteditable=true]:empty::before {
            content: attr(placeholder);
            color: #999;
        }

        #opaque-background {
            background-color: #D7D7D7;
            opacity: 0.7;
            position: absolute;
            z-index: 12;
            width: 100%;
            height: 100%;
        }

        #flex-container {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            display: none;
            justify-content: center;
            align-items: center;
        }

        #popup {
            background-color: white;
            height: 600px;
            width: 600px;
            z-index: 13;
            border-radius: 19px;
            padding: 40px 40px;
        }

        #midiaInput {
            display: none;
        }

        .picture {
            width: 100%;
            aspect-ratio: 16/9;
            border-radius: 19px;
            background: #F0EBF1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #aaa;
            border: 2px dashed #833B8D;
            cursor: pointer;
            font-family: sans-serif;
            transition: color 300ms ease-in-out, background 300ms ease-in-out;
            outline: none;
            overflow: hidden;
            margin-top: 40px;
        }

        .picture:hover {
            color: #777;
            background: #E7DFE9;
        }

        .picture:active {
            background: #DFC1E4;
        }

        .picture:focus {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .picture__img {
            max-width: 100%;
        }

        .pInfo {
            color: #B8B5B5;
            font-size: 13px;
            margin-bottom: 30px;
            float: left;
        }

        #popup {
            box-shadow: -10px 12px 17px 0px rgba(0, 0, 0, 0.185);
        }

        #popup button {
            font-size: 15px;
            font-weight: 600;
            float: right;
            margin-top: 5px;
            background: none;
            border: none
        }

        #popup button:focus {
            outline: none;
            box-shadow: none;
        }

        #popup p:nth-child(1) {
            font-size: 22px;
            font-weight: bold;
            float: left;
        }

        #pYoutube {
            font-size: 17px;
            color: #827E7E;
            font-weight: bold;
            margin-bottom: 5px;
        }

        #popup input[type="text"] {
            width: 100%;
            background-color: #EFEFEF;
            border: none;
            padding: 10px;
            border-radius: 8px;
        }

        #videoContainer {
            width: 100%;
            height: 33.5vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #videoContainer iframe {
            width: 450px;
            height: 280px;
        }
    </style>
@endsection

@section('bodyAccess')
    <!--Pop up upload de arquivo-->
    <!--Explicação: Ele teve que ficar dentro do body, ao colocar o elemento dentro da section content, ele fica dentro
                        de um "Main wrapper" que possui um tamanho menor que o tamanho inteiro da tela-->
    <div id="flex-container">
        <div id="opaque-background"></div>

        <div id="popup">
            <p>Upload file</p>
            <button onclick="fecharPopUp()">X</button>

            <label id="upload-area" class="picture" for"midiaInput" tabIndex="0">
                <img src="{{ asset('icons/paineis/upload.svg') }}" alt="">
                <span class="picture__image"></span>
            </label> 

            <p class="pInfo">Formatos suportados: MP4, JPG, JPEG, PNG</p>
            <p class="pInfo" style="float: right">Tamanho máximo: 50MB</p>
            <div style="clear: both;"></div>

            <p id="pYoutube">URL YouTube</p>
            <input id="linkYoutube" type="text" @if ($action == 'edit' && $midiaExtension=="") value="https://www.youtube.com/watch?v={{ $link }} @endif">
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <form action="{{route('paineis.store')}}" method="POST" enctype="multipart/form-data">
            @if ($action == 'edit')
                <input name="id" type="hidden" value={{$id}}>
            @endif
            <input name="action" type="hidden" value={{$action}} id="actionInput">
            @csrf
            <div class="row">
                <div class="col-md-7 coluna linha">
                    <div class="painel">
                        <!--               ________TEXTO SUPERIOR________               -->
                        <div id="txtSuperior" class="txtPainel">
                            @if ($action == 'edit'){!!$txtSuperior!!} @endif
                        </div>
                        <input type="hidden" class="inputTxtPainel" name="txtSuperior"
                            value="@if ($action == 'edit') {{$txtSuperior}} @endif">

                        <!--               ________    MIDIAS    ________               -->
                        <div class="midia">
                            <!-- Circulo que serve como botão para inserir midias pela primeira vez -->
                            <div id="midia" tabindex=0 @if ($action == 'edit') style="display: none;" @endif>
                                <img class="fileMidia" src="{{ asset('images/FileMidia.svg') }}">
                            </div>

                            <!-- Midia (imagem/video) atualmente enviado -->
                            <div id="midiaPreview" edit=@if($action == 'edit')"true" @else "false" style="display: none;" @endif>
                                <!-- Video do youtube recebido do usuário (se recebido) -->
                                <div id="videoContainer"  @if ($midiaExtension != "") style="display: none" @endif>
                                    <iframe 
                                        id="srcYoutube"
                                        @if($action == 'edit')src="https://www.youtube.com/embed/{{ $link }}?autoplay=1"@endif
                                        title="YouTube video player {{ $midiaExtension }}" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                    </iframe>
                                </div>

                                <input type="hidden" id="linkForm" name="link"
                                value="@if ($action == 'edit') {{$link}} @endif">
                                
                                <!-- Video recebido do usuário (se recebido) -->
                                <video id="vidMidia" controls @if ($midiaExtension != "mp4") style="display: none" @endif>
                                    <source id="srcVidMidia"
                                        src="@if ($action == 'edit'){{asset('midiasPainel/' . $arquivoMidia)}}@endif"
                                        type="video/mp4">
                                </video>
                                <!-- Imagem recebida do usuário (se recebida) -->
                                <img src="@if ($action == 'edit'){{asset('midiasPainel/' . $arquivoMidia)}}@endif"
                                    id="imgMidia" @if($midiaExtension == "mp4" || $midiaExtension == "") style="display: none" @endif>
                                
                                <!-- Coisas da edição -->
                                @if ($action == 'edit')
                                    <input type="hidden" name="midiaExtensionEdit" value="{{$midiaExtension}}">
                                    <input type="hidden" name="arquivoMidiaEdit" value="{{$arquivoMidia}}">
                                @endif
                            </div>
                        </div>

                        <!--               ________TEXTO INFERIOR________               -->
                        <div id="txtInferior" class="txtPainel" name="txtInferior">
                            @if ($action == 'edit') {{$txtInferior}} @endif
                        </div>
                        <input type="hidden" class="inputTxtPainel" name="txtInferior"
                            value="@if ($action == 'edit') {{$txtInferior}} @endif">

                        <!--               ________    BOTÕES    ________               -->
                        <div id="areaBtns">
                            <button type="button" id="add">+</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 coluna">
                    <!--Input onde o arquivo de midia é enviado, se encontra aqui pois precisa estar dentro do formulário.-->
                    <input type="file" style="border:none" name="arquivoMidia" id="midiaInput"
                        accept=".png, .jpeg, .jpg, .mp4" onchange="upload_check()" />

                    <!--               ________CONFIGURAÇÕES DO PAINEL________               -->
                    <div id="configPainel">
                        <p id="tituloSelecionado">Configurações de Texto</p> <!--Titulo da configuração selecionada-->

                        <!--Blocos de configuração
                            Explicação: Existem diferentes tipos de configs, de texto e de botão atualmente. Quando se clica no elemento
                            a ser editado, o bloco referente as configurações do elemento recebe seu display setado como block.
                        -->
                        <div id="blocoTxt">
                            <div id="trumbowyg-demo" placeholder="Insira seu texto aqui"></div>
                        </div>

                        <div id="blocoBtn">

                        </div>
                        <input type="submit" value="Salvar" id="salvarPainel" class="submitPanel">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-3.3.1.min.js"><\/script>')</script>
    <script
        src="{{ asset('editor/dist/trumbowyg.min.js?v=' . filemtime(public_path('editor/dist/trumbowyg.min.js'))) }}"></script>
    <script
        src="{{ asset('editor/dist/plugins/fontfamily/trumbowyg.fontfamily.min.js?v=' . filemtime(public_path('editor/dist/plugins/fontfamily/trumbowyg.fontfamily.min.js'))) }}"></script>
    <script
        src="{{ asset('editor/dist/plugins/colors/trumbowyg.colors.min.js?v=' . filemtime(public_path('editor/dist/plugins/colors/trumbowyg.colors.min.js'))) }}"></script>
    <script src="{{asset('js/painel.js?v=' . filemtime(public_path('js/painel.js')))}}" type="module"></script>
    <script>
        //---------------------------------------------------------------------------------------------------------------------
        //  1. EDITOR DE TEXTO
        // Criar editor
        $('#trumbowyg-demo').trumbowyg({
            btns: [
                ['undo', 'redo'], // Only supported in Blink browsers
                ['strong', 'em'],
                ['fontfamily', 'formatting', 'foreColor'],
                ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull']
            ],
            autogrow: false
        });

        // Fazer alterações na estrutura do painel de configuração
        $(document).ready(function () {
            //Adiciona novos elementos no HTML
            let editorBox = document.getElementsByClassName("trumbowyg-editor-box")[0];
            editorBox.innerHTML = "<p class=\"pEditorBox\">Texto superior do painel</p>" + editorBox.innerHTML
            editorBox.outerHTML += editorBox.outerHTML;

            //Coloca o focus em alguns elementos.
            let editor1 = document.getElementsByClassName("trumbowyg-editor")[0]
            let editor2 = document.getElementsByClassName("trumbowyg-editor")[1]
            let label1 = document.getElementsByClassName("pEditorBox")[0]
            let label2 = document.getElementsByClassName("pEditorBox")[1]

            editor1.addEventListener("focus", () => {
                label1.style.color = "#833B8D";
            })
            editor2.addEventListener("focus", () => {
                label2.style.color = "#833B8D";
            })
            editor1.addEventListener("blur", () => {
                label1.style.color = "#CCCCCC";
            })
            editor2.addEventListener("blur", () => {
                label2.style.color = "#CCCCCC";
            })

            //Transfere os dados do editor para o painel
            editor1.addEventListener("input", () => {
                document.getElementById("txtSuperior").innerHTML = editor1.innerHTML;
                document.getElementsByClassName("inputTxtPainel")[0].value = editor1.innerHTML;
            })
            editor2.addEventListener("input", () => {
                document.getElementById("txtInferior").innerHTML = editor2.innerHTML;
                document.getElementsByClassName("inputTxtPainel")[1].value = editor2.innerHTML;
            })

            //Caso for edição, adiciona os valores ao editor de texto.
            @if ($action == 'edit')
                editor1.innerHTML = '{!! addslashes($txtSuperior) !!}';
            @endif
        });
        //---------------------------------------------------------------------------------------------------------------------
        //  2. POP UP DE ADICIONAR MÍDIA
        function fecharPopUp() {
            document.getElementById("flex-container").style.display = 'none';
        }

        function abrirPopUp() {
            document.getElementById("flex-container").style.display = 'flex';
        }
        
        //---------------------------------------------------------------------------------------------------------------------
        //  3. VERIFICAR SE O ARQUIVO EXCEDE O TAMANHO MÁXIMO
        document.getElementById("midiaInput").addEventListener("change", () => {
            fecharPopUp()
            var upl = document.getElementById("midiaInput");
            var max = 160 * 1024 * 1024; // 50MB

            var alert = document.getElementById("alertaGLB");
            if (alert !== null) {
                alert.remove();
            }

            if (upl.files[0].size > max) {
                const div = document.createElement("div");

                upl.parentNode.insertBefore(div, upl.nextSibling);

                div.id = "alertaGLB"
                div.className = "alert alert-danger";

                const ul = document.createElement("ul");
                div.appendChild(ul);

                const li = document.createElement("li");
                li.innerHTML = "Tamanho máximo excedido (~" + Math.round(upl.files[0].size / 1024 / 1024) + "MB > 160MB)";
                ul.appendChild(li);

                upl.value = "";
            }
        });
    </script>
@endsection