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

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            overflow: hidden;
        }

        .menu {
            position: fixed;
            bottom: 10px;
            left: 10px;
            z-index: 1000;
        }

        .canvas-container {
            overflow: hidden;
            width: 100vw;
            height: calc(100vh - 50px);
            position: relative;
        }

        .canvas {
            width: 100%;
            height: 100%;
            background-color: #f0f0f0;
            transform-origin: top left;
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
    </style>
@endsection

@section('content')
    <div class="container">

        <div class="canvas-container">
            <div class="menu">
                <button id="zoom-in">+</button>
                <button id="zoom-out">-</button>
            </div>
            <div id="canvas" class="canvas">
                <p>Conteúdo do Canvas</p>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
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