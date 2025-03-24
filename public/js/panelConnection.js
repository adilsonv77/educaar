//Código da Jaque
//CANVAS INFINITO
let scale = 1;
let alternativeScale = 0;
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

//Código do renan

// Carregar container
let container = document.getElementById("canvas");
let limite = container.getBoundingClientRect();

let painelSelecionado = null;

document.addEventListener("DOMContentLoaded", async () => {
    // Insiste que o zoom seja 100%.
    while (window.devicePixelRatio * 100 !== 100) {
        const resposta = confirm(
            'Para o sistema funcionar adequadamente, não utilize zoom do navegador nesta página! Certifique-se de alterar o seu zoom para "100%" usando os atalhos: "Ctrl +" "Ctrl -"'
        );

        // Espera 2 segundo
        await new Promise((resolve) => setTimeout(resolve, 2000));

        if (!resposta) {
            break;
        }
    }
    limite = container.getBoundingClientRect();
    container.style.height = limite.height + "px";
    container.style.width = limite.width + "px";
});
// Fim carregar container



// Adicionar painel
let addBtn = document.getElementById("addPanel");
let zIndexAtual = 0;

addBtn.addEventListener("click", () => {
    const painel = document.createElement("div");
    painel.className = "painel";
    painel.style.cssText = `
        width: 194px; height: 308px; position: absolute; background: #F8F8F8;
        border: 1px solid #ccc; border-radius: 22px; top: 40000px; left: 40000px;
        box-sizing: border-box; cursor: pointer; box-shadow: -7px 9px 10.2px 0px rgba(0, 0, 0, 0.25);`

    //ADICIONANDO O STYLE DO PAINEL (BÁSICO)
    painel.style.width = "194px";
    painel.style.height = "308px";
    painel.style.position = "absolute"; // Para permitir o arrasto
    painel.style.backgroundColor = "white"; // Cor de fundo
    painel.style.border = "1px solid #ccc"; // Borda do painel
    painel.style.borderRadius = "22px";
    painel.style.top = limiteOriginal.height/2+"px";
    painel.style.left = limiteOriginal.width/2+"px";
    painel.style.boxSizing = "border-box"; // Para incluir padding e border no tamanho total

    // ADICIONANDO O HTML DO PAINAL - BY JAQUE :)
    painel.innerHTML = `
        <textarea name="txtSuperior" id="txtSuperior" maxlength="117"></textarea>
        <div id="espacoMidias">
            <div id="midia" tabindex=0>
                <img class="fileMidia" src="{{ asset('images/FileMidia.svg') }}">
            </div>
                <div id="midiaPreview" edit="false" style="display: none;">
                <video id="vidMidia" controls style="display: none;">
                    <source id="srcVidMidia" src="" type="video/mp4">
                </video>
                <img src="" id="imgMidia" style="display: none;">
            </div>
        </div>
        <div id="areaBtns" class="btn-linhas" style="font-size: 12px;">
            <div class="teste"><div class="circulo"></div> Botão 1</div>
            <div class="teste"><div class="circulo"></div> Botão 2</div>
            <div class="teste"><div class="circulo"></div> Botão 3</div>
        </div>
    `;

    container.appendChild(painel);
    painel.setAttribute("draggable", "true");
    painel.addEventListener("dragstart", (e) => arrastar(e, new Painel(painel)));
    painel.addEventListener("click", () => selecionarPainel(painel));
});

function selecionarPainel(painel) {
    if (painelSelecionado) {
        painelSelecionado.style.border = "1px solid #ccc";
    }
    painelSelecionado = painel;
    painelSelecionado.style.border = "1px solid #FFA600";
}

function alterarFormatoBotoes(formato) {
    if (!painelSelecionado) return;
    let areaBotoes = painelSelecionado.querySelector("#areaBtns");
    if (!areaBotoes) return;

    const layouts = {
        linhas: `
            <div class="teste"><div class="circulo"></div> Botão 1</div>
            <div class="teste"><div class="circulo"></div> Botão 2</div>
            <div class="teste"><div class="circulo"></div> Botão 3</div>
        `,
        blocos: `
            <div class="grid" style="display: flex; justify-content: space-between; flex-wrap: wrap; font-size: 12px;">
                <div class="teste" style="width: 80px; height: 28px;">Botão 1</div>
                <div class="teste" style="width: 80px; height: 28px;">Botão 2</div>
                <div class="teste" style="width: 80px; height: 28px;">Botão 3</div>
                <div class="teste" style="width: 80px; height: 28px;">Botão 4</div>
                <div class="teste" style="width: 80px; height: 28px;">Botão 5</div>
                <div class="teste" style="width: 80px; height: 28px;">Botão 6</div>
            </div>
        `,
        alternativas: `
            <div class="alternativas">
                <div class="teste"><div class="circulo"></div> Opção A</div>
                <div class="teste"><div class="circulo"></div> Opção B</div>
            </div>
        `
    };

    // Remover o círculo e ajustar o tamanho
    if (formato === "blocos") {
        areaBotoes.innerHTML = layouts[formato];
        let botoes = areaBotoes.querySelectorAll(".teste");
        botoes.forEach((botao) => {
            botao.style.width = "80px";
            botao.style.height = "28px";
            botao.querySelector(".circulo").style.display = "none"; // Remove o círculo
        });
    } else {
        areaBotoes.innerHTML = layouts[formato];
    }

    areaBotoes.innerHTML = layouts[formato];
}

document.querySelector(".linhas").addEventListener("click", () => alterarFormatoBotoes("linhas"));
document.querySelector(".blocos").addEventListener("click", () => alterarFormatoBotoes("blocos"));
document.querySelector(".alternativas").addEventListener("click", () => alterarFormatoBotoes("alternativas"));

class Painel {
    constructor(painel) {
        this.newX = 0;
        this.newY = 0;
        this.startX = 0;
        this.startY = 0;
        this.painel = painel;
    }
}

function arrastar(e, painel) {
    zIndexAtual++;
    painel.painel.style.zIndex = zIndexAtual;
    painel.startX = e.clientX;
    painel.startY = e.clientY;
    chamarFuncaoSoltar = (e) => soltar(e, painel);
    document.addEventListener("dragend", chamarFuncaoSoltar);
}

let limiteOriginal = container.getBoundingClientRect();

function soltar(e, painel) {
    // Movimenta para a posição do mouse
    painel.newX = painel.painel.offsetLeft - (painel.startX - e.clientX) / scale;
    painel.newY = painel.painel.offsetTop - (painel.startY - e.clientY) / scale;

    painel.startX = e.clientX;
    painel.startY = e.clientY;

    // Verifica se a posição atual é válida.
    let limitesCaixa = painel.painel.getBoundingClientRect();
    limite = container.getBoundingClientRect();

    //N me pergunte pq o do 20 ou 32, só funciona! O 80000 é o tamanho do canvas
    if (painel.newX + limitesCaixa.width > (limiteOriginal.width+(20*alternativeScale))) {
        painel.newX = (limiteOriginal.width+(20*alternativeScale)) - limitesCaixa.width;
    }
    if (painel.newX < 0) {
        painel.newX = 0;
    }
    if (painel.newY + limitesCaixa.height > (limiteOriginal.height+(32*alternativeScale))) {
        painel.newY = (limiteOriginal.height+(32*alternativeScale)) - limitesCaixa.height;
    }
    if (painel.newY < 0) {
        painel.newY = 0;
    }

    painel.painel.style.top = painel.newY + "px";
    painel.painel.style.left = painel.newX + "px";

    document.removeEventListener("dragend", chamarFuncaoSoltar);
}
// Fim da seção de movimentação do painel
