@extends('layouts.app')

@section('page-name', $titulo)

@section('script-head')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/telainicial.css?v=' . filemtime(public_path('css/telainicial.css'))) }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/painel.css')}}">
    <style>
        #opaque-background {
            background-color: #D7D7D7;
            opacity: 0.7;
            position: absolute;
            z-index: 6;
            width: 100%;
            height: 100%;
        }

        #flex-container {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #popup {
            background-color: white;
            height: 600px;
            width: 600px;
            z-index: 8;
            border-radius: 19px;
            padding: 40px 40px;
        }

        #midiaInput {
            display: none;
        }

        .picture {
            width: 100%;
            aspect-ratio: 16/9;
            border-radius: 19px;
            background: #F0EBF1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #aaa;
            border: 2px dashed #833B8D;
            cursor: pointer;
            font-family: sans-serif;
            transition: color 300ms ease-in-out, background 300ms ease-in-out;
            outline: none;
            overflow: hidden;
            margin-top: 40px;
        }

        .picture:hover {
            color: #777;
            background: #E7DFE9;
        }

        .picture:active {
            background: #DFC1E4;
        }

        .picture:focus {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .picture__img {
            max-width: 100%;
        }

        .pInfo {
            color: #B8B5B5;
            font-size: 13px;
            margin-bottom: 30px;
            float: left;
        }

        #popup{
            box-shadow: -10px 12px 17px 0px rgba(0, 0, 0, 0.185);
        }

        #popup button {
            font-size: 15px;
            font-weight: 600;
            float: right;
            margin-top: 5px;
            background: none;
            color: black;
            border: none;
        }

        #popup button:focus {
            outline: none;
            box-shadow: none;
        }

        #popup p:nth-child(1) {
            font-size: 22px;
            font-weight: bold;
            float: left;
        }

        #pYoutube {
            font-size: 17px;
            color: #827E7E;
            font-weight: bold;
            margin-bottom: 5px;
        }

        #popup input[type="text"] {
            width: 100%;
            background-color: #EFEFEF;
            border: none;
            padding: 10px;
            border-radius: 8px;
        }
    </style>
@endsection

@section('bodyAccess')
    <!--Pop up upload de arquivo-->
    <!--Explicação: Ele teve que ficar dentro do body, ao colocar o elemento dentro da section content, ele fica dentro
                    de um "Main wrapper" que possui um tamanho menor que o tamanho inteiro da tela-->
    <div id="flex-container">
        <div id="opaque-background"></div>

        <div id="popup">

            <p>Upload file</p>
            <button onclick="fecharPopUp()">X</button>
            

            <label class="picture" for="midiaInput" tabIndex="0">
                <img src="{{ asset('icons/paineis/upload.svg') }}" alt="">
                <span class="picture__image"></span>
            </label>
            <!-- <input type="file" name="arquivoMidia" id="midiaInput" accept=".png, .jpeg, .jpg, .mp4"
                    onchange="upload_check()">
                    -este elemento foi movido para dentro do formulario no body, se mt tempo passou apagar comentário. Dia 27/02/25-->

            <p class="pInfo">Formatos suportados: MP4, JPG, JPEG, PNG</p>
            <p class="pInfo" style="float: right">Tamanho máximo: 50MB</p>
            <div style="clear: both;"></div>

            <p id="pYoutube">URL YouTube</p>
            <input type="text">
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <form action="{{route('paineis.store')}}" method="POST" enctype="multipart/form-data">
            @if ($action == 'edit')
                <input name="id" type="hidden" value={{$id}}>
            @endif
            <input name="action" type="hidden" value={{$action}} id="actionInput">
            @csrf
            <div class="row">
                <div class="col-md-8 coluna linha">
                    <div class="painel">
                        <textarea name="txtSuperior" id="txtSuperior" type="text" maxlength="117"
                            placeholder="Digite seu texto aqui"> @if ($action == 'edit') {{$txtSuperior}}
                            @endif</textarea>
                        <div id="espacoMidias">
                            <!-- selecione um tipo de midia -->
                            <div id="midia" tabindex=0 @if ($action == 'edit') style="display: none;" @endif>
                                <p>Selecione um:</p>
                                <div id="selectType">
                                    <button id="img">Imagem</button>
                                    <span>ou</span>
                                    <button id="vid">Vídeo</button>
                                </div>
                            </div>
                            <!-- preview da midia -->
                            <div id="midiaPreview" edit=@if($action == 'edit')"true" @else "false" style="display: none;"
                            @endif>
                                <video id="vidMidia" controls @if ($midiaExtension != "mp4") style="display: none" @endif>
                                    <source id="srcVidMidia"
                                        src="@if ($action == 'edit'){{asset('midiasPainel/' . $arquivoMidia)}}@endif"
                                        type="video/mp4">
                                </video>
                                <img src="@if ($action == 'edit'){{asset('midiasPainel/' . $arquivoMidia)}}@endif"
                                    id="imgMidia" @if($midiaExtension == "mp4") style="display: none" @endif>
                            </div>
                        </div>
                        <textarea name="txtInferior" id="txtInferior" type="text" maxlength="117"
                            placeholder="Digite seu texto aqui"> @if ($action == 'edit') {{$txtInferior}}
                            @endif</textarea>
                        <div id="areaBtns">
                            <button id="add">+</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 coluna">
                    <div id="configPainel">
                        <!--Selecione uma mídia-->
                        <p id="tituloSelecionado">Bloco de Mídia</p>
                        <hr>
                        <div id="blocoMidia">
                            <p>Tipo de mídia</p>
                            <input type="radio" name="midia" id="midia1">
                            <label for="midia1">Video</label>
                            <input type="radio" name="midia" id="midia2">
                            <label for="midia2">Imagem</label>
                            <hr>
                            <label for="youtubeLink" @if ($action == 'edit') value="{{$link}}" @endif>Youtube</label><br>
                            <input id="youtubeLink" placeholder="Ex: https://www.youtube.com/watch?v=4YEy" name="link"><br>
                            <label id="labelMyFile" for="myfile">Local (somente .png, .jpg, .jpeg)</label><br>
                            <input type="file" style="border:none" name="arquivoMidia" id="midiaInput"
                                accept=".png, .jpeg, .jpg, .mp4" onchange="upload_check()" /><br><br>
                        </div>
                        <div id="blocoTxt">

                        </div>
                        <div id="blocoBtn">

                        </div>
                        <input type="submit" value="Salvar" id="salvarPainel" class="submitPanel">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src="{{asset('js/painel.js')}}" type="module"></script>
    <script>
        function fecharPopUp() {
            document.getElementById("flex-container").style.display = 'none';
        }

        function abrirPopUp() {
            document.getElementById("flex-container").style.display = 'flex';
        }

        document.getElementById("midiaInput").addEventListener("change", () => {
            fecharPopUp();

            var upl = document.getElementById("midiaInput");
            var max = 160 * 1024 * 1024; // 50MB

            var alert = document.getElementById("alertaGLB");
            if (alert !== null) {
                alert.remove();
            }

            if (upl.files[0].size > max) {
                const div = document.createElement("div");

                upl.parentNode.insertBefore(div, upl.nextSibling);

                div.id = "alertaGLB"
                div.className = "alert alert-danger";

                const ul = document.createElement("ul");
                div.appendChild(ul);

                const li = document.createElement("li");
                li.innerHTML = "Tamanho máximo excedido (~" + Math.round(upl.files[0].size / 1024 / 1024) + "MB > 160MB)";
                ul.appendChild(li);

                upl.value = "";
            }

            //Input de arquivo derivado do código do qual a área de input foi retirado. Provavelmente não é necessário para o funcionamento de nada
            //     código comentado em 27/02/25 se uns meses tiverem passado e o código continua comentado, favor apagar código.  
            const inputFile = document.querySelector("#picture__input");
            const pictureImage = document.querySelector(".picture__image");
            const pictureImageTxt = "Choose an image";
            pictureImage.innerHTML = pictureImageTxt;

            inputFile.addEventListener("change", function (e) {
                const inputTarget = e.target;
                const file = inputTarget.files[0];

                if (file) {
                    const reader = new FileReader();

                    reader.addEventListener("load", function (e) {
                        const readerTarget = e.target;

                        const img = document.createElement("img");
                        img.src = readerTarget.result;
                        img.classList.add("picture__img");

                        pictureImage.innerHTML = "";
                        pictureImage.appendChild(img);
                    });

                    reader.readAsDataURL(file);
                } else {
                    pictureImage.innerHTML = pictureImageTxt;
                }
            });
        });
    </script>
@endsection