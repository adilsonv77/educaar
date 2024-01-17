

import { GLTFLoader } from 'three/addons/loaders/GLTFLoader';

import * as THREE from 'three';
import { MindARThree } from 'mindar-image-three';

//const THREE = window.MINDAR.IMAGE.THREE;
var buttonAR = null;
var activeScene = null;
var lastActiveScene = null;

function loadGLTF(path) {
  return new Promise((resolve, reject) => {
    const loader = new GLTFLoader();
    loader.load(path, (gltf) => {
      resolve(gltf);
    });
  });
}

document.addEventListener('DOMContentLoaded', () => {
    const start = async() => {

      buttonAR = document.getElementById("button-ar");

      const mind = document.getElementById("mind");


      const mindarThree = new MindARThree({

        container: document.getElementById("my-ar-container"),
        imageTargetSrc: mind.textContent,
        filterMinCF: 0.0001,
        filterBeta: 0.001
      });
 
      const {renderer, scene, camera} = mindarThree;

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
            buttonAR.className = anchor.clazz;
           
            if (anchor.glb.animations.length > 0) {

              mixer = new THREE.AnimationMixer(anchor.glb.scene);
              action = mixer.clipAction(anchor.glb.animations[0]);
              action.play();
              
            }

            activeScene = glbScene;
            buttonAR.style.display = 'block';
        }
        
        //anchor.addEventListener
        anchor.onTargetLost = () => {
          lastActiveScene = activeScene;
          activeScene = null;
          buttonAR.style.display = 'none';
          if (action != null) {
            action.stop();
            action = null;
            mixer = null;
  
          }
        }
  
      }


      const clock = new THREE.Clock();
      await mindarThree.start();

      var deltaTotal = 0;
      renderer.setAnimationLoop(() => {
        if (mixer != null) {
          const delta = clock.getDelta();
          mixer.update(delta);
        }
        if (activeScene == null) {
          deltaTotal = 0;
          if (lastActiveScene != null) {
            lastActiveScene.rotateY(-lastActiveScene.rotation._y); 
            lastActiveScene.rotateX(-lastActiveScene.rotation._x); 
            lastActiveScene.rotateZ(-lastActiveScene.rotation._z); 
          }
            
        }
        renderer.render(scene, camera);

      });

      const bRotateY = document.getElementById("b_rotate_y");
      bRotateY.onclick = () => {
        if (activeScene != null) {
          const delta = clock.getDelta();
          deltaTotal += delta;

          if (deltaTotal >= 0.05) {
            activeScene.rotateY(0.1);
            deltaTotal = 0;
          }
        }
      };
      

      const bRotateX = document.getElementById("b_rotate_x");
      bRotateX.onclick = () => {
        if (activeScene != null) {
          const delta = clock.getDelta();
          deltaTotal += delta;

          if (deltaTotal >= 0.05) {
            activeScene.rotateX(0.1);
            deltaTotal = 0;
          }
        }
      };

    }
    start();
  });