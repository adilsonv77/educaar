@extends('layouts.app')

@section('page-name', 'Criação de painéis')

@section('script-head')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('css/telainicial.css?v=' . filemtime(public_path('css/telainicial.css'))) }}" rel="stylesheet">
<link rel="stylesheet" href="{{asset('css/paineis.css')}}">
@endsection

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 coluna linha">
            <div id="painel">
                <textarea type="text" maxlength="117" placeholder="Digite seu texto aqui"></textarea>
                <div id="media">
                    <p>Selecione um:</p>
                    <div id="selectType">
                        <button id="img">Imagem</button>
                        <span>ou</span>
                        <button id="vid">Vídeo</button>
                    </div>
                </div>
                <textarea type="text" maxlength="117" placeholder="Digite seu texto aqui"></textarea>
                <div id="areaBtns">
                    <button id="add">+</button>
                </div>    
            </div>
        </div>
        <div class="col-md-4 coluna">

        </div>
    </div>
</div>

<script src="{{asset('js/paineis.js')}}" type="module"></script>
@endsection