@extends('layouts.app')

@section('page-name', 'QR Code')

@section('content')

<div class="d-flex justify-content-center align-items-center" style="min-height: 50vh;">
    <div class="card border-0 shadow-sm text-center p-4" style="max-width: 400px; width: 100%;">
        
        <div class="card-body">
            <h5 class="mb-1"><strong>Acesso à sala</strong></h5>

            <div class="mb-4">
                <img src="{{ $qrCode }}" 
                     alt="QR Code para acessar a sala" 
                     class="img-fluid rounded"
                     style="max-width: 300px;">
            </div>

            <div>
                <a href="{{ $qrCode }}" download="{{ $qrCodeName }}" class="btn btn-primary">
                    {{ __('Download') }}
                </a>
            </div>
        </div>

    </div>
</div>

@endsection