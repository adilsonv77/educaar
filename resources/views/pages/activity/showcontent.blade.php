@extends('layouts.app')

@section('page-name', 'Atividade '.$name)

@section('content')

    <div class="card">
        <div class="card-body">
            <?php
            $filetime = @filemtime(public_path('/modelos3d/'.$activity));
            ?>
            @if ($filetime != FALSE)
                <model-viewer src="{{ asset('/modelos3d/'.$activity.'?v=' . $filetime) }}"
                    poster="" alt="{{ $name }}" shadow-intensity="1" camera-controls auto-rotate autoplay>
                </model-viewer>

                <!-- Botão de download -->
                <div style="text-align: center; margin-top: 10px;">
                    <a href="{{ asset('/modelos3d/'.$activity) }}" download="{{ $activity }}" class="btn btn-primary">
                        Baixar Modelo 3D
                    </a>
                </div>
            @else
                <div>Arquivo não encontrado</div>
            @endif
        </div>
    </div>


@endsection

@section('script')
    <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>

@endsection

@section('style')

    <style>
        body {
            margin: 1em;
            padding: 0;
            font-family: Google Sans, Noto, Roboto, Helvetica Neue, sans-serif;
            color: #244376;
        }


        #card {
            margin: 3em auto;
            display: flex;
            flex-direction: column;
            max-width: 600px;
            border-radius: 6px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }

        model-viewer {
            width: 100%;
            height: 550px;
            background-color: #F4F5F9;
            --poster-color: #ffffff00;
        }

        .attribution {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            margin: 1em;
        }

        .attribution h1 {
            margin: 0 0 0.25em;
        }

        .attribution img {
            opacity: 0.5;
            height: 2em;
        }

        .attribution .cc {
            flex-shrink: 0;
            text-decoration: none;
        }

        footer {
            display: flex;
            flex-direction: column;
            max-width: 600px;
            margin: auto;
            text-align: center;
            font-style: italic;
            line-height: 1.5em;
        }

    </style>
@endsection
