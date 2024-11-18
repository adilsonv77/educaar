//Criar buttons no painel
let btns = []
const areaBtn = document.getElementById("areaBtns")
const btnAdd = document.getElementById("add")
btnAdd.onclick = () => {
  if (btns.length != 5) {
    let btn = document.createElement("button")
    btn.classList = ["btnPainel"]
    btns.push(btn)
    areaBtn.appendChild(btn)
    if (btns.length == 5) btnAdd.style.display = "none"
  }
}

//Trocar configuração de painel
const midiaArea = document.getElementById("midia")
const btnImg = document.getElementById("img")
const btnVid = document.getElementById("vid")
const txtSuperior = document.getElementById("txtSuperior")
const txtInferior = document.getElementById("txtInferior")
let tituloConfig = document.getElementById("tituloSelecionado")
let ultimaConfig;
const blocoMedia = document.getElementById("blocoMidia")
const blocoTxt = document.getElementById("blocoTxt")
const blocoBtn = document.getElementById("blocoBtn")

midiaArea.onclick = () => updateConfiguracoesPainel("midia", null)
btnImg.onclick = () => updateConfiguracoesPainel("midia", 1)
btnVid.onclick = () => updateConfiguracoesPainel("midia", 2)
txtSuperior.onclick = () => updateConfiguracoesPainel("texto", 1)
txtInferior.onclick = () => updateConfiguracoesPainel("texto", 2)

function updateConfiguracoesPainel(tipo, identificador) {
  blocoMedia.style.display = "none";
  midiaArea.classList = ""
  txtSuperior.classList = ""
  txtInferior.classList = ""
  blocoBtn.style.display = "none";
  blocoTxt.style.display = "none";
  switch (tipo) {
    case "midia":
      //Seleciona a area de midia
      midiaArea.classList = "selecionado"
      //Carrega configs de midia
      tituloConfig.innerText = "Bloco de mídia"
      blocoMedia.style.display = "block";
      switch (identificador) {
        case 1:
          selectVideo()
          break;
        case 2:
          selectImage()
          break;
      }
      break;
    case "botao":
      //Carrega configs de um botão
      tituloConfig.innerText = "Botão"
      blocoBtn.style.display = "block";
      break;
    case "texto":
      //Carrega configs de texto
      tituloConfig.innerText = "Texto"
      blocoTxt.style.display = "block";
      switch (identificador) {
        case 1:
          txtSuperior.classList = "selecionado"
          break;
        case 2:
          txtInferior.classList = "selecionado"
          break;
      }
      break;
  }
}

//Controle do vídeo
const videoRadio = document.getElementById("midia1")
const imagemRadio = document.getElementById("midia2")
const midia = document.getElementById("midia")
const labelFile = document.getElementById("labelMyFile")

midia.accept = ".png, .jpeg, .jpg, .mp4"

videoRadio.onchange = selectVideo;
imagemRadio.onchange = selectImage;

function selectVideo() {
  midia.accept = ".mp4"
  labelFile.innerText = "Local (somente .mp4)"
}

function selectImage() {
  midia.accept = ".png, .jpeg, .jpg"
  labelFile.innerText = "Local (somente .png, .jpg, .jpeg)"
}

//Colocar imagem e/ou video no painel

const fileBtn = document.getElementById("midiaInput")
const espacoMidias = document.getElementById("espacoMidias")
const mediaPreviewArea = document.getElementById("midiaPreview")
let primeiraVez = true

if (mediaPreviewArea.getAttribute("edit") == "true") {
  document.addEventListener("DOMContentLoaded", () => {
    mediaPreviewArea.style.display = "flex"
    primeiraVez = false
    midiaArea.style.display = "none";

    //Coloca os event listener de configuração de painel quando selecionar
    mediaPreviewArea.onclick = () => updateConfiguracoesPainel("midia", null)
    Array.from(mediaPreviewArea.children).forEach(child => {
      child.addEventListener('click', () => {
        updateConfiguracoesPainel("midia", null)
      });
    });
  })
}


fileBtn.onchange = (event) => midiaPreview(event)
function midiaPreview(event) {
  if (primeiraVez) {
    //Esconde a seleção do tipo de midia.
    mediaPreviewArea.setAttribute('style', 'display: flex')
    primeiraVez = false;
    midiaArea.style.display = "none";

    //Coloca os event listener de configuração de painel quando selecionar
    mediaPreviewArea.onclick = () => updateConfiguracoesPainel("midia", null)
    Array.from(mediaPreviewArea.children).forEach(child => {
      child.addEventListener('click', () => {
        updateConfiguracoesPainel("midia", null)
      });
    });
  }

  let img = document.getElementById("imgMidia")
  let vid = document.getElementById("vidMidia")
  let srcVid = document.getElementById("srcVidMidia")

  //Coloca a tag vídeo se for vídeo a tag img se for imagem
  let eVideo = event.target.files[0].name.endsWith(".mp4"); //É video (true) ou imagem (false)?
  if (eVideo) {
    //É vídeo
    img.style.display = "none"
    vid.style.display = "block"
    srcVid.src = URL.createObjectURL(event.target.files[0]);
  } else {
    //É imagem
    img.style.display = "block"
    vid.style.display = "none"
    img.src = URL.createObjectURL(event.target.files[0]);
  }
}
