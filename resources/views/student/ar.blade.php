@extends('layouts.mobile', ['back' => $rota, 'showBack' => true, 'showOthers' => true])

@section('style')
    <!--Nao dar erro do ngrok:-->
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<link rel="stylesheet" href="{{asset('css/painel.css')}}">
<style>
    #painelContainer {
        display: flex;
        justify-content: center;
    }

    #my-ar-container {
        height: 80vh;
        width: 120vh;
        position: relative;
        overflow: hidden; 
    }

    
/* =============================
   MINI PAINEIS
   ============================= */
.painel {
    padding: 20px;
    width: 291px;
    height: 462px;
    background: #F8F8F8;
    border: 1px solid #ccc;
    border-radius: 22px;
    box-sizing: border-box;
    cursor: pointer;
    box-shadow: -10.5px 13.5px 15.3px 0px rgba(0, 0, 0, 0.25);
    transition: border 0.2s ease-in-out;
}

.painel .txtPainel {
    width: 100%;
    height: 20%;
    font-size: 1.5vh;
    /* Mantém o tamanho do texto */
    border-radius: 1.5vh;
    padding: 1% 10px;
    border: none;
    resize: none;
    background-color: white;
}

.painel.selecionado {
    border: 1px solid #FFA600;
}

.idPainel {
    position: absolute;
    top: -34px;
    left: 0;
    font-size: 17px;
}

.button_Panel.selecionado {
    border: 1px solid #FFA600;
}

.midia {
    width: 100%;
    height: 172.5px;
    /* 115 * 1.5 */
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 4% 0;
}

.midia * {
    max-width: 100%;
    max-height: 172.5px;
    /* 115 * 1.5 */
    object-fit: contain;
}

.areaBtns {
    display: flex;
    flex-direction: column;
    width: 100%;
    height: 146px;
    justify-content: space-between;
}

.button_Panel {
    display: flex;
    align-items: center;
    border: 0.5px solid #833B8D;
    border-radius: 9px;
    padding: 8.25px;
    /* 5.5 * 1.5 */
    margin-bottom: 4.5px;
    /* 3 * 1.5 */
    height: 45px;
    /* 30 * 1.5 */
    width: 100%;

}

.circulo {
    width: 30px;
    /* 20 * 1.5 */
    height: 30px;
    /* 20 * 1.5 */
    background-color: #823688;
    border-radius: 50%;
    margin-right: 22.5px;
    /* 15 * 1.5 */
}

.no_midia {
    position: relative;
    cursor: pointer;
    background-color: rgb(255, 255, 255);
    aspect-ratio: 1/1;
    height: 172.5px;
    /* 115 * 1.5 */
    border-radius: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.fileMidia {
    margin: auto;
    width: 50%;
    aspect-ratio: 1;
}

.videoContainer {
    height: 100%;
    display: flex;
    align-items: center;
}
</style>
@endsection

@section('content')

<span id="mind" style="display: none;">/mind/{{session()->get('content_id')}}.mind</span>

<script>
    var mind = document.getElementById("mind");
    mind.textContent = mind.textContent + "?" + Math.floor(Math.random() * 100000);
</script>

<span id="glbs" style="display: none;">
    @foreach ($activities as $item)
    <li id="act_{{$item->id}}" usar_class=@if($item->bloquearPorData == 1)"#000000" @else @if($item->respondido == 1)"#efbecc" @else "" @endif @endif
        @if(!empty($item->scene_id)) json="{{$item->json}}" @endif painel=@if(!empty($item->scene_id))
        {{$item->scene_id}} @else "0" @endif>
        /modelos3d/{{$item->glb}}
    </li>
    @endforeach
</span>

<div id="barradeprogresso">
    <div class="progress" style="height: 20px">
        <div id="progressbar" class="progress-bar progress-bar-striped progress-bar-animated bg-danger"
            role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
            Carregando</div>
    </div>
</div>

<div id="painelContainer">

</div>
<div id="my-ar-container">
    
</div>

@endsection

@section('script')	

<script type="importmap">
    {
      "imports": {
        "three": "https://unpkg.com/three@0.160.0/build/three.module.js", 
        "three/examples/jsm/": "https://unpkg.com/three@0.160.0/examples/jsm/",
        "three/addons/": "https://unpkg.com/three@0.160.0/examples/jsm/",
        "mindar-image-three":"https://cdn.jsdelivr.net/npm/mind-ar@1.2.5/dist/mindar-image-three.prod.js"      }
    }
    </script>

<script src="{{ asset('js/main-mindar.js?v=' . filemtime(public_path('js/main-mindar.js'))) }}" type="module"></script>

@endsection