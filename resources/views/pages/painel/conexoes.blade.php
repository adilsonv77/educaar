@extends('layouts.app')

@section('page-name', "Conexões do Painel")

@section('script-head')
    <!-- CSS PAINEL CONEXÕES -->

    <link rel="stylesheet" href="{{ asset('css/panelConnection.css?v=' . filemtime(public_path('css/panelConnection.css'))) }}">
    <!-- SELETOR DE CORES -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/nano.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr"></script>

    <!-- EDITOR DE TEXTO -->
    <link rel="stylesheet" href="{{ asset('editor/dist/plugins/colors/ui/trumbowyg.colors.min.css?v=' . filemtime(public_path('editor/dist/plugins/colors/ui/trumbowyg.colors.min.css'))) }}">
    <link rel="stylesheet" href="{{ asset('editor/dist/ui/trumbowyg.min.css?v=' . filemtime(public_path('editor/dist/ui/trumbowyg.min.css'))) }}">
    
    <!-- STYLE ESPECIFICO DO EDITOR DE TEXTO -->
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

@section('content')
    <div class="AddPainel">
        <button id="addPanel">Add painel</button>
    </div>
    <!-- MENU LATERAL -->
    <div class="menu-lateral">
        <div>
            <!-- Quando um painel está selecionado -->
            <div class="menu-opcoes painel-opcoes">
                <!-- FORMATOS -->
                <div class="mb-6">
                    <h3>
                        FORMATO DOS BOTÕES
                    </h3>
                    <div class="tipos">
                        <div class="linhas">
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                        <div class="blocos">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                        <div class="alternativas">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                    </div>
                </div>
                <!-- BLOCO DE TEXTO -->
                <div id="blocoTxt">
                    <div id="trumbowyg-demo" placeholder="Insira seu texto aqui"></div>
                </div>
                <!-- BOTÕES DE SALVAR/EXCLUIR -->
                <div class="buttons">
                    <button>
                        Editar Mídia
                    </button>
                    <button>
                        Excluir
                    </button>
                </div>
            </div>

            <!-- Quando o botão está selecionado -->
            <div class="menu-opcoes botao-opcoes">
                <!-- CORES -->
                <div class="mb-6">
                    <h3 class="mb-2">
                        CORES DOS BOTÕES
                    </h3>
                    <div class="mb-2">
                        <div id="color-picker-container"></div>
                    </div>
                </div>
                <!-- TEXTO DO BOTÃO -->
                <div class="mb-6">
                    <h3 class="mb-2">
                        TEXTO DO BOTÃO
                    </h3>
                    <input class="" type="text" />
                </div>
                <!-- TRANSIÇÕES -->
                <div class="mb-6">
                    <h3 class="mb-2">TRANSIÇÕES</h3>
                    <div class="select select-transicoes">
                        <div class="selected" data-default="Nenhuma" data-one="Final da experiência" data-two="Próximo painel">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" class="arrow">
                                <path
                                    d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z">
                                </path>
                            </svg>
                        </div>
                        <div class="options">
                            <div>
                                <input id="nenhuma" name="option-transicoes" type="radio" checked />
                                <label class="option" for="nenhuma" data-txt="Nenhuma"></label>
                            </div>
                            <div>
                                <input id="final-experiencia" name="option-transicoes" type="radio" />
                                <label class="option" for="final-experiencia" data-txt="Final da experiência"></label>
                            </div>
                            <div>
                                <input id="proximo-painel" name="option-transicoes" type="radio" />
                                <label class="option" for="proximo-painel" data-txt="Próximo painel"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- SELECIONAR O PAINEL -->
                <div class="mb-6">
                    <h3 class="mb-2 singleTap">
                        SELECIONAR PAINEL
                        <img src="{{ asset('images/singletap.svg') }}" alt="Ícone">
                    </h3>
                    <div class="select select-painel">
                        <div class="selected" data-default="Painel (nº ID)" data-one="Painel 1" data-two="Painel 2"
                            data-three="Painel 3">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" class="arrow">
                                <path
                                    d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z">
                                </path>
                            </svg>
                        </div>
                        <div class="options">
                            <div>
                                <input id="painel-1" name="option-painel" type="radio" checked />
                                <label class="option" for="painel-1" data-txt="Painel 1"></label>
                            </div>
                            <div>
                                <input id="painel-2" name="option-painel" type="radio" />
                                <label class="option" for="painel-2" data-txt="Painel 2"></label>
                            </div>
                            <div>
                                <input id="painel-3" name="option-painel" type="radio" />
                                <label class="option" for="painel-3" data-txt="Painel 3"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quando o canvas está "selecionado" -->
            <div class="menu-opcoes canvas-opcoes">
                <!-- NOME DA CENA -->
                <div class="mb-6">
                    <h3 class="mb-2">
                        NOME DA CENA
                    </h3>
                    <input class="" type="text" />
                </div>
                <!-- SELECIONAR O PAINEL INICIAL-->
                <div class="mb-6">
                    <h3 class="mb-2 singleTap">
                        SELECIONAR PAINEL INICIAL
                        <img src="{{ asset('images/singletap.svg') }}" alt="Ícone">
                    </h3>
                    <div class="select select-painel">
                        <div class="selected" data-default="Painel (nº ID)" data-one="Painel 1" data-two="Painel 2"
                            data-three="Painel 3">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" class="arrow">
                                <path
                                    d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z">
                                </path>
                            </svg>
                        </div>
                        <div class="options">
                            <div>
                                <input id="painel-1" name="option-painel" type="radio" checked />
                                <label class="option" for="painel-1" data-txt="Painel 1"></label>
                            </div>
                            <div>
                                <input id="painel-2" name="option-painel" type="radio" />
                                <label class="option" for="painel-2" data-txt="Painel 2"></label>
                            </div>
                            <div>
                                <input id="painel-3" name="option-painel" type="radio" />
                                <label class="option" for="painel-3" data-txt="Painel 3"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- SELECIONAR DISCIPLINA -->
                <div class="mb-6">
                    <h3 class="mb-2 singleTap">
                        SELECIONAR DISCIPLINA CORRESPONDENTE
                        <img src="{{ asset('images/singletap.svg') }}" alt="Ícone">
                    </h3>
                    <div class="select select-painel">
                        <div class="selected" data-default="Painel (nº ID)" data-one="Painel 1" data-two="Painel 2"
                            data-three="Painel 3">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" class="arrow">
                                <path
                                    d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z">
                                </path>
                            </svg>
                        </div>
                        <div class="options">
                            <div>
                                <input id="painel-1" name="option-painel" type="radio" checked />
                                <label class="option" for="painel-1" data-txt="Disciplina 1"></label>
                            </div>
                            <div>
                                <input id="painel-2" name="option-painel" type="radio" />
                                <label class="option" for="painel-2" data-txt="Disciplina 2"></label>
                            </div>
                            <div>
                                <input id="painel-3" name="option-painel" type="radio" />
                                <label class="option" for="painel-3" data-txt="Disciplina 3"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CANVAS SPACE -->
    <div class="container-paineis">
        <div class="canvas-container">
            <div class="menu-zoom">
                <button id="zoom-out">-</button>
                <button id="zoom-in">+</button>
            </div>
            <div id="canvas" class="canvas">
                @foreach ($paineis as $painel)
                <div class="painel">                        
                    <!--Texto do painel-->
                    <div class="txtPainel">{!! isset($painel->panel["txt"]) ? $painel->panel["txt"] : 'Texto não disponível' !!}</div>
                    <input type="hidden" class="inputTxtPainel" name="txt" value="{!! $painel->panel["txt"] ?? '' !!}">
                    <!--Midia do painel-->
                    <div class="midia">
                        <!--1. Não informado-->
                        <div class="no_midia" tabindex=0 @if($painel->panel["midiaType"]!="none")style="display: none"@endif>
                            <img class="fileMidia" src="{{ asset('images/FileMidia.svg') }}">
                        </div>
                        <!--2. Imagem-->
                        <img src="{{ asset("midiasPainel/".$painel->panel["arquivoMidia"]) }}" @if($painel->panel["midiaType"]!="image")style="display: none"@endif>
                        <!--3. Vídeo-->
                        <video id="vidMidia" controls @if($painel->panel["midiaType"]!="video")style="display: none"@endif>
                            <source id="srcVidMidia" src="{{ asset("midiasPainel/".$painel->panel["arquivoMidia"]) }}" type="video/mp4">
                        </video>
                        <!--4. Youtube-->
                        <div class="videoContainer" @if($painel->panel["midiaType"]!="youtube")style="display: none"@endif>
                            <iframe 
                                id="srcYoutube"
                                src="https://www.youtube.com/embed/{{$painel->panel["link"]}}?autoplay=0"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                    <!--Botões do painel-->
                    <div class="areaBtns" class="btn-linhas" style="font-size: 12px;">
                        <div class="button_Panel">
                            <div class="circulo"></div> Botão 1
                        </div>
                        <div class="button_Panel">
                            <div class="circulo"></div> Botão 2
                        </div>
                        <div class="button_Panel">
                            <div class="circulo"></div> Botão 3
                        </div>
                    </div>
                    <!--Informações do painel-->
                    <input type="hidden" name="link" value="{{$painel->panel["link"]}}">
                    <input type="hidden" name="midiaExtension" value="{{ asset("midiasPainel/".$painel->panel["midiaExtension"]) }}">
                    <input type="hidden" name="arquivoMidia" value="{{ asset("midiasPainel/".$painel->panel["arquivoMidia"]) }}">
                </div>
                @endforeach
                <img src="{{ asset('images/inicioConexoes.svg') }}" alt="">
            </div>
        </div>
@endsection

@section('script')
    <script src="{{ asset('js/panelConnection.js?v=' . filemtime(public_path('js/panelConnection.js'))) }}"></script>
    <script>
        //----PANEL LOADING---------------------------------------------------------------------
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".painel").forEach(panel => {
                panel.setAttribute("draggable", "true");

                panel.addEventListener("dragstart", (e) => {
                    arrastar(e, new Painel(panel));
                });

                panel.addEventListener("click", (e) => {
                    selecionarPainel(panel, e);
                });
            });
            mostrarMenu("canvas");
        });


        //---------------------------------------------------------------------------------------------------------------------
        // EDITOR DE TEXTO
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
    </script>
@endsection