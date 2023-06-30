<div>
    <div class="form-group">
        <label for="name">Nome do Aluno* </label>
        <div class="col-md-6">
            <input class="form-control @error('nome') is-invalid @enderror" type="text" name="nome" wire:model="nome"
                id="nome" value="{{ $nome }}" list="historico" required autocomplete="nome" autofocus />
            @if ($existe && $nome != '')
                <div class="row mt-4">
                    <div class="col">
                        <div class="alert alert-warning " role="alert">
                            <strong>Aluno já matriculado!</strong> Aluno já matriculado na turma {{ $Nometurma }}.
                        </div>
                    </div>
                </div>
                <input name="acao" type="hidden" value="edit" />
            @else
                <input name="acao" type="hidden" value="insert" />
            @endif
        </div>

        <datalist id="historico">
            @foreach ($alunos as $item)
                <option value="{{ $item->name }}"></option>
            @endforeach
        </datalist>

        <input name="aluno_id" type="hidden" value="{{ $aluno_id }}" />

        <div class="form-group">
            <div class="col-md-6">
                <label wire:model="anoletivo" for="">Escolha a Turma* (Ano letivo:
                    {{ $anoletivo->name }})</label>

                <select class="form-control" name="turma" wire:model="turma"
                    @if ($nome == '') disabled @endif>
                    @foreach ($turmas as $turma)
                        <option value="{{ $turma->id }}">{{ $turma->nome }}</option>
                    @endforeach
                </select>

            </div>
        </div>
        <div class="form-group mt-4">
            <button type="submit" class="btn btn-success"@if ($habilitar) disabled @endif>
                Matricular
            </button>
        </div>

        <div class="modal fade show" id="divmodal" tabindex="-1" role="dialog" aria-labelledby="modalAluno"
            aria-model="true" style="display: {{ $alterar ? 'block' : 'none' }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h3>Você deseja alterar a turma do aluno {{ $nome }}?</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            wire:click="closeModal">Cancelar</button>
                        <button type="button" class="btn btn-danger" wire:click="updateAluno">Alterar</button>
                    </div>
                </div>
            </div>
        </div>
