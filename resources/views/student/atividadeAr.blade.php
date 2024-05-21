@extends('layouts.mobile', ['back' => $rota, 'showBack' => true, 'showOthers' => false])

@section('page-name')

@section('script-head')
    <script>
        // history.forward();

        document.addEventListener("DOMContentLoaded", function() {
            var buttonsfooter = document.getElementById("buttons_footer");
            buttonsfooter.appendChild(document.createElement("div"));
            
            var buttonsalvarquestao = document.getElementById("salvarquestao");
            buttonsfooter.appendChild(buttonsalvarquestao);
            
        });


    </script>

@endsection

@section('content')


    <form action="{{ route('student.store') }}">
        @foreach ($questions as $item)
            @csrf
            <div class="">
                <div class="card-body">
                    <div>
                        <input name="id" type="hidden" value="{{ $item->id }}" />
                        <input name="id" type="hidden" value="{{ $item->id }}" />
                        <h2 type="submit" style=" font-size: 25px" class="text">
                            {{ $loop->iteration }}.{{ $item->question }}
                        </h2>
                    </div>
                    
                    
                    

                    <div class="card-body">
                        @foreach ($item->options as $option)
                        
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="questao{{ $item->id }}"
                                    id="flexRadioDefault{{ $loop->index }} {{ $item->id }}" value="{{ $loop->index }}"
                                    
                                    @if ($item->alternative_answered != NULL) 
                                        disabled 
                                        @if ($option == $item->alternative_answered) checked @endif
                                    @elseif ($item->alternative_answered == NULL and $loop->first) 
                                        checked 
                                    @endif>
                                <label for="flexRadioDefault{{ $loop->index }} {{ $item->id }}"
                                    class="form-check-label" style="font-size: 17px">
                                    {{ $option }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
        @endforeach
                    <div id="salvarquestao">
                        <button type="submit" @if ($respondida == 1) hidden="hidden" @endif
                            class="btn btn-success">Salvar</button>
                    </div>
    </form>

@section('style')
    <style>
        input[type="radio"] {
            border: 0px;
            width: 7%;
            height: 2em;
        }

        .form-check-label {

            font-family: arial;

        }
    </style>
@endsection


@endsection
