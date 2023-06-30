@extends('layouts.app')

@section('page-name', $titulo)

@section('content')
    <div class="">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('turmas.novoAlunoTurmaStore') }}">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @csrf
                    {{-- <livewire:matricula-novo-aluno :nome="$nome" :anoletivo="$anoletivo" /> --}}
                    @livewire('matricula-novo-aluno', ['nome' => $nome, 'anoletivo' => $anoletivo])


            </div>
            </form>
        </div>
    </div>
@endsection
