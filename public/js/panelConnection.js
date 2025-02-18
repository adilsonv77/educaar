
//Carregar container
let container = document.getElementById("container")
let limite = container.getBoundingClientRect();

document.addEventListener("DOMContentLoaded", async () => {
    // Insiste que o zoom seja 100%.
    while (window.devicePixelRatio * 100 !== 100) {
        const resposta = confirm("Para o sistema funcionar adequadamente, não utilize zoom do navegador nesta página! Certifique-se de alterar o seu zoom para \"100%\" usando os atalhos: \"Ctrl +\" \"Ctrl -\"");

        // Espera 1 segundo
        await new Promise(resolve => setTimeout(resolve, 2000));

        if (!resposta) {
            break;
        }
    }
    limite = container.getBoundingClientRect();
    container.style.height = limite.height + "px";
    container.style.width = limite.width + "px";
})
//Fim carregar container

//Adicionar painel
let addBtn = document.getElementById("addPanel")

let zIndexAtual = 0;

addBtn.addEventListener("click", () => {
    const painel = document.createElement('div');
    painel.id = 'card';
    painel.style.background = gerarCorAleatoria();
    painel.setAttribute('draggable', 'true');
    container.appendChild(painel);

    //Adiciona funções de movimentação
    painel.addEventListener('dragstart', (e) => arrastar(e, new Painel(painel)))
})

class Painel {
    constructor(painel) {
        this.newX = 0;
        this.newY = 0;
        this.startX = 0;
        this.startY = 0;
        this.painel = painel;
    }
}

function gerarCorAleatoria() {
    // Gera um número aleatório entre 0 e 16777215 (0xFFFFFF)
    const corAleatoria = Math.floor(Math.random() * 16777215).toString(16);
    // Retorna a cor no formato hexadecimal, preenchendo com zeros à esquerda, se necessário
    return `#${corAleatoria.padStart(6, '0')}`;
}
//Fim de adicionar painel

//Movimentação de um painel
var chamarFuncaoSoltar;

function arrastar(e, painel) {
    zIndexAtual++;
    painel.painel.style.zIndex = zIndexAtual;
    painel.startX = e.clientX
    painel.startY = e.clientY
    chamarFuncaoSoltar = (e) => soltar(e, painel)
    document.addEventListener('dragend', chamarFuncaoSoltar)
}

function soltar(e, painel) {
    //Movimenta para a posição do mouse
    painel.newX = painel.painel.offsetLeft - (painel.startX - e.clientX)
    painel.newY = painel.painel.offsetTop - (painel.startY - e.clientY)

    painel.startX = e.clientX
    painel.startY = e.clientY

    //Verifica se a posição atual é válida.
    let limitesCaixa = painel.painel.getBoundingClientRect();
    limite = container.getBoundingClientRect();

    if (painel.newX + limitesCaixa.width > limite.right) {
        painel.newX = limite.right - limitesCaixa.width
    }
    if (painel.newX < limite.left) {
        painel.newX = limite.left
    }
    if (painel.newY + limitesCaixa.height > limite.bottom) {
        painel.newY = limite.bottom - limitesCaixa.height
    }
    if (painel.newY < limite.top) {
        painel.newY = limite.top
    }

    painel.painel.style.top = (painel.newY) + 'px'
    painel.painel.style.left = (painel.newX) + 'px'

    document.removeEventListener('dragend', chamarFuncaoSoltar);
}
//Fim da seção de movimentação do painel
