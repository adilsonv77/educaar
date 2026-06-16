@extends('layouts.app')

@section('page-name', $titulo)

@section('content')

<div class="card">
    <div class="card-body">
        @if (!empty($salas))
        <div class="table-responsive">
            <table class="table table-hover table-responsive-sm">
                <thead>
                    <tr style="text-align: center;">
                        <th style="text-align: left;">{{ __('Name') }}</th>
                        <th>{{ __('Class') }}</th>
                        <th>{{ __('Enter') }}</th>
                        <th>{{ __('Results') }}</th>
                        <!-- <th>{{ __('Edit') }}</th> -->
                        <th>{{ __('Delete') }}</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($salas as $sala)
                    <tr style="text-align: center;">

                        <td style="text-align: left;">{{ $sala->nome }}</td>

                        <td>
                            {{ $sala->nome_turma }}
                        </td>

                        <td>
                            <form action="{{ route('sala.enter', $sala->id) }}">
                                @csrf
                                <input type="hidden" name="sala_id" value="{{ $sala->id }}">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-collection"></i></button>
                            </form>
                        </td>

                        <td>
                            <form action="{{ route('sala.results', $sala->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-secondary"><i class="bi bi-bar-chart"></i></button>
                            </form>
                        </td>
                        <!-- <td> COMENTÁRIO: deixado de lado por enquanto, caso voltar com a ideia, descomentar
                            <button type="button" class="btn btn-primary btn-editar" data-toggle="modal" data-target="#sala-modal" @if(!$sala->aberta) disabled title="Sala já concluída" @endif> <i class="bi bi-pencil"></i>
                            </button>
                            COMENTÁRIO: Esse modal deve sair daqui, se não terá N modais por tela, de acordo com o loop. Deve ser tratado de outra forma
                            <div class="modal fade" id="sala-modal" tabindex="-1" role="dialog" data-backdrop="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body">

                                            <div id="painel-edit">
                                                <h1>Editar sala</h1>
                                                <form action="{{ route('sala.edit', $sala->id) }}" method="post" class="p-3">
                                                    @csrf
                                                    <input type="hidden" name="sala_id" value="{{ $sala->id }}">
                                                    <input type="hidden" name="jogo_id" value="{{ $jogoId }}">

                                                    <div class="form-group row">
                                                        <label>{{ __('Name') }}</label>
                                                        <input type="text" name="nome" value="{{ $sala->nome }}" class="form-control" required>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label>{{ __('Class') }}</label>
                                                        <select name="turma_id" class="form-control" required>
                                                            @foreach($classes as $class)
                                                            <option value="{{ $class->id }}" @if($sala->turma_id === $class->id) selected @endif>
                                                                {{ $class->nome }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label>{{ __('Rules') }}</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <button type="button" class="btn btn-success" onclick="mostrarPainel('painel-rule')">
                                                                    + {{ __('New Rule') }}
                                                                </button>
                                                            </div>
                                                            <select name="regra_id" class="form-control" required>
                                                                @foreach($rules as $rule)
                                                                <option value="{{ $rule->id }}" @if($sala->regra_id === $rule->id) selected @endif>
                                                                    {{ $rule->pontMax }} pontos | {{ $rule->tempo }} segundos
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row mt-4">
                                                        <button type="submit" class="btn btn-success">{{ __('Save') }}</button>
                                                    </div>
                                                </form>
                                            </div>

                                            <div id="painel-rule" style="display: none;">
                                                <button type="button" class="btn btn-link p-0 mb-3" onclick="mostrarPainel('painel-edit')">
                                                    {{ __('Back') }}
                                                </button>
                                                <h1>Criar nova regra</h1>
                                                <form action="{{ route('regra.store') }}" method="post" class="p-3">
                                                    @csrf
                                                    <input type="hidden" name="type" value="editSala">
                                                    <div class="form-group row">
                                                        <label>{{ __('Time') }}</label>
                                                        <input type="number" class="form-control" name="tempo" min="0" required>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label>{{ __('Score') }}</label>
                                                        <input type="number" class="form-control" name="pontMax" min="0" required>
                                                    </div>
                                                    <div class="form-group row">
                                                        <button type="submit" class="btn btn-primary mt-4 w-100">{{ __('Save') }}</button>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td> -->
                        <td>
                            <form action="{{ route('sala.destroy', $sala->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    <div class="modal fade" id="modal{{ $jogo->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <h3>{{ __('Delete the content :content', ["content" => $jogo->content->name]) }} </h3>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">{{ __('Cancel') }}</button>
                                    <form action="{{ route('game.destroy', $jogo->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </tbody>
            </table>
        </div>
        @else
        <div>
            <h2>{{ __('No Games') }}</h2>
        </div>
        @endif
    </div>
</div>

<div>
    <form action="{{ route('sala.create') }}">
        @csrf
        <input type="hidden" name="jogo_id" value="{{ $jogo->id }}">
        <button class="btn btn-smaller, btn-primary " id="novo" title="{{ __('New') }}">
            <i class="bi bi-plus-circle-dotted h1" style="color : #ffffff;"></i>
        </button>
    </form>
</div>

<script>
    function mostrarPainel(id) {
        document.querySelectorAll('#sala-modal .modal-body > div').forEach(function(el) {
            el.style.display = 'none';
        });
        document.getElementById(id).style.display = 'block';
    }

    $('#sala-modal').on('hidden.bs.modal', function() {
        mostrarPainel('painel-edit');
    });
</script>
@endsection