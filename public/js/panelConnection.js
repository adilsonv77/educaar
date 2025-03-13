// Carregar container
let container = document.getElementById("canvas");
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
});
// Fim carregar container

// Adicionar painel
let addBtn = document.getElementById("addPanel");
let zIndexAtual = 0;

addBtn.addEventListener("click", () => {
    const painel = document.createElement('div');
    painel.className = 'painel'; // Adiciona a classe painel

    //ADICIONANDO O STYLE DO PAINEL (BÁSICO)
    painel.style.width = '194px';
    painel.style.height = '308px';
    painel.style.position = 'absolute'; // Para permitir o arrasto
    painel.style.backgroundColor = 'white'; // Cor de fundo
    painel.style.border = '1px solid #ccc'; // Borda do painel
    painel.style.padding = '10px'; // Espaçamento interno
    painel.style.boxSizing = 'border-box'; // Para incluir padding e border no tamanho total

    // ADICIONANDO O HTML DO PAINAL - BY JAQUE :)
    painel.innerHTML = `
        <textarea name="txtSuperior" id="txtSuperior" type="text" maxlength="117" placeholder="Digite seu texto aqui"></textarea>
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
        <div id="areaBtns">
            <div class="teste">
                <div class="circulo"></div>
                Teste
            </div>
            <div class="teste">
                <div class="circulo"></div>
                Teste
            </div>
            <div class="teste">
            <div class="circulo"></div>
                Teste
            </div>
            </div>
    `;

    container.appendChild(painel);

    // Adiciona funções de movimentação
    painel.setAttribute('draggable', 'true');
    painel.addEventListener('dragstart', (e) => arrastar(e, new Painel(painel)));
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

function arrastar(e, painel) {
    zIndexAtual++;
    painel.painel.style.zIndex = zIndexAtual;
    painel.startX = e.clientX;
    painel.startY = e.clientY;
    chamarFuncaoSoltar = (e) => soltar(e, painel);
    document.addEventListener('dragend', chamarFuncaoSoltar);
}

function soltar(e, painel) {
    // Movimenta para a posição do mouse
    painel.newX = painel.painel.offsetLeft - (painel.startX - e.clientX);
    painel.newY = painel.painel.offsetTop - (painel.startY - e.clientY);

    painel.startX = e.clientX;
    painel.startY = e.clientY;

    // Verifica se a posição atual é válida.
    let limitesCaixa = painel.painel.getBoundingClientRect();
    limite = container.getBoundingClientRect();

    if (painel.newX + limitesCaixa.width > limite.right) {
        painel.newX = limite.right - limitesCaixa.width;
    }
    if (painel.newX < limite.left) {
        painel.newX = limite.left;
    }
    if (painel.newY + limitesCaixa.height > limite.bottom) {
        painel.newY = limite.bottom - limitesCaixa.height;
    }
    if (painel.newY < limite.top) {
        painel.newY = limite.top;
    }

    painel.painel.style.top = (painel.newY) + 'px';
    painel.painel.style.left = (painel.newX) + 'px';

    document.removeEventListener('dragend', chamarFuncaoSoltar);
}
// Fim da seção de movimentação do painel