@extends('layouts.mobile')



@section('style')

@endsection


@section('content')

    
@endsection

@section('script')	

<script type="importmap">
    {
      "imports": {
	"three": "https://unpkg.com/three@0.147.0/build/three.module.js",
	"three/addons/": "https://unpkg.com/three@0.147.0/examples/jsm/",
	"mindar-image-three":"../js/mind-ar/mindar-image-three.js"
      }
    }
    </script>
    
    <script src="../js/main-mindar.js" type="module"></script>

@endsection