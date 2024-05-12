@extends('layouts.app')

@php
    $pageName = 'Disciplinas';
@endphp

@section('page-name', $pageName)

@section('content')

     <div>
        <form action="{{ route('class.create') }}">
            @csrf
            <button class="btn btn-sm btn-primary " id="novo" title="Novo"><i class="bi bi-plus-circle-dotted h1" style = "color : #ffffff;"></i></button>
        </form>
    </div>

    <form action="{{ route('class.index') }}" method="GET">
        <div class="form-inline">
            <input class="form-control" type="text" name="titulo" id="titulo" value="{{ $disciplina }}"
                list="historico" />
            <section class="itens-group">
                <button class="btn btn-primary btn-lg "type="submit">Pesquisar</button>
            </section>
        </div>
        <datalist id="historico">
            @foreach ($disciplinas as $disciplina)
                <option value="{{ $disciplina->name }}">{{ $disciplina->name }}</option>
            @endforeach
        </datalist>
    </form>
    <br>

    <div class="card">
        <div class="card-body">
            @if (!empty($disciplinas))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($disciplinas as $item)
                                <tr>

                                    <td>{{ $item->name }}</td>


                                    <td>
                                        <form action="{{ route('class.edit', $item->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-warning" title="Editar"><i class="bi bi-pencil-square h2 " style = "color : #ffffff;"></i></button>
                                        </form>
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
@endsection
