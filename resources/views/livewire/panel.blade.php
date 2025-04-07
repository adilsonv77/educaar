<div class="painel">                      
    <div class="txtPainel">{!! $painel->panel["txt"] !!}</div>

    <input type="hidden" class="inputTxtPainel" name="txt" 
        wire:model="texto" 
        wire:change="update">

    <div class="midia">
        <div class="no_midia" tabindex=0 @if($painel->panel["midiaType"]!="none")style="display: none"@endif>
            <img class="fileMidia" src="{{ asset('images/FileMidia.svg') }}">
        </div>

        <img src="{{ asset("midiasPainel/".$painel->panel["arquivoMidia"]) }}" 
             @if($painel->panel["midiaType"]!="image")style="display: none"@endif>

        <video id="vidMidia" controls @if($painel->panel["midiaType"]!="video")style="display: none"@endif>
            <source id="srcVidMidia" src="{{ asset("midiasPainel/".$painel->panel["arquivoMidia"]) }}" type="video/mp4">
        </video>

        <div class="videoContainer" @if($painel->panel["midiaType"]!="youtube")style="display: none"@endif>
            <iframe 
                id="srcYoutube"
                src="https://www.youtube.com/embed/{{$painel->panel["link"]}}?autoplay=0"
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

    <input type="hidden" name="link" value="{{$painel->panel["link"]}}">
    <input type="hidden" name="midiaExtension" value="{{ asset("midiasPainel/".$painel->panel["midiaExtension"]) }}">
    <input type="hidden" name="arquivoMidia" value="{{ asset("midiasPainel/".$painel->panel["arquivoMidia"]) }}">
</div>
