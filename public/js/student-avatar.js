window.addEventListener('DOMContentLoaded', (event) => {

    const imgPreview = document.getElementById('avatar-preview');
    const inputHidden = document.getElementById('avatar-input');
    const cards = document.querySelectorAll('.card');

    inputHidden.value = imgPreview.src;

    cards.forEach(card => {
        card.addEventListener('click', () => {
            const divCategoria = card.closest('.container');
            const todosOsCardsDaCategoria = divCategoria.querySelectorAll('.card');

            todosOsCardsDaCategoria.forEach( card =>{
                card.style.border = "none";
            });

            card.style.border = "3px solid #911bffff";

            const propriedade = card.dataset.property;
            const valor = card.dataset.value;

            let urlAtual = new URL(imgPreview.src);

            if(valor === 'none'){
                urlAtual.searchParams.delete(propriedade);
                urlAtual.searchParams.set(`${propriedade}Probability`, 0);
            } else
                 if(propriedade === 'beard') {
                 urlAtual.searchParams.set(propriedade, valor);
                 urlAtual.searchParams.set(`${propriedade}Probability`, 100);
            } else{
                urlAtual.searchParams.set(propriedade, valor);
                urlAtual.searchParams.delete(`${propriedade}Probability`);
            }

            const urlFinal = urlAtual.toString();

            imgPreview.src = urlFinal;

            inputHidden.value = urlFinal;

        });
    });

});

/*
function selecionar(cardClicado) {

    const divCategoria = cardClicado.closest('.container');
    const idDivCategoria = divCategoria.id;
    let urlCaracteristicaSelecionada = cardClicado.querySelector('img').src;
    const todosOsCardsDaCategoria = divCategoria.querySelectorAll('.card');
    let urlBase = "https://api.dicebear.com/9.x/toon-head/svg?seed=Luke&backgroundColor=b6e3f4";

    switch (idDivCategoria) {
        case 'cabelo-container':
            urlBase += `&cabelo=${cardClicado.querySelector('img').src}`;
            break;

        case 'cabeloBaixo-container':
            urlCaracteristicaSelecionada = cardClicado.querySelector('img').src;
            break;

        case 'pele-container':
            urlCaracteristicaSelecionada = cardClicado.querySelector('img').src;
            break;

        case 'olhos-container':
            urlCaracteristicaSelecionada = cardClicado.querySelector('img').src;
            break;

        case 'roupas-container':
            urlCaracteristicaSelecionada = cardClicado.querySelector('img').src;
            break;

        case 'barba-container':
            urlCaracteristicaSelecionada = cardClicado.querySelector('img').src;
            break;

        case 'boca-container':
            urlCaracteristicaSelecionada = cardClicado.querySelector('img').src;
            break;

        case 'olhos-container':
            urlCaracteristicaSelecionada = cardClicado.querySelector('img').src;
            break;

        case 'sobrancelhas-container':
            urlCaracteristicaSelecionada = cardClicado.querySelector('img').src;
            break;

        default:
            break;
    }

    todosOsCardsDaCategoria.forEach( card =>{
        card.style.border = "none";
    });

    cardClicado.style.border = "3px solid #911bffff";
    console.log(cardClicado.style.border);
    
}
*/