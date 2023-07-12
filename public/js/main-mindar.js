

import { GLTFLoader } from 'three/addons/loaders/GLTFLoader';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';

const THREE = window.MINDAR.IMAGE.THREE;
var buttonAR = null;

const loadGLTF = (path) => {
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


      const mindarThree = new window.MINDAR.IMAGE.MindARThree({
//      const mindarThree = new MindARThree({
        container: document.getElementById("my-ar-container"),


        imageTargetSrc: mind.textContent,
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
        anchor.group.add(glbScene);
    
        anchor.onTargetFound = () => {
            buttonAR.href = buttonAR.dataset.href + "?id=" + anchor.activityid;
           
            if (anchor.glb.animations.length > 0) {

              mixer = new THREE.AnimationMixer(anchor.glb.scene);
              action = mixer.clipAction(anchor.glb.animations[0]);
              action.play();
            }

            buttonAR.style.display = 'block';
        }
        
        //anchor.addEventListener
        anchor.onTargetLost = () => {
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

      renderer.setAnimationLoop(() => {
        if (mixer != null) {
          const delta = clock.getDelta();
          mixer.update(delta);
        }
        renderer.render(scene, camera);
      });
    }
    start();
  });