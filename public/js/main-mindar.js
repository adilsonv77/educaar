

import { GLTFLoader } from 'three/addons/loaders/GLTFLoader';
import { DRACOLoader } from 'three/addons/loaders/DRACOLoader';

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
var bdGLB = null;

// inspirado no OrbitControl
var state = 0;  // 1 - rotacionar
var rotateStart = new THREE.Vector2();
function mostrarAvanco(percent) {
  var pb = document.getElementById("progressbar");
  pb.style = "width: " + percent + "%";

};

function loadGLTF(path) {
  return new Promise((resolve, reject) => {
    const loader = new GLTFLoader();
    const dracoLoader = new DRACOLoader()
    dracoLoader.setDecoderPath( 'https://www.gstatic.com/draco/versioned/decoders/1.5.4/' );
    loader.setDRACOLoader( dracoLoader );
    loader.load(path, (gltf) => {
 /*
      console.log("GLTFLoader");

      var transaction = bdGLB.transaction("glbs", IDBTransaction.READ_WRITE);
      transaction.objectStore("glbs").put(gltf, path);
 */     
      resolve(gltf);
    }, (xhr) => {
     mostrarAvanco( ( xhr.loaded / xhr.total * 100 ) );
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
      buttonsAR[0] = document.getElementById("button-ar");
      buttonsAR[1] = document.getElementById("b_rotate_x");
      buttonsAR[2] = document.getElementById("b_rotate_x_");
      buttonsAR[3] = document.getElementById("b_rotate_y_");
      buttonsAR[4] = document.getElementById("b_rotate_y");
      buttonsAR[5] = document.getElementById("zoom_mais");
      buttonsAR[6] = document.getElementById("zoom_menos");

      qtosBotoes = buttonsAR.length;

      hideButtons();

      /*
      var indexedDB = window.indexedDB || window.webkitIndexedDB || window.mozIndexedDB || window.OIndexedDB || window.msIndexedDB,
          IDBTransaction = window.IDBTransaction || window.webkitIDBTransaction || window.OIDBTransaction || window.msIDBTransaction;    
          
      bdGLB = indexedDB.open("GLBAtividades", 1);
      bdGLB.createObjectStore("glbs");

      let db;
      const request = indexedDB.open("MyTestDatabase");
      request.onerror = (event) => {
        console.error("Why didn't you allow my web app to use IndexedDB?!");
      };
      request.onsuccess = (event) => {
        db = event.target.result;
      };

     /* bdGLB.onupgradeneeded = function(event) {
        var db = event.target.result;
        db.createObjectStore("glbs");
      };*/

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
        console.log(li.textContent);
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

            var bq = document.getElementById("button-ar");
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

      let barradeprogresso = document.getElementById("barradeprogresso");
      barradeprogresso.style.display = "none";

      let myarcontainer = document.getElementById("my-ar-container");
      myarcontainer.style.display = "";

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
      }

      const fRotateY = (dir) => {
        if (activeScene != null) {
           activeScene.rotateOnWorldAxis(new THREE.Vector3(0,1,0), dir);
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
           activeScene.rotateX(dir);
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