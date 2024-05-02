<div>

    <script>
    window.addEventListener('openAnoLetivoModal', event => {
        $("#anoLetivoModal").modal('show');
    });

    window.addEventListener('closeAnoLetivoModal', event => {
        $("#anoLetivoModal").modal('hide');
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
                    <input class="form-control" type="text" wire:model="filtro"
                        list="historico" />
                    <section class="itens-group">
                        <button class="btn btn-primary btn-lg" type="button" wire:click="$refresh">Filtrar</button>
                        
                    </section>
                </div>
                <datalist id="historico">
                    @foreach ($anosletivos as $anoLetivo)
                        <option value="{{ $anoLetivo->name }}">{{ $anoLetivo->name }}</option>
                    @endforeach
                </datalist>           
            </div>

            <div>
               <button class="btn btn-sm btn-primary " id="novo" wire:click="novo()"><i class="bi bi-plus-circle-dotted h1" style = "color : #ffffff;"></i></button>
            </div>
        </div>
     </div>

    <div class="card">
        <div class="card-body">
            @if (!empty($anosletivos))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Ação</th>
                                <th>Excluir</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($anosletivos as $item)
                                <tr>

                                    <td>{{ $item->name }}</td>


                                    <td>
                                        <button class="btn btn-warning" wire:click="editar({{$item->id}})">
                                        <i class="bi bi-pencil-square h2" style = "color : #ffffff;"></i>
                                        </button>    
                                   </td>


                                    <td>
                                        <button type="button" class="btn btn-danger" wire:click="confirmarExcluir({{$item->id}})">
                                        <i class="bi bi-trash3 h2" style = "color : #ffffff;"></i>

                                        </button>

                                    </td>
                                </tr>
                                
                                </div>
                            @endforeach

                        </tbody>
                    </table>


                    <div class="d-flex justify-content-center">
                        {{ $anosletivos->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            @else
                <div>
                    <h2>Nenhum ano letivo cadastrado</h2>
                </div>
            @endif
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="anoLetivoModal" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                       {{$modalTitulo}} 
                    </h5>
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
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }} : </label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                    wire:model="name" required autocomplete="name" autofocus>

                            </div>
                        </div>               


                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button class="btn btn-primary">
                                    Salvar
                                </button>

                                <button type="button"  data-dismiss="modal" class="btn btn-primary">
                                    Cancelar
                                </button>
                            </div>
                        </div>

                    </form>


               </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="confirmarExcluirModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h3>Você tem certeza que deseja excluir o ano letivo
                        <b>{{ $anoLetivoExcluir }}?</b>
                    </h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">Não</button>
                    <button type="button" class="btn btn-danger" wire:click="excluir()">Sim</button>
                </div>
            </div>
        </div>
    </div>

                

</div>
