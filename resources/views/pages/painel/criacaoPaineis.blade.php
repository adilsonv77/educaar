@extends('layouts.app')

@section('page-name', $titulo)

@section('script-head')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('css/telainicial.css?v=' . filemtime(public_path('css/telainicial.css'))) }}" rel="stylesheet">
<link rel="stylesheet" href="{{asset('css/painel.css')}}">
@endsection

@section('content')

<div class="container-fluid">
    <form action="{{route('paineis.store')}}" method="POST" enctype="multipart/form-data">
        @if ($action == 'edit')
            <input name="id" type="hidden" value={{$id}}>
        @endif
        <input name="action" type="hidden" value={{$action}} id="actionInput">
        @csrf
        <div class="row">
            <div class="col-md-8 coluna linha">
                <div class="painel">
                    <textarea name="txtSuperior" id="txtSuperior" type="text" maxlength="117"
                        placeholder="Digite seu texto aqui"> @if ($action == 'edit') {{$txtSuperior}}
                        @endif</textarea>
                    <div id="espacoMidias">
                        <!-- selecione um tipo de midia -->
                        <div id="midia" tabindex=0 @if ($action == 'edit') style="display: none;" @endif>
                            <p>Selecione um:</p>
                            <div id="selectType">
                                <button id="img">Imagem</button>
                                <span>ou</span>
                                <button id="vid">Vídeo</button>
                            </div>
                        </div>
                        <!-- preview da midia -->
                        <div id="midiaPreview" edit=@if($action == 'edit')"true" @else "false"  style="display: none;" @endif>
                            <video id="vidMidia" controls @if ($midiaExtension != "mp4") style="display: none" @endif>
                                <source id="srcVidMidia"
                                    src="@if ($action == 'edit'){{asset('midiasPainel/' . $arquivoMidia)}}@endif"
                                    type="video/mp4">
                            </video>
                            <img src="@if ($action == 'edit'){{asset('midiasPainel/' . $arquivoMidia)}}@endif" id="imgMidia" @if($midiaExtension == "mp4") style="display: none" @endif>
                        </div>
                    </div>
                    <textarea name="txtInferior" id="txtInferior" type="text" maxlength="117"
                        placeholder="Digite seu texto aqui"> @if ($action == 'edit') {{$txtInferior}}
                        @endif</textarea>
                    <div id="areaBtns">
                        <button id="add">+</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 coluna">
                <div id="configPainel">
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
                        <label for="youtubeLink" @if ($action == 'edit') value="{{$link}}" @endif>Youtube</label><br>
                        <input id="youtubeLink" placeholder="Ex: https://www.youtube.com/watch?v=4YEy" name="link"><br>
                        <label id="labelMyFile" for="myfile">Local (somente .png, .jpg, .jpeg)</label><br>
                        <input type="file" style="border:none" name="arquivoMidia" id="midiaInput"
                            accept=".png, .jpeg, .jpg, .mp4" /><br><br>
                    </div>
                    <div id="blocoTxt">

                    </div>
                    <div id="blocoBtn">

                    </div>
                    <input type="submit" value="Salvar" id="salvarPainel" class="submitPanel">
                </div>
            </div>
        </div>
    </form>
</div>
<script src="{{asset('js/painel2.js')}}" type="module"></script>
@endsection