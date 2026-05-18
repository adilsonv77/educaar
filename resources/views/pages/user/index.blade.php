@extends('layouts.app')

@php
    $pageName = __('Users');
@endphp

@section('page-name', $pageName)

@section('content')

    <div>
        <form action="{{ route($userCreate) }}">
            @csrf
            <button class="btn btn-sm btn-primary" id="novo" title={{ __('New') }}>
                <i class="bi bi-plus-circle-dotted h1" style="color: #ffffff;"></i>
            </button>
        </form>
    </div>

    <form action="{{ route($userindex) }}" method="GET">
        <div class="form-inline">
            <label for="titulo">{{ __('Enter the name') }}</label>
            <input class="form-control" type="text" name="titulo" id="titulo" value="{{ request('titulo') }}" list="historico" />
            <section class="itens-group">
                <button class="btn btn-primary btn-lg" type="submit">{{ __('Search') }}</button>
            </section>
        </div>
    </form>
    <br>
    
    <style>
        .form-inline {
            display: flex;
            justify-content: flex-start;
        }
        .form-inline label {
            margin-right: 10px;
        }
    </style>

    <div class="card">
        <div class="card-body">
            @if ($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>Login</th>
                                @if ($type == 'student') <th>{{ __('Class') }}</th> @endif
                                @if ($type == 'developer') <th>{{ __('Type') }}</th> @endif
                                <th>{{ __('Edit') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $item)
                                <tr>
                                    <!-- Nome -->
                                    <td>{{ $item->name }}</td>

                                    <!-- Login -->
                                    <td>{{ $item->username }}</td>

                                    <!-- Turma do aluno -->
                                    @if ($type == 'student')
                                        <td>
                                        @if ($item->turma_nome)
                                            {{ $item->turma_nome }}
                                        @else
                                            <span class="text-muted">{{ __('No classes registered') }}</span>
                                        @endif
                                        </td>
                                    @endif

                                    <!-- Tipo de usuário (se for desenvolvedor) -->
                                    @if ($type == 'developer')
                                        <td>{{ $item->type == 'admin' ? __('Administrator') : __('Developer') }}</td>
                                    @endif

                                    <!-- Editar -->
                                    <td>
                                        <form action="{{ route('user.edit', $item->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-warning" title={{ __('Edit') }}>
                                                <i class="bi bi-pencil-square h2" style="color: #ffffff;"></i>
                                            </button>
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
                    <h2>{{ __('No users registered') }}</h2>
                </div>
            @endif
        </div>
    </div>
@endsection
