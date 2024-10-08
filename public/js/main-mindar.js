import { GLTFLoader } from 'three/addons/loaders/GLTFLoader';
import { DRACOLoader } from 'three/addons/loaders/DRACOLoader';
import * as THREE from 'three';
import { MindARThree } from 'mindar-image-three';


// variáveis
var buttonAR = null;
var activeScene = null;
var lastActiveScene = null;
var cameraVar = null;
let manterObjeto = false;
let activeScenes = [];
let currentPosition = null;
let currentRotation = null;
let currentScale = null;


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


  // Função para iniciar o AR
  const start = async () => {
    buttonAR = document.getElementById("button-ar");
    const mind = document.getElementById("mind");


    const mindarThree = new MindARThree({
      container: document.getElementById("my-ar-container"),
      imageTargetSrc: mind.textContent,
      filterMinCF: 0.0001,
      filterBeta: 0.001
    });


    const { renderer, scene, camera } = mindarThree;
    cameraVar = camera;


    const light = new THREE.HemisphereLight(0xffffff, 0xbbbbff, 1);
    scene.add(light);


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

      let foiCadeado = false;

      document.getElementById("showObject").addEventListener('click', () => {
        manterObjeto = true;
        foiCadeado = true;
        console.log("Objeto fixado na tela.");
        // if (activeScene) {
        //   fixObjectPosition(activeScene);
        // }
      });


      document.getElementById("removeObject").addEventListener('click', () => {
        manterObjeto = false;
        if (foiCadeado) {
          if (activeScene) {  // Remover o objeto da cena
            


            console.log("Marcador perdido, mas o objeto permanece na cena.");


            // para esconder o botãoAR quando o target sair
            buttonAR.style.display = "none";


            if (action != null) {
              action.stop();
              action = null;
              mixer = null;
            }

            foiCadeado = !foiCadeado;
            activeScene.visible = false;
            scene.remove(activeScene);
            lastActiveScene = activeScene;
            activeScene = null;
            console.log("Objeto removido da tela.");

            
          } else {
            console.log("Nenhum objeto ativo para remover.");
          }

        }
      });


      anchor.onTargetFound = () => {
        if (manterObjeto) {
          // if(activeScene){
          //   fixObjectPosition(activeScene);
          // }
          // Exibe o botão e o modelo
          buttonAR.href = buttonAR.dataset.href + "?id=" + anchor.activityid;
          var bq = document.getElementById("button-ar");
          bq.style.backgroundColor = anchor.clazz;
          buttonAR.style.display = "block";


          if (anchor.glb.animations.length > 0) {
            mixer = new THREE.AnimationMixer(anchor.glb.scene);
            action = mixer.clipAction(anchor.glb.animations[0]);
            action.play();
          }


          // Centraliza e ajusta o modelo na cena
          activeScene = glbScene;
          const box = new THREE.Box3().setFromObject(activeScene);
          const center = box.getCenter(new THREE.Vector3());
          activeScene.position.sub(center);
          activeScene.rotation.set(0, 0, 0); // Mantido apenas um ajuste de rotação


          // Mantém o objeto na posição atual quando o marcador some
          fixObjectPosition(activeScene); // Atualiza a referência para o objeto correto
        } else {
          buttonAR.href = buttonAR.dataset.href + "?id=" + anchor.activityid;
          var bq = document.getElementById("button-ar");
          bq.style.backgroundColor = anchor.clazz;


          // Para aparecer o buttonAR (das perguntas) quando o target aparecer
          buttonAR.style.display = "block";


          if (anchor.glb.animations.length > 0) {
            mixer = new THREE.AnimationMixer(anchor.glb.scene);
            action = mixer.clipAction(anchor.glb.animations[0]);
            action.play();
          }


          // Ajusta a rotação do objeto para garantir que fique reto
          activeScene = glbScene;


          // Centralizar o objeto no centro da cena (evitar desalinhamentos)
          const box = new THREE.Box3().setFromObject(activeScene);
          const center = box.getCenter(new THREE.Vector3());
          activeScene.position.sub(center);  // Centraliza o objeto


          // Ajustar a rotação para garantir que o objeto fique reto
          activeScene.rotation.set(0, Math.PI / 2, 0);  // Rotação fixa, ajuste conforme necessário


          // Alternativa: Zera a rotação e depois aplica uma correção se necessário
          activeScene.rotation.set(0, 0, 0);  // Zera a rotação em todos os eixos*/
        }
      };
      // anchor.onTargetFound = () => {
      //   console.log("chegou no target found");
      //   // if (manterObjeto) {
      //   //   if(activeScene){
      //   //     fixObjectPosition(activeScene);
      //   //   }
      //   //   // Exibe o botão e o modelo
      //   //   buttonAR.href = buttonAR.dataset.href + "?id=" + anchor.activityid;
      //   //   var bq = document.getElementById("button-ar");
      //   //   bq.style.backgroundColor = anchor.clazz;
      //   //   buttonAR.style.display = "block";


      //   //   if (anchor.glb.animations.length > 0) {
      //   //     mixer = new THREE.AnimationMixer(anchor.glb.scene);
      //   //     action = mixer.clipAction(anchor.glb.animations[0]);
      //   //     action.play();
      //   //   }


      //   //   // // Centraliza e ajusta o modelo na cena
      //   //   // activeScene = glbScene;
      //   //   // const box = new THREE.Box3().setFromObject(activeScene);
      //   //   // const center = box.getCenter(new THREE.Vector3());
      //   //   // activeScene.position.sub(center);
      //   //   // activeScene.rotation.set(0, 0, 0); // Mantido apenas um ajuste de rotação


      //   //   // // Mantém o objeto na posição atual quando o marcador some
      //   //   // fixObjectPosition(activeScene); // Atualiza a referência para o objeto correto


      //   //   const newScene = glbScene.clone();
      //   // } else {
      //   buttonAR.href = buttonAR.dataset.href + "?id=" + anchor.activityid;
      //   var bq = document.getElementById("button-ar");
      //   bq.style.backgroundColor = anchor.clazz;


      //   // Para aparecer o buttonAR (das perguntas) quando o target aparecer
      //   buttonAR.style.display = "block";


      //   if (anchor.glb.animations.length > 0) {
      //     mixer = new THREE.AnimationMixer(anchor.glb.scene);
      //     action = mixer.clipAction(anchor.glb.animations[0]);
      //     action.play();
      //   }


      //   // Ajusta a rotação do objeto para garantir que fique reto
      //   activeScene = glbScene;
       
      //   // Centralizar o objeto no centro da cena (evitar desalinhamentos)
      //   const box = new THREE.Box3().setFromObject(activeScene);
      //   const center = box.getCenter(new THREE.Vector3());
      //   activeScene.position.sub(center);  // Centraliza o objeto


      //   // Ajustar a rotação para garantir que o objeto fique reto
      //   activeScene.rotation.set(0, Math.PI / 2, 0);  // Rotação fixa, ajuste conforme necessário


      //   // Alternativa: Zera a rotação e depois aplica uma correção se necessário
      //   activeScene.rotation.set(0, 0, 0);  // Zera a rotação em todos os eixos*/
      //   // }


      // };
      // function fixObjectPosition(object) {
      //   if (!object) {
      //     console.log("não há objeto");
      //   }
      //   // Armazena a posição, rotação e escala atuais
      //   currentPosition = object.position.clone();
      //   currentRotation = object.rotation.clone();
      //   currentScale = object.scale.clone();
      // }

        //   if (object) {
        //     const distanceFromCamera = 2; // Ajuste conforme necessário
        //     const cameraDirection = new THREE.Vector3();
        //     cameraVar.getWorldDirection(cameraDirection);
        //     const newPosition = new THREE.Vector3().copy(cameraVar.position).add(cameraDirection.multiplyScalar(distanceFromCamera));
        //     object.position.copy(newPosition);
        //     object.lookAt(cameraVar.position);
        //   }
        // object.position.copy(currentPosition);
        // object.rotation.copy(currentRotation);
        // object.scale.copy(currentScale);
      // }










      /*anchor.onTargetLost = () => {
        lastActiveScene = activeScene;
        activeScene = null;


        console.log("Marcador perdido, mas o objeto permanece na cena.");
     
        // para esconder o botãoAR quando o target sair
        buttonAR.style.display = "none";
     
        if (action != null) {
          action.stop();
          action = null;
          mixer = null;
        }
      };*/


      anchor.onTargetLost = () => {
        if(manterObjeto){
        // Fixa a posição, rotação e escala atuais
        object.position.copy(currentPosition);
        object.rotation.copy(currentRotation);
        object.scale.copy(currentScale);


        // Remove a âncora, o objeto fica na cena sem depender da detecção do marcador
        scene.add(object); // Adiciona diretamente à cena para manter o objeto visível
        }else{
          //Remove o objeto da cena se `manterObjeto` for `false`
          lastActiveScene = activeScene;
          activeScene = null;


          console.log("Marcador perdido, mas o objeto permanece na cena.");


          // para esconder o botãoAR quando o target sair
          buttonAR.style.display = "none";


          if (action != null) {
            action.stop();
            action = null;
            mixer = null;
          }
          scene.remove(lastActiveScene);
        }
      };


      // anchor.onTargetLost = () => {
      //   if (manterObjeto && activeScene) {
         
      //     // Fixa a posição, rotação e escala atuais
      //     activeScene.position.copy(currentPosition);
      //     activeScene.rotation.copy(currentRotation);
      //     activeScene.scale.copy(currentScale);


      //     // Pegar as dimensões da câmera e projetar o objeto no centro da tela
      //     // const distanceFromCamera = 2; // Ajuste essa distância conforme o tamanho do objeto e o campo de visão desejado


      //     // // Centralizar o objeto à frente da câmera
      //     // const cameraDirection = new THREE.Vector3();
      //     // cameraVar.getWorldDirection(cameraDirection);  // Pega a direção em que a câmera está apontando


      //     // // Definir a nova posição do objeto à frente da câmera
      //     // const newPosition = new THREE.Vector3().copy(cameraVar.position).add(cameraDirection.multiplyScalar(distanceFromCamera));
      //     // object.position.copy(newPosition);


      //     // // Fazer o objeto "olhar" para a câmera, garantindo que fique bem orientado
      //     // object.lookAt(cameraVar.position);


      //     // // Manter a escala original do objeto (ou ajustar conforme necessário)
      //     let x = currentScale.x;
      //     let y = currentScale.y;
      //     let z = currentScale.z;


      //     console.log(x);
      //     console.log(y);
      //     console.log(z);


      //     let xP = currentPosition.x;
      //     let yP = currentPosition.y;
      //     let zP = currentPosition.z;


      //     console.log(currentPosition);


      //     let xR = currentRotation.x;
      //     let yR = currentRotation.y;
      //     let zR = currentRotation.z;


      //     // console.log("Escala antes de remover set(1,1,1):", activeScene.scale);
      //     // activeScene.scale.set(x, y, p);
      //     // activeScene.position.set(xP, yP, zP);
      //     // activeScene.rotation.set(xR, yR, zR);
      //     // activeScene.position.set(0, 0, 0);
      //     activeScene.scale.set(1, 1, 1);
      //     activeScene.position.set(xP, yP, zP);
      //     activeScene.rotation.set(xR, yR, zR);


      //     // activeScene.position.set(xP, yP, 100);
      //     // activeScene.rotation.set(currentRotation);
      //     // activeScene.position.set(currentPosition);
      //     // Remove a âncora, o objeto fica na cena sem depender da detecção do marcador
      //     scene.add(activeScene); // Adiciona diretamente à cena para manter o objeto visível


      //   } else {
      //     // Remove o objeto da cena se `manterObjeto` for `false`
      //     lastActiveScene = activeScene;
      //     activeScene = null;


      //     console.log("Marcador perdido, mas o objeto permanece na cena.");


      //     // para esconder o botãoAR quando o target sair
      //     buttonAR.style.display = "none";


      //     if (action != null) {
      //       action.stop();
      //       action = null;
      //       mixer = null;
      //     }
      //     scene.remove(lastActiveScene);
      //   }


      //   // Oculta o botão quando o objeto não está sendo mantido
      //   buttonAR.style.display = "none";
      // };


    }


    // Funções de toque
    let touchStart = null;
    let touchStartRotation = { x: 0, y: 0 };
    let touchStartPosition = { x: 0, y: 0 };
    let initialScale = 1;


    const handleTouchStart = (event) => {
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
