@extends('layouts.mobile')



@section('style')

<style>

        #my-ar-container {
            height: 80vh; width: 120vh; position: relative; overflow: hidden;   
         }

         .glyph {
               background: white;  font-size: 32px;  position: fixed; bottom: 3%; right: 5%; border-radius: 50%
         }


         #button-ar {
            font-size: 32px; position: absolute; bottom: 10px; left: 10px; background: white; z-index: 100; display: none;

    
         }

         
</style>
@endsection


@section('content')
<!-- display: none; -->
    <span id="mind" style="display: none;">/mind/{{$content_id}}.mind</span>

    <span id="glbs" style="display: none;"> 
        @foreach ($activities as $item)
         <li id="act_{{$item->id}}">/modelos3d/{{$item->glb}}</li>
        @endforeach
    </span>
    
    <div id="my-ar-container">
        <a id="button-ar" class="flaticon-381-list-1" data-href="{{ route('student.questoes') }}"></a>
    </div>
   <!--
    <script src="https://unpkg.com/three@0.147.0/build/three.module.js"></script>
	<script src="https://unpkg.com/three@0.147.0/examples/jsm/"></script>
    ,
	"mindar-image-three":"../js/mind-ar/mindar-image-three.prod.js"
        -->

    
@endsection

@section('script')	

    <script type="importmap">
    {
      "imports": {
	"three": "https://unpkg.com/three@0.147.0/build/three.module.js",
	"three/addons/": "https://unpkg.com/three@0.147.0/examples/jsm/"
      }
    }
    </script>
    <script src="../js/mind-ar/mindar-image-three.prod.js"></script>
    
    <script src="../js/main-mindar.js" type="module"></script>

@endsection