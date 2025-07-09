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

    {{--<livewire:questionario-aluno-form :activity_id="$activity_id">/>--}}
    @livewire('questionario-aluno-form', ['activity_id' => $activity_id])

@endsection