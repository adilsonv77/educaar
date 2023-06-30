// Executar isso no terminal: npx webpack --config webpack.config.js

import  { OfflineCompiler }  from 'mind-ar/src/image-target/offline-compiler';

import { loadImage } from 'canvas';

export default function run(){
  
  document.getElementById('button').addEventListener('click', async function(evt) {
    const compiler   = new OfflineCompiler();
    const imagePaths = ['http://127.0.0.1:8887/images/imagem1.png'];
    const images     = await Promise.all(imagePaths.map(value => loadImage(value)));
    await compiler.compileImageTargets(images, console.log);
    const buffer     = compiler.exportData();

 
    //writeFile('targets.mind', buffer);
  });
}

run();