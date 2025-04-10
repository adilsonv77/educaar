<div class="painel" draggable="true">
    <!--Esse php previne erros-->    
    <?php
        $panelData = is_string($painel->panel) ? json_decode($painel->panel, true) : $painel->panel;
    ?>                  

    <p class="idPainel" id="{{ $panelData["id"] }}">Painel ({{ $panelData["id"] }})</p>    

    <div class="txtPainel">{{$texto}}</div>

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

    <div class="areaBtns" style="font-size: 12px;">
        <div class="button_Panel"><div class="circulo"></div> Botão 1</div>
        <div class="button_Panel"><div class="circulo"></div> Botão 2</div>
        <div class="button_Panel"><div class="circulo"></div> Botão 3</div>
    </div>

    <input type="hidden" name="link" wire:model.lazy="link" value="{{$panelData["link"]}}" id="link-{{ $panelData["id"] }}"> <!--Link enviado-->
    <input type="file" name="midia" wire:model.lazy="midia" style="display: none" id="file-{{ $panelData["id"] }}"> <!--Arquivo enviado-->
    <input type="hidden" name="arquivoMidia" value="{{ asset("midiasPainel/".$panelData["arquivoMidia"]) }}"> <!--Nome arquivo-->
    <input type="hidden" name="midiaExtension" value="{{ asset("midiasPainel/".$panelData["midiaExtension"]) }}"> <!--Extenção arquivo-->   
</div>
