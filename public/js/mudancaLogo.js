const menu = document.getElementById("menu");

function mudarLogo() {
    
    const img = document.getElementById("imagem");

    if (caminhoVertical != img.src) {
        img.src = caminhoVertical;
        img.alt = caminhoVertical;
    } else {
        // style="margin-left:23%;margin-top:0.5%"
        img.src = caminhoHorizontal;
        img.alt = caminhoHorizontal;
        img.style.width = "120";
        img.style.marginLeft = "23%";
        img.style.marginTop = "0.5%";
    }


}

menu.addEventListener("click", mudarLogo);
