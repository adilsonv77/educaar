@extends('layouts.app')

@php
    $pageName = 'Questões não-respondidas';
@endphp

@section('page-name', $pageName)

@section('style')
<style>
.nivel1 {

}

.nivel2 {

}
]</style>
@endsection

@section('content')

<div id="formTurma">

    <div class="card">
        <div class="card-body">
            @if (!empty($questoes))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                       
                        <tbody>
                            @foreach ($questoes as $content)
                                <tr>
                                    <td>{{ $content['content_name'] }}</td>

                                </tr>

                                @foreach ($content['activities'] as $activity)
                                    <tr>
                                        <td class="nivel1">&nbsp;&nbsp;-&nbsp;&nbsp;{{ $activity['activity_name'] }}</td>
                                    </tr>

                                    @foreach ($activity['alunos'] as $aluno)
                                        <tr>
                                            <td class="nivel2">&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;{{ $aluno }}</td>
                                        </tr>
                                    @endforeach

                                @endforeach

                            @endforeach

                        </tbody>
                    </table>

                 </div>
            @else
                <div>
                    <h2>Não existem alunos com questões não respondidas.</h2>
                </div>
            @endif
        </div>
    </div>



@endsection
