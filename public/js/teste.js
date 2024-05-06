const menu = document.getElementById("marcio");

function metodoTeste() {
    
    const img = document.getElementById("imagem");
    console.log(img.src.match(caminhoHorizontal));
    if (img.src.match(caminhoHorizontal)) {
        img.src = caminhoHorizontal;
        img.alt = caminhoHorizontal;
        
    } else {
        img.src = caminhoVertical;
        img.alt = caminhoVertical;
    }


}

menu.addEventListener("click", metodoTeste);
