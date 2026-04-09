@extends('layouts.error')

@section('style')

    <style>
        .message {
            max-width: 600px;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-left: 4px solid #86398e;
            border-radius: 8px;
            padding: clamp(1rem, 5vw, 2rem);
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        }    
    </style>

@endsection

@section('page-name', 'Erro 419')

@section('content')
    <body class="d-flex justify-content-center align-items-center min-vh-100 m-0 p-3">
        
        <div class="message text-start">
            <h3><strong>Erro 419</strong><i class="bi bi-hourglass-bottom ml-3"></i></h3>
            <hr>
            <p>Ops! Sua página ficou aberta por muito tempo e a sessão expirou. Por segurança, precisamos que você volte e tente novamente.</p>
            <a href="#" class="btn btn-sm btn-primary rounded-lg w-100 w-sm-auto" onclick="window.location.replace(document.referrer); return false;">Página anterior</a>
        </div>

    </body>
@endsection

