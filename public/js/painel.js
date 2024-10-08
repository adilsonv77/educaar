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
    if(btns.length==5) btnAdd.style.display = "none"
  }
}

//Trocar configuração de painel
const mediaArea = document.getElementById("media")
const btnImg = document.getElementById("img")
const btnVid = document.getElementById("vid")
const txtSuperior = document.getElementById("textoSuperior")
const txtInferior = document.getElementById("textoInferior")
let tituloConfig = document.getElementById("tituloSelecionado")
let ultimaConfig;
const blocoMedia = document.getElementById("blocoMidia")
const blocoTxt = document.getElementById("blocoTxt")
const blocoBtn = document.getElementById("blocoBtn")

mediaArea.onclick = ()=>updateConfiguracoesPainel("media",null)
btnImg.onclick = ()=>updateConfiguracoesPainel("media",1)
btnVid.onclick = ()=>updateConfiguracoesPainel("media",2)
txtSuperior.onclick = ()=>updateConfiguracoesPainel("texto",1)
txtInferior.onclick = ()=>updateConfiguracoesPainel("texto",2)

function updateConfiguracoesPainel(tipo, identificador) {
  blocoMedia.style.display = "none";
  blocoBtn.style.display = "none";
  blocoTxt.style.display = "none";
  switch(tipo){
    case "media":
      //Carrega configs de media
      tituloConfig.innerText = "Bloco de mídia"
      blocoMedia.style.display = "block";
      switch (identificador) {
        case 1:
          
          break;
        case 2:
          
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
      break;
  }
}
