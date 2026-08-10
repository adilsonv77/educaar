@extends('layouts.mobile', ['back' => $rota, 'showBack' => true, 'showOthers' => false])

@section('content')
    <script src="{{ asset('js/student-avatar.js') }}"></script>

    @php
        $urlBaseMenu = 'https://api.dicebear.com/9.x/toon-head/svg?seed=Luke';
        $coresFundo = ['b6e3f4', 'f4b6e3', 'e3f4b6', 'b6f4e3', 'e3b6f4'];
        $coresRoupas = ['0b3286', '147f3c', '731ac3', '151613', '545454', 'b11f1f', 'e8e9e6', 'eab308', 'ec4899', 'f97316'];
        $coresCabelo = ['2c1b18', '724133', 'a55728', 'b58143', 'd6b370', '1A1A1A', 'CFCFCF'];
        $peles = ['5c3829', 'a36b4f', 'b98e6a', 'c68e7a', 'f1c3a5'];
        $cabelosCima = ['bun', 'sideComed', 'spiky', 'undercut', 'none'];
        $cabelosBaixo = ['longStraight', 'longWavy', 'neckHigh', 'shoulderHigh', 'none'];
        $roupas = ['dress', 'openJacket', 'shirt', 'tShirt', 'turtleNeck'];
        $barbas = ['chin', 'chinMoustache', 'fullBeard', 'longBeard', 'moustacheTwirl', 'none'];
        $bocas = ['agape', 'angry', 'laugh', 'sad', 'smile'];
        $olhos = ['bow', 'happy', 'humble', 'wide', 'wink'];
        $sobrancelhas = ['angry', 'neutral', 'happy', 'sad', 'raised'];
    @endphp

    <style>
        .avatar-option-card {
            background-color: #FAFAFA;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: 2px solid transparent;
        }
        .avatar-option-card:hover, .avatar-option-card:active {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-color: #bb68ff;
        }
        .section-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #5a3286;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            text-align: center;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 0.5rem;
        }
    </style>

    <div class="container px-3 px-md-0" style="padding-bottom: 100px;">
        
        <div class="d-flex flex-column align-items-center mx-auto mt-4 mb-4 rounded shadow"
            style="position: sticky; top: 15px; z-index: 999; width: 100%; max-width: 300px; background-color: #f2e3ff; border: 2px solid #bb68ff;"
            id="student-profile">

            <div class="position-relative mx-auto mb-3 mt-3" style="width: 130px; height: 130px;">
                <div class="bg-light rounded-circle d-flex justify-content-center align-items-center w-100 h-100 shadow-sm"
                    style="overflow: hidden; border: 3px solid white;">
                    @php
                        $urlAvatar = Auth::user()->avatar ?? 'https://api.dicebear.com/9.x/toon-head/svg?seed=Luke&backgroundColor=b6e3f4';
                    @endphp
                    <img id="avatar-preview" src="{{ $urlAvatar }}" alt="Avatar" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            </div>
        </div>

        <h2 class="section-title">Cor de Fundo</h2>
        <div class="row justify-content-center" id="cor-fundo-container">
            @foreach ($coresFundo as $index => $cor)
                <div class="col-4 col-sm-3 col-md-2 mb-3">
                    <div class="card h-100 align-items-center py-2 avatar-option-card rounded" 
                         id="corFundo{{ $index + 1 }}" data-property="backgroundColor" data-value="{{ $cor }}">
                        <img src="{{ $urlBaseMenu }}&skinColor=ffffff&hairColor=000000&rearHairProbability=0&clothesColor=ffffff&backgroundColor={{ $cor }}"
                            class="rounded-circle img-fluid" style="max-width: 70px;" alt="Cor de Fundo">
                    </div>
                </div>
            @endforeach
        </div>

        <h2 class="section-title">Pele</h2>
        <div class="row justify-content-center" id="pele-container">
            @foreach ($peles as $index => $cor)
                <div class="col-4 col-sm-3 col-md-2 mb-3">
                    <div class="card h-100 align-items-center py-2 avatar-option-card rounded" 
                         id="pele{{ $index + 1 }}" data-property="skinColor" data-value="{{ $cor }}">
                        <img src="{{ $urlBaseMenu }}&backgroundColor=b6e3f4&hairProbability=0&rearHairProbability=0&hairColor=000000&clothesColor=ffffff&skinColor={{ $cor }}"
                            class="rounded-circle img-fluid" style="max-width: 70px;" alt="Pele">
                    </div>
                </div>
            @endforeach
        </div>

        <h2 class="section-title">Cabelo (Cima)</h2>
        <div class="row justify-content-center" id="cabelo-container">
            @foreach ($cabelosCima as $index => $cabelo)
                @php
                    $parametroUrl = $cabelo == 'none' ? '&hairProbability=0' : '&hair=' . $cabelo;
                @endphp
                <div class="col-4 col-sm-3 col-md-2 mb-3">
                    <div class="card h-100 align-items-center py-2 avatar-option-card rounded" 
                         id="cabelo{{ $index + 1 }}" data-property="hair" data-value="{{ $cabelo }}">
                        <img src="{{ $urlBaseMenu }}&backgroundColor=b6e3f4&skinColor=ffffff&hairColor=000000&rearHairProbability=0&clothesColor=ffffff{{ $parametroUrl }}"
                            class="rounded-circle img-fluid" style="max-width: 70px;" alt="Cabelo">
                    </div>
                </div>
            @endforeach
        </div>

        <h2 class="section-title">Cabelo (Baixo)</h2>
        <div class="row justify-content-center" id="cabeloBaixo-container">
            @foreach ($cabelosBaixo as $index => $cabeloBaixo)
                @php
                    $parametroUrl = $cabeloBaixo == 'none' ? '&rearHairProbability=0' : '&rearHair=' . $cabeloBaixo;
                @endphp
                <div class="col-4 col-sm-3 col-md-2 mb-3">
                    <div class="card h-100 align-items-center py-2 avatar-option-card rounded" 
                         id="cabeloBaixo{{ $index + 1 }}" data-property="rearHair" data-value="{{ $cabeloBaixo }}">
                        <img src="{{ $urlBaseMenu }}&backgroundColor=b6e3f4&skinColor=ffffff&hairColor=000000&hairProbability=0&clothesColor=ffffff{{ $parametroUrl }}"
                            class="rounded-circle img-fluid" style="max-width: 70px;" alt="Cabelo Baixo">
                    </div>
                </div>
            @endforeach
        </div>

        <h2 class="section-title">Cor do Cabelo</h2>
        <div class="row justify-content-center">
            @foreach ($coresCabelo as $index => $corCabelo)
                <div class="col-3 col-sm-2 col-md-2 mb-3">
                    <div class="card h-100 align-items-center py-3 avatar-option-card rounded" 
                         id="corCabelo{{ $index + 1 }}" data-property="hairColor" data-value="{{ $corCabelo }}">
                        <div class="rounded-circle shadow-sm" style="background-color: #{{ $corCabelo }}; width: 45px; height: 45px;"></div>
                    </div>
                </div>
            @endforeach
        </div>

        <h2 class="section-title">Roupas</h2>
        <div class="row justify-content-center" id="roupas-container">
            @foreach ($roupas as $index => $roupa)
                <div class="col-4 col-sm-3 col-md-2 mb-3">
                    <div class="card h-100 align-items-center py-2 avatar-option-card rounded" 
                         id="roupa{{ $index + 1 }}" data-property="clothes" data-value="{{ $roupa }}">
                        <img src="{{ $urlBaseMenu }}&backgroundColor=b6e3f4&hairProbability=0&rearHairProbability=0&hairColor=000000&skinColor=ffffff&clothes={{ $roupa }}"
                            class="rounded-circle img-fluid" style="max-width: 70px;" alt="Roupa">
                    </div>
                </div>
            @endforeach
        </div>

        <h2 class="section-title">Cor da Roupa</h2>
        <div class="row justify-content-center" id="cor-roupa-container">
            @foreach ($coresRoupas as $index => $corRoupa)
                <div class="col-3 col-sm-2 col-md-2 mb-3">
                    <div class="card h-100 align-items-center py-3 avatar-option-card rounded" 
                         id="corRoupa{{ $index + 1 }}" data-property="clothesColor" data-value="{{ $corRoupa }}">
                        <div class="rounded-circle shadow-sm" style="background-color: #{{ $corRoupa }}; width: 45px; height: 45px;"></div>
                    </div>
                </div>
            @endforeach
        </div>

        <h2 class="section-title">Barba</h2>
        <div class="row justify-content-center" id="barba-container">
            @foreach ($barbas as $index => $barba)
                @php
                    $parametroUrl = $barba == 'none' ? '&beardProbability=0' : '&beardProbability=100&beard=' . $barba;
                @endphp
                <div class="col-4 col-sm-3 col-md-2 mb-3">
                    <div class="card h-100 align-items-center py-2 avatar-option-card rounded" 
                         id="barba{{ $index + 1 }}" data-property="beard" data-value="{{ $barba }}">
                        <img src="{{ $urlBaseMenu }}&backgroundColor=b6e3f4&hairProbability=0&rearHairProbability=0&hairColor=000000&skinColor=ffffff&clothesColor=ffffff{{ $parametroUrl }}"
                            class="rounded-circle img-fluid" style="max-width: 70px;" alt="Barba">
                    </div>
                </div>
            @endforeach
        </div>

        <h2 class="section-title">Boca</h2>
        <div class="row justify-content-center" id="boca-container">
            @foreach ($bocas as $index => $boca)
                <div class="col-4 col-sm-3 col-md-2 mb-3">
                    <div class="card h-100 align-items-center py-2 avatar-option-card rounded" 
                         id="boca{{ $index + 1 }}" data-property="mouth" data-value="{{ $boca }}">
                        <img src="{{ $urlBaseMenu }}&backgroundColor=b6e3f4&hairProbability=0&rearHairProbability=0&hairColor=000000&skinColor=ffffff&clothesColor=ffffff&mouth={{ $boca }}"
                            class="rounded-circle img-fluid" style="max-width: 70px;" alt="Boca">
                    </div>
                </div>
            @endforeach
        </div>

        <h2 class="section-title">Olhos</h2>
        <div class="row justify-content-center" id="olhos-container">
            @foreach ($olhos as $index => $olho)
                <div class="col-4 col-sm-3 col-md-2 mb-3">
                    <div class="card h-100 align-items-center py-2 avatar-option-card rounded" 
                         id="olho{{ $index + 1 }}" data-property="eyes" data-value="{{ $olho }}">
                        <img src="{{ $urlBaseMenu }}&backgroundColor=b6e3f4&hairProbability=0&rearHairProbability=0&hairColor=000000&skinColor=ffffff&clothesColor=ffffff&eyes={{ $olho }}"
                            class="rounded-circle img-fluid" style="max-width: 70px;" alt="Olho">
                    </div>
                </div>
            @endforeach
        </div>

        <h2 class="section-title">Sobrancelhas</h2>
        <div class="row justify-content-center" id="sobrancelhas-container">
            @foreach ($sobrancelhas as $index => $sobrancelha)
                <div class="col-4 col-sm-3 col-md-2 mb-3">
                    <div class="card h-100 align-items-center py-2 avatar-option-card rounded" 
                         id="sobrancelha{{ $index + 1 }}" data-property="eyebrows" data-value="{{ $sobrancelha }}">
                        <img src="{{ $urlBaseMenu }}&backgroundColor=b6e3f4&hairProbability=0&rearHairProbability=0&hairColor=000000&skinColor=ffffff&clothesColor=ffffff&eyebrows={{ $sobrancelha }}"
                            class="rounded-circle img-fluid" style="max-width: 70px;" alt="Sobrancelha">
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 mb-5 text-center">
            <input type="hidden" id="avatar-input" 
                value="https://api.dicebear.com/9.x/toon-head/svg?seed=Luke&backgroundColor=b6e3f4"
                data-url="{{ route('student.avatar.update', $student->id) }}"
                data-token="{{ csrf_token() }}">
                
            <small class="text-success fw-bold" id="status-salvamento" style="display: none; transition: 0.3s; font-size: 1.1rem;">
                <i class="fas fa-check-circle"></i> Avatar salvo automaticamente!
            </small>
        </div>

    </div>
@endsection