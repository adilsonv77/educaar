@extends('layouts.app')

@php
    $pageName = 'Usuários';
@endphp

@section('page-name', $pageName)

@section('content')

    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </head>    

    <form action="{{ route($userindex) }}" method="GET">
        <div class="form-inline">
            <input class="form-control" type="text" name="titulo" id="titulo" value="{{ $usuarios }}"
                list="historico" />
            <section class="itens-group">
                <button class="btn btn-primary" type="submit">Pesquisar</button>
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
                                            <button type="submit" class="btn btn-warning"><i class="bi bi-pencil-square"></i></button>
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
