<div>
    @if(!$jogoIniciado)
        <div wire:poll.2s="verificarStatus" style="position: absolute; top: 0; left: 0; width: 100vw; height: 100vh; background: white; z-index: 9999; display: flex; flex-direction: column; justify-content: center; align-items: center;">
            <h2 style="color: #823688;">{{ $mensagemStatus }}</h2>
            <div class="spinner-border text-warning" role="status"></div>
        </div>
    @endif
</div>