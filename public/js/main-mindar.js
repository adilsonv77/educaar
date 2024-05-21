

import { GLTFLoader } from 'three/addons/loaders/GLTFLoader';

import * as THREE from 'three';
import { TrackballControls } from 'three/addons/controls/TrackballControls.js';

import { MindARThree } from 'mindar-image-three';

//const THREE = window.MINDAR.IMAGE.THREE;
var buttonAR = null;
var buttonsAR = [];
var activeScene = null;
var lastActiveScene = null;
var cameraVar = null;
var qtosBotoes = 0;

// inspirado no OrbitControl
var state = 0;  // 1 - rotacionar
var rotateStart = new THREE.Vector2();

function loadGLTF(path) {
  return new Promise((resolve, reject) => {
    const loader = new GLTFLoader();
    loader.load(path, (gltf) => {
      resolve(gltf);
    });
  });
}

document.addEventListener('DOMContentLoaded', () => {
  
  
  function onMouseDownScene(event) { 
    event.preventDefault();
  
    if (event.button == 0) { // botao esquerda
      rotateStart.set( event.clientX, event.clientY );
      state = 1;
    }
    console.log("onmousedown"); 
  };

  function hideButtons() {
    for (var i = 0; i < qtosBotoes; i++) {
      buttonsAR[i].style.display = "none";
    }
  }
  
  function showButtons() {
    for (var i = 0; i < qtosBotoes; i++) {
      buttonsAR[i].style.display = "";
    }
  }
  
  const start = async() => {

      // inicializa o array
      buttonsAR[0] = document.getElementById("buttonQuestion");
      buttonsAR[1] = document.getElementById("buttonRotateX");
      buttonsAR[2] = document.getElementById("buttonRotateX_");
      buttonsAR[3] = document.getElementById("buttonRotateY");
      buttonsAR[4] = document.getElementById("buttonRotateY_");
      buttonsAR[5] = document.getElementById("buttonZoomIn");
      buttonsAR[6] = document.getElementById("buttonZoomOut");

      qtosBotoes = buttonsAR.length;

      hideButtons();

      buttonAR = document.getElementById("button-ar");

      const mind = document.getElementById("mind");


      const mindarThree = new MindARThree({

        container: document.getElementById("my-ar-container"),
        imageTargetSrc: mind.textContent,
        filterMinCF: 0.0001,
        filterBeta: 0.001
      });
 
      const {renderer, scene, camera} = mindarThree;

      cameraVar = camera;

      const light = new THREE.HemisphereLight( 0xffffff, 0xbbbbff, 1 );
      scene.add(light);
      
      const glbs = document.getElementById("glbs");

      var mixer = null;
      var action = null;

      for (var i = 0; i<glbs.childElementCount; i++) {
        var li = glbs.children[i];
        
        const glb = await loadGLTF(li.textContent);

        const glbScene = glb.scene;
  
        const box = new THREE.Box3().setFromObject(glbScene);
        const sceneSize = box.getSize(new THREE.Vector3());
        const sceneCenter = box.getCenter(new THREE.Vector3());
  
        
        //Rescale the object to normalized space
        var maxAxis = Math.max(sceneSize.x, sceneSize.y, sceneSize.z);
        glbScene.scale.multiplyScalar(1.0 / maxAxis);
        box.setFromObject(glbScene);
        box.getCenter(sceneCenter);
        box.getSize(sceneSize);
        //Reposition to 0,halfY,0
        glbScene.position.copy(sceneCenter).multiplyScalar(-1);

        const anchor = mindarThree.addAnchor(i);
        anchor.glb = glb;
        anchor.activityid = li.id.split("_")[1];
        anchor.clazz = li.getAttribute("usar_class");
        anchor.group.add(glbScene);
    
        anchor.onTargetFound = () => {
            buttonAR.href = buttonAR.dataset.href + "?id=" + anchor.activityid;

            var bq = document.getElementById("button_question");
            bq.style.backgroundColor = anchor.clazz;

            if (anchor.glb.animations.length > 0) {

              mixer = new THREE.AnimationMixer(anchor.glb.scene);
              action = mixer.clipAction(anchor.glb.animations[0]);
              action.play();
              
            }

            activeScene = glbScene;
            showButtons();

        }
        
        //anchor.addEventListener
        anchor.onTargetLost = () => {

          lastActiveScene = activeScene;
          activeScene = null;
          hideButtons();
          if (action != null) {
            action.stop();
            action = null;
            mixer = null;
  
          }
        }
  
      }


      const clock = new THREE.Clock();
      await mindarThree.start();

      // essas duas linhas servem para o painel nao sobrepor os botões
      var mindarscanning = document.getElementsByClassName("mindar-ui-scanning");
      mindarscanning[0].style.bottom = "120px";


      const getFov = () => {
        return Math.floor(
          (2 *
            Math.atan(cameraVar.getFilmHeight() / 2 / cameraVar.getFocalLength()) *
            180) /
            Math.PI
        );
      };

      var deltaTotal = 0;
      var origFov = getFov();
      renderer.setAnimationLoop(() => {
        if (mixer != null) {
          const delta = clock.getDelta();
          mixer.update(delta);
        }
        if (activeScene == null) {
          cameraVar.fov = origFov;
          cameraVar.updateProjectionMatrix();

          deltaTotal = 0;

          if (lastActiveScene != null) {
            lastActiveScene.rotateY(-lastActiveScene.rotation._y); 
            lastActiveScene.rotateX(-lastActiveScene.rotation._x); 
            lastActiveScene.rotateZ(-lastActiveScene.rotation._z); 
          }
            
        }
        renderer.render(scene, camera);

      });

      const fRotateY = (dir) => {
        if (activeScene != null) {
          const delta = clock.getDelta();
          deltaTotal += delta;

          if (deltaTotal >= 0.05) {
            //activeScene.rotateY(0.1); //rotateOnAxis rotateOnWorldAxis
            activeScene.rotateOnWorldAxis(new THREE.Vector3(0,1,0), dir);
            deltaTotal = 0;
          }
        }
      };
      
      const bRotateY = document.getElementById("b_rotate_y");
      bRotateY.onclick = () => {
        fRotateY(0.1);
      }

      const bRotateY_ = document.getElementById("b_rotate_y_");
      bRotateY_.onclick = () => {
        fRotateY(-0.1);
      }

      const fRotateX = (dir) => {
        if (activeScene != null) {
          const delta = clock.getDelta();
          deltaTotal += delta;

          if (deltaTotal >= 0.05) {
            activeScene.rotateX(dir);
            deltaTotal = 0;
          }
        }
      };

      const bRotateX = document.getElementById("b_rotate_x");
      const bRotateX_ = document.getElementById("b_rotate_x_");
      
      bRotateX.onclick = () => {
          fRotateX(0.1);
      };

      bRotateX_.onclick = () => {
        fRotateX(-0.1);
    };

      const clickZoom = (value, zoomType) => {
        if (value >= 20 && zoomType === "zoomIn") {
          return value - 5;
        } else if (value <= 75 && zoomType === "zoomOut") {
          return value + 5;
        } else {
          return value;
        }
      };

      const bZoomMais = document.getElementById("zoom_mais");
      bZoomMais.onclick = () => {
        const fov = getFov();
        cameraVar.fov = clickZoom(fov, "zoomIn");
        cameraVar.updateProjectionMatrix();
      };

      const bZoomMenos = document.getElementById("zoom_menos");
      bZoomMenos.onclick = () => {
        const fov = getFov();
        cameraVar.fov = clickZoom(fov, "zoomOut");
        cameraVar.updateProjectionMatrix();
      
      };
    }
    start();
  });