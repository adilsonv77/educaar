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
                <label for="">{{ __('Enter the School Year') }} : </label>
                    <input class="form-control" type="text" wire:model.defer="filtroTemp"
                        list="historico" />
                    <section class="itens-group">
                        <button class="btn btn-primary btn-lg" type="button" wire:click="aplicarFiltro">{{ __('Filter') }}</button>
                        
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
                <datalist id="historico">
                    @foreach ($anosletivos as $anoLetivo)
                        <option value="{{ $anoLetivo->name }}">{{ $anoLetivo->name }}</option>
                    @endforeach
                </datalist>           
            </div>

            <div>
               <button class="btn btn-sm btn-primary " id="novo" wire:click="novo()" title={{ __('New') }}><i class="bi bi-plus-circle-dotted h1" style = "color : #ffffff;"></i></button>
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
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Edit') }}</th>
                                <th>{{ __('Delete') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($anosletivos as $item)
                                <tr>

                                    <td>{{ $item->name }}</td>


                                    <td>
                                        <button class="btn btn-warning" wire:click="editar({{$item->id}})" title="Editar">
                                        <i class="bi bi-pencil-square h2" style = "color : #ffffff;"></i>
                                        </button>    
                                   </td>


                                    <td>
                                        <button type="button" class="btn btn-danger" wire:click="confirmarExcluir({{$item->id}})" title="Excluir">
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
                    <h2>{{ __('No school year registered') }}</h2>
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

                    <form wire:submit.prevent="salvar"  autocomplete="off">
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }} : </label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                    wire:model="name" required autofocus>

                            </div>
                        </div>               
                        
                        <div class="modal-footer">
                                <button class="btn btn-primary">
                                    {{ __('Save') }}
                                </button>

                                <button type="button"  data-dismiss="modal" class="btn btn-primary">
                                    {{ __('Cancel') }}
                                </button>

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
                    <h3> {{ __('Confirm delete school year :school_year', ["school_year" => $anoLetivoExcluir]) }} </h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ __('No') }}</button>
                    <button type="button" class="btn btn-danger" wire:click="excluir()">{{ __('Sim') }}</button>
                </div>
            </div>
        </div>
    </div>

                

</div>
