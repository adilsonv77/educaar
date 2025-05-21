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
    positionIndicadorInicio()
    positionTodosIndicadoresNenhuma();
}

function zoomIn(e) {
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
document.addEventListener("DOMContentLoaded", function () {
    if (document.querySelector("#color-picker-container")) {
        window.pickr = Pickr.create({
            el: "#color-picker-container",
            theme: "nano",
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
                    clear: true,
                },
            },
        });
    } else {
        console.warn("❌ Container do Color Picker não encontrado.");
    }

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

        // Só executa lógica de transição se for o menu de botões
        if (tipo === "botao") {
            const selectTransicao = document.getElementById("selectTransicao");
            const painelContainer = document.getElementById("selecaoPainelContainer");

            if (selectTransicao && painelContainer) {
                function atualizarVisibilidadePainel() {
                    painelContainer.style.display = selectTransicao.value === "proximo" ? "block" : "none";
                }

                atualizarVisibilidadePainel();

                // Evita múltiplos listeners duplicados
                selectTransicao.removeEventListener("change", atualizarVisibilidadePainel);
                selectTransicao.addEventListener("change", atualizarVisibilidadePainel);
            }
        }
    }
}

//----CLIQUE GLOBAL------------------------------------------------------------------------------------------------
document.addEventListener("click", (e) => {
    if (e.target.closest(".menu-opcoes")) {
        return;
    }

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
let isDraggingPanel = false;
function selecionarPainel(painel, e) {
    isDraggingPanel = true;

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
    isDraggingPanel = true;

    if (painelSelecionado) painelSelecionado.classList.remove("selecionado");
    if (botaoSelecionado) botaoSelecionado.classList.remove("selecionado");

    painelSelecionado = null;
    botaoSelecionado = botao;

    botao.classList.add("selecionado");
    mostrarMenu("botao");

    //Carrega as informações do botão no menu
    let btnInfo = botaoSelecionado.querySelector("#buttonInfo");

    btnTxt.value = botaoSelecionado.textContent.trim();
    selectPainel.value = btnInfo.getAttribute("destination_id");
    selectTransicao.value = btnInfo.getAttribute("transition");
    setTimeout(() => {
        pickr.setColor(btnInfo.getAttribute("color"));
    }, 100);
}

//----FUNÇÃO DE SELECIONAR CANVAS------------------------------------------------------------------------------------------------
function selecionarCanvas() {
    isDraggingPanel = true;

    if (painelSelecionado) painelSelecionado.classList.remove("selecionado");
    if (botaoSelecionado) botaoSelecionado.classList.remove("selecionado");

    painelSelecionado = null;
    botaoSelecionado = null;

    document.querySelector(".canvas-container").classList.add("selecionado");

    mostrarMenu("canvas");
    isDraggingPanel = false;
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
        alert("Você não pode trocar formatos, o formato de linhas suporta até 3 botões. Exclua alguns botões e tente novamente.")
        return;
    } else if (formato == "alternativas" && qtdBotoes > 4) {
        alert("Você não pode trocar formatos, o formato de círculos suporta até 4 botões. Exclua alguns botões e tente novamente")
        return;
    }

    let layout = painelSelecionado.querySelector("#layout")
    switch (formato) {
        case "linhas":
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

    document.addEventListener("mousemove", function (e) {
        if (!isDragging) return;

        const canvasRect = canvas.getBoundingClientRect();
        let newX = (e.clientX - canvasRect.left - offsetX) / scale;
        let newY = (e.clientY - canvasRect.top - offsetY) / scale;

        newX = Math.max(0, Math.min(newX, 80000 - 291));
        newY = Math.max(0, Math.min(newY, 80000 - 462));

        painelElement.style.left = `${newX}px`;
        painelElement.style.top = `${newY}px`;
        atualizarTodasConexoes();
        positionIndicadorInicio();
        positionTodosIndicadoresNenhuma();

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
    });
}

//----MOVIMENTAÇÃO CANVAS----------------------------------------------------------------------------
const div = document.getElementById("canvas");
let isDragging = false;
let startX = 0, startY = 0;
let startLeft = 0, startTop = 0, startLeftPoint = 0, startTopPoint = 0;

div.addEventListener("mousedown", (e) => {
    if (e.target.closest(".painel")) return;
    setTimeout(() => {
        if (isDraggingPanel) return;
        isDragging = true;
        startX = e.clientX;
        startY = e.clientY;
        startLeft = div.offsetLeft;
        startTop = div.offsetTop;
        startLeftPoint= centroCordenadas.offsetLeft;
        startTopPoint = centroCordenadas.offsetTop;
        div.style.cursor = "grabbing";
        e.preventDefault();
    }, 100);
});

//Coisas novas
let centroCamera = [(canvas.getBoundingClientRect().width * 1.41) / 2, (canvas.getBoundingClientRect().height * 1.41) / 2];
let centroCordenadas = document.createElement("div")
centroCordenadas.style.position = "absolute"
centroCordenadas.style.top = centroCamera[1]+"px"
centroCordenadas.style.left = centroCamera[0]+"px"

canvas.append(centroCordenadas)

document.addEventListener("mousemove", (e) => {
    if (!isDragging || isDraggingPanel) return;
    
    let deltaX = e.clientX - startX;
    let deltaY = e.clientY - startY;
    
    div.style.left = `${startLeft + deltaX}px`;
    div.style.top = `${startTop + deltaY}px`;
    
    let scaleLeft = deltaX;
    let scaleTop = deltaY;

    let leftCentro = (startLeftPoint - scaleLeft);
    let topCentro = (startTopPoint - scaleTop);
    centroCordenadas.style.top = `${topCentro}px`;
    centroCordenadas.style.left = `${leftCentro}px`;
    
    canvas.style.transformOrigin = (leftCentro-centroCamera[0])+"px "+(topCentro-centroCamera[1])+"px";
    
    if (isDragging) {
        atualizarTodasConexoes();
        positionIndicadorInicio();
        positionTodosIndicadoresNenhuma();
    }
});

document.addEventListener("mouseup", () => {
    if (isDraggingPanel) return;
    isDragging = false;
    div.style.cursor = "grab";

    canvasLeft = div.offsetLeft;
    canvasTop = div.offsetTop;

    zoomAtual = scale;
    atualizarTodasConexoes();
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
}

let urlYoutubeInformado = false;
let inputAtivo = null;
const midiaPreviewPorInput = new Map();

function adicionarInteracaoPopup(id) {
    let painel = document.getElementById(id);
    let fileBtn = painel.querySelector("#file-" + id);
    let midiaArea = painel.querySelector(".midia");

    // let img = painel.querySelector(".imgMidia");
    // let vid = painel.querySelector(".vidMidia");
    // let srcVid = painel.querySelector("#srcVidMidia");
    // let vidYoutube = painel.querySelector(".youtubeMidia");
    // let url = document.getElementById("linkYoutube").src;
    // let idYoutube = painel.querySelector("#link-" + id);
    // let iFrameYoutube = painel.querySelector("#srcYoutube");

    const midiaPreview = () => {
        // if (urlYoutubeInformado) {
        //     urlYoutubeInformado = false;
        //     img.style.display = "none";
        //     vid.style.display = "none";
        //     vidYoutube.style.display = "block";
        //     try { vid.pause(); } catch (error) { }
        //     iFrameYoutube.src = "https://www.youtube.com/embed/" + idYoutube.value + "?autoplay=1";
        // } else {
        //     let eVideo = fileBtn.files[0].name.endsWith(".mp4");
        //     if (eVideo) {
        //         img.style.display = "none";
        //         vid.style.display = "block";
        //         vidYoutube.style.display = "none";
        //         document.getElementById("linkYoutube").src = "";
        //         iFrameYoutube.src = "";
        //         idYoutube.value = "";
        //         srcVid.src = URL.createObjectURL(fileBtn.files[0]);
        //         vid.load();
        //     } else {
        //         img.style.display = "block";
        //         vid.style.display = "none";
        //         vidYoutube.style.display = "none";
        //         try { vid.pause(); } catch (error) { }
        //         document.getElementById("linkYoutube").src = "";
        //         iFrameYoutube.src = "";
        //         idYoutube.value = "";
        //         img.src = URL.createObjectURL(fileBtn.files[0]);
        //     }
        // }
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

//----DESENHAR CONEXÃO (LINHA)---------------------------------------------------------------------------
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

    const linha = new LeaderLine(startElem, endElem, {
        color: '#833B8D',
        size: 4,
        path: 'fluid',
        startPlug: 'disc',
        endPlug: 'arrow3',
        startSocket: 'auto',
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

//----FUNÇÃO DE GERAR CONEXÃO INICIAL------------------------------------------------------------------------------------------------
let linhaIndicador = null;

function positionIndicadorInicio() {
    const canvas = document.getElementById('canvas');
    const img = document.getElementById('indicadorInicio');
    const startId = canvas?.dataset?.startId;

    if (!startId || !img || !canvas) {
        if (img) img.style.display = 'none';
        if (linhaIndicador) {
            linhaIndicador.remove();
            linhaIndicador = null;
        }
        return;
    }

    const painel = document.getElementById(startId);
    if (!painel) {
        img.style.display = 'none';
        if (linhaIndicador) {
            linhaIndicador.remove();
            linhaIndicador = null;
        }
        return;
    }

    function reposicionar() {
        const imgHeight = img.offsetHeight || 40; // fallback caso 0
        const top = painel.offsetTop + (painel.offsetHeight / 2) - (imgHeight / 2);
        const left = painel.offsetLeft - 140;

        img.style.top = `${top}px`;
        img.style.left = `${left}px`;
        img.style.display = 'block';

        if (linhaIndicador) {
            linhaIndicador.remove(); // remove a antiga
            linhaIndicador = null;
        }

        linhaIndicador = new LeaderLine(
            img,
            painel,
            {
                color: '#833B8D',
                size: 4,
                path: 'straight',
                startSocket: 'right',
                endSocket: 'left',
                endPlug: 'none'
            }
        );
    }

    requestAnimationFrame(() => {
        reposicionar();
    });
}
//----FUNÇÃO DE GERAR SEM CONEXÃO------------------------------------------------------------------------------------------------
const imagensNenhumaMap = new Map();
const linhasNenhumaMap = new Map();

function positionIndicadorNenhuma(botao) {
    if (!botao) return;

    const infoDiv = botao.querySelector("#buttonInfo");
    const transicao = infoDiv?.getAttribute("transition");

    const canvasContainer = document.querySelector(".canvas-container");
    if (!canvasContainer) return;

    // Remove anteriores
    if (imagensNenhumaMap.has(botao)) {
        imagensNenhumaMap.get(botao).remove();
        imagensNenhumaMap.delete(botao);
    }
    if (linhasNenhumaMap.has(botao)) {
        linhasNenhumaMap.get(botao).remove();
        linhasNenhumaMap.delete(botao);
    }

    // Apenas para transição "" ou "nenhuma"
    if (transicao !== "" && transicao?.toLowerCase() !== "nenhuma") return;

    const template = document.getElementById("indicadorNenhuma");
    if (!template) return;

    const img = template.cloneNode(true);
    img.style.display = "block";
    img.style.position = "absolute";

    const botaoRect = botao.getBoundingClientRect();
    const canvasRect = canvasContainer.getBoundingClientRect();

    const top = (botaoRect.top + botaoRect.height / 2 - 20) - canvasRect.top;
    const left = (botaoRect.left - 70 - 40) - canvasRect.left;

    img.style.top = `${top}px`;
    img.style.left = `${left}px`;

    img.style.transform = `scale(${scale})`;
    img.style.transformOrigin = 'top left';

    canvasContainer.appendChild(img);
    imagensNenhumaMap.set(botao, img);

    // Cria linha
    const linha = new LeaderLine(
        LeaderLine.pointAnchor(img, { x: "100%", y: "50%" }),
        LeaderLine.pointAnchor(botao, { x: "0%", y: "50%" }),
        {
            color: '#833B8D',
            size: 4,
            path: 'straight',
            startSocket: 'right',
            endSocket: 'left',
            endPlug: 'none'
        }
    );

    linhasNenhumaMap.set(botao, linha);
}

function positionTodosIndicadoresNenhuma() {
    document.querySelectorAll(".button_Panel").forEach(botao => {
        positionIndicadorNenhuma(botao);
    });
}

//----FUNÇÃO DE SELECIONAR FORMATO------------------------------------------------------------------------------------------------
function selecionarFormato(elemento) {
    // Remove a seleção anterior de todas as divs de formato
    document.querySelectorAll('.linhas, .blocos, .alternativas').forEach((element) => {
        element.addEventListener('click', function () {
            // Remove a classe 'selecionado' de todas as divs
            document.querySelectorAll('.linhas, .blocos, .alternativas').forEach((el) => {
                el.classList.remove('selecionado');
            });

            // Adiciona a classe 'selecionado' à div clicada
            this.classList.add('selecionado');
        });
    });

}

//----CONFIGURAR BOTÕES------------------------------------------------------------------------------------------
let addBtnBtn = document.getElementById("addButton")

addBtnBtn.onclick = () => {
    let id = painelSelecionado.querySelector(".idPainel").id;
    let tipoFormato = painelSelecionado.querySelector("#layout").classList[0];

    if (tipoFormato == "layout-blocos" && qtdBotoes >= 6) {
        alert("Você não pode adicionar mais botões, o formato de blocos suporta até 6 botões")
        return;
    } else if (tipoFormato == "layout-linhas" && qtdBotoes >= 3) {
        alert("Você não pode adicionar mais botões, o formato de linhas suporta até 3 botões")
        return;
    } else if (tipoFormato == "layout-alternativas" && qtdBotoes >= 4) {
        alert("Você não pode adicionar mais botões, o formato de círculos suporta até 4 botões")
        return;
    }
    qtdBotoes++;
    window.livewire.emit('createButton', { id: id });
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

// 6. Altera cor botão
// let corInput = document.getElementsByClassName("pcr-result")[0];
// window.pickr.on("change", (color) => {
//     clearTimeout(debouceTimer);
//     debouceTimer = setTimeout(() => {
//         window.livewire.emit('updateCor', { id: botaoSelecionado.querySelector(".circulo").id, color: color.toHEXA().toString() })
//     }, 1000);
// });