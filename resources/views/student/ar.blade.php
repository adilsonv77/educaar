@extends('layouts.mobile',['back' => $rota, 'showBack' => true, 'showOthers' => true])



@section('style')

<style>

        #my-ar-container {
            height: 80vh; width: 120vh; position: relative; overflow: hidden;   
         }

        
         #button-return {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background: orange;
            z-index: 100;
            display: block;
            border-radius: 90px;
            padding-top: 5px
        }

        .flaticon-voltar:before {
            content: url("/images/voltar.png");
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
        
         <li id="act_{{$item->id}}" usar_class=@if($item->respondido==1)"flaticon-questoes-ok"@else"flaticon-questoes-nao"@endif>/modelos3d/{{$item->glb}}</li>
        @endforeach
    </span>
    
    <div id="my-ar-container">
        {{-- <a id="button-return" class="flaticon-voltar" href="{{ route('student.conteudos') }}?id={{ $disciplina }}"></a>  --}}
        <a id="button-ar_x" data-href="{{ route('student.questoes') }}"></a> 

    </div>

    
@endsection

@section('script')	


    <script type="importmap">
        
    {
      "imports": {
          "three": "https://unpkg.com/three@0.154.0/build/three.module.js",
	      "three/addons/": "https://unpkg.com/three@0.154.0/examples/jsm/",
          "mindar-image-three":"https://cdn.jsdelivr.net/npm/mind-ar@1.2.3/dist/mindar-image-three.prod.js"
      }
    }
    </script>



  
    <script src="{{ asset('js/main-mindar.js?v=' . filemtime(public_path('js/main-mindar.js'))) }}" type="module"></script>
    
@endsection 