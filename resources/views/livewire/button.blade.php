<div class="button_Panel" data-id="{{ $button->id }}" style="border: 1px solid {{ $cor }};">
    <div id="{{ $button->id }}" class="circulo" style="background: {{ $cor }}"></div> {{ $texto ? $texto : "Botão ".$button->id}}
    <div id="buttonInfo" color="{{ $cor }}" transition="{{ $transicao }}" destination_id="{{ $painelDestino }}"></div>
</div>