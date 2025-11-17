@extends('layouts.app')

@section('page-name', 'Mural')

@section('script-head')
    <!-- CSS PAINEL CONEXÕES -->
    <link rel="stylesheet"
        href="{{ asset('css/muralConnection.css?v=' . filemtime(public_path('css/muralConnection.css'))) }}">

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

        .loading {
            transition: transform 25s linear;
        }

        .areaBtns .loading {
            position: absolute;
            align-self: center;
            height: 146px;
            width: calc(100% - 40px);
            background: #00000059;
            border-radius: 5px;
            display: flex;
            justify-content: center;
            z-index: 1;
        }

        .areaBtns .loading img {
            height: 80%;
            margin-top: 6%;
            transition: transform 25s linear;
        }

        .ponto{
            height: 10px;
            width: 10px;
            background-color: white;
            border: 3px solid #833B8D;
            border-radius: 100%;
            position: absolute;
        }
    </style>
@endsection

@section('bodyAccess')
    <!--Pop up upload de arquivo-->
    <!--Explicação: Ele teve que ficar dentro do body, ao colocar o elemento dentro da section content, ele fica dentro de um "Main wrapper" que possui um tamanho menor que o tamanho inteiro da tela-->

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

    <livewire:mural-edit :muralId="$muralId"/>
    
@endsection

@section('script')


    <script src="{{ asset('editor/dist/trumbowyg.min.js') }}"></script>
    <script src="{{ asset('editor/dist/plugins/colors/trumbowyg.colors.min.js') }}"></script>
    <script src="{{ asset('js/panelConnection.js?v=' . filemtime(public_path('js/panelConnection.js'))) }}"></script>
    <!-- LINHAS DE CONEXÕES -->
    <script src="https://cdn.jsdelivr.net/npm/leader-line@1.0.8/leader-line.min.js"></script>
    <script>
        // ---------------------------------------------CARREGAR CANVAS---------------------------------------------
        let preloader = document.getElementById("preloader");
        let mainWrapper = document.getElementById("main-wrapper");

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
            atualizarIndicadorInicio();
            atualizarIndicadoresDeTransicao();
            atualizarIndicadoresDeFinal();

            document.querySelectorAll(".painel").forEach(panel => {
                let id = panel.querySelector('.idPainel')?.id;
                if (id) {
                    atribuirListeners(panel);
                    habilitarArrastoPersonalizado(panel);
                    habilitarArrastarMidia(id);
                }
            });

            window.livewire.on("painelCriado", (id) => {
                //let panel = document.getElementById(id);
                let panel = document.querySelectorAll('[data-painel-id="'+id+'"]')[0];
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

                atribuirListeners(panel);
                habilitarArrastoPersonalizado(panel);
                mostrarMenu("painel");
            });

            mostrarMenu("canvas");
            recriarConexoes();
        });

        function atribuirListeners(panel) {
            panel.addEventListener("click", (e) => selecionarPainel(panel, e));
            
            adicionarInteracaoPopup(panel.id.substr(1));
        }

        function habilitarArrastarMidia(id) {
            //inputAtivo eh inicializado no abrirpopup
            const dropArea = document.getElementById("upload-area-" + id);
            dropArea.addEventListener("click", () => {
                if (inputAtivo) {
                    inputAtivo.setAttribute("accept", "image/*");
                    inputAtivo.click();
                }
            });

            ["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
                dropArea.addEventListener(eventName, (e) => e.preventDefault());
            });

            // Destaque visual
            dropArea.addEventListener("dragover", () => dropArea.classList.add("dragover"));
            dropArea.addEventListener("dragleave", () =>
                dropArea.classList.remove("dragover")
            );

            // Droppou um arquivo no drop área
            dropArea.addEventListener("drop", (e) => {
                if (!inputAtivo) return;

                const files = e.dataTransfer.files;

                const dataTransfer = new DataTransfer();
                for (const file of files) {
                    dataTransfer.items.add(file);
                }
                inputAtivo.files = dataTransfer.files;

                dropArea.classList.remove("dragover");

                //fecharPopUp();

                let file = dataTransfer.files[0]
                let painel = inputAtivo.parentElement.parentElement;
                let wire = window.livewire.find(painel.getAttribute('wire:id'));
                if (file) {
                    wire.upload('midia', file);
                }

             });
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

            const menuEdicao = document.getElementsByClassName("trumbowyg-button-pane")[0];
            menuEdicao.onclick = () => {
                enviarDadosController();
                setTimeout(() => {
                    document.addEventListener("click", function esperarClique() {
                        enviarDadosController();
                        document.removeEventListener("click", esperarClique);
                    });
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
                }, 1000);
            }
        }

        window.addEventListener('atualizarTextoPainel', (event) => {
            const { painelId, novoTexto } = event.detail;
            const painel = document.querySelector(`.painel[data-painel-id="${painelId}"]`);
            if (painel) {
                painel.setAttribute('data-texto', novoTexto);
            }
        });

        window.addEventListener('atualizarImgMidia', (event) => {
            
            const { painelId, uploadedArea } = event.detail;
            const imgmidia = document.getElementById("img-midia-"+painelId+"-copia");
            imgmidia.src = uploadedArea;
        });

        document.addEventListener('DOMContentLoaded', function () {
            initTrumbowygEditor();
        });

        let zoomAtual = 0.7;
        let canvasLeft = 0;
        let canvasTop = 0;

        window.livewire.hook('message.sent', () => {
            const canvas = document.getElementById("canvas");
            const transform = window.getComputedStyle(canvas).transform;
            if (transform && transform.includes("matrix")) {
                const valores = transform.match(/matrix\(([^)]+)\)/)[1].split(', ');
                zoomAtual = parseFloat(valores[0]);
            }
            canvasLeft = canvas.offsetLeft;
            canvasTop = canvas.offsetTop;
        });

        document.addEventListener("DOMContentLoaded", () => {
            atualizarIndicadoresDeTransicao();
            atualizarIndicadoresDeFinal();
            const selectTransicao = document.getElementById("selectTransicao");
            if (selectTransicao) {
                selectTransicao.addEventListener("change", () => {
                    tentarConectarOuRemover();
                });
            }

            mainWrapper.style.display = "none";
            let carregando = setInterval(() => {
                preloader.style.display = "block"
            }, 100);

            window.livewire.emit("buscarDadosIniciais");
            window.livewire.on('carregarCanvas', ($data) => {
                carregarCanvas($data[0], $data[1], $data[2], $data[3], $data[4])
                clearInterval(carregando);

                mainWrapper.style.display = "block";
                preloader.style.display = "none"

            })

            window.livewire.hook('message.processed', (message, component) => {
                atualizarIndicadoresDeTransicao();
                atualizarIndicadoresDeFinal();
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

                        if (botaoSelecionado?.id) {
                            const novoBotao = document.getElementById(botaoSelecionado.id);
                            if (novoBotao) {
                                selecionarBotao(novoBotao); // já inicia o Pickr internamente
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

                carregarCanvas(centroCordenadas.style.top, centroCordenadas.style.left, scale, canvasTop, canvasLeft);
                recriarConexoes();
                atualizarTodasConexoes();
                atualizarIndicadorInicio();
            });

            window.livewire.on("buttonCriado", () => {
                setTimeout(() => {
                    atualizarIndicadoresDeTransicao();
                    atualizarIndicadoresDeFinal();
                }, 50);
            });
        });

        function carregarCanvas(centroTop, centroLeft, zoom, canvasTop2, canvasLeft2) {
            const canvas = document.getElementById("canvas");
            centroCordenadas.style.top = centroTop;
            centroCordenadas.style.left = centroLeft;
            canvas.style.left = `${canvasLeft2}px`;
            canvas.style.top = `${canvasTop2}px`;
            canvasLeft = canvasLeft2;
            canvasTop = canvasTop2;

            scale = zoom;
            canvas.style.transform = `scale(${zoom}) translate(-50%, -50%)`;

            const restaurarZoom = document.getElementById("resizeZoom");
            restaurarZoom.hidden = (scale === 0.7);

            canvas.append(centroCordenadas);

            canvas.style.transformOrigin =
                (parseInt(centroCordenadas.style.left) - centroCamera[1]) + "px " +
                (parseInt(centroCordenadas.style.top) - centroCamera[0]) + "px";
        }

    </script>
@endsection