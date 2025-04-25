<div class="painel" data-painel-id="{{ $painel->id }}" data-texto="{{ $texto }}">

    <!--Esse php previne erros-->    
    <?php
        $panelData = is_string($painel->panel) ? json_decode($painel->panel, true) : $painel->panel;
    ?>                  

    <p class="idPainel" id="{{ $panelData["id"] }}">Painel ({{ $panelData["id"] }})</p>    

    <div class="txtPainel">{!! $panelData["txt"] !!}</div>

    <input type="hidden" class="inputTxtPainel" name="txt" 
        wire:model.lazy="texto">

    <div class="midia">
        <div class="no_midia" tabindex=0 @if($panelData["midiaType"]!="none")style="display: none"@endif>
            <img class="fileMidia" src="{{ asset('images/FileMidia.svg') }}">
        </div>

        <img class="imgMidia" src="{{ asset("midiasPainel/".$panelData["arquivoMidia"]) }}" 
             @if($panelData["midiaType"]!="image")style="display: none"@endif>

        <video class="vidMidia" controls @if($panelData["midiaType"]!="video")style="display: none"@endif>
            <source id="srcVidMidia" src="{{ asset("midiasPainel/".$panelData["arquivoMidia"]) }}" type="video/mp4">
        </video>

        <div class="videoContainer youtubeMidia" @if($panelData["midiaType"]!="youtube")style="display: none"@endif>
            <iframe 
                id="srcYoutube"
                src="https://www.youtube.com/embed/{{$panelData["link"]}}?autoplay=0"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
        </div>
    </div>

    <div class="areaBtns" class="btn-linhas" style="font-size: 12px;">
        <div id="layout" class="layout-{{ $btnFormat }}">
            @foreach ($buttonRenderizados as $button)
                @livewire('button', ['button' => $button, ""], key($button->id))
            @endforeach
        </div>
    </div>

    <input type="hidden" name="link" wire:model="link" wire:change="updateLink" value="{{$panelData["link"]}}" id="link-{{ $panelData["id"] }}"> <!--Link enviado-->
    <input type="file" name="midia" wire:model="midia" style="display: none" id="file-{{ $panelData["id"] }}"> <!--Arquivo enviado-->
    <input type="hidden" name="arquivoMidia" value="{{ asset("midiasPainel/".$panelData["arquivoMidia"]) }}"> <!--Nome arquivo-->
    <input type="hidden" name="midiaExtension" value="{{ asset("midiasPainel/".$panelData["midiaExtension"]) }}"> <!--Extenção arquivo-->   
</div>
