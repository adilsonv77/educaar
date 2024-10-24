import { GLTFLoader } from 'three/addons/loaders/GLTFLoader';
import { DRACOLoader } from 'three/addons/loaders/DRACOLoader';
import * as THREE from 'three';
import { MindARThree } from 'mindar-image-three';

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

  // Função para iniciar o AR
  const start = async () => {

    isSetup = mindarThree == null;

    const mind = document.getElementById("mind");

    mindarThree = new MindARThree({
      container: document.getElementById("my-ar-container"),
      imageTargetSrc: mind.textContent,
      filterMinCF: 0.0001,
      filterBeta: 0.001
    });

    var { renderer, scene, camera } = mindarThree;
    cameraVar = camera;

    //const light = new THREE.HemisphereLight(0xffffff, 0xbbbbff, 1);
    const light = new THREE.HemisphereLight(0xffffff, 0xffffff, Math.PI);
    scene.add(light);

    const helper = new THREE.HemisphereLightHelper(light, 5)
    scene.add(helper);

    const glbs = document.getElementById("glbs");
    var mixer = null;
    var action = null;

    for (var i = 0; i < glbs.childElementCount; i++) {
      var li = glbs.children[i];
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
        console.log("chegou no targetfound")
        buttonAR.href = buttonAR.dataset.href + "?id=" + anchor.activityid;
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
      
        // para esconder o botaoAR quando o target sair
        buttonAR.style.display = "none";
        bloquear.style.display = "none";
        desbloquear.style.display = "none";
      
        if (action != null) {
          action.stop();
          action = null;
          mixer = null;
        }
      };
      
    }

    buttonAR = document.getElementById("button-ar");
    bloquear = document.getElementById("showObject");
    desbloquear = document.getElementById("removeObject");

    if (isSetup) {
      
      document.getElementById("showObject").addEventListener('click', () => {
        bloquear.style.display = "none";
        desbloquear.style.display = "block";
        mindarThree.stop();
        scene.background = new THREE.Color(0x00ced1);

      });
  
      document.getElementById("removeObject").addEventListener('click', () => {
        desbloquear.style.display = "none";
        buttonAR.style.display = "none";
        document.getElementById("barradeprogresso").style.display = "block";
        //mindarThree.start();
  
        document.querySelectorAll('.mindar-ui-overlay').forEach(function(a){
          a.remove()
        });

        const startX = async () => {
          start();
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
      renderer.render(scene, camera);
    });

    buttonAR.onclick = () => {
      location.href = buttonAR.href;
    };
  };

  start();
  
  

  document.addEventListener('touchmove', (event) => {
    if (event.scale !== 1) {
      event.preventDefault(); // Impede o zoom normal do navegador por gesto de pinça
    }
  }, { passive: false });
});
