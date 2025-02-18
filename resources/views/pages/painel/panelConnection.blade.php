@extends('layouts.app')

@section('page-name', "Conexão de painéis")

@section('script-head')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('css/telainicial.css?v=' . filemtime(public_path('css/telainicial.css'))) }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/panelConnection.css?v=' . filemtime(public_path('css/panelConnection.css'))) }}">
@endsection

@section('content')
<!-- <p>{{$data}}</p> -->
<div id="container">
     <button id="addPanel">Adicionar painel</button>
</div>

<script src="{{ asset('js/panelConnection.js?v=' . filemtime(public_path('js/panelConnection.js'))) }}">
@endsection

