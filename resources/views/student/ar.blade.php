@extends('layouts.mobile',['back' => $rota, 'showBack' => true, 'showOthers' => true])



@section('style')
<link rel="stylesheet" href="{{asset('css/painel.css')}}">
<style>
        #painelContainer{
            display: flex;
            justify-content: center;
        }
        
        #my-ar-container {
            height: 80vh; width: 120vh; position: relative; overflow: hidden;   
         }
      
 </style>
@endsection

@section('content')
    <div id="painelContainer">
            
    </div>
    <span id="mind" style="display: none;">/mind/{{session()->get('content_id')}}.mind</span>

    <script>
        var mind = document.getElementById("mind");
        mind.textContent = mind.textContent + "?" + Math.floor(Math.random() * 100000);
    </script>

    <span id="glbs" style="display: none;"> 
        @foreach ($activities as $item)
            <li id="act_{{$item->id}}" usar_class=@if($item->respondido==1)"#efbecc" @else "" @endif @if(!empty($item->painel_inicial_id)) json="{{$item->json}}" @endif painel=@if(!empty($item->painel_inicial_id)) {{$item->painel_inicial_id}} @else "0" @endif >/modelos3d/{{$item->glb}}</li>
        @endforeach
    </span>
    
    <div id="barradeprogresso">
        <div class="progress" style="height: 20px">
            <div id="progressbar" class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                            Carregando</div>
        </div>
    </div>

    <div id="my-ar-container"> </div>
    
@endsection

@section('script')	

    <script type="importmap"> 
    {
      "imports": {
        "three": "https://unpkg.com/three@0.160.0/build/three.module.js",
        "three/addons/": "https://unpkg.com/three@0.160.0/examples/jsm/",
        "mindar-image-three":"https://cdn.jsdelivr.net/npm/mind-ar@1.2.5/dist/mindar-image-three.prod.js"      }
    }
    </script>

    <script src="{{ asset('js/main-mindar.js?v=' . filemtime(public_path('js/main-mindar.js'))) }}" type="module"></script>
    
@endsection 