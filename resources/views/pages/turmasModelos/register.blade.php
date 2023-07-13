@extends('layouts.app')
@section('style')
    <link rel="stylesheet" type="text/css" href="/css/bootstrap-duallistbox.css">
    <link rel="stylesheet" type="text/css" href="/css/glyphicons.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="/js/jquery.bootstrap-duallistbox.js"></script>

@endsection
@section('page-name', $titulo)

@section('content')
    <div class="card">

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form method="POST" action="{{ route('turmasmodelos.store') }}">
                @csrf
                <input name="id" type="hidden" value="{{ $id }}" />
                <input name="acao" type="hidden" value="{{ $acao }}" />

                <div class="form-group row">
                    <label for="serie" class="col-md-4 col-form-label text-md-right">{{ __('Série') }} : </label>

                    <div class="col-md-6">
                        <input id="serie" type="text" class="form-control @error('serie') is-invalid @enderror"
                            name="serie" value="{{ old('serie', $serie) }}" required autocomplete="serie" autofocus>
                    </div>
                </div>

                @csrf
                <select multiple="multiple" size="10" name="duallistbox_disc[]">
                    @foreach ($disciplinas as $item)
                        <option value="{{ $item['id'] }}" @if ($item['selected']) selected="selected" @endif>
                            {{ $item['name'] }}</option>
                    @endforeach
                </select>

                <script>
                    var demo1 = $('select[name="duallistbox_disc[]"]').bootstrapDualListbox({
                        nonSelectedListLabel: 'Todas as disciplinas',
                        selectedListLabel: 'Todas as disciplinas para a turma',
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
                        <button type="submit" class="btn btn-primary">
                            Salvar
                        </button>
                    </div>
                </div>

            </form>


        </div>
    </div>
@endsection
