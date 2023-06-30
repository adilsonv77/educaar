@extends('layouts.mobile')

@section('content')
    <div class="card">
        <div class="card-body">
            <h2 class="mb-3">Relatório de atividades</h2>
            @if (!empty($activities))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Relatório</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activities as $activity)
                                @php
                                    $cont = 0;
                                @endphp
                                @foreach ($student_answers as $answer)
                                    @if ($answer->activity_id == $activity->id)
                                        @php
                                            $cont++;
                                        @endphp
                                    @break
                                @endif
                            @endforeach
                            @if ($cont == 0)
                                <tr>
                                    <td>{{ $activity->name }}</td>
                                    <td><img src="{{ $activity->qrcode }}"></td>
                                    <td><a href="/activity/{{ $activity->id }}" class="btn btn-primary">Iniciar</a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div>
                <h2>Nenhum conteúdo cadastrado</h2>
            </div>
        @endif
    </div>
</div>
@endsection
