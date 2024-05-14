@extends('layouts.app')

@section('page-name', $titulo)

@section('content')
    <div class="">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('content.store') }}">
                    @csrf

                    <input name="id" type="hidden" value="{{ $id }}" />
                    <input name="acao" type="hidden" value="{{ $acao }}" />


                    <div class="form-group row">
                        <label for="name">Nome* </label>

                        <div class="col-md-6">
                            <input id="name" type="text" maxlength="100" class="form-control @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name', $name) }}" required autocomplete="name"
                                placeholder="Digite aqui o nome do conteúdo" autofocus />

                        </div>
                    </div>
                    @can('teacher')
                    <div class="form-group row">
                        <label for="">Escolha a Disciplina*</label>
                        <select class="form-control" name="disciplina_id">
                            @foreach ($disciplinas as $item)
                                <option value="{{ $item->tid }}_{{ $item->did }}"
                                    @if ($item->tid."_".$item->did === $disciplina) selected="selected" @endif>
                                    {{ $item->tnome }} - {{ $item->dnome }}</option>
                            @endforeach
                        </select>

                    </div>
                    @endcan

                    @can('admin')
                    <livewire:discporturma :turma="$turma" :disciplinaKey="$disciplina"/>
                    @endcan

                    <div class="form-group row mt-4">
                        <input type="submit" value="Salvar" class="btn btn-success">
                    </div>


                </form>
            </div>
        </div>
    </div>
@endsection