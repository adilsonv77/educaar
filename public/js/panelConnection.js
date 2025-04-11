//----CONFIGURAÇÕES DO CANVAS INFINITO E ZOOM----------------------------------------------------------------------------
let scale = 0.7;
let alternativeScale = 3;
const canvas = document.getElementById("canvas");

document.getElementById("zoom-in").addEventListener("click", () => {
    scale += 0.1; // Aumenta o zoom
    alternativeScale += 1;
    updateCanvasScale();
    todasAsLinhas.forEach(linha => linha.position());
});

document.getElementById("zoom-out").addEventListener("click", () => {
    scale = Math.max(scale - 0.1, 0.1); // Diminui o zoom, mas não permite que fique menor que 0.1
    alternativeScale = Math.max(alternativeScale - 1, -9);
    updateCanvasScale();
    todasAsLinhas.forEach(linha => linha.position());
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

    // Supondo que idPainel seja o ID do banco que você já pegou do backend
    let idPainel = 5; // exemplo

    painel.setAttribute("data-id", idPainel);
    painel.setAttribute("id", `painel-${idPainel}`);


    painel.setAttribute("data-id", idPainel);
    painel.setAttribute("id", `painel-${idPainel}`);


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
            <div class="button_Panel" data-botao="1"><div class="circulo"></div> Botão 1</div>
            <div class="button_Panel" data-botao="2"><div class="circulo"></div> Botão 2</div>
            <div class="button_Panel" data-botao="3"><div class="circulo"></div> Botão 3</div>
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

    //teste
    setTimeout(() => {
        conectarBotoes(5, 1, 17);
    }, 100);



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

    // Impede que cliques no menu lateral troquem a seleção
    if (e.target.closest(".menu-opcoes")) {
        return;
    }

    let painel = e.target.closest(".painel");
    if (painel) {
        selecionarPainel(painel, e);
    } else {
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
            <div class="button_Panel" data-botao="1"><div class="circulo"></div> Botão 1</div>
            <div class="button_Panel" data-botao="2"><div class="circulo"></div> Botão 2</div>
            <div class="button_Panel" data-botao="3"><div class="circulo"></div> Botão 3</div>
        `,
        blocos: `
        <div class="layout-blocos">
            <div class="button_Panel" data-botao="1">Botão 1</div>
            <div class="button_Panel" data-botao="2">Botão 2</div>
            <div class="button_Panel" data-botao="3">Botão 3</div>
            <div class="button_Panel" data-botao="4">Botão 4</div>
            <div class="button_Panel" data-botao="5">Botão 5</div>
            <div class="button_Panel" data-botao="6">Botão 6</div>
        </div>
        `,
        alternativas: `
        <div class="layout-alternativas">
            <div class="botao-circular" data-botao="1">A</div>
            <div class="botao-circular" data-botao="2">B</div>
            <div class="botao-circular" data-botao="3">C</div>
            <div class="botao-circular" data-botao="4">D</div>
        </div>
        `,
    };

    // Remover o círculo e ajustar o tamanho
    if (formato === "blocos") {
        areaBotoes.innerHTML = layouts[formato];
        let botoes = areaBotoes.querySelectorAll(".button_Panel");
        botoes.forEach((botao) => {
            botao.style.width = "calc((100% - 3px) / 2)";
            botao.style.height = "calc((100% - 6px) / 3)";
            botao.querySelector(".circulo").style.display = "none"; // Remove o círculo
        });
    } else {
        areaBotoes.innerHTML = layouts[formato];
    }

    botoes[0].setAttribute("data-botao", "1"); // Exemplo
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
    if (painel.newX + larguraPainel > larguraMax) {
        painel.newX = larguraMax - larguraPainel;
    }
    if (painel.newX < 0) {
        painel.newX = 0;
    }
    if (painel.newY + alturaPainel > alturaMax) {
        painel.newY = alturaMax - alturaPainel;
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
    if (isDraggingPanel) return;

    startX = e.clientX;
    startY = e.clientY;
    startLeft = div.offsetLeft;
    startTop = div.offsetTop;

    isDragging = false; // Inicia como falso

    setTimeout(() => {
        if (!isDraggingPanel) { 
            isDragging = true;
            div.style.cursor = "grabbing";
        }
    }, 100);
});

document.addEventListener("mousemove", (e) => {
    // if (!isDragging || isDraggingPanel) return;
    if (!isDragging) return;
    let deltaX = e.clientX - startX;
    let deltaY = e.clientY - startY;
    div.style.left = `${startLeft + deltaX}px`;
    div.style.top = `${startTop + deltaY}px`;

    todasAsLinhas.forEach(linha => linha.position());
});


document.addEventListener("mouseup", () => {
    // if (isDraggingPanel) return;
    isDragging = false;
    div.style.cursor = "grab";
});

//----DESENHAR CONEXÃO (LINHA)-testes manual---------------------------------------------------------------------------
const todasAsLinhas = [];
function conectarBotoes(idPainelOrigem, numBotao, idPainelDestino) {
    const origem = document.querySelector(`[data-id="${idPainelOrigem}"]`);
    const destino = document.querySelector(`[data-id="${idPainelDestino}"]`);

    if (!origem || !destino) {
        console.warn("Origem ou destino não encontrados.");
        return;
    }

    const botao = origem.querySelector(`.button_Panel[data-botao="${numBotao}"]`);

    if (!botao) {
        console.warn("Botão de origem não encontrado.");
        return;
    }

    const linha = new LeaderLine(
        botao,
        destino,
        {
            color: "#00bfff",
            size: 4,
            path: "fluid",
            startPlug: "disc",
            endPlug: "arrow3"
        }
    );

    todasAsLinhas.push(linha);

    return linha;
}

let line;

function conectarPainel() {
    const start = document.querySelector('[data-id="5"] .button_Panel[data-botao="1"]');
    const end = document.querySelector('[data-id="17"]');

    if (start && end) {
        line = new LeaderLine(start, end);
    }
}

function ativarDrag(painel) {
    let isDragging = false;
    let offsetX, offsetY;

    painel.addEventListener('mousedown', function (e) {
        isDragging = true;
        offsetX = e.clientX - painel.offsetLeft;
        offsetY = e.clientY - painel.offsetTop;
        painel.style.position = 'absolute';
    });

    document.addEventListener('mousemove', function (e) {
        if (isDragging) {
            painel.style.left = (e.clientX - offsetX) + 'px';
            painel.style.top = (e.clientY - offsetY) + 'px';

            todasAsLinhas.forEach(linha => linha.position());
        }
    });

    document.addEventListener('mouseup', function () {
        isDragging = false;
    });
}

