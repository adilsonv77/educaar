@extends('layouts.app')

@section('page-name', $content->name)


@section('content')
    @can('student')
        <h1 class="text-center">Responda as questões abaixo</h1>

    @endcan
    <div class="card">
            <div class="card">
                <div class="card-body">


                    <model-viewer src="/uploads/{{ $content->glb }}" ios-src="/uploads/{{ $content->usdz }}" poster=""
                        alt="{{ $content->name }}" shadow-intensity="1" camera-controls auto-rotate ar>
                    </model-viewer>


                    
                </div>
            </div>

        @can('teacher')
            <div class="container">

                <div class="d-flex align-items-center">
                    <h2 class="mb-3">Questões</h2>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Questão</th>
                            <th scope="col">A</th>
                            <th scope="col">B</th>
                            <th scope="col">C</th>
                            <th scope="col">D</th>
                            <th scope="col">Resposta correta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($activity->questions as $item)
                            <tr>
                                <td>{{ $item->question }}</td>
                                <td>{{ $item->a }}</td>
                                <td>{{ $item->b }}</td>
                                <td>{{ $item->c }}</td>
                                <td>{{ $item->d }}</td>
                                <td>{{ $item->answer }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div>
                    <h2 class="mb-3">Relatório</h2>

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Alunos</th>
                                <th scope="col">Relatório</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                @foreach ($activityGradeUser as $grade)
                                    @if ($grade->user_id == $user->id)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td><a href="/activity/report/{{ $activity->id }}/{{ $user->id }}"
                                                    class="btn btn-primary">Visualizar</a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endcan

        @can('student')
            <div class="container questoes mb-3">
                @if (isset($timeEnterActivity))
                    <h2 class="mb-3">Questões</h2>
                    <form method="POST" action="/question/{{ $activity->id }}/{{ $timeEnterActivity }}"
                        enctype="multipart/form-data">

                        @foreach ($activity->questions as $key => $item)
                            @csrf

                            <div class="container">
                                <label for="">{{ $key + 1 }} - {{ $item->question }}</label>

                                <div class="radio-tile-group">
                                    <div class="input-container">
                                        <input id="fly" class="radio-button" required type="radio"
                                            name="question[{{ $key }}][{{ $item->id }}]"
                                            value="{{ $item->a }}" />
                                        <div class="radio-tile">

                                            <label for="fly" class="radio-tile-label">{{ $item->a }}</label>
                                        </div>
                                    </div>


                                    <div class="input-container">
                                        <input id="fly" class="radio-button" type="radio"
                                            name="question[{{ $key }}][{{ $item->id }}]"
                                            value="{{ $item->b }}" />
                                        <div class="radio-tile">

                                            <label for="fly" class="radio-tile-label">{{ $item->b }}</label>
                                        </div>
                                    </div>

                                    @if ($item->c !== null)
                                        <div class="input-container">
                                            <input id="fly" class="radio-button" type="radio"
                                                name="question[{{ $key }}][{{ $item->id }}]"
                                                value="{{ $item->c }}" />
                                            <div class="radio-tile">

                                                <label for="fly" class="radio-tile-label">{{ $item->c }}</label>
                                            </div>
                                        </div>

                                    @endif
                                    @if ($item->d !== null)
                                        <div class="input-container">
                                            <input id="fly" class="radio-button" type="radio"
                                                name="question[{{ $key }}][{{ $item->id }}]"
                                                value="{{ $item->d }}" />
                                            <div class="radio-tile">

                                                <label for="fly" class="radio-tile-label">{{ $item->d }}</label>
                                            </div>
                                        </div>

                                    @endif
                                </div>
                            </div>

                        @endforeach
                        <div class="form-group mt-4">
                            <input type="submit" value="Responder" class="btn btn-lg btn-success">
                        </div>

                        <input type="text" style="visibility:hidden" value="{{ $activity->id }}" name="activityId">
                    </form>
                @endif



                @if (!isset($timeEnterActivity))

                    @foreach ($activityGradeUser as $item)
                        <p>Acertos: {{ $item->correctQuestions }}</p>
                        <p>Erros: {{ $item->wrongQuestions }}</p>

                    @endforeach

                @endif

            </div>

        @endcan

    </div>
    </div>



@endsection

@section('script')

    <script type="module" src="/js/app.js"></script>

@endsection

@section('style')

    <style>
        #card {
            margin: 3em auto;
            display: flex;
            flex-direction: column;
            max-width: 600px;
            border-radius: 6px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }

        model-viewer {
            width: 100%;
            height: 550px;
            background-color: #70BCD1;
            --poster-color: #ffffff00;
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

    </style>
@endsection
