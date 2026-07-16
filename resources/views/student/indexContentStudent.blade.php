@extends('layouts.mobile', ['back' => $rota, 'showBack' => true, 'showOthers' => false])

@section('content')

    <style>
        .btn-animado {

            background-image: linear-gradient(to right, #86398e 0%, #d400ff 50%, #86398e 100%);
            background-size: 200% auto;
            color: white;
            border: none;
            transition: all 0.5s ease;
        }

        .btn-animado:hover {
            background-position: right center;
            color: white;
            box-shadow: 0 8px 15px rgba(13, 110, 253, 0.4);
        }

        .btn-animado:active {
            transform: translateY(1px);
            box-shadow: 0 2px 5px rgba(13, 110, 253, 0.4);
        }
    </style>
    <div class="">
        @if (session('type') == 'student' || session('type') == 'developer')
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td class="text-center fw-bold py-3">
                                        Conteúdos Padrão
                                    </td>
                                </tr>
                                @php
                                    $mostrouDivisorJogos = false;
                                @endphp

                                @foreach ($conteudos->sortBy('is_jogo') as $conteudo)
                                    @if ($conteudo->is_jogo && !$mostrouDivisorJogos)
                                        <tr>
                                            <td class="text-center fw-bold py-3"
                                                style="border-top: 80px solid #ffffff;">
                                                Jogos
                                            </td>
                                        </tr>
                                        @php $mostrouDivisorJogos = true; @endphp
                                    @endif
                                    <tr>
                                        <td>
                                            <div class="d-flex gap-2 text-center">
                                                <form action="{{ route('student.showActivity') }}" method="get"
                                                    class="flex-grow-1 mr-2">
                                                    @csrf
                                                    <input name="id" type="hidden" value="{{ $conteudo->id }}" />
                                                    <input name="type" type="hidden" value="aluno" />

                                                    <button type="submit"
                                                        class="btn btn-warning flex-grow-1 {{ $conteudo->is_jogo ? 'btn-animado' : '' }}">
                                                        {{ $conteudo->name }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>




            @section('script')
                <script type="module" src="/js/app.js"></script>
            @endsection

            @section('style')
                <style>
                    body {
                        margin: 1em;
                        padding: 0;
                        font-family: Google Sans, Noto, Roboto, Helvetica Neue, sans-serif;
                        color: #244376;
                    }

                    #card {
                        margin: 3em auto;
                        display: flex;
                        flex-direction: column;
                        max-width: 600px;
                        border-radius: 6px;
                        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.25);
                        overflow: hidden;
                    }

                    .btn.btn-warning {
                        width: 100%;
                        height: 40%;


                    }

                    .btn.btn-success {
                        width: 100%;
                        height: 40%;
                        /* background-color: #efbecc;  */
                        background-color: gray;

                    }

                    .attribution {
                        display: flex;
                        flex-direction: row;
                        justify-content: space-between;
                        margin: 1em;
                    }

                    .attribution h1 {
                        margin: 0 0 0.25em;
                    }

                    .attribution img {
                        opacity: 0.5;
                        height: 2em;
                    }

                    .attribution .cc {
                        flex-shrink: 0;
                        text-decoration: none;
                    }

                    footer {
                        display: flex;
                        flex-direction: column;
                        max-width: 600px;
                        margin: auto;
                        text-align: center;
                        font-style: italic;
                        line-height: 1.5em;
                    }
                </style>
            @endsection
        @endif
    </div>
@endsection
