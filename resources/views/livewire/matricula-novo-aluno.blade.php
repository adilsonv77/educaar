<div>
    <div class="form-group">
        <label for="name">{{ __('Student name') }}* </label>
        <div class="col-md-6">
            <input class="form-control @error('nome') is-invalid @enderror" type="text" name="nome" wire:model="nome"
                id="nome" value="{{ $nome }}" list="historico" required autocomplete="nome" autofocus />
            @if ($existe && $nome != '')
                <div class="row mt-4">
                    <div class="col">
                        <div class="alert alert-warning " role="alert">
                            <strong>{{ __('Student already registered') }}</strong>
                            {{ __('Student already registered in the class :class', ["class" => $nomeTurma]) }}
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

        <div class="form-group mt-3">
            <div class="col-md-6">
                <label wire:model="anoletivo" for="">
                    {{ __('Enter the class') }} ( {{ __('School Year') }}: {{ $anoletivo->name }} )
                </label>

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
                {{ __('Register') }}
            </button>
        </div>

        <div class="modal fade show" id="divmodal" tabindex="-1" role="dialog" aria-labelledby="modalAluno"
            aria-model="true" style="display: {{ $alterar ? 'block' : 'none' }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h3>{{ __('Do you want to change the class of student :student', ["student" => $nome]) }}?</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            wire:click="closeModal">{{ __('Cancel') }}</button>
                        <button type="button" class="btn btn-danger" wire:click="updateAluno">{{ __('Change') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-backdrop fade show"
         id="backdrop"
         style="display: @if($alterar === true)
                 block
         @else
                 none
         @endif;"></div>
