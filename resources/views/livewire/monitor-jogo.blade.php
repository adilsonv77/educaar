<div>
    @if($sala && $sala->aberta && $sala->started_at)
        <div wire:poll.5s="verificarFimDeJogo" style="position: absolute; top: 10px; right: 10px; z-index: 1000; background: rgba(255, 255, 255, 0.9); padding: 5px 15px; border-radius: 20px; font-weight: bold; color: #833B8D; border: 2px solid #833B8D;">
            <i class="bi bi-stopwatch"></i> 
            <span id="cronometroVisual">Calculando...</span>
        </div>

        <script>
            if (!window.cronometroIniciado) {
                window.cronometroIniciado = true;

                let horaFim = new Date("{{ \Carbon\Carbon::parse($sala->started_at)->addSeconds($sala->regra->tempo)->toIso8601String() }}").getTime();

                let intervalo = setInterval(function () {
                    let agora = new Date().getTime();

                    let distancia = horaFim - agora;
                    let tempoRestante = Math.floor(distancia / 1000);

                    if (tempoRestante > 0) {
                        let minutos = Math.floor(tempoRestante / 60);
                        let segundos = tempoRestante % 60;
                        
                        document.getElementById('cronometroVisual').innerText = 
                            String(minutos).padStart(2, '0') + ':' + 
                            String(segundos).padStart(2, '0');
                    } else {
                        document.getElementById('cronometroVisual').innerText = "Tempo Esgotado!";
                        clearInterval(intervalo); 
                        Livewire.emit('tempoAcabou');
                    }
                    
                }, 1000);
            }
        </script>

    @endif
</div>