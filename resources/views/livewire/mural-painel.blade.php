<div>
    <?php
        $panelData = is_string($painel->panel) ? json_decode($painel->panel, true) : $painel->panel;
    ?>

    @php
        $x = $panelData['x'] ?? null;
        $y = $panelData['y'] ?? null;
        $style = ($x !== null && $y !== null) ? "left: {$x}px; top: {$y}px;" : "";
    @endphp

    <div wire:ignore.self class="modal fade" id="flex-container-{{ $panelData['id'] }}" tabindex="-1" data-backdrop="static" data-keyboard="false"
        role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mídia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="popup">
                        <form wire:submit.prevent="salvarMidia">
                            <p>Upload file</p>
                            <label id="upload-area" class="picture" tabIndex="0">
                                <img src="{{ asset('icons/paineis/upload.svg') }}" alt="">
                                <span class="picture__image"></span>
                            </label>

                            <p class="pInfo">Formatos suportados: MP4, JPG, JPEG, PNG</p>
                            <p class="pInfo" style="float: right">Tamanho máximo: 50MB</p>
                            <div style="clear: both;"></div>

                            <p id="pYoutube">URL YouTube</p>
                            <input id="linkYoutube" type="text" wire:model="link">

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


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

        <input type="file" name="midia" wire:model="midia" style="display: none" id="file-{{ $panelData["id"] }}"> <!--Arquivo enviado-->
        <input type="hidden" name="arquivoMidia" value="{{ asset("midiasPainel/".$panelData["arquivoMidia"]) }}"> <!--Nome arquivo-->
        <input type="hidden" name="midiaExtension" value="{{ asset("midiasPainel/".$panelData["midiaExtension"]) }}"> <!--Extenção arquivo-->   
    </div>
</div>