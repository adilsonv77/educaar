<div>
    <!-- o componente totalmente deve estar dentro desse div -->
    <div class="form-group row">
        <label for="">Escolha a Turma*</label>
        <select class="form-control" name="turma" wire:model="turma">
            @foreach ($turmas as $turma)
                <option value="{{ $turma->tid }}">{{ $turma->tnome }}</option>
            @endforeach
        </select>

    </div>

    <div class="form-group row">
        <label for="">Escolha a Disciplina*</label>

        <select class="form-control" name="disciplina" wire:model="disciplina">
            @foreach ($disciplinas as $disciplina)
                <option value="{{ $disciplina->did }}">{{ $disciplina->dnome }}</option>
            @endforeach
        </select>
    </div>
</div>