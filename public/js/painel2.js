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

//Colocar imagem e/ou video no coisa
const fileBtn = document.getElementById("midiaInput")
const espacoMidias = document.getElementById("espacoMidias")
let primeiraVez = true
fileBtn.onchange = (event) => {
  if (primeiraVez) {
    primeiraVez = false
    midiaArea.style.display = "none";
    espacoMidias.innerHTML += `
    <img src="" id="imgMidia" class="midiaPreview">
    <video id="vidMidia" controls class="midiaApresentada" style="display: none; height: 33.5vh; margin: 4% 0;">
      <source id="srcVidMidia" src="" type="video/mp4">
    </video>`;
  }
  let img = document.getElementById("imgMidia")
  let vid = document.getElementById("vidMidia")
  let srcVid = document.getElementById("srcVidMidia")

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

  setTimeout(() => {
    let widthPainel = document.getElementsByClassName("painel")[0].getBoundingClientRect().width;
    console.log(widthPainel - widthPainel * 0.036);
    if (eVideo) {
      if (vid.getBoundingClientRect().width > widthPainel - widthPainel * 0.036) {
        //Video maior que o permitido.
        alert("video maior")
      }
    } else {
      if (img.getBoundingClientRect().width > widthPainel - widthPainel * 0.036) {
        //Imagem maior que o permitido.
        
      }
    }
  }, 1);
}