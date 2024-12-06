import { GLTFLoader } from 'three/addons/loaders/GLTFLoader';
import { DRACOLoader } from 'three/addons/loaders/DRACOLoader';
import * as THREE from 'three';
import { MindARThree } from 'mindar-image-three';
import { CSS3DObject } from "three/addons/renderers/CSS3DRenderer.js"

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

  var paineis = {};

  // Função para iniciar o AR
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
        const painelInicial = li.getAttribute("painel")
        const usarModelo = painelInicial == 0;

        //Alterar para 
        if (usarModelo) {
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
            buttonAR.disabled = (anchor.clazz=="#24060e"); // criancas.. nao façam isso em casa
              
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
          //Usar painel
          //Pega dados do banco (guardados dentro da var "json") e faz um objeto JSON.
          const jsonAtribute = li.getAttribute("json")
          const jsonObject = JSON.parse(jsonAtribute)

          //Gera painel HTML
          paineis[jsonObject.id] = jsonObject;
          createPainel(jsonObject, false)

          let painelHtml = document.getElementById(jsonObject.id);

          //Pega aquele elemento HTML criado e liga com o mindAR

          const obj = new CSS3DObject(painelHtml)

          const cssAnchor = mindarThree.addCSSAnchor(i)
          cssAnchor.group.add(obj)

          cssAnchor.onTargetFound = () => {
            activeScene = obj;
            painelHtml.style.visibility = 'visible';
            painelHtml.style.display = "block";

            // Para aparecer o buttonAR (das perguntas) quando o target aparecer
            buttonAR.style.display = "block";
            bloquear.style.display = "block";

            if (jsonObject.midiaExtension == "mp4") {
              painelHtml.getElementsByTagName("video")[0].play();
            }
          }

          cssAnchor.onTargetLost = () => {
            lastActiveScene = activeScene;
            activeScene = null;

            painelHtml.visibility = 'hidden';
            painelHtml.style.display = "none";

            // para esconder o botaoAR quando o target sair
            buttonAR.style.display = "none";
            bloquear.style.display = "none";
            desbloquear.style.display = "none";

            if (jsonObject.midiaExtension == "mp4") {
              painelHtml.getElementsByTagName("video")[0].pause();
            }
          }
        }

        buttonAR = document.getElementById("button-ar");
        bloquear = document.getElementById("showObject");
        desbloquear = document.getElementById("removeObject");
      }
      if (isSetup) {

        document.getElementById("showObject").addEventListener('click', () => {
          bloquear.style.display = "none";
          desbloquear.style.display = "block";

          mindarThree.stop();
          scene.background = new THREE.Color(0x00ced1);

          //Verifica se é um objeto 3D ou um painel
          if (activeScene.children.length != 0) {
            //modelo 3D
          } else { 
            //painel 
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
            //Remove o containerAR
            document.getElementById("my-ar-container").style.display = "none";

            createPainel(paineis[activeScene.element.id], true)
          }

        });

        document.getElementById("removeObject").addEventListener('click', () => {
          desbloquear.style.display = "none";
          buttonAR.style.display = "none";
          document.getElementById("barradeprogresso").style.display = "block";
          document.getElementById("my-ar-container").style.display = "block";
          //mindarThree.start();

          document.querySelectorAll('.mindar-ui-overlay').forEach(function (a) {
            a.remove()
          });

          //Verifica se é um objeto 3D ou um painel
          if (activeScene.children.length != 0) {
            //modelo 3D
          } else {
            //painel
            //Exclui todo os paineis carregados, afinal. O sistema chama a função start(), que cria novamente todos.
            Array.from(document.getElementsByClassName("painel")).filter((painel) => {
              return !painel.id.includes("lock")
            }).forEach(painel => {
              painel.remove()
            });
            //Exclui o painel bloqueado pra não ficar incomodando e pesando o site
            let painelBloqueado = document.getElementById(activeScene.element.id + "lock")
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

//Função para criar um painel
function createPainel(painel, bloquearPainel) {
  const container = document.createElement('div');
  container.classList.add('painel');
  if (!bloquearPainel) {
    //Painel criado para ser mostrado em AR
    container.id = painel.id;
    container.style.visibility = 'hidden';
    container.style.display = "none";
  } else {
    //Painel criado para ser mostrado quando o painel é bloqueado
    container.classList.add('painelCelular');
    container.style.zIndex = "1";
    container.id = painel.id + 'lock';
  }
  var midiaHTML;
  if (painel.midiaExtension == "mp4") {
    //Vídeo
    midiaHTML = `
      <video controls class="midiaApresentada">
        <source src="${window.location.origin + '/midiasPainel/' + painel.arquivoMidia}" type="video/mp4">
      </video>
    `
  } else {
    //Imagem
    midiaHTML = `
      <img class="midiaApresentada" src="${window.location.origin + '/midiasPainel/' + painel.arquivoMidia}">
    `
  }
  container.innerHTML = `
      <textarea name="txtSuperior" id="txtSuperior" type="text" maxlength="117" placeholder="Digite seu texto aqui" disabled>
          `+ painel.txtSuperior + `
      </textarea>
      <div id="midiaPreview">
        `+ midiaHTML + `
      </div>
      <textarea name="txtInferior" id="txtInferior" type="text" maxlength="117" placeholder="Digite seu texto aqui" disabled>
          `+ painel.txtInferior + `
      </textarea>
      <div id="areaBtns">
        
      </div>
  `;
  document.getElementById("painelContainer").appendChild(container);
}