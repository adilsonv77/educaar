@extends('layouts.mobile', ['back' => $rota, 'showBack' => true, 'showOthers' => false])

@section('content')

<div class="container px-3 px-md-0" style="padding-bottom: 100px;">
    <div class="d-flex flex-column align-items-center mx-auto mt-4 rounded p-2 p-md-3" 
         style="width: 100%; max-width: 700px; background-color: #a156e4; border: 2px solid #7b01d8;" 
         id="student-profile">

        <div class="w-100 bg-white rounded p-3 p-md-4 d-flex flex-column align-items-center shadow-sm">
            
            <h2 class="mt-2 mb-3 text-center">Seu Perfil</h2>
            
            <div class="position-relative mb-3" style="width: 100px; height: 100px;">
                <div class="bg-light rounded-circle d-flex justify-content-center align-items-center w-100 h-100" style="overflow: hidden;">
                    @php
                        $urlAvatar = Auth::user()->avatar ?? "https://api.dicebear.com/9.x/toon-head/svg?seed=Luke&backgroundColor=b6e3f4";
                    @endphp
                    <img src="{{ $urlAvatar }}" alt="Avatar" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                </div>

                <a href="{{ route('student.avatar') }}" class="position-absolute d-flex justify-content-center align-items-center text-white text-decoration-none shadow" style="bottom: 0px; right: 0px; width: 32px; height: 32px; background-color: #bb68ff; border-radius: 50%; border: 2px solid #f2e3ff;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                      <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                    </svg>
                </a>
            </div>

            <h3 class="text-center text-break">{{ $student->name }}</h3>

            <div class="w-100 my-3" style="height: 1px; background-color: #3e2c4e7e;"></div>

            <div class="w-100 d-flex flex-column text-start px-2 px-md-3" id="student-info">
                <div class="mb-3">
                    <strong class="d-block d-md-inline text-muted mb-1" style="font-size: 1.1rem;">Email: </strong>
                    <span class="text-break">{{ $student->email }}</span>
                </div>
                <div class="mb-3">
                    <strong class="d-block d-md-inline text-muted mb-1" style="font-size: 1.1rem;">Escola: </strong>
                    <span class="text-break">{{ $student->escola->nome }}</span>
                </div>
                <div>
                    <strong class="d-block d-md-inline text-muted mb-1" style="font-size: 1.1rem;">Turma: </strong>
                    <span class="text-break">{{ $student->turma->nome }}</span>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection