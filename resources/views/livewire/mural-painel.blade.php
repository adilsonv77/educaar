<?php
    $panelData = is_string($painel->panel) ? json_decode($painel->panel, true) : $painel->panel;
?>

@php
    $x = $panelData['x'] ?? null;
    $y = $panelData['y'] ?? null;
    $style = ($x !== null && $y !== null) ? "left: {$x}px; top: {$y}px;" : "";
@endphp

<div class="painel {{ $classes }}" id="p{{ $panelData['id'] }}" data-painel-id="{{ $painel->id }}" data-texto="{{ $texto }}" data-panel='@json($panelData)' style="{{ $style }}"> 

    <p class="idPainel" id="{{ $panelData["id"] }}">Painel ({{ $panelData["id"] }})</p>    

    <div class="txtPainel">{!! $panelData["txt"] !!}</div>

    <input type="hidden" class="inputTxtPainel" name="txt" 
        wire:model.lazy="texto">

    <div class="midia">
        <div class="loadedMidia">
            <div class="no_midia" tabindex=0 @if($panelData["midiaType"]!="none")style="display: none" @endif>
                <img class="fileMidia" src="{{ asset('images/FileMidia.svg') }}" draggable="false">
            </div>

            @if($panelData["midiaType"] != "none")
                @if($panelData["midiaType"] === "image")
                    <img class="imgMidia" src="{{ asset("midiasPainel/".$panelData["arquivoMidia"]) }}?v={{ random_int(0,10000) }}" 
                        draggable="false"/>
                @endif

                
                @if($panelData["midiaType"] === "video")
                    <video class="vidMidia" controls draggable="false">
                        <source id="srcVidMidia" src="{{ asset("midiasPainel/".$panelData["arquivoMidia"]) }}" type="video/mp4" draggable="false">
                    </video>
                @endif

                @if($panelData["midiaType"] === "youtube")
                    <div class="videoContainer youtubeMidia" >
                        <iframe
                            id="srcYoutube"
                            src="https://www.youtube.com/embed/{{$panelData["link"]}}?autoplay=0"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    </div>
                @endif
            @endif
        </div>
        <div class="loading" style="display: none">
            <img src="{{ asset("images/circle.png") }}" alt="Carregando midia...">
        </div>
    </div>

    <div class="areaBtns" class="btn-linhas" style="font-size: 12px;">
        <div class="loading" style="display: none; align-self: center;">
            <img src="{{ asset("images/circle.png") }}" alt="Carregando midia...">
        </div>
        <div id="layout" class="layout-{{ $btnFormat }}">
            @foreach ($buttonRenderizados as $button)
                @livewire('mural-button', ['button' => $button, 'style' => "border: 1px solid"], key($button->id))
            @endforeach
            @if (count($buttonRenderizados) < $maxButtons)

                <button class="criadorButton button_Panel placeholder disabled-look" style="border: 1px dotted #833B8D; opacity: 0.4; cursor: pointer;"
                    wire:click.prevent="createButton( {{ json_encode(['id' => $painel->id]) }} )">
                    <div class="circulo" style="background: #833B8D;"></div> 
                    Criar botão
                </button>

            @endif
        </div>
    </div>

    <input type="hidden" name="link" wire:model="link" wire:change="updateLink" value="{{$panelData["link"]}}" id="link-{{ $panelData["id"] }}"> <!--Link enviado-->
    <input type="file" name="midia" wire:model="midia" style="display: none" id="file-{{ $panelData["id"] }}"> <!--Arquivo enviado-->
    <input type="hidden" name="arquivoMidia" value="{{ asset("midiasPainel/".$panelData["arquivoMidia"]) }}"> <!--Nome arquivo-->
    <input type="hidden" name="midiaExtension" value="{{ asset("midiasPainel/".$panelData["midiaExtension"]) }}"> <!--Extenção arquivo-->   
</div>
