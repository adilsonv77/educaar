@extends('layouts.mobile', ['back' => $rota, 'showBack' => true, 'showOthers' => false])

@section('content')

    <script src="{{ asset('js/student-avatar.js') }}"></script>

    <style>
        @media (max-width: 576px) {
            .container.d-flex.wrap.p-2 {
                flex-wrap: wrap;
            }
        }
    </style>

    @php
        $urlBaseMenu = "https://api.dicebear.com/9.x/toon-head/svg?seed=Luke&backgroundColor=b6e3f4";

        $peles = ['5c3829', 'a36b4f', 'b98e6a', 'c68e7a', 'f1c3a5'];

        $cabelosCima = ['bun', 'sideComed', 'spiky', 'undercut', 'none'];

        $cabelosBaixo = ['longStraight', 'longWavy', 'neckHigh', 'shoulderHigh', 'none'];

        $roupas = ['dress', 'openJacket', 'shirt', 'tShirt', 'turtleNeck'];

        $barbas = ['chin', 'chinMoustache', 'fullBeard', 'longBeard', 'moustacheTwirl', 'none'];

        $bocas = ['agape', 'angry', 'laugh', 'sad', 'smile'];

        $olhos = ['bow', 'happy', 'humble', 'wide', 'wink'];

        $sobrancelhas = ['angry', 'neutral', 'happy', 'sad', 'raised'];
    @endphp

    <div class="container px-3 px-md-0">
        <div class="d-flex flex-column align-items-center mx-auto mt-4 rounded" style="width: 100%; max-width: 800px; background-color: #f2e3ff; border: 2px solid #bb68ff;" id="student-profile">

        <div class="position-relative mx-auto mb-3 mt-5" style="width: 100px; height: 100px;">
            
            <div class="bg-light rounded-circle d-flex justify-content-center align-items-center w-100 h-100" style="overflow: hidden;">
                <img id="avatar-preview" src="https://api.dicebear.com/9.x/toon-head/svg?seed=Luke&backgroundColor=b6e3f4" alt="Avatar" style="width: 100%; height: auto;">
            </div>

            <a href="{{ route('student.avatar') }}" class="position-absolute d-flex justify-content-center align-items-center text-white text-decoration-none" style="bottom: 0px; right: 0px; width: 32px; height: 32px; background-color: #bb68ff; border-radius: 50%; border: 2px solid #f2e3ff;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                  <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                </svg>
            </a>
        </div>
        </div>
        
        <h2>Pele</h2>
        <div class="container d-flex wrap p-2" id="pele-container">
           @foreach ($peles as $index => $cor)
               <div class="card mx-auto" id="pele{{ $index + 1 }}" data-property="skinColor" data-value="{{ $cor }}" style="width: 18rem; background-color: #FAFAFA; cursor: pointer;">
                   <img src="{{ $urlBaseMenu }}&hairProbability=0&rearHairProbability=0&hairColor=000000&clothesColor=ffffff&skinColor={{ $cor }}" class="card-img-top rounded-circle mx-auto" style="width: 100px;" alt="Pele">
               </div>
           @endforeach
        </div>

        <h2>Cabelo (Cima)</h2>
        <div class="container d-flex wrap p-2" id="cabelo-container">
            @foreach ($cabelosCima as $index => $cabelo)
                @php
                    $parametroUrl = ($cabelo == 'none') ? '&hairProbability=0' : '&hair=' . $cabelo;
                @endphp

                <div class="card mx-auto" id="cabelo{{ $index + 1 }}" data-property="hair" data-value="{{ $cabelo }}" style="width: 18rem; background-color: #FAFAFA; cursor: pointer;">
                    <img src="{{ $urlBaseMenu }}&skinColor=ffffff&hairColor=000000&rearHairProbability=0&clothesColor=ffffff{{ $parametroUrl }}" class="card-img-top rounded-circle mx-auto" style="width: 100px;" alt="Cabelo">
                </div>
            @endforeach
        </div>

        <h2>Cabelo (Baixo)</h2>
        <div class="container d-flex wrap p-2" id="cabeloBaixo-container">
            @foreach ($cabelosBaixo as $index => $cabeloBaixo)
                @php
                    $parametroUrl = ($cabeloBaixo == 'none') ? '&rearHairProbability=0' : '&rearHair=' . $cabeloBaixo;
                @endphp

                <div class="card mx-auto" id="cabeloBaixo{{ $index + 1 }}" data-property="rearHair" data-value="{{ $cabeloBaixo }}" style="width: 18rem; background-color: #FAFAFA; cursor: pointer;">
                    <img src="{{ $urlBaseMenu }}&skinColor=ffffff&hairColor=000000&hairProbability=0&clothesColor=ffffff{{ $parametroUrl }}" class="card-img-top rounded-circle mx-auto" style="width: 100px;" alt="Cabelo Baixo">
                </div>
            @endforeach
        </div>

        <h2>Roupas</h2>
        <div class="container d-flex wrap p-2" id="roupas-container">
           @foreach ($roupas as $index => $roupa)
                <div class="card mx-auto" id="roupa{{ $index + 1 }}" data-property="clothes" data-value="{{ $roupa }}" style="width: 18rem; background-color: #FAFAFA; cursor: pointer;">
                     <img src="{{ $urlBaseMenu }}&hairProbability=0&rearHairProbability=0&hairColor=000000&skinColor=ffffff&clothes={{ $roupa }}" class="card-img-top rounded-circle mx-auto" style="width: 100px;" alt="Roupa">
                </div>
           @endforeach
        </div>

        <h2>Barba</h2>
        <div class="container d-flex wrap p-2" id="barba-container">
            @foreach ($barbas as $index => $barba)
                @php
                    $parametroUrl = ($barba == 'none') ? '&beardProbability=0' : '&beardProbability=100&beard=' . $barba;
                @endphp
                <div class="card mx-auto" id="barba{{ $index + 1 }}" data-property="beard" data-value="{{ $barba }}" style="width: 18rem; background-color: #FAFAFA;">
                    <img src="{{ $urlBaseMenu }}&hairProbability=0&rearHairProbability=0&hairColor=000000&skinColor=ffffff&clothesColor=ffffff{{ $parametroUrl }}" class="card-img-top rounded-circle mx-auto" style="width: 100px;" alt="Barba">
                </div>
            @endforeach
        </div>

        <h2>Boca</h2>
        <div class="container d-flex wrap p-2" id="boca-container">
            @foreach ($bocas as $index => $boca)
                <div class="card mx-auto" id="boca{{ $index + 1 }}" data-property="mouth" data-value="{{ $boca }}" style="width: 18rem; background-color: #FAFAFA;">
                    <img src="{{ $urlBaseMenu }}&hairProbability=0&rearHairProbability=0&hairColor=000000&skinColor=ffffff&clothesColor=ffffff&mouth={{ $boca }}" class="card-img-top rounded-circle mx-auto" style="width: 100px;" alt="Boca">
                </div>
            @endforeach
        </div>

        <h2>Olhos</h2>
        <div class="container d-flex wrap p-2" id="olhos-container">
            @foreach ($olhos as $index => $olho)
                <div class="card mx-auto" id="olho{{ $index + 1 }}" data-property="eyes" data-value="{{ $olho }}" style="width: 18rem; background-color: #FAFAFA;">
                    <img src="{{ $urlBaseMenu }}&hairProbability=0&rearHairProbability=0&hairColor=000000&skinColor=ffffff&clothesColor=ffffff&eyes={{ $olho }}" class="card-img-top rounded-circle mx-auto" style="width: 100px;" alt="Olho">
                </div>
            @endforeach
        </div>

        <h2>Sobrancelhas</h2>
        <div class="container d-flex wrap p-2" id="sobrancelhas-container">
            @foreach ($sobrancelhas as $index => $sobrancelha)
                <div class="card mx-auto" id="sobrancelha{{ $index + 1 }}" data-property="eyebrows" data-value="{{ $sobrancelha }}" style="width: 18rem; background-color: #FAFAFA;">
                    <img src="{{ $urlBaseMenu }}&hairProbability=0&rearHairProbability=0&hairColor=000000&skinColor=ffffff&clothesColor=ffffff&eyebrows={{ $sobrancelha }}" class="card-img-top rounded-circle mx-auto" style="width: 100px;" alt="Sobrancelha">
                </div>
            @endforeach
        </div>

        <form action="{{ route('student.avatar.update', $student->id) }}" method="POST" class="mt-4 mb-5 text-center">
            @csrf
            @method('PUT')
            <input type="hidden" name="avatar" id="avatar-input" value="https://api.dicebear.com/9.x/toon-head/svg?seed=Luke&backgroundColor=b6e3f4">
            <button type="submit" class="btn text-white" style="background-color: #bb68ff; font-weight: bold; padding: 10px 30px;">Salvar</button>
        </form>

    </div>
@endsection