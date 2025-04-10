//----CONFIGURAÇÕES DO CANVAS INFINITO E ZOOM----------------------------------------------------------------------------
let scale = 0.7;
let alternativeScale = 3;
const canvas = document.getElementById("canvas");

document.getElementById("zoom-in").addEventListener("click", () => {
    scale += 0.1; // Aumenta o zoom
    alternativeScale += 1;
    updateCanvasScale();
});

document.getElementById("zoom-out").addEventListener("click", () => {
    scale = Math.max(scale - 0.1, 0.1); // Diminui o zoom, mas não permite que fique menor que 0.1
    alternativeScale = Math.max(alternativeScale - 1, -9);
    updateCanvasScale();
});

function updateCanvasScale() {
    canvas.style.transform = `scale(${scale}) translate(-50%, -50%)`;
}

//----COLOR PICKER----------------------------------------------------------------------------
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
            clear: true,
        },
    },
});

// pickr.on("save", (color) => {
//     console.log("Cor selecionada:", color.toHEXA().toString());
// });

pickr.on("change", (color) => {
    console.log("Cor selecionada:", color.toHEXA().toString());
});

//----ADICIONAR PAINEL----------------------------------------------------------------------------
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
function mostrarMenu(tipo) {
    document.querySelectorAll(".menu-opcoes").forEach(menu => menu.classList.remove("ativo"));

    let menu = document.querySelector(`.${tipo}-opcoes`);
    if (menu) {
        menu.classList.add("ativo");
    
    }
}
//----FUNÇÃO DE SELECIONAR PAINEL, BOTÃO E CANVAS----------------------------------------------------------------------------
function selecionarPainel(painel, e) {
    isDraggingPanel = true;
    if (e.target.closest(".button_Panel")) return;

    if (painelSelecionado) {
        painelSelecionado.classList.remove("selecionado");
    }

    painelSelecionado = painel;
    painelSelecionado.classList.add("selecionado");

    if (botaoSelecionado) {
        botaoSelecionado.classList.remove("selecionado");
    }
    botaoSelecionado = null;

    mostrarMenu("painel"); // Atualiza o menu
}


document.addEventListener("click", (e) => {
    if (e.target.classList.contains("button_Panel")) {
        e.stopPropagation();
        selecionarBotao(e.target);
        return;
    }
    
    let painel = e.target.closest(".painel");
    if (painel) {
        selecionarPainel(painel, e);
    } else if(!(e.target.classList.contains("menu-opcoes"))) {
        selecionarCanvas();
    }
});

function selecionarBotao(botao) {
    isDraggingPanel = true;
    let botoes = document.querySelectorAll(".button_Panel");
    botoes.forEach((btn) => btn.classList.remove("selecionado"));

    botaoSelecionado = botao;
    botao.classList.add("selecionado");

    if (painelSelecionado) {
        painelSelecionado.classList.remove("selecionado");
        painelSelecionado = null;
    }

    mostrarMenu("botao"); // Atualiza o menu
}


function selecionarCanvas() {
    isDraggingPanel = true;
    if (painelSelecionado) {
        painelSelecionado.classList.remove("selecionado");
        painelSelecionado = null;
    }
    if (botaoSelecionado) {
        botaoSelecionado.classList.remove("selecionado");
        botaoSelecionado = null;
    }
    
    document.getElementsByClassName("canvas-container")[0].classList.add("selecionado");

    mostrarMenu("canvas"); // Atualiza o menu
    isDraggingPanel = false;
}


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

    const layouts = {
        linhas: `
            <div class="button_Panel"><div class="circulo"></div> Botão 1</div>
            <div class="button_Panel"><div class="circulo"></div> Botão 2</div>
            <div class="button_Panel"><div class="circulo"></div> Botão 3</div>
        `,
        blocos: `
            <div class="grid" style="display: flex; justify-content: space-between; flex-wrap: wrap; font-size: 12px;">
                <div class="button_Panel" style="width: 80px; height: 28px;">Botão 1</div>
                <div class="button_Panel" style="width: 80px; height: 28px;">Botão 2</div>
                <div class="button_Panel" style="width: 80px; height: 28px;">Botão 3</div>
                <div class="button_Panel" style="width: 80px; height: 28px;">Botão 4</div>
                <div class="button_Panel" style="width: 80px; height: 28px;">Botão 5</div>
                <div class="button_Panel" style="width: 80px; height: 28px;">Botão 6</div>
            </div>
        `,
        alternativas: `
            <div class="alternativas">
                <div class="button_Panel"><div class="circulo"></div> Opção A</div>
                <div class="button_Panel"><div class="circulo"></div> Opção B</div>
            </div>
        `,
    };

    // Remover o círculo e ajustar o tamanho
    if (formato === "blocos") {
        areaBotoes.innerHTML = layouts[formato];
        let botoes = areaBotoes.querySelectorAll(".button_Panel");
        botoes.forEach((botao) => {
            botao.style.width = "80px";
            botao.style.height = "28px";
            botao.querySelector(".circulo").style.display = "none"; // Remove o círculo
        });
    } else {
        areaBotoes.innerHTML = layouts[formato];
    }
    // areaBotoes.innerHTML = layouts[formato];
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
let isDraggingPanel = false;

function arrastar(e, painel) {
    isDraggingPanel = true;
    zIndexAtual++;
    painel.painel.style.zIndex = zIndexAtual;
    painel.startX = e.clientX;
    painel.startY = e.clientY;
    chamarFuncaoSoltar = (e) => soltar(e, painel);
    document.addEventListener("dragend", chamarFuncaoSoltar);
}

function soltar(e, painel) {
    isDraggingPanel = false;

    //Inserir manualmente
    let alturaMax = 80000;
    let larguraMax = 80000;
    let alturaPainel = 462;
    let larguraPainel = 291;

    // Movimenta para a posição do mouse
    painel.newX = painel.painel.offsetLeft - (painel.startX - e.clientX) / scale;
    painel.newY = painel.painel.offsetTop - (painel.startY - e.clientY) / scale;

    painel.startX = e.clientX;
    painel.startY = e.clientY;

    // Verifica se a posição atual é válida.
    if ( painel.newX + larguraPainel > larguraMax) {
        painel.newX =  larguraMax-larguraPainel;
    }
    if (painel.newX < 0) {
        painel.newX = 0;
    }
    if (painel.newY + alturaPainel > alturaMax) {
        painel.newY = alturaMax-alturaPainel;
    }
    if (painel.newY < 0) {
        painel.newY = 0;
    }

    painel.painel.style.top = painel.newY + "px";
    painel.painel.style.left = painel.newX + "px";

    document.removeEventListener("dragend", chamarFuncaoSoltar);
}

//----MOVIMENTAÇÃO CANVAS----------------------------------------------------------------------------
const div = document.getElementById("canvas");
let isDragging = false;
let startX = 0,
    startY = 0;
let startLeft = 0,
    startTop = 0;

div.addEventListener("mousedown", (e) => {
    //Antes de movimentar espera 0.05 segundos para ver se ta movimentando um painel primeiro
    setTimeout(() => {
        if (isDraggingPanel) return;
        isDragging = true;
        startX = e.clientX;
        startY = e.clientY;
        startLeft = div.offsetLeft;
        startTop = div.offsetTop;
        div.style.cursor = "grabbing";
    }, 100);
});

document.addEventListener("mousemove", (e) => {
    if (!isDragging || isDraggingPanel) return;
    let deltaX = e.clientX - startX;
    let deltaY = e.clientY - startY;
    div.style.left = `${startLeft + deltaX}px`;
    div.style.top = `${startTop + deltaY}px`;
});

document.addEventListener("mouseup", () => {
    if (isDraggingPanel) return;
    isDragging = false;
    div.style.cursor = "grab";
});

//----MOSTRAR POPUP QUANDO SELECIONAR------------------------------------------------------------------------------------------------
function fecharPopUp() {
    document.getElementById("flex-container").style.display = 'none';
}

let painelPopup = null;
function abrirPopUp(id) {
    painelPopup = id;

    let painel = document.getElementById(id).parentElement;

    // Define o input file correspondente a este painel
    inputAtivo = painel.querySelector("#file-"+id);

    // Atualiza o atributo "for" da label para apontar pro input atual
    const dropLabel = document.getElementById("upload-area");
    dropLabel.setAttribute("for", "#file-"+id);

    // Abre o pop-up
    document.getElementById("flex-container").style.display = "flex";
}

function adicionarInteracaoPopup(id) {
    let painel = document.getElementById(id).parentElement;
    let fileBtn = painel.querySelector("#file-"+id); //Tem multiplos
    let midiaArea = painel.querySelector(".midia")

    let img = painel.querySelector(".imgMidia");
    let vid = painel.querySelector(".vidMidia");
    let srcVid = painel.querySelector("#srcVidMidia");
    let vidYoutube = painel.querySelector(".youtubeMidia");
    let url = document.getElementById("linkYoutube").src;
    let idYoutube = painel.querySelector("#link-"+id);
    let iFrameYoutube = painel.querySelector("#srcYoutube");
    let urlYoutubeInformado = false;

    fileBtn.onchange = () => midiaPreview();

    //Faz o popup aparecer quando clicar
    midiaArea.onclick = ()=> {abrirPopUp(id)};
    Array.from(midiaArea.children).forEach((child) => {
        child.onclick = ()=> {abrirPopUp(id)};
    });

    //Carrega a imagem no painel
    function midiaPreview() {
        //Descobre se arquivo inserido é imagem ou vídeo ou video youtube e ativa o html correspondente
        if (urlYoutubeInformado) {
            //É vídeo do youtube
            urlYoutubeInformado = false;
            img.style.display = "none";
            vid.style.display = "none";
            vidYoutube.style.display = "block";
            try {
                vid.pause()
            } catch (error) { }
    
            iFrameYoutube.src =
                "https://www.youtube.com/embed/"+idYoutube.value+"?autoplay=1";
        } else {
            let eVideo = fileBtn.files[0].name.endsWith(".mp4"); //É video (true) ou imagem (false)?
            if (eVideo) {
                //É vídeo
                img.style.display = "none";
                vid.style.display = "block";
                vidYoutube.style.display = "none";

                document.getElementById("linkYoutube").src = "";
                iFrameYoutube.src = "";
                idYoutube.value = "";
                srcVid.src = URL.createObjectURL(fileBtn.files[0]);
                vid.load();
            } else {
                //É imagem
                img.style.display = "block";
                vid.style.display = "none";
                vidYoutube.style.display = "none";
                try {
                    vid.pause()
                } catch (error) { }

                document.getElementById("linkYoutube").src = "";
                iFrameYoutube.src = "";
                idYoutube.value = "";
                img.src = URL.createObjectURL(fileBtn.files[0]);
            }
        }
    }

}
let inputAtivo = null;

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
dropArea.addEventListener("dragleave", () => dropArea.classList.remove("dragover"));

// Solta arquivos na área
dropArea.addEventListener("drop", (e) => {
    if (!inputAtivo) return;

    const files = e.dataTransfer.files;

    // Cria um DataTransfer para simular a seleção
    const dataTransfer = new DataTransfer();
    for (const file of files) {
        dataTransfer.items.add(file);
    }
    inputAtivo.files = dataTransfer.files;

    dropArea.classList.remove("dragover");

    // Chama a função de preview (passando o inputAtivo, se quiser adaptar)
    midiaPreview();

    // Fecha o pop-up, se quiser
    document.getElementById("flex-container").style.display = "none";
});

function enviarYoutube() {
    let valor = document.getElementById("linkYoutube").value;
    if (!painelPopup) return;

    const inputHidden = document.getElementById("link-" + painelPopup);
    
    if (inputHidden) {
        inputHidden.value = valor;
    }
}