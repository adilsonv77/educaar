@extends('layouts.app')

@section('page-name', "Listagem de painéis")

@section('script-head')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/telainicial.css?v=' . filemtime(public_path('css/telainicial.css'))) }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/panelListing.css?v=' . filemtime(public_path('css/panelListing.css'))) }}">
    <link href="/css/style.css" rel="stylesheet">
@endsection

@section('content')
    @if (session('type') !== 'developer')
        <!-- Container para os botões -->
        <div class="buttons-container">
            <!-- Botão Novo -->
            <form action="{{ route('scenes.store') }}" method="POST">
                @csrf
                <input type="hidden" name="author_id" value="1">
                <input type="hidden" name="disciplina_id" value="1">
                <button class="btn btn-primary btn-sm" id="novo" title="Novo" type="submit">
                    <i class="bi bi-plus-circle-dotted h1" style="color: #ffffff;"></i>
                </button>
            </form>

            <!-- Botão Conexões -->
            <form action="{{ route('paineis.conexoes') }}" method="GET">
                @csrf
                <button class="btn btn-warning btn-sm" type="submit" title="Conexões">
                    <i class="bi bi-link-45deg"></i> Conexões
                </button>
            </form>
        </div>
    @endif

    <!-- Barra de pesquisa -->
    <form action="{{ route('content.index') }}" method="GET">
        <div class="form-inline">
            <label for="">Informe o conteúdo :</label>
            <input maxlength="100" class="form-control" type="text" name="titulo" id="titulo" value="{{ $content }}"
                list="historico" />
            <section class="itens-group">
                <button class="btn btn-primary btn-lg" type="submit">Pesquisar</button>
            </section>
        </div>

        <datalist id="historico">
            @foreach ($data as $content)
                <option value="{{ $content->pesq_name }}">{{ $content->pesq_name }}</option>
            @endforeach
        </datalist>
    </form>
    <br>

    <style>
        .form-inline {
            display: flex;
            justify-content: flex-start;
            width: 100%;
        }

        .form-inline label {
            margin-right: 10px;
        }

        .form-control {
            flex-grow: 1;
            width: auto;

        }

        .btn-primary {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            border-top-right-radius: 20px;
            border-bottom-right-radius: 20px;
            border-left: none;
        }

        /* Estiliza o checkbox desmarcado */
        input[type="checkbox"] {
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid #86398e;
            border-radius: 4px;
            background-color: #ffffff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Estiliza o checkbox quando marcado */
        input[type="checkbox"]:checked {
            background-color: #86398e;
            border: none;
        }

        input[type="checkbox"]:checked::after {
            content: "✔";
            color: white;
            font-size: 16px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>

    <!-- Tabela de Listagem dos Painéis -->
    <div class="card">
        <div class="card-body">
            @if (!empty($data))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>ID</th>
                                <!-- <th>Selecionar para conexão</th> -->
                                <th>Tipo de mídia</th>
                                <th>Editar</th>
                                <th>Excluir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $scene)
                                <tr>
                                    <td>{{ $scene->id }}</td>
                                    <!-- <td>
                                        <form action="{{ route('paineis.conexoes', [$scene->id]) }}" method="GET">
                                            @csrf
                                            <button type="submit" class="btn btn-warning" title="Conexões">Conexões</button>
                                        </form>
                                    </td> -->
                                    <td>{{ json_decode($scene->panel)->midiaExtension ?? 'N/A' }}</td>

                                    <!-- Editar painel -->
                                    <td>
                                        <form action="{{ route('scenes.edit', [$scene->id]) }}" method="GET">
                                            @csrf
                                            <button type="submit" class="btn btn-warning" title="Editar">
                                                <i class="bi bi-pencil-square h2" style="color: #ffffff;"></i>
                                            </button>
                                        </form>
                                    </td>

                                    <!-- Excluir painel -->
                                    <td>
                                        <button type="button" class="btn btn-danger @if($scene->sendoUsado) disabled @endif"
                                            data-toggle="modal" data-target="#modal{{ $scene->id }}" title="Excluir">
                                            <i class="bi bi-trash3 h2" style="color: #ffffff;"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal de Confirmação de Exclusão -->
                                <div class="modal fade" id="modal{{ $scene->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <h3>Você tem certeza que deseja excluir o painel
                                                    {{ json_decode($scene->name) }}?
                                                </h3>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Cancelar</button>
                                                <form action="{{ route('paineis.destroy', ['id' => $scene->id]) }}" method="POST">
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
                </div>
            @else
                <div>
                    <h2>Nenhum painel cadastrado</h2>
                </div>
            @endif
        </div>
    </div>
    <script src="{{ asset('js/panelListing.js?v=' . filemtime(public_path('js/panelListing.js'))) }}"></script>
@endsection