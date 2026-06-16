<div wire:poll.2s class="mt-4">
    <h5 class="text-center fw-bold" style="color: #833B8D;">
        <i class="bi bi-people-fill"></i> Alunos na Sala: {{ count($alunos) }}
    </h5>
    
    <div class="d-flex flex-wrap justify-content-center gap-2 mt-3">
        @forelse($alunos as $aluno)
            <span class="badge bg-success rounded-pill px-3 py-2 fs-6 shadow-sm">
                {{ $aluno->name }}  
            </span>
        @empty
            <span class="text-muted fst-italic">Aguardando os alunos entrarem...</span>
        @endforelse
    </div>
</div>