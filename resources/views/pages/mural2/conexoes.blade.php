@extends('layouts.app')

@section('page-name', 'Mural')

@section('script-head')
    <!-- CSS PAINEL CONEXÕES -->
    <link rel="stylesheet"
        href="{{ asset('css/muralConnection.css?v=' . filemtime(public_path('css/muralConnection.css'))) }}">

    <!-- SELETOR DE CORES -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/nano.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr"></script>

    <!-- EDITOR DE TEXTO -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet"
        href="{{ asset('editor/dist/plugins/colors/ui/trumbowyg.colors.min.css?v=' . filemtime(public_path('editor/dist/plugins/colors/ui/trumbowyg.colors.min.css'))) }}">
    <link rel="stylesheet"
        href="{{ asset('editor/dist/ui/trumbowyg.min.css?v=' . filemtime(public_path('editor/dist/ui/trumbowyg.min.css'))) }}">

    <style>
        .content-body {
            padding-top: 0rem !important;
            margin-top: 7.5rem !important;
        }

        .loading {
            transition: transform 25s linear;
        }

        .areaBtns .loading {
            position: absolute;
            align-self: center;
            height: 146px;
            width: calc(100% - 40px);
            background: #00000059;
            border-radius: 5px;
            display: flex;
            justify-content: center;
            z-index: 1;
        }

        .areaBtns .loading img {
            height: 80%;
            margin-top: 6%;
            transition: transform 25s linear;
        }

        .ponto {
            height: 10px;
            width: 10px;
            background-color: white;
            border: 3px solid #833B8D;
            border-radius: 100%;
            position: absolute;
        }
    </style>
@endsection

@section('script')
    <script src="{{ asset('editor/dist/trumbowyg.min.js') }}"></script>
    <script src="{{ asset('editor/dist/plugins/colors/trumbowyg.colors.min.js') }}"></script>
    <script src="{{ asset('js/muralConnection.js?v=' . filemtime(public_path('js/muralConnection.js'))) }}"></script>
    <!-- LINHAS DE CONEXÕES -->
    <script src="https://cdn.jsdelivr.net/npm/leader-line@1.0.7/leader-line.min.js"></script>

    <script>
        // ---------------------------------------------CARREGAR CANVAS---------------------------------------------
        let preloader = document.getElementById("preloader");
        let mainWrapper = document.getElementById("main-wrapper");

        // ---------------------------------------------APLICAR LISTENERS AOS PAINÉIS EXISTENTES E NOVOS---------------------------------------------
        window.livewire.on("painelCriado", (id) => {
            let panel = document.getElementById(id);
            if (!panel) return;

            try {
                const painelData = JSON.parse(panel.dataset.panel);
                if (painelData.x != null && painelData.y != null) {
                    panel.style.left = painelData.x + "px";
                    panel.style.top = painelData.y + "px";
                  //  atualizarTodasConexoes();
                }
            } catch (e) {
                console.warn("Falha ao aplicar posição inicial ao novo painel:", e);
            }

            /*
            atribuirListeners(panel, id);
            habilitarArrastoPersonalizado(panel);
            mostrarMenu("painel");
            */
        });
    </script>

@endsection

@section('content')
    <livewire:mural-edit :muralId="$muralId" />

@endsection