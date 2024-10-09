@extends('layouts.app')

@section('page-name', 'Criação de painéis')

@section('script-head')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('css/telainicial.css?v=' . filemtime(public_path('css/telainicial.css'))) }}" rel="stylesheet">
<link rel="stylesheet" href="{{asset('css/painel1.css')}}">
@endsection

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 coluna linha">
            <div id="painel">
                <textarea id="textoSuperior" type="text" maxlength="117" placeholder="Digite seu texto aqui"></textarea>
                <div id="midia" tabindex=0>
                    <p>Selecione um:</p>
                    <div id="selectType">
                        <button id="img">Imagem</button>
                        <span>ou</span>
                        <button id="vid">Vídeo</button>
                    </div>
                </div>
                <textarea id="textoInferior" type="text" maxlength="117" placeholder="Digite seu texto aqui"></textarea>
                <div id="areaBtns">
                    <button id="add">+</button>
                </div>
            </div>
        </div>
        <div class="col-md-4 coluna">
            <div id="configPainel">
                <form action="{{route("paineis.store")}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!--Selecione uma mídia-->
                    <p id="tituloSelecionado">Bloco de Mídia</p>
                    <hr>
                    <div id="blocoMidia">
                        <p>Tipo de mídia</p>
                        <input type="radio" name="midia" id="midia1">
                        <label for="midia1">Video</label>
                        <input type="radio" name="midia" id="midia2">
                        <label for="midia2">Imagem</label>
                        <hr>
                        <label for="youtubeLink">Youtube</label><br>
                        <input id="youtubeLink" placeholder="Ex: https://www.youtube.com/watch?v=4YEy" name="link"><br>
                        <label id="labelMyFile" for="myfile">Local (somente .png, .jpg, .jpeg)</label><br>
                        <input type="file" style="border:none" name="midia" id="midiaInput" accept=".png, .jpeg, .jpg, .mp4"/><br><br>
                    </div>
                    <div id="blocoTxt">

                    </div>
                    <div id="blocoBtn">

                    </div>
                    <input type="submit" value="Salvar" id="salvarPainel">
                </form>

            </div>
        </div>
    </div>
</div>
<script src="{{asset('js/painel1.js')}}" type="module"></script>
@endsection