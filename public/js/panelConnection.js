//----UPDATE NOME DA CENA AO ALTERAR------------------------------------------------------
window.livewire.on("updateHtmlSceneName", (sceneName) => {
    document.getElementsByClassName("dashboard_bar")[0].innerText = sceneName;
})

//----CONFIGURAÇÕES DO CANVAS INFINITO E ZOOM------------------------------------------------------
const canvas = document.getElementById("canvas");
let scale = 0.7;
let alternativeScale = 3;

function updateCanvasScale() {
    canvas.style.transform = `scale(${scale}) translate(-50%, -50%)`;
    atualizarTodasConexoes();
}

function aplicarZoom(novoZoom) {
    const canvas = document.getElementById('canvas');
    canvas.style.transform = `scale(${novoZoom})`;
    atualizarTodasConexoes();
}

function zoomIn() {
    scale += 0.1;
    alternativeScale += 1;
    updateCanvasScale();
    document.getElementById("resizeZoom").hidden = false;
}

function zoomOut() {
    scale = Math.max(scale - 0.1, 0.1);
    alternativeScale = Math.max(alternativeScale - 1, -9);
    updateCanvasScale();
    document.getElementById("resizeZoom").hidden = false;
}

document.getElementById("zoom-in")?.addEventListener("click", (e) => zoomIn(e));

document.getElementById("zoom-out")?.addEventListener("click", zoomOut);

document.getElementById("resizeZoom").addEventListener("click", () => {
    scale = 0.7;
    updateCanvasScale();
    document.getElementById("resizeZoom").hidden = true;
});

canvas.onmousewheel = (e) => {
    if (e.deltaY < 0) {
        zoomIn(e)
    } else {
        zoomOut()
    }
}

//----COLOR PICKER--------------------------------------------------------------------------------
let pickrInicializado = false;

function iniciarPickr() {
    const container = document.getElementById("color-picker-container");

    if (!pickrInicializado && container) {
        window.pickr = Pickr.create({
            el: '#color-picker-container',
            theme: 'nano',
            default: '#3498db',
            inline: true,
            showAlways: true,
            useAsButton: false,
            components: {
                preview: true,
                opacity: true,
                hue: true,
                interaction: {
                    input: true,
                    clear: true,
                },
            },
        });

        pickrInicializado = true;

        window.pickr.on("change", (color) => {
            clearTimeout(debouceTimer);
            debouceTimer = setTimeout(() => {
                const circulo = botaoSelecionado?.querySelector(".circulo");
                if (circulo) {
                    window.livewire.emit("updateCor", {
                        id: circulo.id,
                        color: color.toHEXA().toString()
                    });
                }
            }, 1000);
        });
    } else if (window.pickr && container) {
        // Se já existe, atualize apenas a cor
        const corAtual = botaoSelecionado?.querySelector("#buttonInfo")?.getAttribute("color");
        if (corAtual) window.pickr.setColor(corAtual);
    }
}


document.addEventListener("DOMContentLoaded", function () {
    document.querySelector(".linhas").addEventListener("click", (e) => {
        selecionarFormato(e.currentTarget); // Chama a função ao clicar na div "linhas"
    });
    document.querySelector(".blocos").addEventListener("click", (e) => {
        selecionarFormato(e.currentTarget); // Chama a função ao clicar na div "blocos"
    });
    document.querySelector(".alternativas").addEventListener("click", (e) => {
        selecionarFormato(e.currentTarget); // Chama a função ao clicar na div "alternativas"
    });
});

//----ADICIONAR PAINEL--------------------------------------------------------------------------------------------------------
let container = document.getElementById("canvas");
let painelSelecionado = null;
let botaoSelecionado = null;
let zIndexAtual = 0;
let addBtn = document.getElementById("addPanel");

class Painel {
    constructor(painel) {
        this.newX = 0;
        this.newY = 0;
        this.startX = 0;
        this.startY = 0;
        this.painel = painel;
    }
}

//----FUNÇÃO DE MOSTRAR MENUS------------------------------------------------------------------------------------------------
let menuAtivoAtual = "canvas";

function mostrarMenu(tipo) {
    document.querySelectorAll(".menu-opcoes").forEach(menu => menu.classList.remove("ativo"));

    const menu = document.querySelector(`.${tipo}-opcoes`);
    if (menu) {
        menu.classList.add("ativo");
        menuAtivoAtual = tipo;

        if (tipo === "botao") {
            iniciarPickr(); // Aqui reinicializa ou atualiza

            const selectTransicao = document.getElementById("selectTransicao");
            const painelContainer = document.getElementById("selecaoPainelContainer");

            if (selectTransicao && painelContainer) {
                function atualizarVisibilidadePainel() {
                    painelContainer.style.display = selectTransicao.value === "proximo" ? "block" : "none";
                }

                atualizarVisibilidadePainel();

                selectTransicao.removeEventListener("change", atualizarVisibilidadePainel);
                selectTransicao.addEventListener("change", atualizarVisibilidadePainel);
            }
        }
    }
}

//----CLIQUE GLOBAL------------------------------------------------------------------------------------------------
document.addEventListener("click", (e) => {
    if (e.target.closest(".menu-opcoes") || e.target.closest(".menu-lateral") || e.target.closest("#confirmModal")) return;

    if (e.target.classList.contains("button_Panel")) {
        e.stopPropagation();
        selecionarBotao(e.target);
        return;
    }

    const painel = e.target.closest(".painel");
    if (painel) {
        selecionarPainel(painel, e);
        e.stopPropagation();
        return;
    }

    selecionarCanvas();
});

//----FUNÇÃO DE SELECIONAR PAINEL------------------------------------------------------------------------------------------------
let qtdBotoes = 0;
function selecionarPainel(painel, e) {
    if (e.target.closest(".button_Panel")) return;

    // limpa seleção anterior
    if (painelSelecionado) painelSelecionado.classList.remove("selecionado");
    if (botaoSelecionado) botaoSelecionado.classList.remove("selecionado");

    painelSelecionado = painel;
    botaoSelecionado = null;

    painel.classList.add("selecionado");
    mostrarMenu("painel");

    const editor = $('#trumbowyg-editor');
    const textoPainel = painel.getAttribute('data-texto');
    if (editor.length && textoPainel !== null) {
        editor.trumbowyg('html', textoPainel);
    }

    qtdBotoes = painelSelecionado.querySelector("#layout").children.length
}

//----FUNÇÃO DE SELECIONAR BOTÃO------------------------------------------------------------------------------------------------
function selecionarBotao(botao) {
    if (botaoSelecionado) botaoSelecionado.classList.remove("selecionado");
    botaoSelecionado = botao;
    botao.classList.add("selecionado");

    mostrarMenu("botao");

    let btnInfo = botaoSelecionado.querySelector("#buttonInfo");

    btnTxt.value = botaoSelecionado.textContent.trim();
    selectPainel.value = btnInfo.getAttribute("destination_id");
    selectTransicao.value = btnInfo.getAttribute("transition");

    // Atualiza a cor no pickr, sem reiniciar
    if (window.pickr && btnInfo.getAttribute("color")) {
        window.pickr.setColor(btnInfo.getAttribute("color"));
    }
}

document.addEventListener("DOMContentLoaded", () => {
    iniciarPickr();
});

//----FUNÇÃO DE SELECIONAR CANVAS------------------------------------------------------------------------------------------------
function selecionarCanvas() {
    if (painelSelecionado) painelSelecionado.classList.remove("selecionado");
    if (botaoSelecionado) botaoSelecionado.classList.remove("selecionado");

    painelSelecionado = null;
    botaoSelecionado = null;

    document.querySelector(".canvas-container").classList.add("selecionado");

    mostrarMenu("canvas");
}

//----SELECIONAR PAINEL INICIAL CLICANDO NO PAINEL-------------------------------------------------------------------
let modoSelecionarPainelInicial = false;
let btn = document.querySelectorAll('.tapSelect')[1];

btn.addEventListener('click', function () {
    modoSelecionarPainelInicial = !modoSelecionarPainelInicial;
});

let btn2 = document.querySelectorAll('.tapSelect')[0];
let lastBtnId;
btn2.addEventListener('click', function () {
    if (!modoSelecionarPainelInicial) {
        modoSelecionarPainelInicial = "Painel Normal";
        lastBtnId = botaoSelecionado.querySelector(".circulo").id;
    } else {
        modoSelecionarPainelInicial = false;
    }
});

// Interceptar clique em painel só se o modo estiver ativo
let selectPainel = document.getElementsByClassName("select-native")[1]

document.addEventListener("click", function (e) {
    if (!modoSelecionarPainelInicial) return;

    const painel = e.target.closest('.painel');
    if (!painel) return;

    e.stopPropagation();

    const idPainel = painel.querySelector('.idPainel')?.id;
    if (!idPainel) {
        console.warn("Painel clicado não tem ID válido.");
        return;
    }

    if (modoSelecionarPainelInicial == "Painel Normal") {
        selectPainel.value = idPainel;
        mudarPainelDestino(lastBtnId);
        document.querySelectorAll('.tapSelect')[0].classList.remove('selected');
    } else {
        // Envia pro Livewire
        window.livewire.emit('updateStartPanel', idPainel);

        document.querySelectorAll('.tapSelect')[1].classList.remove('selected');
    }

    // Sai do modo de seleção
    modoSelecionarPainelInicial = false;
});

//----FORMATO DOS BOTÕES----------------------------------------------------------------------------
function alterarFormatoBotoes(formato) {
    if (!painelSelecionado) {
        console.log("Nenhum painel selecionado!");
        return;
    }

    let qtdBotoes = painelSelecionado.querySelector("#layout").children.length;

    if (formato == "linhas" && qtdBotoes > 3) {
        enviarMsg("Você não pode trocar formatos, o formato de linhas suporta até 3 botões. Exclua alguns botões e tente novamente.")
        return;
    } else if (formato == "alternativas" && qtdBotoes > 4) {
        enviarMsg("Você não pode trocar formatos, o formato de círculos suporta até 4 botões. Exclua alguns botões e tente novamente")
        return;
    }

    let layout = painelSelecionado.querySelector("#layout");
    let txtBtn = document.getElementById("btnTxt");
    txtBtn.setAttribute("maxlength", "6");

    switch (formato) {
        case "linhas":
            txtBtn.setAttribute("maxlength", "20");
            layout.classList = "layout-linhas";
            break;
        case "blocos":
            layout.classList = "layout-blocos";
            break;
        case "alternativas":
            layout.classList = "layout-alternativas";
            break;
    }

    //Guardar no banco
    window.livewire.emit("updateBtnFormat", { id: painelSelecionado.querySelector(".idPainel").id, btnFormat: formato })
}

document
    .querySelector(".linhas")
    .addEventListener("click", () => alterarFormatoBotoes("linhas"));
document
    .querySelector(".blocos")
    .addEventListener("click", () => alterarFormatoBotoes("blocos"));
document
    .querySelector(".alternativas")
    .addEventListener("click", () => alterarFormatoBotoes("alternativas"));

//----MOVIMENTAÇÃO PAINEL (Personalizada)----------------------------------------------------------------------------
function habilitarArrastoPersonalizado(painelElement) {
    let offsetX, offsetY, isDragging = false;

    painelElement.addEventListener("mousedown", function (e) {
        if (e.button !== 0) return;
        e.preventDefault();
        isDragging = true;

        const rect = painelElement.getBoundingClientRect();
        offsetX = e.clientX - rect.left;
        offsetY = e.clientY - rect.top;

        document.body.style.userSelect = "none";
        painelElement.style.zIndex = ++zIndexAtual;
    });

    let animationFrame;

    document.addEventListener("mousemove", function (e) {
        if (!isDragging) return;

        if (animationFrame) cancelAnimationFrame(animationFrame);

        animationFrame = requestAnimationFrame(() => {
            const canvas = document.getElementById("canvas");
            const canvasRect = canvas.getBoundingClientRect();
            let newX = (e.clientX - canvasRect.left - offsetX) / scale;
            let newY = (e.clientY - canvasRect.top - offsetY) / scale;

            newX = Math.max(0, Math.min(newX, 80000 - 291));
            newY = Math.max(0, Math.min(newY, 80000 - 462));

            painelElement.style.left = `${newX}px`;
            painelElement.style.top = `${newY}px`;

            atualizarTodasConexoes();

            // Se o painel arrastado for o inicial, atualiza o indicador em tempo real
            const startId = canvas.getAttribute("data-start-id");
            const painelId = painelElement.querySelector('.idPainel')?.id;
            if (painelId === startId) {
                atualizarIndicadorInicio();
            }
        });
    });

    document.addEventListener("mouseup", function () {
        if (!isDragging) return;
        isDragging = false;
        document.body.style.userSelect = "";

        const x = parseFloat(painelElement.style.left);
        const y = parseFloat(painelElement.style.top);
        const id = parseInt(painelElement.querySelector('.idPainel').id);

        if (!isNaN(id)) {
            window.livewire.emit("updateCoordinate", id, x, y);
        }

        const painelId = painelElement.querySelector('.idPainel')?.id;
        const startId = document.getElementById("canvas").getAttribute("data-start-id");
        if (painelId === startId) {
            atualizarIndicadorInicio();
        }
    });
}


//----MOVIMENTAÇÃO CANVAS----------------------------------------------------------------------------
const div = document.getElementById("canvas");
let isDragging = false;
let startX = 0, startY = 0;
let startLeft = 0, startTop = 0, startLeftPoint = 0, startTopPoint = 0;
div.addEventListener('contextmenu', event => event.preventDefault());

//Centro da câmera usado para o zoom em direção para onde a câmera esta olhando.
let centroCamera = [(canvas.getBoundingClientRect().width * 1.428) / 2, (canvas.getBoundingClientRect().height * 1.428) / 2];
let centroCordenadas = document.createElement("div")
centroCordenadas.style.position = "absolute"
centroCordenadas.style.top = centroCamera[1] + "px"
centroCordenadas.style.left = centroCamera[0] + "px"
//        Descomente as linhas abaixo para poder vizualizar o centro da tela.
// centroCordenadas.style.background = "red"; centroCordenadas.style.borderRadius = "100%";
// centroCordenadas.style.width = "50px"; centroCordenadas.style.height = "50px";

div.addEventListener("mousedown", (e) => {
    if (e.target.closest(".painel") || e.button != 1) return;

    setTimeout(() => {
        isDragging = true;
        startX = e.clientX;
        startY = e.clientY;
        startLeft = div.offsetLeft;
        startTop = div.offsetTop;
        startLeftPoint = centroCordenadas.offsetLeft;
        startTopPoint = centroCordenadas.offsetTop;
        div.style.cursor = "grabbing";
        e.preventDefault();
    }, 100);
});

canvas.append(centroCordenadas)

document.addEventListener("mousemove", (e) => {
    if (!isDragging) return;

    //Define o quanto foi movimentado mouse
    let deltaX = (e.clientX - startX) / scale;
    let deltaY = (e.clientY - startY) / scale;

    //Calcula a posição que devia ocupar. (Posição inicial + movimentação feita)
    //Obs: O código joga o canvas na posição oposta que se deseja ir, revelando novas partes na direção movimentada.
    div.style.left = `${(startLeft + deltaX)}px`;
    div.style.top = `${(startTop + deltaY)}px`;

    //Calcula a posição do centro. (Posição inicial centro - movimentação feita). 
    //Obs: O canvas jogou o centro na direção oposta, se corrige isso subtraindo a movimentação feita do centro.
    let leftCentro = (startLeftPoint - deltaX);
    let topCentro = (startTopPoint - deltaY);

    //Aplica-se os valores do centro
    centroCordenadas.style.top = `${topCentro}px`;
    centroCordenadas.style.left = `${leftCentro}px`;

    //Define com base no centro, onde deve ser feito o zoom no canvas caso houver.
    canvas.style.transformOrigin = (leftCentro - centroCamera[0]) + "px " + (topCentro - centroCamera[1]) + "px";

    if (isDragging) {
        atualizarTodasConexoes();
    }
});

document.addEventListener("mouseup", (e) => {
    if (e.button != 1) return;

    isDragging = false;
    div.style.cursor = "grab";

    canvasLeft = div.offsetLeft;
    canvasTop = div.offsetTop;

    zoomAtual = scale;
});

window.addEventListener('beforeunload', function (e) {
    window.livewire.emit('updateCanvasPosition', [canvasTop, canvasLeft, scale, centroCordenadas.style.top, centroCordenadas.style.left]);
});

//----MOSTRAR POPUP QUANDO SELECIONAR------------------------------------------------------------------------------------------------
function fecharPopUp() {
    document.getElementById("flex-container").style.display = "none";
}

let painelPopup = null;
function abrirPopUp(id) {
    painelPopup = id;

    // Define o input file correspondente a este painel
    inputAtivo = document.querySelector("#file-" + id);

    // Atualiza o atributo "for" da label para apontar pro input atual
    const dropLabel = document.getElementById("upload-area");
    dropLabel.setAttribute("for", "#file-" + id);

    // Abre o pop-up
    document.getElementById("flex-container").style.display = "flex";

    let painel = document.getElementById(id)

    if (painel.querySelector("video").style.display != 'none') {
        setTimeout(() => {
            painel.querySelector("video").pause()
        }, 500);
    }

    //Salvar as cordenadas por precaução.
    window.livewire.emit('updateCanvasPosition', [canvasTop, canvasLeft, scale, centroCordenadas.style.top, centroCordenadas.style.left]);
}

let urlYoutubeInformado = false;
let inputAtivo = null;
const midiaPreviewPorInput = new Map();

function adicionarInteracaoPopup(id) {
    let painel = document.getElementById(id);
    let fileBtn = painel.querySelector("#file-" + id);
    let midiaArea = painel.querySelector(".midia");

    const midiaPreview = () => {
      //Por agr ta aqui só n dar erros por remover ele.
      //Se estiver lendo isso dps da data 23/06/2025, apague qualquer instancia desse método sendo chamado.
    };

    // vincula o midiaPreview a esse input
    //fileBtn.onchange = midiaPreview;
    midiaPreviewPorInput.set(fileBtn, midiaPreview);

    // clique no painel abre popup
    midiaArea.onclick = () => abrirPopUp(id);

    Array.from(midiaArea.children).forEach(child => {
        child.onclick = () => abrirPopUp(id);
    });
}

let editarMidiaBtn = document.getElementById("editarMidia")
editarMidiaBtn.onclick = () => abrirPopUp(painelSelecionado.querySelector(".idPainel").id)

let excluirPainelBtn = document.getElementById("excluirPainel")
excluirPainelBtn.onclick = () => {
    $id = painelSelecionado.querySelector(".idPainel").id;
    if ($id = canvas.getAttribute("data-start-id")) {
        enviarMsg("Você não pode deletar o painel inicial da cena!")
        return;
    }

    window.livewire.emit('deletePainel', $id);
}

window.livewire.on("carregarVideo", (id) => {
    document.getElementById(id).querySelector("video").load()
})

const dropArea = document.getElementById("upload-area");

// Clique para abrir o seletor de arquivos
dropArea.addEventListener("click", () => {
    if (inputAtivo) inputAtivo.click();
});

// Evita comportamento padrão ao arrastar arquivos
["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
    dropArea.addEventListener(eventName, (e) => e.preventDefault());
});

// Destaque visual
dropArea.addEventListener("dragover", () => dropArea.classList.add("dragover"));
dropArea.addEventListener("dragleave", () =>
    dropArea.classList.remove("dragover")
);

dropArea.addEventListener("drop", (e) => {
    if (!inputAtivo) return;

    const files = e.dataTransfer.files;

    const dataTransfer = new DataTransfer();
    for (const file of files) {
        dataTransfer.items.add(file);
    }
    inputAtivo.files = dataTransfer.files;

    dropArea.classList.remove("dragover");

    // Chama a função de preview associada ao input atual
    const midiaPreview = midiaPreviewPorInput.get(inputAtivo);
    if (midiaPreview) midiaPreview();

    fecharPopUp();

    let file = dataTransfer.files[0]
    let painel = inputAtivo.parentElement;
    let wire = window.livewire.find(painel.getAttribute('wire:id'));
    if (file) {
        wire.upload('midia', file);
    }

    //window.livewire.emit("updatedMidia",null,dataTransfer.files[0]);
});

// 3.3 Um link do youtube foi inserido
document.getElementById("linkYoutube").oninput = () => {
    const prefix = "https://www.youtube.com/watch?v=";
    const prefix2 = "https://youtu.be/";
    url = document.getElementById("linkYoutube").value;

    const inputHidden = document.getElementById("link-" + painelPopup);
    if (!painelPopup) return;

    if (url.startsWith(prefix) && url.slice(prefix.length).length == 11) {
        urlYoutubeInformado = true;
        inputHidden.value = url.slice(prefix.length);
        sendValueLivewire(painelPopup, inputHidden.value)
    } else if (
        url.startsWith(prefix2) &&
        url.slice(prefix2.length).length == 11
    ) {
        urlYoutubeInformado = true;
        inputHidden.value = url.slice(prefix2.length);
        sendValueLivewire(painelPopup, inputHidden.value)
    }
};

function sendValueLivewire(id, link) {
    window.livewire.emit('updateLink', { id: id, link: link });
}

//----DESENHAR CONEXÃO - ENTRE PAINEIS---------------------------------------------------------------------------
const todasAsLinhas = [];
const linhasPorBotao = new Map();

// Essa função atualiza a posição das linhas quando o canvas ou os painéis se movem
function atualizarTodasConexoes() {
    requestAnimationFrame(() => {
        linhasPorBotao.forEach(linha => {
            if (linha.position) linha.position();
        });
    });
}

// Conectar um botão específico (passando o botão como elemento e os IDs)
function conectarBotoes(startElem, idOrigem, idPainel) {
    const endElem = document.getElementById(idPainel);

    if (!startElem || !endElem) return;

    // Remove a linha antiga
    if (linhasPorBotao.has(idOrigem)) {
        linhasPorBotao.get(idOrigem).remove();
        linhasPorBotao.delete(idOrigem);
    }

    // Determinar se o botão é filho par ou ímpar
    const parent = startElem.parentElement;
    const children = Array.from(parent.children).filter(el => el.classList.contains("button_Panel"));
    const index = children.indexOf(startElem);
    const startSocket = index % 2 === 0 ? 'left' : 'right'; 

    const linha = new LeaderLine(startElem, endElem, {
        color: '#833B8D',
        size: 4,
        path: 'fluid',
        startPlug: 'disc',
        endPlug: 'arrow3',
        startSocket: startSocket,
        endSocket: 'auto'
    });

    linhasPorBotao.set(idOrigem, linha);

    const infoDiv = startElem.querySelector('#buttonInfo');
    if (infoDiv) {
        infoDiv.setAttribute('destination_id', idPainel);
    }

    return linha;
}

// Recriar conexões salvas ao carregar a tela
function recriarConexoes() {
    todasAsLinhas.forEach(linha => linha.remove());
    linhasPorBotao.forEach(l => l.remove());
    linhasPorBotao.clear();


    document.querySelectorAll(".button_Panel").forEach(botao => {
        const botaoId = botao.querySelector(".circulo")?.id;
        const infoDiv = botao.querySelector("#buttonInfo");
        const destinoId = infoDiv?.getAttribute("destination_id");
        const transicao = infoDiv?.getAttribute("transition");

        if (botaoId && destinoId && transicao === "proximo") {
            const destinoElem = document.getElementById(destinoId);
            if (destinoElem) {
                conectarBotoes(botao, botaoId, destinoId);
            }
        }
    });
}

function tentarConectarOuRemover() {
    let selectPainelDestino = document.getElementsByClassName("selectPainelDestino")[0];
    let selectTransicao = document.getElementById("selectTransicao");

    const transicao = selectTransicao.value;
    const destinoId = selectPainelDestino.value;

    const botaoSelecionado = document.querySelector(".button_Panel.selecionado");
    if (!botaoSelecionado) {
        console.warn("Nenhum botão selecionado.");
        return;
    }

    const idBotaoOrigem = botaoSelecionado.querySelector(".circulo")?.id;
    if (!idBotaoOrigem) return;

    const infoDiv = botaoSelecionado.querySelector("#buttonInfo");
    if (!infoDiv) return;

    // Atualiza o atributo transition com a nova seleção
    infoDiv.setAttribute("transition", transicao);

    // Se for "proximo", conectar
    if (transicao === "proximo") {
        if (destinoId) {
            infoDiv.setAttribute("destination_id", destinoId);
            conectarBotoes(botaoSelecionado, idBotaoOrigem, destinoId);
        } else {
            // Se ainda não foi escolhido nenhum painel, não conecta ainda
            infoDiv.removeAttribute("destination_id");
        }
    } else {
        // Se não, remove linha específica do botão
        if (linhasPorBotao.has(idBotaoOrigem)) {
            linhasPorBotao.get(idBotaoOrigem).remove();
            linhasPorBotao.delete(idBotaoOrigem);
        }
        // Limpa
        infoDiv.removeAttribute("destination_id");


        if (selectPainelDestino) {
            selectPainelDestino.value = '';
        }

        window.livewire.emit('updatePainelDestino', { id: idBotaoOrigem, destination_id: null });
    }
}

//----DESENHAR CONEXÃO - PAINEL INICIAL------------------------------------------------------------------------------------------------
function atualizarIndicadorInicio() {
    const canvas = document.getElementById("canvas");
    const indicador = document.getElementById("indicadorInicio");
    const startId = canvas.getAttribute("data-start-id");

    if (!startId || !indicador) return;

    const painel = document.getElementById(startId);
    if (!painel) return;

    const painelRect = painel.getBoundingClientRect();
    const canvasRect = canvas.getBoundingClientRect();

    const left = painel.offsetLeft - 50;
    const top = painel.offsetTop + (painel.offsetHeight / 2) - 20;

    indicador.style.left = `${left}px`;
    indicador.style.top = `${top}px`;
    indicador.style.display = "block";
}

//----DESENHAR CONEXÃO - NENHUMA ------------------------------------------------------------------------------------------------
function atualizarIndicadoresDeTransicao() {
    document.querySelectorAll(".button_Panel").forEach(botao => {
        const infoDiv = botao.querySelector("#buttonInfo");
        if (!infoDiv) return;

        const transicao = infoDiv.getAttribute("transition");

        // Remove indicadores antigos
        botao.querySelector(".indicadorTransicao")?.remove();
        botao.querySelector(".indicadorNenhuma")?.remove();

        if (transicao === "" || transicao === "nenhuma") {
            const layoutContainer = botao.closest(".painel")?.querySelector("#layout");
            const layout = layoutContainer?.classList[0];

            let lado = "esquerda";

            if (layout === "layout-blocos" || layout === "layout-alternativas") {
                // Conta os botões dentro do mesmo painel
                const botoes = Array.from(botao.parentElement.querySelectorAll(".button_Panel"));
                const index = botoes.indexOf(botao);

                // Se o índice for par (0, 2...), está na coluna da esquerda
                lado = (index % 2 === 0) ? "esquerda" : "direita";
            }

            const offset = 100;

            const indicador = document.createElement("img");
            indicador.classList.add("indicadorNenhuma");
            indicador.src = "/images/semConexoes.svg";
            indicador.style.position = "absolute";
            indicador.style.width = "24px";
            indicador.style.height = "24px";
            indicador.style.zIndex = 20;
            indicador.style.display = "block";

            indicador.style.left = (lado === "esquerda" ? -offset : botao.offsetWidth + offset - 24) + "px";
            indicador.style.top = "50%";
            indicador.style.transform = "translateY(-50%)";

            botao.appendChild(indicador);
        }
    });
}
//----DESENHAR CONEXÃO - FINAL DA EXPERIÊNCIA ---------------------------------------------------------------------------------
function atualizarIndicadoresDeFinal() {
    document.querySelectorAll(".button_Panel").forEach(botao => {
        const infoDiv = botao.querySelector("#buttonInfo");
        if (!infoDiv) return;

        const transicao = infoDiv.getAttribute("transition");

        // Remover indicador final anterior
        const indicadorFinalExistente = botao.querySelector(".indicadorFinal");
        if (indicadorFinalExistente) indicadorFinalExistente.remove();

        if (transicao === "final") {
            const layoutContainer = botao.closest(".painel")?.querySelector("#layout");
            const layout = layoutContainer?.classList[0];

            const botoes = Array.from(botao.parentElement.children).filter(el => el.classList.contains("button_Panel"));
            const index = botoes.indexOf(botao);

            // Lógica do lado baseada na paridade
            let lado = "esquerda";
            if (layout === "layout-alternativas" && index % 2 === 1) {
                lado = "direita";
            }

            const botaoX = botao.offsetLeft;
            const botaoWidth = botao.offsetWidth;
            const offset = 100;

            const indicador = document.createElement("img");
            indicador.classList.add("indicadorFinal");
            indicador.src = "/images/endConnection.svg";
            indicador.style.position = "absolute";
            indicador.style.width = "40px";   // ⬅️ aqui você altera o tamanho
            indicador.style.height = "40px";  // ⬅️ idem
            indicador.style.zIndex = 20;
            indicador.style.display = "block";
            indicador.style.left = (lado === "esquerda" ? -offset : botaoWidth + offset - 40) + "px";
            indicador.style.top = "50%";
            indicador.style.transform = "translateY(-50%)";

            botao.appendChild(indicador);
        }
    });
}


//----CONFIGURAR BOTÕES------------------------------------------------------------------------------------------
let addBtnBtn = document.getElementById("addButton")

// 1. Criar novo botão
addBtnBtn.onclick = () => {
    let id = painelSelecionado.querySelector(".idPainel").id;
    let tipoFormato = painelSelecionado.querySelector("#layout").classList[0];

    if (tipoFormato == "layout-blocos" && qtdBotoes >= 6) {
        enviarMsg("Você não pode adicionar mais botões, o formato de blocos suporta até 6 botões")
        return;
    } else if (tipoFormato == "layout-linhas" && qtdBotoes >= 3) {
        enviarMsg("Você não pode adicionar mais botões, o formato de linhas suporta até 3 botões")
        return;
    } else if (tipoFormato == "layout-alternativas" && qtdBotoes >= 4) {
        enviarMsg("Você não pode adicionar mais botões, o formato de círculos suporta até 4 botões")
        return;
    }
    qtdBotoes++;
    window.livewire.emit('createButton', { id: id });
}

function enviarMsg(mensagem) {
    document.getElementById('msgModal').textContent = mensagem;
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    confirmModal.show();
}

// 2. Alterar texto botão
let debouceTimer;

btnTxt.oninput = () => {
    clearTimeout(debouceTimer);
    debouceTimer = setTimeout(() => {
        window.livewire.emit('updateTexto', { id: botaoSelecionado.querySelector(".circulo").id, text: btnTxt.value })
    }, 10);
}

// 3. Altera transição
selectTransicao.onchange = () => {
    window.livewire.emit('updateTransicao', { id: botaoSelecionado.querySelector(".circulo").id, transition: selectTransicao.value })
}

// 4. Altera o painel de destino
selectPainel.onchange = () => { mudarPainelDestino(botaoSelecionado.querySelector(".circulo").id) };
function mudarPainelDestino(id) {
    window.livewire.emit('updatePainelDestino', { id: id, destination_id: selectPainel.value })
}

// 5. Deletar botão
let deleteBtn = document.getElementById("deleteBtn")
deleteBtn.onclick = () => {
    let painel = botaoSelecionado.querySelector(".circulo").parentElement.parentElement.parentElement.parentElement;
    window.livewire.emit('deleteBtn', { id: botaoSelecionado.querySelector(".circulo").id, id_painel: painel.querySelector(".idPainel").id })
}

//6. Altera cor botão
