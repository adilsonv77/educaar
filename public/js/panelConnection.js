//----CONFIGURAÇÕES DO CANVAS INFINITO E ZOOM------------------------------------------------------
let scale = 0.7;
let alternativeScale = 3;
const canvas = document.getElementById("canvas");

function updateCanvasScale() {
    canvas.style.transform = `scale(${scale}) translate(-50%, -50%)`;
}

function updateCanvasScale() {
    canvas.style.transform = `scale(${scale}) translate(-50%, -50%)`;
    atualizarTodasConexoes();
}

document.getElementById("zoom-in")?.addEventListener("click", () => {
    scale += 0.1;
    alternativeScale += 1;
    updateCanvasScale();
    document.getElementById("resizeZoom").hidden = false;
});

document.getElementById("zoom-out")?.addEventListener("click", () => {
    scale = Math.max(scale - 0.1, 0.1);
    alternativeScale = Math.max(alternativeScale - 1, -9);
    updateCanvasScale();
    document.getElementById("resizeZoom").hidden = false;
});

document.getElementById("resizeZoom").addEventListener("click", () => {
    scale = 0.7;
    updateCanvasScale();
    document.getElementById("resizeZoom").hidden = true;
});

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


// pickr.on("save", (color) => {
//     console.log("Cor selecionada:", color.toHEXA().toString());
// });

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

    let areaBotoes = painelSelecionado.querySelector(".areaBtns");
    console.log("Área de botões encontrada:", areaBotoes);

    if (!areaBotoes) {
        console.warn("O painel carregado do banco pode ter uma estrutura diferente. Verifique o HTML.");
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

//----MOVIMENTAÇÃO PAINEL----------------------------------------------------------------------------
// let isDraggingPanel = false;

// function arrastar(e, painel) {
//     isDraggingPanel = true;
//     zIndexAtual++;
//     painel.painel.style.zIndex = zIndexAtual;
//     painel.startX = e.clientX;
//     painel.startY = e.clientY;
//     chamarFuncaoSoltar = (e) => soltar(e, painel);
//     document.addEventListener("dragend", chamarFuncaoSoltar);
// }

// function soltar(e, painel) {
//     isDraggingPanel = false;

//     //Inserir manualmente
//     let alturaMax = 80000;
//     let larguraMax = 80000;
//     let alturaPainel = 462;
//     let larguraPainel = 291;

//     // Movimenta para a posição do mouse
//     painel.newX = (painel.painel.offsetLeft - (painel.startX - e.clientX)) / scale;
//     painel.newY = (painel.painel.offsetTop - (painel.startY - e.clientY)) / scale;

//     painel.startX = e.clientX;
//     painel.startY = e.clientY;

//     // Verifica se a posição atual é válida.
//     if (painel.newX + larguraPainel > larguraMax) {
//         painel.newX = larguraMax - larguraPainel;
//     }
//     if (painel.newX < 0) {
//         painel.newX = 0;
//     }
//     if (painel.newY + alturaPainel > alturaMax) {
//         painel.newY = alturaMax - alturaPainel;
//     }
//     if (painel.newY < 0) {
//         painel.newY = 0;
//     }

//     painel.painel.style.top = painel.newY + "px";
//     painel.painel.style.left = painel.newX + "px";

//     // Atualiza as coordenadas no banco de dados
//     Livewire.emit('atualizarCoordenadas', painel.painel.id, painel.newX, painel.newY);

//     document.removeEventListener("dragend", chamarFuncaoSoltar);
// }

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

    });

    document.addEventListener("mouseup", function () {
        if (!isDragging) return;
        isDragging = false;
        document.body.style.userSelect = "";

        const x = parseFloat(painelElement.style.left);
        const y = parseFloat(painelElement.style.top);
        const id = parseInt(painelElement.querySelector('.idPainel').id);

        if (!isNaN(id)) {
            console.log("Enviando coordenadas", id, x, y);
            window.livewire.emit("updateCoordinate", id, x, y);
        }
    });
}

//----MOVIMENTAÇÃO CANVAS----------------------------------------------------------------------------
const div = document.getElementById("canvas");
let isDragging = false;
let startX = 0, startY = 0;
let startLeft = 0, startTop = 0;

div.addEventListener("mousedown", (e) => {
    if (e.target.closest(".painel")) return;
    setTimeout(() => {
        if (isDraggingPanel) return;
        isDragging = true;
        startX = e.clientX;
        startY = e.clientY;
        startLeft = div.offsetLeft;
        startTop = div.offsetTop;
        div.style.cursor = "grabbing";
        e.preventDefault();
    }, 100);
});


document.addEventListener("mousemove", (e) => {
    if (!isDragging || isDraggingPanel) return;
    let deltaX = e.clientX - startX;
    let deltaY = e.clientY - startY;
    div.style.left = `${startLeft + deltaX}px`;
    div.style.top = `${startTop + deltaY}px`;
    if (isDragging) {
        atualizarTodasConexoes();
    }
});

document.addEventListener("mouseup", () => {
    if (isDraggingPanel) return;
    isDragging = false;
    div.style.cursor = "grab";
});

//----MOSTRAR POPUP QUANDO SELECIONAR------------------------------------------------------------------------------------------------
function fecharPopUp() {
    document.getElementById("flex-container").style.display = "none";
}

let painelPopup = null;
function abrirPopUp(id) {
    painelPopup = id;

    let painel = document.getElementById(id).parentElement;

    // Define o input file correspondente a este painel
    inputAtivo = painel.querySelector("#file-" + id);

    // Atualiza o atributo "for" da label para apontar pro input atual
    const dropLabel = document.getElementById("upload-area");
    dropLabel.setAttribute("for", "#file-" + id);

    // Abre o pop-up
    document.getElementById("flex-container").style.display = "flex";
}

let urlYoutubeInformado = false;
let inputAtivo = null;
const midiaPreviewPorInput = new Map();

function adicionarInteracaoPopup(id) {
    let painel = document.getElementById(id).parentElement;
    let fileBtn = painel.querySelector("#file-" + id);
    let midiaArea = painel.querySelector(".midia");

    let img = painel.querySelector(".imgMidia");
    let vid = painel.querySelector(".vidMidia");
    let srcVid = painel.querySelector("#srcVidMidia");
    let vidYoutube = painel.querySelector(".youtubeMidia");
    let url = document.getElementById("linkYoutube").src;
    let idYoutube = painel.querySelector("#link-" + id);
    let iFrameYoutube = painel.querySelector("#srcYoutube");

    const midiaPreview = () => {
        if (urlYoutubeInformado) {
            urlYoutubeInformado = false;
            img.style.display = "none";
            vid.style.display = "none";
            vidYoutube.style.display = "block";
            try { vid.pause(); } catch (error) { }
            iFrameYoutube.src = "https://www.youtube.com/embed/" + idYoutube.value + "?autoplay=1";
        } else {
            let eVideo = fileBtn.files[0].name.endsWith(".mp4");
            if (eVideo) {
                img.style.display = "none";
                vid.style.display = "block";
                vidYoutube.style.display = "none";
                document.getElementById("linkYoutube").src = "";
                iFrameYoutube.src = "";
                idYoutube.value = "";
                srcVid.src = URL.createObjectURL(fileBtn.files[0]);
                vid.load();
            } else {
                img.style.display = "block";
                vid.style.display = "none";
                vidYoutube.style.display = "none";
                try { vid.pause(); } catch (error) { }
                document.getElementById("linkYoutube").src = "";
                iFrameYoutube.src = "";
                idYoutube.value = "";
                img.src = URL.createObjectURL(fileBtn.files[0]);
            }
        }
    };

    // vincula o midiaPreview a esse input
    fileBtn.onchange = midiaPreview;
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

//----DESENHAR CONEXÃO (LINHA)-testes manual---------------------------------------------------------------------------
const todasAsLinhas = [];

function atualizarTodasConexoes() {
    todasAsLinhas.forEach(linha => linha.position());
}

function conectarBotoes(idBotao, idPainel) {
    const startElem = document.querySelector(".button_Panel.selecionado");
    const endElem = document.getElementById(idPainel);

    const linha = new LeaderLine(startElem, endElem, {
        color: 'rgba(30, 144, 255, 0.7)',
        size: 4,
        path: 'fluid',
        startPlug: 'disc',
        endPlug: 'arrow3',
        startSocket: 'auto',
        endSocket: 'auto'
    });

    todasAsLinhas.push(linha);
    return linha;
}

//----FUNÇÃO DE SELECIONAR FORMATO------------------------------------------------------------------------------------------------
function selecionarFormato(elemento) {
    // Remove a seleção anterior de todas as divs de formato
    document.querySelectorAll('.linhas, .blocos, .alternativas').forEach((element) => {
        element.addEventListener('click', function() {
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
    window.livewire.emit('createButton', { id: id });
}

// 2. Alterar texto botão
let debouceTimer;

btnTxt.oninput = () => {
    clearTimeout(debouceTimer);
    debouceTimer = setTimeout(() => {
        window.livewire.emit('updateTexto', { id: botaoSelecionado.querySelector(".circulo").id, text: btnTxt.value })
    }, 1000);
}

// 3. Altera transição
selectTransicao.onchange = () => {
    window.livewire.emit('updateTransicao', { id: botaoSelecionado.querySelector(".circulo").id, transition: selectTransicao.value })
}

// 4. Altera o painel de destino
selectPainel.onchange = () => {mudarPainelDestino(botaoSelecionado.querySelector(".circulo").id)};
function mudarPainelDestino(id) {
    alert("alou")
    window.livewire.emit('updatePainelDestino', { id: id, destination_id: selectPainel.value })
}

// 5. Deletar botão
let deleteBtn = document.getElementById("deleteBtn")
deleteBtn.onclick = () => {
    let painel = botaoSelecionado.querySelector(".circulo").parentElement.parentElement.parentElement.parentElement;
    window.livewire.emit('deleteBtn', { id: botaoSelecionado.querySelector(".circulo").id, id_painel: painel.querySelector(".idPainel").id })
}

// 6. Altera cor botão
let corInput = document.getElementsByClassName("pcr-result")[0];

window.pickr.on("change", (color) => {
    clearTimeout(debouceTimer);
    debouceTimer = setTimeout(() => {
        window.livewire.emit('updateCor', { id: botaoSelecionado.querySelector(".circulo").id, color: color.toHEXA().toString() })
    }, 1000);
});