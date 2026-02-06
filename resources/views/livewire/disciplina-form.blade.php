<div>

    <script>
        window.addEventListener('openDisciplinaModal', event => {
            $("#disciplinaModal").modal('show');
        });

        window.addEventListener('closeDisciplinaModal', event => {
            $("#disciplinaModal").modal('hide');
        });

        window.addEventListener('openConfirmarExcluirModal', event => {
            $("#confirmarExcluirModal").modal('show');
        });

        window.addEventListener('closeConfirmarExcluirModal', event => {
            $("#confirmarExcluirModal").modal('hide');
        });
    </script>

    <div class="py-4 space-y-4">
        <div class="flex justify-between">
            <div class="w-1/4">
                <div class="form-inline">
                <label for="">Informe a disciplina : </label>
                    <input class="form-control" type="text" wire:model.defer="filtroTemp" list="disciplinas" />
                    <section class="itens-group">
                        <button class="btn btn-primary btn-lg" type="button" wire:click="aplicarFiltro">Filtrar</button>
                    </section>
                </div>

                <style>
                .form-inline{
                    display: flex;
                    justify-content: flex-start; 
                }

                .form-inline label {
                    margin-right: 10px;
                }
                
                </style>
                <datalist id="disciplinas">
                    @foreach ($disciplinas as $disciplina)
                        <option value="{{ $disciplina->name }}">{{ $disciplina->name }}</option>
                    @endforeach
                </datalist>           
            </div>

            <div>
               <button class="btn btn-sm btn-primary" id="novo" wire:click="novo()" title="Nova Disciplina">
                   <i class="bi bi-plus-circle-dotted h1" style="color: #ffffff;"></i>
               </button>
            </div>
        </div>
     </div>

    <div class="card">
        <div class="card-body">
            @if (!empty($disciplinas))
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Editar</th>
                                <th>Excluir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($disciplinas as $disciplina)
                                <tr>
                                    <td>{{ $disciplina->name }}</td>
                                    <td>
                                        <button class="btn btn-warning" wire:click="editar({{ $disciplina->id }})" title="Editar">
                                            <i class="bi bi-pencil-square h2" style="color: #ffffff;"></i>
                                        </button>    
                                   </td>
                                    <td>
                                        <button type="button" class="btn btn-danger" wire:click="confirmarExcluir({{ $disciplina->id }})" title="Excluir">
                                            <i class="bi bi-trash3 h2" style="color: #ffffff;"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center">
                        {{ $disciplinas->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            @else
                <div>
                    <h2>Nenhuma disciplina cadastrada</h2>
                </div>
            @endif
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="disciplinaModal" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $modalTitle }}</h5>
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

                    <form wire:submit.prevent="salvar" autocomplete="off">
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Nome:</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name" required autofocus>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
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

    <div wire:ignore.self class="modal fade" id="confirmarExcluirModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h3>Você tem certeza que deseja excluir a disciplina <b>{{ $disciplinaExcluir }}</b>?</h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                    <button type="button" class="btn btn-danger" wire:click="excluir()">Sim</button>
                </div>
            </div>
        </div>
    </div>

</div>
