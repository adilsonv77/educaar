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

        document.addEventListener("DOMContentLoaded", function() {   
           
            
            /*
            var buttonsfooter = document.getElementById("buttons_footer");
            buttonsfooter.appendChild(document.createElement("div"));  

            var carregarquestao = document.getElementById("button-ar");
            //buttonsfooter.insertBefore(carregarquestao, buttonsfooter.firstElementChild.nextSibling); 

            var buttonsalvarquestao = document.getElementById("salvarquestao");
            buttonsfooter.appendChild(buttonsalvarquestao); 
            */
    
        });
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
                        Questões
                    </h5>
                </div>
                <div class="modal-body" style="max-height: 400px; overflow-y: scroll">
                    <div> <!--  class="scroll" -->
                        @if (!empty($questions))    
                        <form wire:submit.prevent="salvar" name="questoesform" >
                            @foreach ($questions as $item)
                                @csrf
                                <div class="">
                                    <div class="card-body">
                                        <div>
                                            <h2 style="font-size: 25px" class="text">
                                                {{ $loop->iteration }}.{{ $item->question }}
                                            </h2>
                                        </div>

                                        <div class="card-body">
                                            @foreach ($item->options as $option)
                                                <div class="form-check">
                                                    <!-- wire.model.defer para avisar que nao é preciso atualizar a tela a cada mudança -->
                                                    
                                                    <input 
                                                        wire:model.defer="alternativas.{{ $item->id }}"
                                                        class="form-check-input question-radio" type="radio" 
                                                        name="questao{{ $item->id }}"
                                                        id="flexRadioDefault{{ $loop->index }}{{ $item->id }}" 
                                                        value="{{ $loop->index }}"
                                                        @if ($item->alternative_answered != NULL) 
                                                            disabled 
                                                            @if ($option == $item->alternative_answered) checked @endif
                                                        @endif>
                                                    <label for="flexRadioDefault{{ $loop->index }}{{ $item->id }}"
                                                        class="form-check-label" style="font-size: 17px">
                                                        {{ $option }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                            @endforeach
                            <div><br/></div>
                            <div class="modal-footer">
                                <div id="salvarquestao">
                                    @if (!session('tipotrocado'))
                                        <button id="salvibutton" @if ($respondida == 1) hidden="hidden" @endif
                                                class="btn btn-success">Salvar</button>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-primary" data-dismiss="modal">
                                @if ($respondida == 1) Fechar @else Cancelar @endif
                                </button>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>

        function checkIfAllAnswered() {
    // data-dismiss="modal"
            // Seleciona todas as questoes
            const questions = document.querySelectorAll('.question-radio[name^="questao"]');
            const totalQuestions = new Set(Array.from(questions).map(input => input.name)).size;
            
            // Conta quantas questões foram respondidas
            const answeredQuestions = new Set(Array.from(questions).filter(input => input.checked).map(input => input.name)).size;
            
            // Habilita o botão "Salvar" se todas as questões foram respondidas
            document.getElementById('salvibutton').disabled = answeredQuestions !== totalQuestions;
        }
        document.querySelectorAll('.question-radio').forEach(input => {
            input.addEventListener('change', checkIfAllAnswered);
        });
    </script>
</div>