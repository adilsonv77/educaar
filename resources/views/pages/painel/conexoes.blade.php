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
            <input id="linkYoutube" type="text">
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
                let id = panel.querySelector('.idPainel').id;

                atribuirListeners(panel, id);
            });
            mostrarMenu("canvas");
            
            window.livewire.on("painelCriado",(id)=>{
                let panel = document.getElementById(id).parentElement;

                atribuirListeners(panel, id);
            });
        });

        function atribuirListeners(panel, id) {
            let inputLink = panel.querySelector("#file-"+id);

            panel.addEventListener("dragstart", onDragStart);
            panel.addEventListener("click", onClick);
            adicionarInteracaoPopup(id);
        }

        //----GERAR CONEXÃO---------------------------------------------------------------------
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.painel').forEach(painel => {
                //ativarDrag(painel);
            });

            // manualmente por enquanto
            conectarBotoes("117", "1", "130");
            conectarBotoes("117", "2", "17")
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
    </script>
@endsection