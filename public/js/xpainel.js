//  1. ADICIONAR BOTÕES NO PAINEL
let btns = [];
const areaBtn = document.getElementById("areaBtns");
const btnAdd = document.getElementById("add");

const maxBtns = {
    linha: 3,
    bloco: 6,
    alternativa: 4,
};

let tipoAtual = "linha"; // Tipo inicial

btnAdd.onclick = () => {
    if (btns.length < maxBtns[tipoAtual]) {
        let btn = document.createElement("button");
        btn.type = "button";
        btn.classList.add("btnPainel", tipoAtual);
        btns.push(btn);
        areaBtn.appendChild(btn);
        if (btns.length === maxBtns[tipoAtual]) btnAdd.style.display = "none";
    }
};
//---------------------------------------------------------------------------------------------------------------------
//  2. TROCAR CONFIGURAÇÕES DO PAINEL
const midiaArea = document.getElementById("midia");
const txtSuperior = document.getElementById("txtSuperior");
// const txtInferior = document.getElementById("txtInferior");
// let tituloConfig = document.getElementById("tituloSelecionado");
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
            tituloConfig.innerText = "Botão";
            blocoBtn.style.display = "block";
            break;
        case "texto":
            txtSuperior.classList.add("selecionado");
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
                document.getElementById("flex-container").style.display =
                    "flex";
            });
        });
    });
}

// 3.2 Uma arquivo novo foi INSERIDO
let img = document.getElementById("imgMidia");
let vid = document.getElementById("vidMidia");
let srcVid = document.getElementById("srcVidMidia");
let vidYoutube = document.getElementById("videoContainer");
let url = document.getElementById("linkYoutube").src;
let iFrameYoutube = document.getElementById("srcYoutube");
let idYoutube = "";
let urlYoutubeInformado = false;

fileBtn.onchange = () => midiaPreview();

function midiaPreview() {
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
                document.getElementById("flex-container").style.display =
                    "flex";
            });
        });
    }

    //Descobre se arquivo inserido é imagem ou vídeo ou video youtube e ativa o html correspondente
    if (urlYoutubeInformado) {
        //É vídeo do youtube
        urlYoutubeInformado = false;
        img.style.display = "none";
        vid.style.display = "none";
        vidYoutube.style.display = "block";
        try {
            vid.pause()
        } catch (error) { }

        document.getElementById("srcYoutube").src =
            "https://www.youtube.com/embed/" +
            document.getElementById("linkForm").value +
            "?autoplay=1";
    } else {
        let eVideo = fileBtn.files[0].name.endsWith(".mp4"); //É video (true) ou imagem (false)?
        if (eVideo) {
            //É vídeo
            img.style.display = "none";
            vid.style.display = "block";
            vidYoutube.style.display = "none";
            document.getElementById("linkYoutube").src = "";
            document.getElementById("srcYoutube").src = "";
            document.getElementById("linkForm").value = "";
            srcVid.src = URL.createObjectURL(fileBtn.files[0]);
            vid.load();
        } else {
            //É imagem
            img.style.display = "block";
            vid.style.display = "none";
            vidYoutube.style.display = "none";
            try {
                vid.pause()
            } catch (error) { }
            document.getElementById("srcYoutube").src = "";
            document.getElementById("linkYoutube").src = "";
            document.getElementById("linkForm").value = "";
            img.src = URL.createObjectURL(fileBtn.files[0]);
        }
    }
}

// ----- Caso o arquivo for jogado na area de upload -----
const dropArea = document.getElementById("upload-area");
const midiaInput = document.getElementById("midiaInput");

// Clique para abrir o seletor de arquivos
dropArea.addEventListener("click", () => midiaInput.click());

// Evita o comportamento padrão ao arrastar arquivos sobre a página
["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
    dropArea.addEventListener(eventName, (e) => e.preventDefault());
});

// Adiciona a classe de destaque ao arrastar arquivos sobre a área
dropArea.addEventListener("dragover", () => dropArea.classList.add("dragover"));

// Remove a classe ao sair da área
dropArea.addEventListener("dragleave", () =>
    dropArea.classList.remove("dragover")
);

// Lidar com arquivos soltos na área
dropArea.addEventListener("drop", (e) => {
    const files = e.dataTransfer.files;
    midiaInput.files = files; // Define o arquivo no input
    dropArea.classList.remove("dragover");
    midiaPreview();
    document.getElementById("flex-container").style.display = "none";
});

// 3.3 Um link do youtube foi inserido
document.getElementById("linkYoutube").oninput = () => {
    const prefix = "https://www.youtube.com/watch?v=";
    const prefix2 = "https://youtu.be/";
    url = document.getElementById("linkYoutube").value;

    if (url.startsWith(prefix) && url.slice(prefix.length).length == 11) {
        urlYoutubeInformado = true;
        document.getElementById("linkForm").value = url.slice(prefix.length);
        midiaPreview();
        zerarValores();
    } else if (
        url.startsWith(prefix2) &&
        url.slice(prefix2.length).length == 11
    ) {
        urlYoutubeInformado = true;
        document.getElementById("linkForm").value = url.slice(prefix2.length);
        midiaPreview();
        zerarValores();
    }
};

function zerarValores() {
    const inputArquivo = document.querySelector("#midiaInput");
    const novoInput = document.createElement("input");
    novoInput.type = "file";
    novoInput.id = "midiaInput";
    novoInput.name = "arquivoMidia";
    inputArquivo.parentNode.replaceChild(novoInput, inputArquivo);
}
