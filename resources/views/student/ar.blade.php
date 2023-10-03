@extends('layouts.mobile')



@section('style')
    <style>
        #my-ar-container {
            height: 80vh;
            width: 120vh;
            position: relative;
            overflow: hidden;
        }

        .glyph {
            background: white;
            font-size: 32px;
            position: fixed;
            bottom: 3%;
            right: 5%;
            border-radius: 50%
        }


        #button-ar {
            font-size: 32px;
            position: absolute;
            bottom: 10px;
            left: 10px;
            background: white;
            z-index: 100;
            display: none;
        }

        #return-container {
            height: 80vh;
            width: 120vh;
            position: relative;
            overflow: hidden;
        }

        #button-return {
            font-size: 32px;
            position: absolute;
            bottom: 10px;
            left: 10px;
            background: white;
            z-index: 100;
            display: block;
        }
    </style>
@endsection


@section('content')
    <span id="mind" style="display: none;">/mind/{{ session()->get('content_id') }}.mind</span>

    <span id="glbs" style="display: none;">
        @foreach ($activities as $item)
            <li id="act_{{ $item->id }}">/modelos3d/{{ $item->glb }}</li>
        @endforeach
    </span>

    <div id="my-ar-container">
        <a id="button-ar" class="flaticon-381-list-1" data-href="{{ route('student.questoes') }}"></a>

        <form action="{{ route('student.conteudos') }}">
            @csrf
            <input name="id" type="hidden" value="{{ $disciplina }}" />
            <button type="submit" class="btn" id="button-return"><i class="flaticon-381-back"></i></button>
        </form>
        {{-- <a id="button-return" class="flaticon-381-back" data-href="{{ route('student.conteudos') }}">
            <input name="acao" type="hidden" value="{{ true }}" />
            <input name="id" type="hidden" value="{{ Auth::user()->id }}" />
        </a> --}}
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
