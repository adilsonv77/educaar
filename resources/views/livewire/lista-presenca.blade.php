<div wire:poll.2s class="mt-4 w-100">
    
    <div class="mb-4 text-start bg-light p-3 rounded shadow-sm border">
        <h6 class="fw-bold mb-3" style="color: #833B8D;">
            <i class="bi bi-list-ol"></i> Atividades do Jogo
        </h6>
        <div class="d-flex flex-wrap gap-2">
            @foreach($atividades as $index => $atividade)
                <span class="badge bg-white text-dark border border-secondary p-2 shadow-sm" style="font-size: 13px;">
                    <strong>{{ $index + 1 }}.</strong> {{ $atividade->name }}
                </span>
            @endforeach
        </div>
    </div>

    <h5 class="fw-bold mb-3 text-center" style="color: #833B8D;">
        <i class="bi bi-people-fill"></i> Alunos na Sala: {{ count($alunos) }}
    </h5>
    
    <div class="d-flex flex-column gap-2">
        @forelse($alunos as $aluno)
            <div class="d-flex justify-content-between align-items-center p-3 border rounded shadow-sm bg-white">
                
                <div class="text-start">
                    <strong class="fs-6">{{ $aluno->name }}</strong>
                    @if($aluno->sort)
                        <div class="text-muted mt-1" style="font-size: 11px;">
                            <i class="bi bi-shuffle"></i> Ordem: {{ $aluno->sort }}
                        </div>
                    @endif
                </div>
                
                <div class="d-flex gap-1 align-items-center">
                    @if($aluno->is_finalizado)
                        <span class="badge bg-success p-2 fs-6 shadow-sm">
                            <i class="bi bi-check-circle-fill"></i> Concluído
                        </span>
                    @else
                        @foreach($atividades as $index => $atividade)
                            @php
                                $isAtual = ($aluno->atividade_id_atual == $atividade->id);
                            @endphp
                            
                            <span class="badge {{ $isAtual ? 'bg-warning text-dark border border-warning' : 'bg-secondary opacity-50' }}" 
                                  title="{{ $atividade->name }}"
                                  style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; font-size: 14px; {{ $isAtual ? 'transform: scale(1.2); box-shadow: 0 0 10px rgba(255,193,7,0.7); z-index: 2; transition: 0.3s;' : 'transition: 0.3s;' }}">
                                {{ $index + 1 }}
                            </span>
                        @endforeach
                    @endif
                </div>

            </div>
        @empty
            <div class="text-center w-100 py-3">
                <span class="text-muted fst-italic">Aguardando os alunos entrarem...</span>
            </div>
        @endforelse
    </div>
</div>