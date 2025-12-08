<div>
    <style>
    .scroll {
        margin: 4px, 4px;
        padding: 4px;

        height: 80%;
        overflow-x: hidden;
        overflow-y: auto;

            }
    input[type="radio"] {
        border: 0px;
        width: 7%;
        height: 2em;
    }
    .form-check-label {
        font-family: arial;
        margin-left: 15px;
        margin-top: 5px;
    }
    .modal-dialog {
        max-width: 100%;
    }
    #button-ar {
        position: fixed;
        bottom: 70px;
        right: 20px;
        width: 50px;
        height: 50px;
        z-index: 10;
    }
   /*
    @media (max-width: 640px    ) {
    .overflow-y-auto {
        -webkit-overflow-scrolling: touch; /* Scroll suave no iOS 
        overscroll-behavior-y: contain; /* Evita que o scroll "vaze" para o body 
        }
    
        .max-h-[70vh] {
            max-height: 70vh !important;
        }
    }
   */
    
    </style>
    
    <script>
        window.addEventListener('openQuestionsModal', event => {
            //inicia com o botao desligado
            document.getElementById('salvibutton').disabled = true;
                        
            $("#questionarioModal").modal('show');
        });
        
        window.addEventListener('showError', event => {
            $("#questionarioModal").modal('hide');
            $("#alertaModal").modal('show');
        });

        window.addEventListener('closeQuestionsModal', event => {
            $("#questionarioModal").modal('hide');
        });

        window.addEventListener('openFeedbackModal', event => {
            $('#questionarioModal').modal('hide');
            $('#feedbackModal').modal('show');
        });

        window.addEventListener('closeFeedbackModal', event => {
            $('#feedbackModal').modal('hide');
        })

        window.addEventListener('stopTimer', event => {
            $('#questionarioModal').modal('hide');
            $('#stopTimer').modal('show');
        });
    
        document.addEventListener("DOMContentLoaded", function() {   

            const timerSpan = document.getElementById('timerSpan');
            
            /*
            var buttonsfooter = document.getElementById("buttons_footer");
            buttonsfooter.appendChild(document.createElement("div"));  

            var carregarquestao = document.getElementById("button-ar");
            //buttonsfooter.insertBefore(carregarquestao, buttonsfooter.firstElementChild.nextSibling); 

            var buttonsalvarquestao = document.getElementById("salvarquestao");
            buttonsfooter.appendChild(buttonsalvarquestao); 
            */
    
        });



        function checkIfAllAnswered() {

            if (document.getElementById('salvibutton') == null)
               return;

            if (document.getElementById('salvibutton').getAttribute("respondida_ultima") == "1") {

                document.getElementById('salvibutton').disabled = true;


            } else {

                // Seleciona todas as questoes
                const questions = document.querySelectorAll('.question-radio[name^="questao"]');
                //const totalQuestions = new Set(Array.from(questions).map(input => input.name)).size;
                const totalQuestions = 1;
                
                // Conta quantas questões foram respondidas
                const answeredQuestions = new Set(Array.from(questions).filter(input => input.checked).map(input => input.name)).size;
                
                // Habilita o botão "Salvar" se todas as questões foram respondidas
                document.getElementById('salvibutton').disabled = answeredQuestions !== totalQuestions;

            }
        }

        window.addEventListener('checkAllPost', event => {
            document.querySelectorAll('.question-radio').forEach(input => {
                input.addEventListener('change', checkIfAllAnswered);
            });

            checkIfAllAnswered();
        });

        /* Eventos Timer */
        let intervalo = null;
        let restanteSegundos;
        let tempoMaximoMs;
        let inicio;

        window.addEventListener('startTimer', event => {
            if(event.detail?.tempoMaximo) {
                tempoMaximoMs = Number(event.detail?.tempoMaximo) * 1000;
            }
            const inicio = performance.now();

            if(intervalo === null) {
                intervalo = setInterval(() => {
                    const agora = performance.now();
                    const decorrido = agora - inicio;
                    const restanteMs = tempoMaximoMs - decorrido;
                    
                    restanteSegundos = Number(restanteMs/1000).toFixed(2);
                     
                    if(restanteMs <= 0) {
                        limparIntervalo();
                        dispatchEvent(new CustomEvent('timesOver'));
                        return;
                    }
                    atualizarTimer(); 
                }, 250);
            }
        });

        window.addEventListener('timesOver', event => {
            if(intervalo) {
                limparIntervalo();
            }
            limparIntervalo();
        });

        function handleSubmit() {
            if(event.target.matches('#salvibutton') || event.target.closest('#salvibutton')) {
                const teste = event.target.closest('[wire\\:id]').__livewire;
                teste.call('addTempo', ((tempoMaximoMs/1000) - restanteSegundos));
                setTimeout(() => {
                    teste.call('salvar');
                    limparIntervalo();
                    dispatchEvent(new CustomEvent('startTimer'));
                }, 100);
            }
        }

        function atualizarTimer() {
            if(restanteSegundos <= 0) {
                timerSpan.textContent = "00:00";
                return;
            } 

            const restanteInteiros = Math.floor(restanteSegundos);
            const minutos = Math.floor(restanteInteiros/60);
            const segundos = restanteInteiros % 60;
            const minutosF = String(minutos).padStart(2, '0');
            const segundosF = String(segundos).padStart(2, '0');

            timerSpan.textContent = `${minutosF}:${segundosF}`;
        }

        function limparIntervalo() {
            clearInterval(intervalo);
            intervalo = null;
        }
</script>

    <!-- wire:ignore foi necessario porque livewire escondia o botão assim que mostrava a janela de diálogo -->

    <div wire:ignore>
        <button id="button-ar" class="btn btn-warning" style="display: none;">
            <span><i style="color:#ffffff;" class="bi bi-book"></i></span>
        </button>
    </div>

    <div wire:ignore.self class="modal fade" id="alertaModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h5>
                        Erro ao salvar
                    </h5>
                    <h3>
                        Questionário já respondido por outro login. Você somente consegue retornar.
                    </h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="questionarioModal" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    
        <div class="modal-dialog modal-dialog-scrollable" role="document"> <!--  -->
            
            <div class="modal-content">  <!--  -->
                <div class="modal-header">
                    <h5 class="modal-title">
                        Questão  {{ ($nrquestao+1) }} de {{ $qtasquestoes }}
                    </h5>
                    @if($tempoMaximo != null)
                        <span id="timerSpan">
                            {{ gmdate('i:s', $tempoMaximo) }}
                        </span>
                    @endif
                </div>
                <div class="modal-body scroll" >
                    <div> <!--  class="scroll" -->
                        @if (!empty($questions))  
                        <form wire:submit.prevent="salvar" name="questoesform" >
                               <!-- tive que usar essa técnica porque não conseguia mais acessar questions a partir da segunda tela -->
                                @php $jsonDecodeValue = json_decode($questions,true); @endphp
                                @csrf
                                <div class="">
                                    <div class="card-body">
                                        
                                        <div>
                                            <h2 style="font-size: 25px" class="text">
                                                {{ ($nrquestao+1) }}.{{ $jsonDecodeValue[$nrquestao]['question'] }}
                                            </h2>
                                        </div>

                                    </div>
                                </div>
                                <div>
                                    @foreach ($jsonDecodeValue[$nrquestao]['options'] as $option)
                                    
                                        <div class="form-check">

                                        <!-- wire.model.defer para avisar que nao é preciso atualizar a tela a cada mudança  -->

                                            <input 
                                                wire:model.defer="alternativas.{{ $jsonDecodeValue[$nrquestao]['id'] }}"
                                                class="form-check-input question-radio" type="radio" 
                                                name="questao{{ $jsonDecodeValue[$nrquestao]['id'] }}"
                                                id="flexRadioDefault{{ $loop->index }}{{ $jsonDecodeValue[$nrquestao]['id'] }}" 
                                                value="{{ $loop->index }}"
                                                @if ($jsonDecodeValue[$nrquestao]['alternative_answered'] != NULL) 
                                                    disabled 
                                                    @if ($option == $jsonDecodeValue[$nrquestao]['alternative_answered']) checked @endif
                                                @endif>
                                                <label for="flexRadioDefault{{ $loop->index }}{{ $jsonDecodeValue[$nrquestao]['id'] }}"
                                                    class="form-check-label" style="font-size: 17px">
                                                    {{ $option }}
                                                </label>
                                            </input>
                                        </div>

        
                                    @endforeach
                                    <br/>
                                </div>

                                <div class="modal-footer">

                                    @if ($nrquestao > 0)
                                    <button type="button" class="btn btn-primary" wire:click="anterior()" title="Anterior"> 
                                        <span><i  style = "color:#ffffff;"class="bi bi-box-arrow-in-left" ></i></span>
                                    </button>
                                    @endif

                                    <div id="salvarquestao">
                                        <!-- @ if (!session('tipotrocado'))  -->
                                         <!-- @ if ($respondida == 1) hidden="hidden" @ endif -->
                                            <button id="salvibutton" 
                                                    class="btn btn-success" 
                                                    type="button"
                                                    onclick="handleSubmit()"
                                                    @if ($nrquestao == $qtasquestoes-1 && $respondida == 1) 
                                                        respondida_ultima="1" 
                                                    @else
                                                        respondida_ultima="0"
                                                    @endif
                                            >
                                                    @if ($nrquestao == $qtasquestoes-1 && $respondida != 1) 
                                                        <span><i  style = "color:#ffffff;"class="bi bi-save" ></i></span> 
                                                    @else 
                                                        <span><i  style = "color:#ffffff;"class="bi bi-box-arrow-in-right" ></i></span> 
                                                    @endif
                                            </button>
                                        <!-- @ endif -->
                                    </div>

                                    <button type="button" class="btn btn-primary" data-dismiss="modal"  wire:click="cancel()" title="@if ($respondida == 1) Fechar @else Cancelar @endif">
                                        <span><i  style = "color:#ffffff;"class="bi bi-x-square"></i></span>
                                    </button>

                                </div>
                                
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="feedbackModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h1>Respostas salvas!</h1>
                    <p>Acompanhe seus resultados abaixo:</p>
                </div>
                <div class="container my-4 max" style="max-height: 60vh; overflow-y: auto;">
                    @foreach($feedback as $questao)
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <p class="card-text mb-3">{{ $questao['question'] }}</p>

                                <div class="alert @if($questao['correct']) alert-success @else alert-danger @endif mb-0 d-flex align-items-center" role="alert">
                                    @if($questao['correct'])
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                    @else
                                        <i class="bi bi-x-circle-fill me-2"></i>
                                    @endif
                                    <div><strong>Sua resposta:</strong> {{ $questao['alternative_answered'] }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button wire:click="close()" type="button" class="btn btn-primary" data-dismiss="modal">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
<!-- Esse modal não está mais sendo usado
    <div wire:ignore.self class="modal fade" id="notAllowedModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h1>ATENÇÃO</h1>
                    <p>Você precisa concluir a atividade anterior para acessar as questões dessa atividade!</p>
                </div>
                <div class="modal-footer">
                    <button wire:click="closeNotAllowedModal" type="button" class="btn btn-primary" data-dismiss="modal">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
-->

    <div wire:ignore.self class="modal fade" id="stopTimer" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h5>
                        O tempo acabou!
                    </h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>