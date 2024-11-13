@extends('layouts.mobile', ['back' => $rota, 'showBack' => true, 'showOthers' => false])

@section('page-name')
@section('style')
<style>
.scroll {
            margin: 4px, 4px;
            padding: 4px;

            height: 80%;
            overflow-x: hidden;
            overflow-y: auto;
            text-align: justify;
        }
</style>
 @endsection

@section('script-head')
    <script>

        
             
        // history.forward();
        document.addEventListener("DOMContentLoaded", function() {     
            var buttonsfooter = document.getElementById("buttons_footer");
            buttonsfooter.appendChild(document.createElement("div"));  
            var buttonsalvarquestao = document.getElementById("salvarquestao");
            buttonsfooter.appendChild(buttonsalvarquestao); 
        });
        function submitForm() {
            
            document.questoesform.submit();
        }
    </script>
    <style>
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
    </style>
@endsection
@section('content')
<div class="scroll">
    <form name="questoesform" action="{{ route('student.store') }}">
        @foreach ($questions as $item)
            @csrf
            <div class="">
                <div class="card-body">
                    <div>
                        <input name="id" type="hidden" value="{{ $item->id }}" />
                        <h2 style="font-size: 25px" class="text">
                            {{ $loop->iteration }}.{{ $item->question }}
                        </h2>
                    </div>

                    <div class="card-body">
                        @foreach ($item->options as $option)
                            <div class="form-check">
                                <input class="form-check-input question-radio" type="radio" 
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
        <div id="salvarquestao">
            @if (!session('tipotrocado'))
                <button id="salvibutton" @if ($respondida == 1) hidden="hidden" @endif
                        class="btn btn-success" onclick="submitForm()">Salvar</button>
            @endif
        </div>
    </form>
</div>

<script>
    //inicia com o botao desligado
    document.getElementById('salvibutton').disabled = true;

    function checkIfAllAnswered() {
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



@endsection
