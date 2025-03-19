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
                <h3 class="mb-2">SELECIONAR PAINEL</h3>
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
    <div class="container">
        <div class="canvas-container">
            <!-- <div class="AddPainel">
                    <button id="addPanel">Adicionar painel</button>
                </div> -->

            <div class="menu-zoom">
                <button id="zoom-in">+</button>
                <button id="zoom-out">-</button>
            </div>
            <div id="canvas" class="canvas">
                <img src="{{ asset('images/inicioConexoes.svg') }}" alt="">
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/panelConnection.js?v=' . filemtime(public_path('js/panelConnection.js'))) }}"></script>
    <script>
        //CANVAS INFINITO
        let scale = 1;
        const canvas = document.getElementById('canvas');

        document.getElementById('zoom-in').addEventListener('click', () => {
            scale += 0.1; // Aumenta o zoom
            updateCanvasScale();
        });

        document.getElementById('zoom-out').addEventListener('click', () => {
            scale = Math.max(scale - 0.1, 0.1); // Diminui o zoom, mas não permite que fique menor que 0.1
            updateCanvasScale();
        });

        function updateCanvasScale() {
            canvas.style.transform = `scale(${scale})`;
        }

        const pickr = Pickr.create({
            el: "#color-picker-container",
            theme: "nano", // Opções: classic, nano, monolith
            default: "#3498db",
            inline: true,
            showAlways: true,
            useAsButton: false,
            components: {
                preview: true,
                opacity: true,
                hue: true,
                interaction: {
                    input: true,
                    hex: false,
                    rgba: false,
                    save: true,
                    clear: true
                }
            }
        });

        // pickr.on("save", (color) => {
        //     console.log("Cor selecionada:", color.toHEXA().toString());
        // });

        pickr.on("change", (color) => {
            console.log("Cor selecionada:", color.toHEXA().toString());
        });
    </script>
@endsection