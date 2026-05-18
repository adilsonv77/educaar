@extends('layouts.mobile', ['back' => $rota, 'showBack' => true, 'showOthers' => false])

@section('content')

<div class="container px-3 px-md-0">
    <div class="d-flex flex-column align-items-center mx-auto mt-4 rounded" style="width: 100%; max-width: 800px; background-color: #f2e3ff; border: 2px solid #bb68ff;" id="student-profile">

        <div class="position-relative mx-auto mb-3 mt-5" style="width: 100px; height: 100px;">
            
            <div class="bg-light rounded-circle d-flex justify-content-center align-items-center w-100 h-100" style="overflow: hidden;">
                <img src={{ $urlAvatar }} alt="Avatar" style="width: 100%; height: auto;">
            </div>

            <a href="{{ route('student.avatar') }}" class="position-absolute d-flex justify-content-center align-items-center text-white text-decoration-none" style="bottom: 0px; right: 0px; width: 32px; height: 32px; background-color: #bb68ff; border-radius: 50%; border: 2px solid #f2e3ff;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                  <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                </svg>
            </a>
            
        </div>
        <h2 class="text-center px-3">{{ $student->name }}</h2>

        <div class="vstack gap-3 mx-auto mb-4 mt-4 w-100 px-4" id="student-info">
            <div class="p-2"><span class="h4">Email: </span>{{ $student->email }}</div>
            <div class="p-2"><span class="h4">Escola: </span>{{ $student->escola->nome }}</div>
            <div class="p-2"><span class="h4">Turma: </span>{{ $student->turma->nome }}</div>
        </div>
        
    </div>
</div>

@endsection