window.addEventListener('DOMContentLoaded', (event) => {

    let avatarState = {
        seed: 'Luke',
        backgroundColor: 'b6e3f4',
    };

    const urlBase = "https://api.dicebear.com/9.x/toon-head/svg?";

    const imgPreview = document.getElementById('avatar-preview');
    const inputHidden = document.getElementById('avatar-input');

    const cards = document.querySelectorAll('.card');

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

            avatarState[propriedade] = valor;

            const parametros = new URLSearchParams(avatarState).toString();
            const urlFinal = urlBase + parametros;

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