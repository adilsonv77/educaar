@extends('layouts.app')

@section('page-name', $nameScene)

@section('script-head')
    <!-- CSS PAINEL CONEXÕES -->
    <link rel="stylesheet"
        href="{{ asset('css/panelConnection.css?v=' . filemtime(public_path('css/panelConnection.css'))) }}">

    <!-- SELETOR DE CORES -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/nano.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr"></script>

    <!-- EDITOR DE TEXTO -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet"
        href="{{ asset('editor/dist/plugins/colors/ui/trumbowyg.colors.min.css?v=' . filemtime(public_path('editor/dist/plugins/colors/ui/trumbowyg.colors.min.css'))) }}">
    <link rel="stylesheet"
        href="{{ asset('editor/dist/ui/trumbowyg.min.css?v=' . filemtime(public_path('editor/dist/ui/trumbowyg.min.css'))) }}">

    <style>
        .content-body {
            padding-top: 0rem !important;
            margin-top: 7.5rem !important;
        }
    </style>
@endsection

@section('bodyAccess')
    <!--Pop up upload de arquivo-->
    <!--Explicação: Ele teve que ficar dentro do body, ao colocar o elemento dentro da section content, ele fica dentro de um "Main wrapper" que possui um tamanho menor que o tamanho inteiro da tela-->
    <div id="flex-container">
        <div id="opaque-background"></div>

        <div id="popup">
            <p>Upload file</p>
            <button onclick="fecharPopUp()">X</button>

            <label id="upload-area" class="picture" tabIndex="0">
                <img src="{{ asset('icons/paineis/upload.svg') }}" alt="">
                <span class="picture__image"></span>
            </label>

            <p class="pInfo">Formatos suportados: MP4, JPG, JPEG, PNG</p>
            <p class="pInfo" style="float: right">Tamanho máximo: 50MB</p>
            <div style="clear: both;"></div>

            <p id="pYoutube">URL YouTube</p>
            <input id="linkYoutube" type="text">
        </div>
    </div>
@endsection

@section('content')
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h3 id="msgModal"></h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>

    @livewire('scene', ['paineis' => $paineis, "scene_id" => $scene_id, "nameScene" => $nameScene])
    @livewireScripts
@endsection

@section('script')


    <script src="{{ asset('editor/dist/trumbowyg.min.js') }}"></script>
    <script src="{{ asset('editor/dist/plugins/colors/trumbowyg.colors.min.js') }}"></script>
    <script src="{{ asset('js/panelConnection.js?v=' . filemtime(public_path('js/panelConnection.js'))) }}"></script>
    <!-- LINHAS DE CONEXÕES -->
    <script src="https://cdn.jsdelivr.net/npm/leader-line@1.0.7/leader-line.min.js"></script>
    <script>
        // ---------------------------------------------TOGGLE EM SELECT PERSONALIZADO---------------------------------------------
        $(document).ready(function () {
            $('.tapSelect').click(function () {
                $(this).toggleClass('selected');
            });
        });

        // ---------------------------------------------DRAG & CLICK DOS PAINÉIS---------------------------------------------
        function onDragStart(e) {
            arrastar(e, new Painel(e.currentTarget));
        }

        function onClick(e) {
            selecionarPainel(e.currentTarget, e);
        }

        // ---------------------------------------------APLICAR LISTENERS AOS PAINÉIS EXISTENTES E NOVOS---------------------------------------------
        document.addEventListener("DOMContentLoaded", function () {
            // Painéis já existentes
            document.querySelectorAll(".painel").forEach(panel => {
                let id = panel.querySelector('.idPainel')?.id;
                if (id) {
                    atribuirListeners(panel, id);
                    habilitarArrastoPersonalizado(panel);
                }
            });

            // Painel criado via Livewire
            window.livewire.on("painelCriado", (id) => {
                let panel = document.getElementById(id);
                if (!panel) return;

                try {
                    const painelData = JSON.parse(panel.dataset.panel);
                    if (painelData.x != null && painelData.y != null) {
                        panel.style.left = painelData.x + "px";
                        panel.style.top = painelData.y + "px";
                        atualizarTodasConexoes();
                    }
                } catch (e) {
                    console.warn("Falha ao aplicar posição inicial ao novo painel:", e);
                }

                atribuirListeners(panel, id);
                habilitarArrastoPersonalizado(panel);
                mostrarMenu("painel");
            });

            mostrarMenu("canvas");
            recriarConexoes();
        });

        function atribuirListeners(panel, id) {
            let inputLink = panel.querySelector("#file-" + id);
            panel.addEventListener("click", (e) => selecionarPainel(panel, e));
            adicionarInteracaoPopup(id);
        }

        //---------------------------------------------EDITOR DE TEXTO---------------------------------------------
        function initTrumbowygEditor() {
            const $editor = $('#trumbowyg-editor');

            if (!$editor.length) {
                console.warn('❌ Editor não encontrado');
                return;
            }

            if ($editor.parent().hasClass('trumbowyg-box')) {
                try {
                    $editor.trumbowyg('destroy');
                } catch (e) {
                    console.warn('Erro ao destruir o editor:', e);
                }
            }

            // Inicializa o Trumbowyg
            $editor.trumbowyg({
                btns: [
                    ['undo', 'redo'],
                    ['strong', 'em'],
                    ['fontfamily', 'formatting', 'foreColor'],
                    ['justifyLeft', 'justifyCenter', 'justifyFull']
                ],
                autogrow: false,
                resetCss: true
            });

            let debounceTimer;
            let ultimoTextoSalvo = '';

            $editor.on('keyup', enviarDadosController);

            const menuEdicao = document.getElementsByClassName("trumbowyg-button-pane")[0]
            menuEdicao.onclick = () => {
                enviarDadosController() //Envia dados imediatamente editados (como centralizar o texto)

                function esperarClique(e) { //Envia dados que precisam uma segunda seleção (como a cor do texto)
                    enviarDadosController()
                    // Remove este listener após o clique
                    document.removeEventListener("click", esperarClique);
                }

                // Adiciona o listener que espera o próximo clique
                setTimeout(() => {
                    document.addEventListener("click", esperarClique);
                }, 100);
            };

            function enviarDadosController() {
                const texto = $editor.trumbowyg('html');
                const txtPainel = painelSelecionado.querySelector(".txtPainel");

                if (txtPainel) {
                    txtPainel.innerHTML = texto;
                }

                clearTimeout(debounceTimer);

                debounceTimer = setTimeout(() => {
                    if (painelSelecionado && texto !== ultimoTextoSalvo) {
                        const painelId = painelSelecionado?.dataset?.painelId;

                        if (painelId) {
                            window.livewire.emit('salvarTexto', painelId, texto);
                            ultimoTextoSalvo = texto;
                        }
                    }
                }, 1000); // Espera 3s depois da última tecla
            }
        }

        window.addEventListener('atualizarTextoPainel', (event) => {
            const { painelId, novoTexto } = event.detail;
            const painel = document.querySelector(`.painel[data-painel-id="${painelId}"]`);
            if (painel) {
                painel.setAttribute('data-texto', novoTexto);
            }
        });


        document.addEventListener('DOMContentLoaded', function () {
            initTrumbowygEditor();
        });

        let zoomAtual = 0.7;
        let canvasLeft = 0;
        let canvasTop = 0;

        window.livewire.hook('message.sent', () => {
            const canvas = document.getElementById("canvas");

            // Salva o zoom atual (usando transform matrix)
            const transform = window.getComputedStyle(canvas).transform;
            if (transform && transform.includes("matrix")) {
                const valores = transform.match(/matrix\(([^)]+)\)/)[1].split(', ');
                zoomAtual = parseFloat(valores[0]);
            }

            // Salva a posição atual
            canvasLeft = canvas.offsetLeft;
            canvasTop = canvas.offsetTop;
        });

        document.addEventListener("DOMContentLoaded", () => {
            positionIndicadorInicio();
            positionTodosIndicadoresNenhuma();

            const selectTransicao = document.getElementById("selectTransicao");
            if (selectTransicao) {
                selectTransicao.addEventListener("change", () => {
                    tentarConectarOuRemover();
                    positionTodosIndicadoresNenhuma();
                });
            }

            window.livewire.hook('message.processed', (message, component) => {
                const painelSelecionado = document.querySelector(".painel.selecionado");
                const botaoSelecionado = document.querySelector(".botao.selecionado");

                if (message.updateQueue && message.updateQueue.some(m => m.payload?.event === 'salvarTexto')) {
                    setTimeout(() => {
                        initTrumbowygEditor();

                        const painelSelecionado = document.querySelector(".painel.selecionado");
                        const editor = $('#trumbowyg-editor');
                        if (painelSelecionado && editor.length) {
                            const novoTexto = painelSelecionado.getAttribute('data-texto');
                            if (novoTexto !== null) {
                                editor.trumbowyg('html', novoTexto);
                            }
                        }
                    }, 50);
                }

                mostrarMenu(menuAtivoAtual);

                document.querySelectorAll(".painel").forEach(panel => {
                    let id = panel.querySelector('.idPainel')?.id;
                    if (id) {
                        habilitarArrastoPersonalizado(panel);
                    }
                });

                const canvas = document.getElementById("canvas");
                canvas.style.transform = `scale(${zoomAtual}) translate(-50%, -50%)`;
                canvas.style.left = `${canvasLeft}px`;
                canvas.style.top = `${canvasTop}px`;
                scale = zoomAtual;

                const restaurarZoom = document.getElementById("resizeZoom");
                restaurarZoom.hidden = (scale === 0.7);

                canvas.append(centroCordenadas)

                canvas.style.transformOrigin =
                    (parseInt(centroCordenadas.style.left) - centroCamera[1]) + "px " +
                    (parseInt(centroCordenadas.style.top) - centroCamera[0]) + "px";

                recriarConexoes();
                atualizarTodasConexoes();
                positionIndicadorInicio();
                positionTodosIndicadoresNenhuma();
            });
        });
    </script>
@endsection