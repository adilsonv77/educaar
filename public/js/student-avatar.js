window.addEventListener('DOMContentLoaded', (event) => {

    const imgPreview = document.getElementById('avatar-preview');
    const inputHidden = document.getElementById('avatar-input');
    const statusSalvamento = document.getElementById('status-salvamento');
    const cards = document.querySelectorAll('.card');

    inputHidden.value = imgPreview.src;

    const updateUrl = inputHidden.dataset.url;
    const csrfToken = inputHidden.dataset.token;

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
            } else if(propriedade === 'beard') {
                 urlAtual.searchParams.set(propriedade, valor);
                 urlAtual.searchParams.set(`${propriedade}Probability`, 100);
            } else {
                urlAtual.searchParams.set(propriedade, valor);
                urlAtual.searchParams.delete(`${propriedade}Probability`);
            }

            const urlFinal = urlAtual.toString();

            imgPreview.src = urlFinal;
            inputHidden.value = urlFinal;

            statusSalvamento.style.display = 'none';

            fetch(updateUrl, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    avatar: urlFinal
                })
            })
            .then(response => {
                if(response.ok) {
                    statusSalvamento.style.display = 'block';
                    
                    setTimeout(() => {
                        statusSalvamento.style.display = 'none';
                    }, 2000);
                } else {
                    console.error('Erro ao salvar o avatar no servidor.');
                }
            })
            .catch(error => {
                console.error('Falha na requisição:', error);
            });

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