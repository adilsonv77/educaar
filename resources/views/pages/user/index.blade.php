@extends('layouts.app')

@php
    $pageName = 'Usuários';
@endphp

@section('page-name', $pageName)

@section('content')

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
                                <th>Ação</th>
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
                                    @else
                                        <td>Administrador</td>
                                    @endif

                                    <td>
                                        <form action="{{ route('user.edit', $item->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-warning">Editar</button>
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
