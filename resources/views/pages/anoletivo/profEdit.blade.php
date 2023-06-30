@extends('layouts.app')

@section('style')
    <link rel="stylesheet" type="text/css" href="/css/bootstrap-duallistbox.css">
    <link rel="stylesheet" type="text/css" href="/css/glyphicons.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="/js/jquery.bootstrap-duallistbox.js"></script>

@endsection

@section('page-name', $titulo)

@section('content')
    <div class="card">

        <div class="card-body">
 
            @if ($errors->any())
                <div class="alert alert-danger" >
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="container">
            <form method="POST" action="{{ route('profanoletivo.store') }}">
                <input type="hidden" name="iddisc" value="{{$iddisc}}"/>
                <input type="hidden" name="anoletivoid" value="{{$anoletivoid}}"/>
                @csrf
                <select multiple="multiple" size="10" name="duallistbox_prof[]">
                    @foreach ($professores as $item)    
                        <option value="{{$item->u_id}}" @if($item->d_id !== null) selected="selected" @endif>{{$item->u_name}}</option>
                    @endforeach
                </select>

                <script>
                var demo1 = $('select[name="duallistbox_prof[]"]').bootstrapDualListbox({
                    nonSelectedListLabel: 'Todos os professores',
                    selectedListLabel: 'Professores para a disciplina',
                    bootstrap2Compatible: false,
                    filterTextClear: "Mostrar tudo",
                    filterPlaceHolder: "Filtro",
                    moveAllLabel: "Mover todos",
                    removeAllLabel: "Remover todos",
                    infoText: "Mostrando todos os {0}",
                    infoTextFiltered: "<span class='label label-warning'>Filtrado</span> {0} de {1}",
                    infoTextEmpty: "Lista vazia"
                
                });
                </script>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <br/>
                        <button type="submit" class="btn btn-primary">
                            Salvar
                        </button>
                    </div>
                </div>

            </form>
            </div>
        
        </div>
    </div>
@endsection
