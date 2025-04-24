<div class="button_Panel" style="border: 1px solid {{ $cor }};">
    <div id="{{ $button->id }}" class="circulo" style="background: {{ $cor }}"></div> {{ $texto ? $texto : "Botão ".$button->id}}
    <div id="buttonInfo" color="{{ $cor }}"></div>
</div>