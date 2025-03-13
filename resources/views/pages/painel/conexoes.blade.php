@extends('layouts.app')

@section('page-name', "Conexões do Painel")

@section('script-head')
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Feather Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.css">

    <!-- Heroicons -->
    <link href="https://cdn.jsdelivr.net/npm/heroicons@1.0.6/outline/heroicons.min.css" rel="stylesheet">

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet"
        href="{{ asset('css/panelConnection.css?v=' . filemtime(public_path('css/panelConnection.css'))) }}">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            overflow: hidden;
        }

        .AddPainel {
            position: fixed;
        }

        .AddPainel {
            position: fixed;
            z-index: 1000;
            width: 63px;
            height: 31px;
            flex-shrink: 0;
            margin-left: 18px;
            border-radius: 2px;
        }

        .menu {
            position: fixed;
            bottom: 10px;
            z-index: 1000;
            width: 63px;
            height: 31px;
            flex-shrink: 0;
            margin-left: 18px;
            border-radius: 2px;
            /* background: rgba(131, 59, 141, 0.71); */
        }

        .canvas-container {
            overflow: hidden;
            width: 100vw;
            height: calc(100vh - 50px);
            position: relative;
            margin-right: 346px;
        }

        .canvas {
            width: 70000px;
            height: 70000px;
            background-color: #f0f0f0;
            transform-origin: top left;
            overflow: hidden;
        }

        .content-body .container {
            margin: 0px !important;
        }

        .container-fluid {
            padding: 0px !important;
        }

        .container {
            padding: 0px !important;
        }

        .menu-lateral {
            position: fixed;
            top: 0;
            right: 0;
            width: 346px;
            height: 100vh;
            background-color: pink;
            transform: scale(1);
            pointer-events: none;
            position: fixed;
            padding-top: 120px;
            z-index: 1000;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="canvas-container">
            <div class="AddPainel">
                <button id="addPanel">Adicionar painel</button>
            </div>
            <div class="menu">
                <button id="zoom-in">+</button>
                <button id="zoom-out">-</button>
            </div>
            <div id="canvas" class="canvas">
                <!-- <p>Conteúdo do Canvas</p> -->
                <img src="{{ asset('images/play.png') }}" alt="">
            </div>
            <!-- <div class="menu-lateral">
                <p>Menu Lateral</p>
            </div> -->
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/panelConnection.js?v=' . filemtime(public_path('js/panelConnection.js'))) }}"></script>
    <script>
        //CANVAS INFINITO
        let scale = 1;
        const canvas = document.getElementById('canvas');

        document.getElementById('zoom-in').addEventListener('click', () => {
            scale += 0.1; // Aumenta o zoom
            updateCanvasScale();
        });

        document.getElementById('zoom-out').addEventListener('click', () => {
            scale = Math.max(scale - 0.1, 0.1); // Diminui o zoom, mas não permite que fique menor que 0.1
            updateCanvasScale();
        });

        function updateCanvasScale() {
            canvas.style.transform = `scale(${scale})`;
        }


    </script>

@endsection