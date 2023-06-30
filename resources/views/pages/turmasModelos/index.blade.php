@extends('layouts.app')

@php
    $pageName = 'Turmas Modelos';
@endphp

@section('page-name', $pageName)

@section('content')

    {{-- <form action= "{{ route('turmasmodelos.index') }}" method="GET">
        <div class="form-inline">
                    <input class="form-control" type="text" name="titulo" id="titulo" value= "{{$anoLetivo}}" list= "historico"/>
                    <section class="itens-group">
                        <button class="btn btn-primary "type="submit">Pesquisar</button>
                    </section>
            </div> 
            <datalist id="historico">
                @foreach ($anosletivos as $anoLetivo)
                    <option value = "{{ $anoLetivo->name }}">{{ $anoLetivo->name }}</option>
                @endforeach
            </datalist>          
        </form>
        <br> --}}

    <div class="card">
        <div class="card-body">
            @if (!empty($turmas))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($turmas as $item)
                                <tr>

                                    <td>{{ $item->serie }}</td>


                                    <td>
                                        <form action="{{ route('turmasmodelos.edit', $item->id) }}">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-warning"@if ($item->qntTurmas > 0) disabled @endif>Editar</button>
                                        </form>
                                    </td>
                                    <td>
                                        <button type="button"
                                            class="btn btn-danger"@if ($item->qntTurmas > 0) disabled @endif
                                            data-toggle="modal" data-target="#modal{{ $item->id }}">
                                            Excluir
                                        </button>
                                    </td>
                                    <div class="modal fade" id="modal{{ $item->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <h3>Você tem certeza que deseja excluir o conteúdo {{ $item->serie }}?
                                                    </h3>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Cancelar</button>
                                                    <form action="{{ route('content.destroy', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Excluir</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            @endforeach

                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center">
                        {{ $turmas->links('vendor.pagination.bootstrap-4') }}
                    </div>

                </div>
            @else
                <div>
                    <h2>Nenhuma turma modelo cadastrada</h2>
                </div>
            @endif
        </div>
    </div>
@endsection
