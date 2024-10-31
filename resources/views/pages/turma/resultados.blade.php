@extends('layouts.app')

@php
    $pageName = 'Resultados de uma turma';
@endphp

@section('page-name', $pageName)

@section('script-head')
<link rel="stylesheet" href="{{asset('css/resultados.css')}}">
@endsection
@section('content')

<div id="formTurma">
    <form action="{{ route('activity.results') }}" method="GET">
        @csrf
        <label for="">Informe a turma:</label>
        <div class="form-inline">
            <select class="form-control" name="turma_id">
                @foreach ($turmas as $item)
                    <option value="{{ $item->id }}" @if ($item->id === $turma->id) selected="selected" @endif>
                        {{ $item->nome }}
                    </option>
                @endforeach
            </select>
            <section class="itens-group">
                <button class="btn btn-primary btn-lg" type="submit">Pesquisar</button>
            </section>
        </div>
    </form>
    <br>
</div>

@if (!empty($contents))
    <div class="table-responsive">
        <table id="table">
            <thead>
                <tr>
                    <th rowspan="2"></th>
                    @foreach ($contents as $content)
                                @php
                                    $colspan_content = 0;
                                    foreach ($content['activities'] as $activity) {
                                        foreach ($activity['questions'] as $question) {
                                            $colspan_content++;
                                        }
                                    }
                                @endphp
                                <th class="contents" colspan="{{ $colspan_content }}">{{ $content['name'] }}</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($contents as $content)
                            @foreach ($content['activities'] as $activity)
                                    @php
                                        $colspan_activity = count($activity['questions']);
                                    @endphp
                                    <th class="activity" colspan="{{ $colspan_activity }}">{{ $activity['name'] }}</th>
                            @endforeach
                    @endforeach
                </tr>
                <tr>
                    <td class="aluno">Aluno</td>
                    @foreach ($contents as $content)
                        @foreach ($content['activities'] as $activity)
                            @foreach ($activity['questions'] as $question)
                                <!-- Tooltip com o texto da pergunta -->
                                 <div class="tooltip">
                                    <th title="{{ $question['question_text'] ?? 'Pergunta não encontrada' }}">Q{{ $loop->iteration }}</th>
                                 </div>
                            @endforeach
                        @endforeach
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($studentsResponses as $student_name => $contents_responses)
                    <tr>
                        <td class="student">{{ $student_name }}</td>
                        @foreach ($contents as $content_id => $content)
                            @foreach ($content['activities'] as $activity_id => $activity)
                                @foreach ($activity['questions'] as $question_id => $question)
                                    @if (isset($contents_responses[$content_id][$activity_id][$question_id]))
                                        @php
                                            $response = $contents_responses[$content_id][$activity_id][$question_id];
                                            $status = $response['status'];
                                            $answer = $response['answer'];
                                        @endphp
                                        <td title="{{ $answer }}">
                                            {{ $status }} <!-- Exibe o ícone de status -->
                                        </td>
                                    @else
                                        <td title="Aluno não respondeu">🟡</td>
                                    @endif
                                @endforeach
                            @endforeach
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection