@extends('layouts.app')

@php
    $pageName = 'Conteúdos';
@endphp

@section('style')
    <link rel="stylesheet" href="/css/list_content.css">
@endsection

@section('page-name', $pageName)

@section('script-head')
@endsection

@section('content')
    <form action="{{ route('content.index') }}" method="GET">
        <div class="form-inline">
            <label for="">Informe o conteúdo :</label>
            <input maxlength="100" class="form-control" type="text" name="titulo" id="titulo" value="{{ $content }}"
                list="historico" />
            <button class="btn btn-primary btn-lg" type="submit">Pesquisar</button>

        </div>

        <datalist id="historico">
            @foreach ($contents as $content)
                <option value="{{ $content->pesq_name }}">{{ $content->pesq_name }}</option>
            @endforeach
        </datalist>
    </form>
    <br>

    <style>
        .form-inline {
            display: flex;
            justify-content: flex-start;
        }

        .form-inline label {

            margin-right: 10px;
        }
    </style>

    @if (session('type') !== 'developer')
        <div>
            <form action="{{ route('content.create') }}">
                @csrf
                <button class="btn btn-smaller, btn-primary " id="novo" title="Novo"><i
                        class="bi bi-plus-circle-dotted h1" style = "color : #ffffff;"></i></button>
            </form>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if (!empty($contents))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr style="text-align: center;">
                                <th>Nome</th>
                                <th>Disciplina</th>
                                <th>Série</th>
                                <th>Fechar</th>
                                @if (session('type') == 'teacher')
                                    <th>Resultados</th>
                                @endif
                                @if (session('type') !== 'developer')
                                    <th>Selecionar Devs</th>
                                    <th>PDF</th>
                                    <th>Editar</th>
                                    <th>Excluir</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($contents as $item)
                                <tr style="text-align: center;">

                                    <td>{{ $item->content_name }}</td>
                                    <td>{{ $item->disc_name }}</td>
                                    <td>{{ $item->turma_name }}</td>


                                    <!-- Açoes -->
                                    <td>

                                    @php
                                        
                                    $isFecharEnabled = ($item->qtasatividades > 0 && !$item->fechado && $item->qtasQuestoes > 0);
                                   
                                    $isPdfDisabled = $isFecharEnabled || $item->qtasatividades == 0 || $item->qtasQuestoes == 0;
                                    @endphp

                                    <form action="{{ route('fechar.index') }}">
                                        @csrf
                                        <input type="hidden" name="content" value="{{ $item->id }}">
                                        <button type="submit" id="FecharConteudo" class="btn btn-info"
                                            {{ !$isFecharEnabled ? 'disabled' : '' }}
                                            @if ($item->qtasQuestoes == 0) title="Sem questões" 
                                            @elseif ($item->fechado) title="Já fechado" 
                                            @else title="Fechar" @endif>
                                            <i class="bi bi-lock-fill h2" style="color: #ffffff;"></i>
                                            ({{ $item->qtasatividades }})
                                        </button>
                                    </form>
                                    </td>


                                    @if (session('type') == 'teacher')
                                        <!-- Resultados -->
                                        <td>
                                            <form action="{{ route('content.resultsContents') }}">
                                                @csrf
                                                <input type="hidden" name="content_id" value="{{ $item->id }}">
                                                <button type="submit" class="btn btn-success"
                                                    @if ($item->qtasQuestoes == 0) title="Sem questões" @else title="Resultados" @endif
                                                    @if ($item->qtasatividades == 0 or $item->qtasQuestoes == 0) disabled @endif>
                                                    <i class="bi bi-journal-bookmark h2" style = "color : #ffffff;"></i>
                                                </button>
                                            </form>
                                        </td>
                                    @endif
                                    @if (session('type') !== 'developer')
                                        <td>
                                            <form action="{{ route('dev.listDevs') }}">
                                                @csrf
                                                <input type="hidden" name="content" value="{{ $item->id }}">
                                                <button type="submit" class="btn btn-warning" title="Selecionar Devs">
                                                    <i class="bi bi-person-fill-gear h2" style = "color : #ffffff;"></i>
                                                </button>
                                            </form>
                                        </td>



                                        <!--Pdf-->
                                        <td>
                                        
                                            <!-- Botão de Visualizar PDF -->
                                            <form action="{{ route('content.atividades.pdf', ['id' => $item->id]) }}" method="GET" target="_blank" style="display: inline;">
                                                <button type="submit" 
                                                        class="btn btn-warning" 
                                                        title="Visualizar PDF" 
                                                        {{ $isPdfDisabled ? 'disabled' : '' }}>
                                                    <i class="bi bi-filetype-pdf h2" style="color: #ffffff;"></i>
                                                </button>
                                            </form>
                                        </td>



                                        
                                        <td>
                                            <!-- Editar -->
                                            <form action="{{ route('content.edit', $item->id) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-warning" title="Editar">
                                                    <i class="bi bi-pencil-square h2" style = "color : #ffffff;"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <!-- Excluir -->
                                            <button type="button"
                                                class="btn btn-danger  @if ($item->qtasatividades > 0) disabled @endif"
                                                data-toggle="modal" data-target="#modal{{ $item->id }}"
                                                title="Excluir">
                                                <i class="bi bi-trash3 h2" style = "color : #ffffff;"></i>
                                            </button>
                                        </td>
                                    @endif
                                </tr>
                                <div class="modal fade" id="modal{{ $item->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <h3>Você tem certeza que deseja excluir o conteúdo
                                                    {{ $item->content_name }}?</h3>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Cancelar</button>
                                                <form action="{{ route('content.destroy', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Excluir</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </tbody>
                    </table>
                    <div class="modal fade" id="modalSemRespostas" tabindex="-1" role="dialog"
                        aria-labelledby="SemRespostasModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <h3>Nenhum aluno respondeu alguma atividade desse conteúdo.</h3>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $contents->links('vendor.pagination.bootstrap-4') }}
                    </div>

                </div>
            @else
                <div>
                    <h2>Nenhum conteúdo cadastrado</h2>
                </div>
            @endif
        </div>
    </div>



@endsection
