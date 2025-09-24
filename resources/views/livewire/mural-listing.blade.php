<div>
    <script>
        window.addEventListener('openMuralModal', event => {
            $("#muralModal").modal('show');
        });

        window.addEventListener('openConfirmarExcluirModal', event => {
            $("#confirmarExcluirModal").modal('show');
        });

        window.addEventListener('closeConfirmarExcluirModal', event => {
            $("#confirmarExcluirModal").modal('hide');
        });
    </script>

    <!-- Barra de pesquisa -->
    <div class="py-4 space-y-4">
        <div class="flex justify-between">
            <div class="w-1/4">
                <div class="form-inline">
                    <label for="disciplinaFiltro">Informe o mural : </label>
                    <input class="form-control" type="text" wire:model.defer="filtroTemp" list="disciplinas"
                        id="disciplinaFiltro" />
                    <section class="itens-group">
                        <button class="btn btn-primary btn-lg" type="button" wire:click="aplicarFiltro">Filtrar</button>
                    </section>
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
                <datalist id="historico">
                    @foreach ($murais as $mural)
                        <option value="{{ $mural->name }}">{{ $mural->name }}</option>
                    @endforeach
                </datalist>
            </div>

            @if (session('type') !== 'developer')
                <div>
                    <button class="btn btn-sm btn-primary" id="novo" wire:click="novo()" title="Novo mural">
                        <i class="bi bi-plus-circle-dotted h1" style="color: #ffffff;"></i>
                    </button>
                </div>
            @endif

        </div>
    </div>


    <br>

    <!-- Tabela de Listagem dos Murais -->
    <div class="card">
        <div class="card-body">
            @if (!empty($murais))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr style="text-align: center;">
                                <th>Nome</th>
                                <th>Disciplina</th>
                                <th>Abrir</th>
                                <th>Editar</th>
                                <th>Excluir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($murais as $mural)
                                <tr style="text-align: center;">
                                    <!-- NOME MURAL -->
                                    <td>{{ $mural->name }}</td>
                                    <!-- DISCIPLINA DO MURAL (VER) -->
                                    <td>{{ $mural->disciplina->name  }}</td>
                                    <!-- VISUALIZAR MURAL  -->
                                    <td>
                                        <form action="{{ route('mural2.view', [$mural->id]) }}" method="GET">
                                            @csrf
                                            <button type="submit" class="btn btn-primary" title="Editar">
                                                <i class="bi bi-eye-fill h2" style="color: #ffffff; font-size: 30px;"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <!-- EDITAR MURAL -->
                                    <td>

                                        <form action="{{ route('mural2.edit', [$mural->id]) }}" method="GET">
                                            @csrf
                                            <button type="submit" class="btn btn-warning" title="Editar">
                                                <i class="bi bi-pencil-square h2" style="color: #ffffff;"></i>
                                            </button>
                                        </form>
                                    </td>

                                    <!-- EXCLUIR MURAL -->
                                    <td>
                                        <button type="button" class="btn btn-danger"
                                            wire:click="confirmarExcluir({{ $mural->id }})" title="Excluir">
                                            <i class="bi bi-trash3 h2" style="color: #ffffff;"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div>
                    <h2>Nenhum mural cadastrado</h2>
                </div>
            @endif
        </div>
    </div>


    <div wire:ignore.self class="modal fade" id="muralModal" tabindex="-1" data-backdrop="static" data-keyboard="false"
        role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Adicionar Mural</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form wire:submit.prevent="salvar">
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Nome:</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                    wire:model="nome" required autofocus>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="disciplina_id" class="col-md-4 col-form-label text-md-right">Disciplina:</label>
                            <div class="col-md-6 select">
                                <select class="form-control" name="disciplina_id" aria-label=""
                                    wire:model="disciplina_id" id="disciplina_id" required>
                                    <option value="">Selecione uma disciplina</option>
                                    @foreach ($disciplinas as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="confirmarExcluirModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h3>Você tem certeza que deseja excluir o mural <b>{{ $muralExcluir }}</b>?</h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                    <button type="button" class="btn btn-danger" wire:click="excluir()">Sim</button>
                </div>
            </div>
        </div>
    </div>
</div>