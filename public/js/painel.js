//  1. ADICIONAR BOTÕES NO PAINEL
let btns = [];
const areaBtn = document.getElementById("areaBtns");
const btnAdd = document.getElementById("add");
btnAdd.onclick = () => {
    if (btns.length != 5) {
        let btn = document.createElement("button");
        btn.type= "button";
        btn.classList = ["btnPainel"];
        btns.push(btn);
        areaBtn.appendChild(btn);
        if (btns.length == 5) btnAdd.style.display = "none";
    }
};
//---------------------------------------------------------------------------------------------------------------------
//  2. TROCAR CONFIGURAÇÕES DO PAINEL
const midiaArea = document.getElementById("midia");
const txtSuperior = document.getElementById("txtSuperior");
const txtInferior = document.getElementById("txtInferior");
let tituloConfig = document.getElementById("tituloSelecionado");
const blocoTxt = document.getElementById("blocoTxt");
const blocoBtn = document.getElementById("blocoBtn");

//Abre o popup quando seleciona a midia
midiaArea.onclick = () => {
    document.getElementById("flex-container").style.display = "flex";
};

txtSuperior.onclick = () => updateConfiguracoesPainel("texto");
txtInferior.onclick = () => updateConfiguracoesPainel("texto");

function updateConfiguracoesPainel(tipo) {
    blocoBtn.style.display = "none";
    blocoTxt.style.display = "none";
    switch (tipo) {
        case "botao":
            //Carrega configs de um botão
            tituloConfig.innerText = "Botão";
            blocoBtn.style.display = "block";
            break;
        case "texto":
            //Seleciona o texto
            txtSuperior.classList = "selecionado";
            //Carrega configs de texto
            tituloConfig.innerText = "Configurações de Texto";
            blocoTxt.style.display = "block";
            break;
    }
}
//---------------------------------------------------------------------------------------------------------------------
//  3. COLOCAR IMAGEM E/OU VIDEO NO PAINEL
const fileBtn = document.getElementById("midiaInput");
const espacoMidias = document.getElementById("espacoMidias");
const mediaPreviewArea = document.getElementById("midiaPreview");
let primeiraVez = true;

// 3.1 Durante EDIÇÃO do painel
if (mediaPreviewArea.getAttribute("edit") == "true") {
    document.addEventListener("DOMContentLoaded", () => {
        mediaPreviewArea.style.display = "flex";
        primeiraVez = false;
        midiaArea.style.display = "none";

        //Coloca os event listener de configuração de painel quando selecionar
        mediaPreviewArea.onclick = () => {
            document.getElementById("flex-container").style.display = "flex";
        };
        Array.from(mediaPreviewArea.children).forEach((child) => {
            child.addEventListener("click", () => {
              document.getElementById("flex-container").style.display = "flex";
            });
        });
    });
}

// 3.2 Uma arquivo novo foi INSERIDO
fileBtn.onchange = (event) => midiaPreview(event);
function midiaPreview(event) {
    if (primeiraVez) {
        //Esconde a seleção do tipo de midia.
        mediaPreviewArea.setAttribute("style", "display: flex");
        primeiraVez = false;
        midiaArea.style.display = "none";

        //Coloca os event listener de configuração de painel quando selecionar
        mediaPreviewArea.onclick = () => {
            document.getElementById("flex-container").style.display = "flex";
        };
        Array.from(mediaPreviewArea.children).forEach((child) => {
            child.addEventListener("click", () => {
              document.getElementById("flex-container").style.display = "flex";
            });
        });
    }

    let img = document.getElementById("imgMidia");
    let vid = document.getElementById("vidMidia");
    let srcVid = document.getElementById("srcVidMidia");

    //Descobre se arquivo inserido é imagem ou vídeo e mostra ou a tag <video> ou a tag <img>  no html
    let eVideo = event.target.files[0].name.endsWith(".mp4"); //É video (true) ou imagem (false)?
    if (eVideo) {
        //É vídeo
        img.style.display = "none";
        vid.style.display = "block";
        srcVid.src = URL.createObjectURL(event.target.files[0]);
        vid.load();
    } else {
        //É imagem
        img.style.display = "block";
        vid.style.display = "none";
        img.src = URL.createObjectURL(event.target.files[0]);
    }
}
