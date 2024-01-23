@extends('layouts.mobile' , ['back' => $rota, 'showBack' => true, 'showOthers' => false])

@section('content')
    <div class="">
        @can('student')
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover ">
                            <tbody>
                                @foreach ($conteudos as $item)
                                    <tr>
                                        <td>
                                            <form action="{{ route('student.showActivity') }}">
                                                @csrf
                                                <input name="id" type="hidden" value="{{ $item->id }}" />
                                                <button type="submit" class="btn btn-warning">{{ $item->name }}</button>
                                            </form>
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
            <!-- isso tem que sair pois está duplicado em mobile.css -->
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
                        height: 40%
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
        @endcan
    </div>
@endsection
