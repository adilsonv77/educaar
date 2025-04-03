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

addBtn.addEventListener("click", () => {
    const painel = document.createElement("div");
    painel.className = "painel";
    painel.innerHTML = `          
        <!--Texto do painel-->
        <div class="txtPainel"></div>
        <input type="hidden" class="inputTxtPainel" name="txt" value="">
        <!--Midia do painel-->
        <div class="midia">
            <!--1. Não informado-->
            <div class="no_midia" tabindex=0>
                <img class="fileMidia" src="${window.location.origin}/images/FileMidia.svg">
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
        <div class="areaBtns" class="btn-linhas" style="font-size: 12px;">
            <div class="button_Panel"><div class="circulo"></div> Botão 1</div>
            <div class="button_Panel"><div class="circulo"></div> Botão 2</div>
            <div class="button_Panel"><div class="circulo"></div> Botão 3</div>
        </div>
        <!--Informações do painel-->
        <input type="hidden" name="midiaExtension" value="">
        <input type="hidden" name="arquivoMidia" value="">
    `;

    container.appendChild(painel);
    painel.setAttribute("draggable", "true");
    painel.addEventListener("dragstart", (e) =>
        arrastar(e, new Painel(painel))
    );
    painel.addEventListener("click", (e) => selecionarPainel(painel, e));
});

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