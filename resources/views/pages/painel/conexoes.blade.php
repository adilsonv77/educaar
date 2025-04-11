@extends('layouts.app')

@section('page-name', "Conexões do Painel")

@section('script-head')
    <!-- CSS PAINEL CONEXÕES -->

    <link rel="stylesheet"
        href="{{ asset('css/panelConnection.css?v=' . filemtime(public_path('css/panelConnection.css'))) }}">
    <!-- SELETOR DE CORES -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/nano.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr"></script>

    <!-- EDITOR DE TEXTO -->
    <link rel="stylesheet"
        href="{{ asset('editor/dist/plugins/colors/ui/trumbowyg.colors.min.css?v=' . filemtime(public_path('editor/dist/plugins/colors/ui/trumbowyg.colors.min.css'))) }}">
    <link rel="stylesheet"
        href="{{ asset('editor/dist/ui/trumbowyg.min.css?v=' . filemtime(public_path('editor/dist/ui/trumbowyg.min.css'))) }}">

@endsection

@section('content')
    @livewire('scene', ['paineis' => $paineis, "scene_id" => $scene_id]);
    @livewireScripts
@endsection

@section('script')
    <script src="{{ asset('js/panelConnection.js?v=' . filemtime(public_path('js/panelConnection.js'))) }}"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="{{ asset('editor/dist/trumbowyg.min.js') }}"></script>
    <script src="{{ asset('editor/dist/plugins/fontfamily/trumbowyg.fontfamily.min.js') }}"></script>
    <script src="{{ asset('editor/dist/plugins/colors/trumbowyg.colors.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/leader-line-new@1.1.8/leader-line.min.js"> </script>
    <script>
        //----PANEL LOADING---------------------------------------------------------------------
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".painel").forEach(panel => {
                panel.setAttribute("draggable", "true");

                panel.addEventListener("dragstart", (e) => {
                    arrastar(e, new Painel(panel));
                });

                panel.addEventListener("click", (e) => {
                    selecionarPainel(panel, e);
                });
            });
            mostrarMenu("canvas");
        });

        //----GERAR CONEXÃO---------------------------------------------------------------------
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.painel').forEach(painel => {
                ativarDrag(painel);
            });

            // manualmente por enquanto
            conectarBotoes("5", "1", "17");
            conectarBotoes("5", "2", "17")
        });

        //---------------------------------------------------------------------------------------------------------------------
        // EDITOR DE TEXTO
        // Criar editor
        $(document).ready(function () {
            //Reset no CSS
            $('.trumbowyg').trumbowyg({
                resetCss: true
            });

            $('#trumbowyg-editor').trumbowyg({
                btns: [
                    ['undo', 'redo'],
                    ['strong', 'em'],
                    ['fontfamily', 'formatting', 'foreColor'],
                    ['justifyLeft', 'justifyCenter', 'justifyFull']
                ],
                autogrow: false
            });

            $('#trumbowyg-editor').on('tbwchange', function () {
                $('#editorInput').val($('#trumbowyg-editor').trumbowyg('html'));
            });
        });

        // Criar editor
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function () {
                const btn = document.querySelector('button[title="Font"]');
                if (btn) {
                    console.log("Botão encontrado! Alterando texto...");
                    btn.textContent = 'F ';
                } else {
                    console.log("Botão ainda não está disponível.");
                }
            }, 300); // Dá tempo pro Trumbowyg renderizar
        });

        //----Carregar o dropdown-----------------------------------------------------------------------------------------------
        
    </script>
@endsection