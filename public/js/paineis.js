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