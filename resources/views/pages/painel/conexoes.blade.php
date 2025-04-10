@extends('layouts.app')

@section('page-name', "Conexões do Painel")

@section('script-head')
    <!-- CSS PAINEL CONEXÕES -->

    <link rel="stylesheet" href="{{ asset('css/panelConnection.css?v=' . filemtime(public_path('css/panelConnection.css'))) }}">
    <!-- SELETOR DE CORES -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/nano.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr"></script>

    <!-- EDITOR DE TEXTO -->
    <link rel="stylesheet" href="{{ asset('editor/dist/plugins/colors/ui/trumbowyg.colors.min.css?v=' . filemtime(public_path('editor/dist/plugins/colors/ui/trumbowyg.colors.min.css'))) }}">
    <link rel="stylesheet" href="{{ asset('editor/dist/ui/trumbowyg.min.css?v=' . filemtime(public_path('editor/dist/ui/trumbowyg.min.css'))) }}">
    
    <!-- STYLE ESPECIFICO DO EDITOR DE TEXTO -->
    <style>
        .trumbowyg-editor[contenteditable=true]:empty::before {
            content: attr(placeholder);
            color: #999;
        }

        #opaque-background {
            background-color: #D7D7D7;
            opacity: 0.7;
            position: absolute;
            z-index: 12;
            width: 100%;
            height: 100%;
        }

        #flex-container {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            display: none;
            justify-content: center;
            align-items: center;
        }

        #popup {
            background-color: white;
            height: 600px;
            width: 600px;
            z-index: 13;
            border-radius: 19px;
            padding: 40px 40px;
        }

        #midiaInput {
            display: none;
        }

        .picture {
            width: 100%;
            aspect-ratio: 16/9;
            border-radius: 19px;
            background: #F0EBF1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #aaa;
            border: 2px dashed #833B8D;
            cursor: pointer;
            font-family: sans-serif;
            transition: color 300ms ease-in-out, background 300ms ease-in-out;
            outline: none;
            overflow: hidden;
            margin-top: 40px;
        }

        .picture:hover {
            color: #777;
            background: #E7DFE9;
        }

        .picture:active {
            background: #DFC1E4;
        }

        .picture:focus {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .picture__img {
            max-width: 100%;
        }

        .pInfo {
            color: #B8B5B5;
            font-size: 13px;
            margin-bottom: 30px;
            float: left;
        }

        #popup {
            box-shadow: -10px 12px 17px 0px rgba(0, 0, 0, 0.185);
        }

        #popup button {
            font-size: 15px;
            font-weight: 600;
            float: right;
            margin-top: 5px;
            background: none;
            border: none
        }

        #popup button:focus {
            outline: none;
            box-shadow: none;
        }

        #popup p:nth-child(1) {
            font-size: 22px;
            font-weight: bold;
            float: left;
        }

        #pYoutube {
            font-size: 17px;
            color: #827E7E;
            font-weight: bold;
            margin-bottom: 5px;
        }

        #popup input[type="text"] {
            width: 100%;
            background-color: #EFEFEF;
            border: none;
            padding: 10px;
            border-radius: 8px;
        }

        #videoContainer {
            width: 100%;
            height: 33.5vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #videoContainer iframe {
            width: 450px;
            height: 280px;
        }
    </style>
@endsection

@section('bodyAccess')
    <!--Pop up upload de arquivo-->
    <!--Explicação: Ele teve que ficar dentro do body, ao colocar o elemento dentro da section content, ele fica dentro
        de um "Main wrapper" que possui um tamanho menor que o tamanho inteiro da tela-->
    <div id="flex-container">
        <div id="opaque-background"></div>

        <div id="popup">
            <p>Upload file</p>
            <button onclick="fecharPopUp()">X</button>

            <label id="upload-area" class="picture" tabIndex="0">
                <img src="{{ asset('icons/paineis/upload.svg') }}" alt="">
                <span class="picture__image"></span>
            </label> 

            <p class="pInfo">Formatos suportados: MP4, JPG, JPEG, PNG</p>
            <p class="pInfo" style="float: right">Tamanho máximo: 50MB</p>
            <div style="clear: both;"></div>

            <p id="pYoutube">URL YouTube</p>
            <input id="linkYoutube" type="text" onchange="enviarYoutube()">
        </div>
    </div>
@endsection

@section('content')
    @livewire('scene', ['paineis' => $paineis,"scene_id"=>$scene_id])
    @livewireScripts
@endsection

@section('script')
    <script src="{{ asset('js/panelConnection.js?v=' . filemtime(public_path('js/panelConnection.js'))) }}"></script>
    <script>
        //----PANEL LOADING---------------------------------------------------------------------
        function onDragStart(e) {
            arrastar(e, new Painel(e.currentTarget));
        }

        function onClick(e) {
            selecionarPainel(e.currentTarget, e);
        }

        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".painel").forEach(panel => {
                panel.addEventListener("dragstart", onDragStart);
                panel.addEventListener("click", onClick);
                adicionarInteracaoPopup(panel.querySelector('.idPainel').id);
            });
            mostrarMenu("canvas");
            
            window.livewire.on("painelCriado",(id)=>{
                let panel = document.getElementById(id).parentElement;

                panel.addEventListener("dragstart", onDragStart);
                panel.addEventListener("click", onClick);
                adicionarInteracaoPopup(id);
            });
        });

        //---------------------------------------------------------------------------------------------------------------------
        // EDITOR DE TEXTO
        // Criar editor
        $('#trumbowyg-demo').trumbowyg({
            btns: [
                ['undo', 'redo'], // Only supported in Blink browsers
                ['strong', 'em'],
                ['fontfamily', 'formatting', 'foreColor'],
                ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull']
            ],
            autogrow: false
        });
    </script>
@endsection