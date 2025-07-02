@extends('layouts.app')

@php
    $pageName = 'Resultados de uma turma';
@endphp

@section('page-name', $pageName)

@section('script-head')
<link rel="stylesheet" href="{{asset('css/resultados.css')}}">

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const tableContainer = document.querySelector('.table-responsive');
        let isDragging = false;
        let startX;
        let scrollLeft;

        tableContainer.addEventListener("mousedown", (e) => {
            isDragging = true;
            tableContainer.classList.add("dragging");
            startX = e.pageX - tableContainer.offsetLeft;
            scrollLeft = tableContainer.scrollLeft;
        });

        tableContainer.addEventListener("mouseleave", () => {
            isDragging = false;
            tableContainer.classList.remove("dragging");
        });

        tableContainer.addEventListener("mouseup", () => {
            isDragging = false;
            tableContainer.classList.remove("dragging");
        });

        tableContainer.addEventListener("mousemove", (e) => {
            if (!isDragging) return; 
            e.preventDefault();
            const x = e.pageX - tableContainer.offsetLeft;
            const walk = (x - startX) * 2; 
            tableContainer.scrollLeft = scrollLeft - walk;
        });
    });
</script>

@endsection
@section('content')

<div id="formTurma">
    <form action="{{ route('turma.resultados') }}" method="GET ">
        @csrf

        <div class="form-inline">
            <label for="">Informe a turma:</label>

            <select class="form-control" name="turma_id">
                <option value="0"  @if ($turma_id === 0) selected="selected" @endif>
                    Todas as turmas
                </option> 
                @foreach ($turmas as $item)
                    <option value="{{ $item->id }}" @if ($item->id === $turma_id) selected="selected" @endif>
                        {{ $item->nome }}
                    </option>
                @endforeach
            </select>
            <button class="btn btn-primary btn-lg" type="submit">Pesquisar</button>
        </div>
    </form>
    <br>
</div>

<style>
    .form-inline {
        display: flex;
        justify-content: flex-start;
    }

    .form-inline label {

        margin-right: 10px;
    }
</style>

@if (!empty($contents))
    <div class="table-container">
        <div class="table-responsive">
            <table id="table">
                <thead>
                    <tr>
                        <th class="fixedCol" rowspan="2"></th>
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
                        <td class="aluno fixedCol">Aluno</td>
                        @foreach ($contents as $content)
                            @foreach ($content['activities'] as $activity)
                                @foreach ($activity['questions'] as $question)
                                    <!-- Tooltip com o texto da pergunta -->
                                    <th class="fixedRow" data-toggle="tooltip"
                                        title="{{ $question['question_text'] ?? 'Pergunta não encontrada' }}">
                                        Q{{ $loop->iteration }}
                                    </th>
                                @endforeach
                            @endforeach
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($studentsResponses as $student_name => $contents_responses)
                        <tr>
                            <td class="student fixedCol">{{ $student_name }}</td>
                            @foreach ($contents as $content_id => $content)
                                @foreach ($content['activities'] as $activity_id => $activity)
                                    @foreach ($activity['questions'] as $question_id => $question)
                                        @if (isset($contents_responses[$content_id][$activity_id][$question_id]))
                                            @php
                                                $response = $contents_responses[$content_id][$activity_id][$question_id];
                                                $status = $response['status'];
                                                $answer = $response['answer'];
                                            @endphp
                                            <td data-toggle="tooltip"
                                                title="Pergunta: {{ $question['question_text'] ?? 'Pergunta não encontrada' }} | Resposta: {{ $answer }}">
                                                {{ $status }} <!-- Exibe o ícone de status -->
                                            </td>
                                        @else
                                            <td data-toggle="tooltip" title="Aluno não respondeu">
                                                🟡
                                            </td>
                                        @endif
                                    @endforeach
                                @endforeach
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

@endsection