let newX = 0, newY = 0, startX = 0, startY = 0;

const card = document.getElementById('card')

card.addEventListener('dragstart', mouseDown)

function mouseDown(e) {
    startX = e.clientX
    startY = e.clientY
    document.addEventListener('dragend', mouseUp)
    document.getElementById("card").style.cursor = "grabbing"
}

function mouseUp(e) {
    //Movimenta para a posição do mouse
    newX = card.offsetLeft - (startX - e.clientX)
    newY = card.offsetTop - (startY - e.clientY)

    startX = e.clientX
    startY = e.clientY

    //Verifica se a posição atual é válida.
    let limite = document.getElementById("container").getBoundingClientRect();
    let limitesCaixa = document.getElementById("card").getBoundingClientRect();

    if(newX+limitesCaixa.width > limite.right){
        newX = limite.right - limitesCaixa.width
    }
    if(newX < limite.left){
        newX = limite.left
    }
    if(newY+limitesCaixa.height > limite.bottom){
        newY = limite.bottom - limitesCaixa.height
    }
    if(newY < limite.top){
        newY = limite.top
    }

    card.style.top = (newY) + 'px'
    card.style.left = (newX) + 'px'
}

