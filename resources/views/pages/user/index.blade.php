@extends('layouts.app')

@php
    $pageName = 'Usuários';
@endphp

@section('page-name', $pageName)

@section('content')

    <div>
        <form action="{{ route($userCreate) }}">
            @csrf
            <button class="btn btn-sm btn-primary " id="novo"><i class="bi bi-plus-circle-dotted h1" style = "color : #ffffff;"></i></button>
        </form>
    </div>

    <form action="{{ route($userindex) }}" method="GET">
        <div class="form-inline">
            <input class="form-control" type="text" name="titulo" id="titulo" value="{{ $usuarios }}"
                list="historico" />
            <section class="itens-group">
                <button class="btn btn-primary btn-lg" type="submit">Pesquisar</button>
            </section>
        </div>
    </form>
    <br>
    <div class="card">
        <div class="card-body">
            @if (!empty($users))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Tipo</th>
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($users as $item)
                                <tr>

                                    <td>{{ $item->name }}</td>

                                    @if ($item->type == 'student')
                                        <td>Aluno</td>
                                    @elseif ($item->type == 'teacher')
                                        <td>Professor</td>
                                    @elseif ($item->type == 'admin')
                                        <td>Administrador</td>
                                    @else
                                        <td>Desenvolvedor</td>
                                    @endif

                                    <td>
                                        <form action="{{ route('user.edit', $item->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-warning"><i class="bi bi-pencil-square h2" style = "color : #ffffff;"></i></button>
                                        </form>
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>
                    </table>


                    <div class="d-flex justify-content-center">
                        {{ $users->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            @else
                <div>
                    <h2>Nenhum usuário cadastrado</h2>
                </div>
            @endif
        </div>
    </div>
@endsection
