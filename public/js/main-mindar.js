import * as THREE from 'three';
import { MindARThree } from 'mindar-image-three';

import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';
import { DRACOLoader } from 'three/examples/jsm/loaders/DRACOLoader.js';

import { CSS3DObject } from "three/examples/jsm/renderers/CSS3DRenderer.js"

// variáveis
var buttonAR = null;
var activeScene = null;
var lastActiveScene = null;
var cameraVar = null;
var mindarThree = null;
var isSetup = false;
var bloquear = null;
var desbloquear = null;

// Função para mostrar progresso
function mostrarAvanco(percent) {
  var pb = document.getElementById("progressbar");
  pb.style = "width: " + percent + "%";
}

// Função para carregar GLTF
function loadGLTF(path) {
  return new Promise((resolve, reject) => {
    const loader = new GLTFLoader();
    const dracoLoader = new DRACOLoader();
    dracoLoader.setDecoderPath('https://www.gstatic.com/draco/versioned/decoders/1.5.4/');
    loader.setDRACOLoader(dracoLoader);
    loader.load(path, (gltf) => {
      resolve(gltf);
    }, (xhr) => {
      mostrarAvanco((xhr.loaded / xhr.total * 100));
    });
  });
}

document.addEventListener('DOMContentLoaded', () => {

  function setup() {

  }

  //-------INICIA O AR---------------------------------------------------------------------------------------------------------
  const start = async (carregarAtividades) => {
    isSetup = mindarThree == null;

    const mind = document.getElementById("mind");

    mindarThree = new MindARThree({
      container: document.getElementById("my-ar-container"),
      imageTargetSrc: mind.textContent,
      filterMinCF: 0.0001,
      filterBeta: 0.001
    });

    var { renderer, scene, camera, cssRenderer, cssScene } = mindarThree;
    cameraVar = camera;

    const light = new THREE.HemisphereLight(0xffffff, 0xffffff, Math.PI);
    scene.add(light);

    const helper = new THREE.HemisphereLightHelper(light, 5);
    scene.add(helper);

    const glbs = document.getElementById("glbs");
    var mixer = null;
    var action = null;

    for (var i = 0; i < glbs.childElementCount; i++) {
      var li = glbs.children[i];

      //Verifica se é um painel ou um modelo3d
      const usarModelo = li.getAttribute("scene_id") == 0;

      if (usarModelo) {
        //------ CARREGAR ATIVIDADE DE MODELO 3D ---------------------------------------------------------------------
        const glb = await loadGLTF(li.textContent);

        const glbScene = glb.scene;
        const box = new THREE.Box3().setFromObject(glbScene);
        const sceneSize = box.getSize(new THREE.Vector3());
        const sceneCenter = box.getCenter(new THREE.Vector3());

        // Normaliza e posicionar o objeto
        var maxAxis = Math.max(sceneSize.x, sceneSize.y, sceneSize.z);
        glbScene.scale.multiplyScalar(1.0 / maxAxis);
        box.setFromObject(glbScene);
        box.getCenter(sceneCenter);
        box.getSize(sceneSize);
        glbScene.position.copy(sceneCenter).multiplyScalar(-1);

        const anchor = mindarThree.addAnchor(i);
        anchor.glb = glb;
        anchor.activityid = li.id.split("_")[1];
        anchor.clazz = li.getAttribute("usar_class");
        anchor.group.add(glbScene);

        anchor.onTargetFound = () => {
          // console.log("chegou no targetfound")
          buttonAR.href = buttonAR.dataset.href + "?id=" + anchor.activityid;
          buttonAR.disabled = (anchor.clazz == "#000000"); // criancas.. nao façam isso em casa... tenho que melhorar isso

          var bq = document.getElementById("button-ar");
          bq.style.backgroundColor = anchor.clazz;

          // Para aparecer o buttonAR (das perguntas) quando o target aparecer
          buttonAR.style.display = "block";
          bloquear.style.display = "block";

          if (anchor.glb.animations.length > 0) {
            mixer = new THREE.AnimationMixer(anchor.glb.scene);
            action = mixer.clipAction(anchor.glb.animations[0]);
            action.play();
          }

          // Calcula a caixa delimitadora do objeto para encontrar o centro
          const box = new THREE.Box3().setFromObject(glbScene);
          const center = box.getCenter(new THREE.Vector3());

          // Centraliza o objeto na cena
          glbScene.position.sub(center);

          // Cria uma caixa para conter o objeto
          const container = new THREE.Object3D();
          container.add(glbScene);  // Adiciona o objeto centralizado na caixa

          // Ajusta a rotação da caixa (com o objeto dentro)
          container.rotation.set(0, Math.PI / 2, 0);  // Exemplo de rotação, ajuste conforme necessário

          // Define a cena ativa como a caixa contendo o objeto
          activeScene = container;

          // Adiciona o container (com o objeto centralizado) ao grupo do anchor
          anchor.group.add(container);

          // Torna o objeto visível

          activeScene.visible = true;;

          /*
          Esse código ta comentado a um tempo ele é necessário? -05/05/25

          // Adiciona um listener para o evento de rolagem do mouse
          window.addEventListener('wheel', (event) => {
            event.preventDefault();
            const zoomFactor = 0.1;
  
            // Verifica a direção da rolagem e ajusta a escala do container
            if (event.deltaY < 0) {
                container.scale.multiplyScalar(1 + zoomFactor);
            } else {
                container.scale.multiplyScalar(1 - zoomFactor);
            }
            
            container.scale.clampScalar(0.4, 10);
          });
          */
        };

        anchor.onTargetLost = () => {
          lastActiveScene = activeScene;
          activeScene = null;

          // para esconder os botoes quando o target sair
          buttonAR.style.display = "none";
          bloquear.style.display = "none";
          desbloquear.style.display = "none";

          if (action != null) {
            action.stop();
            action = null;
            mixer = null;
          }
        };
      } else {
        //-------CARREGAR ATIVIDADE DE CENA ---------------------------------------------------------------------
        let scene_id = li.getAttribute("scene_id");
        let sceneHtml = createScene(scene_id, false)

        //Pega aquele elemento HTML criado e liga com o mindAR
        const obj = new CSS3DObject(sceneHtml)

        const cssAnchor = mindarThree.addCSSAnchor(i)
        cssAnchor.group.add(obj)

        cssAnchor.onTargetFound = () => {
          activeScene = obj;
          sceneHtml.style.visibility = 'visible';
          sceneHtml.style.display = "block";

          // Para aparecer o buttonAR (das perguntas) quando o target aparecer
          buttonAR.style.display = "block";
          bloquear.style.display = "block";
        }

        cssAnchor.onTargetLost = () => {
          lastActiveScene = activeScene;
          activeScene = null;

          sceneHtml.visibility = 'hidden';
          sceneHtml.style.display = "none";

          // para esconder o botaoAR quando o target sair
          buttonAR.style.display = "none";
          bloquear.style.display = "none";
          desbloquear.style.display = "none";

          // Pausar vídeo tocando.
          // if (json.midiaExtension == "mp4") {
          //   sceneHtml.getElementsByTagName("video")[0].pause();
          // }
        }
      }

      buttonAR = document.getElementById("button-ar");
      bloquear = document.getElementById("showObject");
      desbloquear = document.getElementById("removeObject");
    }

    if (isSetup) {
      //Desbloquear atividade sendo mostrada
      document.getElementById("showObject").addEventListener('click', () => {
        bloquear.style.display = "none";
        desbloquear.style.display = "block";

        mindarThree.stop();
        scene.background = new THREE.Color(0x00ced1);

        //Se a cena ativa é um painel
        if (activeScene.children.length == 0) {
          //Por algum motivo a tela só desaparece se esperar 1 milésimo de 1 segundo .
          setTimeout(() => {
            activeScene.element.style.visibility = "hidden";
            activeScene.element.style.display = "none";
            try {
              //Caso houver um vídeo, ele precisa ser mutado
              activeScene.element.getElementsByTagName("video")[0].pause()
            } catch (e) {
              //Caso não houver vídeo, não há erros
            }
          }, 1);

          document.getElementById("my-ar-container").style.display = "none";
          document.getElementById("painelContainer").style.display = "flex";
          
          let scene_id = activeScene.element.id;
          scene_id = scene_id.substring(14)
          
          createScene(scene_id, true)
        }
      });

      //Bloquear atividade sendo mostrada
      document.getElementById("removeObject").addEventListener('click', () => {
        desbloquear.style.display = "none";
        buttonAR.style.display = "none";
        document.getElementById("barradeprogresso").style.display = "block";
        document.getElementById("my-ar-container").style.display = "block";
        document.getElementById("painelContainer").style.display = "none";

        document.querySelectorAll('.mindar-ui-overlay').forEach(function (a) {
          a.remove()
        });

        //Exclui todo os paineis carregados, afinal. O sistema chama a função start(), que cria novamente todos.
        if (activeScene.children.length == 0) {
          Array.from(document.getElementsByClassName("scene")).filter((scene) => {
            return !scene.id.includes("lock")
          }).forEach(scene => {
            scene.remove()
          });
          //Exclui o painel bloqueado pra não ficar incomodando e pesando o site
          let painelBloqueado = document.getElementById(activeScene.element.id + "-lock")
          painelBloqueado.remove()
        }

        const startX = async () => {
          start(false);
        }

        startX();

        //mindarThree.scene = null;
        scene.remove(activeScene);
        activeScene.visible = false;
        activeScene = null;
        scene.background = null;
      });

      // Funções de toque
      let touchStart = null;
      let touchStartRotation = { x: 0, y: 0 };
      let touchStartPosition = { x: 0, y: 0 };
      let initialScale = 1;

      const handleTouchStart = (event) => {
        if (activeScene == null)
          return;
        if (event.touches.length === 1) {
          touchStart = event.touches[0];
          touchStartRotation = { x: activeScene.rotation.x, y: activeScene.rotation.y };
          touchStartPosition = { x: touchStart.clientX, y: touchStart.clientY };
        } else if (event.touches.length === 2) {
          touchStart = event.touches;
          initialScale = activeScene ? activeScene.scale.x : 1;
        }
      };

      const handleTouchMove = (event) => {
        if (activeScene == null)
          return;

        if (touchStart) {
          if (event.touches.length === 1) {
            const touchMove = event.touches[0];
            const deltaX = touchMove.clientX - touchStartPosition.x;
            const deltaY = touchMove.clientY - touchStartPosition.y;
            activeScene.rotation.y = touchStartRotation.y + deltaX * 0.01;
            activeScene.rotation.x = touchStartRotation.x + deltaY * 0.01;
          } else if (event.touches.length === 2) {
            const touch1 = event.touches[0];
            const touch2 = event.touches[1];
            const dx = touch2.clientX - touch1.clientX;
            const dy = touch2.clientY - touch1.clientY;
            const distance = Math.sqrt(dx * dx + dy * dy);
            const prevDx = touchStart[1].clientX - touchStart[0].clientX;
            const prevDy = touchStart[1].clientY - touchStart[0].clientY;
            const prevDistance = Math.sqrt(prevDx * prevDx + prevDy * prevDy);
            const scaleFactor = distance / prevDistance;
            activeScene.scale.set(initialScale * scaleFactor, initialScale * scaleFactor, initialScale * scaleFactor);
          }
        }
      };

      const handleTouchEnd = () => {
        touchStart = null;
      };

      document.addEventListener('touchstart', handleTouchStart);
      document.addEventListener('touchmove', handleTouchMove);
      document.addEventListener('touchend', handleTouchEnd);
    }

    let barradeprogresso = document.getElementById("barradeprogresso");
    barradeprogresso.style.display = "none";

    let myarcontainer = document.getElementById("my-ar-container");
    myarcontainer.style.display = "";

    const clock = new THREE.Clock();
    await mindarThree.start();

    var mindarscanning = document.getElementsByClassName("mindar-ui-scanning");
    mindarscanning[0].style.bottom = "120px";

    const getFov = () => {
      return Math.floor(
        (2 * Math.atan(cameraVar.getFilmHeight() / 2 / cameraVar.getFocalLength()) * 180) / Math.PI
      );
    };

    var origFov = getFov();
    renderer.setAnimationLoop(() => {
      if (mixer != null) {
        const delta = clock.getDelta();
        mixer.update(delta);
      }
      if (activeScene == null) {
        cameraVar.fov = origFov;
        cameraVar.updateProjectionMatrix();

        if (lastActiveScene != null) {
          lastActiveScene.rotateY(-lastActiveScene.rotation._y);
          lastActiveScene.rotateX(-lastActiveScene.rotation._x);
          lastActiveScene.rotateZ(-lastActiveScene.rotation._z);
        }
      }
      cssRenderer.render(cssScene, camera);
      renderer.render(scene, camera);
    });

    buttonAR.onclick = () => {
      location.href = buttonAR.href;
    };
  };

  //Inicia o projeto
  start(true);

  document.addEventListener('touchmove', (event) => {
    if (event.scale !== 1) {
      event.preventDefault(); // Impede o zoom normal do navegador por gesto de pinça
    }
  }, { passive: false });
});

//-------CRIA CENA---------------------------------------------------------------------------------------------------------
function createScene(id, bloquearPainel) {
  let scene_info = document.getElementById("scene-" + id)

  //Cria elemento HTML
  const scene = document.createElement('div');
  scene.id = "scene-display-" + id;
  scene.classList = "scene";

  //Define se é painel bloqueado ou do MINDAR
  if (!bloquearPainel) {
    //MINDAR
    scene.style.visibility = 'hidden';
    scene.style.display = "none";
  } else {
    //Bloqueado
    scene.classList.add('painelCelular');
    scene.style.zIndex = "1";
    scene.id = "scene-display-" + id +"-lock";
  }

  let paineis = scene_info.children;
  Array.from(paineis).forEach(painel => {
    let newPanel = createPanel(painel)
    scene.appendChild(newPanel)
  });

  document.getElementById("painelContainer").appendChild(scene);

  return scene;
}

function createPanel(panel_info) {
  let panelInicialId = panel_info.parentElement.getAttribute("start_panel_id")
  let panel = JSON.parse(panel_info.getAttribute("json"))

  //Cria elemento HTML
  const panel_html = document.createElement('div');
  panel_html.id = "panel-display-" + panel.id;
  panel_html.classList.add('painel');
  if (panelInicialId != panel.id) 
    panel_html.style.display = "none";

  //Define qual tipo de midia deve ser mostrado
  let midiaHTML;

  switch (panel.midiaType) {
    case "image":
      midiaHTML = `
        <img class="imgMidia" src="${window.location.origin + '/midiasPainel/' + panel.arquivoMidia}">
      `
      break;
    case "video":
      midiaHTML = `
        <video class="vidMidia" controls>
          <source id="srcVidMidia" src="${window.location.origin + '/midiasPainel/' + panel.arquivoMidia}" type="video/mp4">
        </video>
      `
      break;
    case "youtube":
      midiaHTML = `
        <div class="videoContainer youtubeMidia">
          <iframe 
            id="srcYoutube"
            src="https://www.youtube.com/embed/${panel.link}?autoplay=0"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen>
          </iframe>
        </div>`
      break;
  }

  //Constróio o HTML
  panel_html.innerHTML = `
      <div class="txtPainel">${panel.txt}</div>

      <div class="midia">
        `+ midiaHTML +`
      </div>

      <div class="areaBtns" class="btn-linhas" style="font-size: 12px;">
        <div id="layout" class="layout-`+ panel.btnFormat +`">

        </div>
      </div>
  `;

  let buttons = panel_info.children;
  Array.from(buttons).forEach(button => {
    let newButton = createButton(button,panel.id)
    panel_html.querySelector("#layout").appendChild(newButton)
  });

  return panel_html;
}

function createButton(button_info,panel_id) {
  let button = JSON.parse(button_info.getAttribute("json"))

  const button_html = document.createElement('div');
  button_html.id = "button-display-" + button_info.getAttribute("destination_id");
  button_html.classList.add('button_Panel');
  button_html.style = "border: 1px solid "+button.color;

  button_html.innerHTML=`
    <div class="circulo" style="background-color: `+button.color+`"></div> `+button.text+`
  `

  button_html.onclick = ()=>{
    let panel_loaded = document.getElementById("panel-display-"+panel_id); 
    let panel = document.getElementById("panel-display-"+button_info.getAttribute("destination_id"))

    panel_loaded.style.display="none";
    panel.style.display="block";

    try {
      let panel_lock_loaded = document.getElementById("panel-display-"+panel_id+"-lock");
      let panel_lock = document.getElementById("panel-display-"+button_info.getAttribute("destination_id")+"-lock")

      panel_lock_loaded.style.display="none";
      panel_lock.style.display="block";
    } catch (error) {
      console.log("Não tem painel bloqueado");
    }
  }

  return button_html;
}