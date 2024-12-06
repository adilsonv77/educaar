@extends('layouts.app')

@section('page-name', "Listagem de painéis")

@section('script-head')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('css/telainicial.css?v=' . filemtime(public_path('css/telainicial.css'))) }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/panelListing.css?v=' . filemtime(public_path('css/panelListing.css'))) }}">
@endsection

@section('content')
@foreach ($data as $painel)
    <form action="{{route('paineis.destroy',['id'=>$painel->id])}}" method="POST">
        @csrf
        @method('DELETE')
        <p>Id: {{$painel->id}}, Texto superior: {{json_decode($painel->panel)->txtSuperior}}</p>
        <input type="Submit" value="Apagar >:D" class="btn btn-danger" @if($painel->sendoUsado) disabled @endif>
    </form>
@endforeach
<script src="{{ asset('js/panelListing.js?v=' . filemtime(public_path('js/panelListing.js'))) }}">
    @endsection