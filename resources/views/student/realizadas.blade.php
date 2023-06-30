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
                            @foreach ($student_answers as $answer)
                                @foreach ($activities as $activity)
                                    @if ($activity->id == $answer->activity_id)
                                        <tr>
                                            <td>{{ $activity->name }}</td>
                                            <td><a href="/activity/{{ $activity->id }}" class="btn btn-primary">Visualizar</a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
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
