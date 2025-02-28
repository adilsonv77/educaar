@extends('layouts.app')

@section('page-name', "Conexões do Painel")

@section('script-head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.css">
@endsection

@section('content')
    <div class="container">
        <h2>Conexões do Painel: {{ json_decode($painel->panel)->txtSuperior }}</h2>
        <p>AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAH Tudo certo?.</p>
@endsection
