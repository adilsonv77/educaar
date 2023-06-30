<div>
    <div class="form-group mt-4">
        <button type='submit'wire:click="openModal({{ $aluno_id }}, {{ $turma_id }})" class="btn btn-success">
            Matricular
        </button>
    </div>
    <div class="modal fade show" id="modal{{ $aluno_id }}" tabindex="-1" role="dialog" aria-labelledby="modalAluno"
        aria-model="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h3>Você deseja alterar a turma do aluno {{ $aluno_id }}?</h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        wire:click="closeModal">Cancelar</button>
                    <form action="{{ route('turmas.novoAlunoTurmaStore', $aluno_id) }}" method="POST">
                        @csrf
                        <input name="turma_id" type="hidden" value="{{ $turma_id }}" wire:model="turma_id" />
                        <button type="submit" class="btn btn-danger">Alterar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    document.addEventListener('livewire:load', function() {
    Livewire.on('openModal', function() {
    $('#modalAluno').modal('show');
    });
    Livewire.on('closeModal', function() {
    $('#modalAluno').modal('hide');
    });
    })
@endpush
