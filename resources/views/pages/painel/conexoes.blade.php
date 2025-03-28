@extends('layouts.app')

@section('page-name', "Conexões do Painel")

@section('script-head')
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Feather Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.css">
    <!-- Heroicons -->
    <link href="https://cdn.jsdelivr.net/npm/heroicons@1.0.6/outline/heroicons.min.css" rel="stylesheet">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet"
        href="{{ asset('css/panelConnection.css?v=' . filemtime(public_path('css/panelConnection.css'))) }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/nano.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr"></script>
@endsection

@section('content')
    <div class="AddPainel">
        <button id="addPanel">Add painel</button>
    </div>
    <!-- MENU LATERAL -->
    <div class="menu-lateral">
        <div>
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

            <!-- CORES -->
            <div class="mb-6">
                <h3 class="mb-2">
                    CORES DOS BOTÕES
                </h3>
                <div class="mb-2">
                    <div id="color-picker-container"></div>
                </div>

                <!-- <div class="mb-2">
                                <input type="text" value="#424459" />
                            </div> -->
                <!-- <div class="mb-2">
                                <button class="">
                                    Save
                                </button>
                                <button class="">
                                    Clear
                                </button>
                            </div> -->
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

            <!-- BOTÕES DE SALVAR/EXCLUIR -->
            <div class="buttons">
                <button>
                    Excluir
                </button>
                <button>
                    Salvar
                </button>
            </div>
        </div>
    </div>

    <!-- CANVAS SPACE -->
    <div class="container-paineis">
        <div class="canvas-container">
            <!-- <div class="AddPainel">
                <button id="addPanel">Adicionar painel</button>
            </div> -->
            <div class="menu-zoom">
                <button id="zoom-out">-</button>
                <button id="zoom-in">+</button>
            </div>
            <div id="canvas" class="canvas">
                <div id="testeCss"></div>

                @foreach ($paineis as $painel)
                <div class="painel">                        
                    <!--Texto do painel-->
                    <div class="txtPainel"></div>
                    <input type="hidden" class="inputTxtPainel" name="txt" value="">
                    <!--Midia do painel-->
                    <div class="midia">
                        <!--1. Não informado-->
                        <div class="no_midia" tabindex=0>
                            <img class="fileMidia" src="{{ asset('images/FileMidia.svg') }}">
                        </div>
                        <!--2. Imagem-->
                        <img src="" style="display: none">
                        <!--3. Vídeo-->
                        <video id="vidMidia" controls style="display: none;">
                            <source id="srcVidMidia" src="" type="video/mp4">
                        </video>
                        <!--4. Youtube-->
                        <div id="videoContainer" style="display: none">
                            <iframe 
                                id="srcYoutube"
                                src="https://www.youtube.com/embed/nvZRDKDfguM?autoplay=0"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                    <!--Botões do painel-->
                    <div id="areaBtns" class="btn-linhas" style="font-size: 12px;">
                        <div class="teste"><div class="circulo"></div> Botão 1</div>
                        <div class="teste"><div class="circulo"></div> Botão 2</div>
                        <div class="teste"><div class="circulo"></div> Botão 3</div>
                    </div>
                    <!--Informações do painel-->
                    <input type="hidden" name="midiaExtension" value="">
                    <input type="hidden" name="arquivoMidia" value="">
                </div>
                @endforeach
                <img src= "{{ asset('images/inicioConexoes.svg') }}" alt="">
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/panelConnection.js?v=' . filemtime(public_path('js/panelConnection.js'))) }}"></script>

    <script>
        //----PANEL LOADING---------------------------------------------------------------------
        $(document).ready(function () {
            let panelsLoaded = document.getElementsByClassName("painel");

            Array.from(panelsLoaded).forEach(panel => {
                panel.setAttribute("draggable", "true");
                panel.addEventListener("dragstart", (e) =>
                    arrastar(e, new Painel(panel))
                );
                panel.addEventListener("click", () => selecionarPainel(panel));
            });
        });
    </script>
@endsection