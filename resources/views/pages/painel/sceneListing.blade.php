@extends('layouts.app')

@section('page-name', "Listagem de Cenas")

@section('script-head')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/telainicial.css?v=' . filemtime(public_path('css/telainicial.css'))) }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/panelListing.css?v=' . filemtime(public_path('css/panelListing.css'))) }}">
    <link rel="stylesheet" href="{{ asset('css/style.css?v=' . filemtime(public_path('css/style.css'))) }}">
@endsection

@section('content')
    @if (session('type') !== 'developer')
        <!-- Container para os botões -->
        <div class="buttons-container">
            <!-- Botão Novo -->
            <form action="{{ route('scenes.store') }}" method="POST">
                @csrf
                <input type="hidden" name="author_id" value="{{ Auth::user()->id }}">
                <input type="hidden" name="disciplina_id" value="">
                <button class="btn btn-primary btn-sm" id="novo" title="Novo" type="submit">
                    <i class="bi bi-plus-circle-dotted h1" style="color: #ffffff;"></i>
                </button>
            </form>
        </div>
    @endif

    <!-- Barra de pesquisa -->
    <form action="{{ route('scenes.index') }}" method="GET">
        <div class="form-inline">
            <label for="titulo">Informe o nome da cena :</label>
            <input maxlength="100" class="form-control" type="text" name="titulo" id="titulo"
                value="{{ request('titulo') }}" list="historico" />
            <section class="itens-group">
                <button class="btn btn-primary btn-lg" type="submit" style="border-radius: 0 20px 20px 0">Pesquisar</button>
            </section>
        </div>

        <datalist id="historico">
            @foreach ($data as $scene)
                <option value="{{ $scene->name }}">{{ $scene->name }}</option>
            @endforeach
        </datalist>
    </form>

    <br>

    <!-- Tabela de Listagem dos Painéis -->
    <div class="card">
        <div class="card-body">
            @if (!empty($data))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th>ID Cena</th>
                                <th>Nome</th>
                                <th>Disciplina</th>
                                <th>ID Painel Inicial</th>
                                <th>Abrir</th>
                                <th>Editar</th>
                                <th>Excluir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $scene)
                                <tr>
                                    <!-- ID CENA -->
                                    <td>{{ $scene->id }}</td>
                                    <!-- NOME CENA -->
                                    <td>{{ $scene->name }}</td>
                                    <!-- DISCIPLINA CENA (VER) -->
                                    <td>{{ $scene->disciplina->name ?? 'Sem disciplina' }}</td>
                                    <!-- PAINEL INICIAL -->
                                    <td>{{ $scene->start_panel_id ?? 'N/A'}}</td>
                                    <!-- VISUALIZAR CENA -->
                                    <td>
                                        <form action="{{ route('paineis.conexoes', [$scene->id]) }}" method="GET">
                                            @csrf
                                            <button type="submit" class="btn btn-primary" title="Editar">
                                                <i class="bi bi-eye-fill h2" style="color: #ffffff; font-size: 30px;"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <!-- <td>{{ json_decode($scene->panel)->midiaExtension ?? 'N/A' }}</td> -->
                                    <!-- EDITAR CENA -->
                                    <td>
                                        <form action="{{ route('scenes.edit', [$scene->id]) }}" method="GET">
                                            <form action="{{ route('scenes.edit', [$scene->id]) }}" method="GET">
                                                @csrf
                                                <button type="submit" class="btn btn-warning" title="Editar">
                                                    <i class="bi bi-pencil-square h2" style="color: #ffffff;"></i>
                                                </button>
                                            </form>
                                    </td>

                                    <!-- EXCLUIR CENA -->
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
                                                <h3>Você tem certeza que deseja excluir a cena
                                                    {{ json_decode($scene->name) }}
                                                </h3>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Cancelar</button>
                                                <form action="{{ route('scenes.destroy', [$scene->id]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" value="{{ $scene->id }}">
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
                    <h2>Nenhuma cena cadastrada</h2>
                </div>
            @endif
        </div>
    </div>
    <script src="{{ asset('js/panelListing.js?v=' . filemtime(public_path('js/panelListing.js'))) }}"></script>
@endsection