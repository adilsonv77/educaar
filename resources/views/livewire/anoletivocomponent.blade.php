<div>

    <script>
    window.addEventListener('openAnoLetivoModal', event => {
        $("#anoLetivoModal").modal('show');
    });
    </script>
    <br>
    <!--<button class="btn btn-sm btn-primary" wire:click="novo()">Novo</button>-->

    <div class="py-4 space-y-4">
        <div class="flex justify-between">
            <div class="w-1/4">
            </div>

            <div>
                <x-button.primary><x-icon.plus/>Novo</x-button.primary>
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
                                        
                                    </td>


                                    <td>
                                        
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
                       {{ $modalTitulo }} 
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
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
                            </div>
                        </div>

                    </form>


                </div>
            </div>
        </div>
    </div>

                

</div>