<div class="painel" draggable="true">
    <!--Esse php previne erros-->    
    <?php
        $panelData = is_string($painel->panel) ? json_decode($painel->panel, true) : $painel->panel;
    ?>                  
    <div class="txtPainel"><?php echo $panelData["txt"]; ?></div>

    <input type="text" class="inputTxtPainel" name="txt" 
        wire:model="texto" 
        wire:change="updateText">

    <div class="midia">
        <div class="no_midia" tabindex=0 @if($panelData["midiaType"]!="none")style="display: none"@endif>
            <img class="fileMidia" src="{{ asset('images/FileMidia.svg') }}">
        </div>

        <img src="{{ asset("midiasPainel/".$panelData["arquivoMidia"]) }}" 
             @if($panelData["midiaType"]!="image")style="display: none"@endif>

        <video id="vidMidia" controls @if($panelData["midiaType"]!="video")style="display: none"@endif>
            <source id="srcVidMidia" src="{{ asset("midiasPainel/".$panelData["arquivoMidia"]) }}" type="video/mp4">
        </video>

        <div class="videoContainer" @if($panelData["midiaType"]!="youtube")style="display: none"@endif>
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

    <input type="hidden" name="link" value="{{$panelData["link"]}}">
    <input type="hidden" name="midiaExtension" value="{{ asset("midiasPainel/".$panelData["midiaExtension"]) }}">
    <input type="hidden" name="arquivoMidia" value="{{ asset("midiasPainel/".$panelData["arquivoMidia"]) }}">
</div>
