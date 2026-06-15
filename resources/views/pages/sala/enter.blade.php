@extends('layouts.app')

@section('page-name', $titulo)

@section('content')

    <div class="card">
        <div class="card-body">
            <h3>{{ $sala->name }}</h3>
            <p>{{ $sala->description }}</p>
        </div>
    </div>

@endsection
