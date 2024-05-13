const menu = document.getElementById("menu");

function mudarLogo() {
    
    const img = document.getElementById("imagem");
    
   
if (caminhoVertical !== img.src) {


    img.src = caminhoVertical;
    img.alt = caminhoVertical;
    
    img.style.width = "";
    img.style.marginLeft = "";
    img.style.marginTop = "";
   
    
} else {
    img.src = caminhoHorizontal;
    img.alt = caminhoHorizontal;
    
    img.style.width = "100%";
    img.style.marginLeft = "23%";
    img.style.marginTop = "0.5%";

    
}



}

mudarLogo();

menu.addEventListener("click", mudarLogo);
