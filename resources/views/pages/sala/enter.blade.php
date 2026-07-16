@extends('layouts.app')

@section('page-name', $sala->nome)

@section('content')

    <livewire:monitor-jogo :contentId="$sala->content_id" :turmaId="$sala->turma_id" :isProfessor="true" />
    

    <div class="card border-0 mx-auto"
         style="
                border-radius: 1.25rem;
                box-shadow: 0 8px 32px rgba(60, 72, 130, 0.13);">

        <div class="card-body p-4">
            <div class="container-fluid">
                <div class="row align-items-start">

                    <div class="col-auto">
                        <div class="p-2">
                            <button class="btn btn-secondary shadow-sm" data-toggle="modal" data-target="#regras-show">
                                <i class="bi bi-book"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card border-0 d-flex flex-column align-items-center py-5 px-4" style="border-radius: 1rem; background: #ffffff; box-shadow: 0 4px 24px rgba(60, 72, 130, 0.10), 0 1.5px 4px rgba(60, 72, 130, 0.07);">

                            <h1 class="mb-4 fw-bold">{{ $sala->nome }}</h1>

                            <div class="align-self-start mb-3 text-muted">
                                <p class="mb-1">
                                    <i class="bi bi-controller me-2"></i>
                                    Jogo: <strong>{{ $sala->nome_conteudo }}</strong>
                                </p>
                                <p class="mb-0">
                                    <i class="bi bi-people me-2"></i>
                                    Turma: <strong>{{ $sala->nome_turma }}</strong>
                                </p>
                            </div>

                            <div class="mt-4 text-center w-100">
                                @if(!$sala->aberta && is_null($sala->started_at))
                                    <form action="{{ route('sala.abrir', $sala->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success px-5 shadow-sm rounded-pill fw-bold">
                                            <i class="bi bi-door-open-fill"></i> Abrir Sala (Lobby)
                                        </button>
                                        <p class="text-muted mt-2 small">Clique para permitir que os alunos entrem na sala.</p>
                                    </form>

                                @elseif($sala->aberta && is_null($sala->started_at))
                                    <form action="{{ route('sala.comecar', $sala->id) }}" method="POST">
                                        @csrf
                                        <div class="alert alert-info py-2 px-3 mb-3 d-inline-block shadow-sm rounded-pill">
                                            <i class="bi bi-info-circle-fill"></i> A sala está aberta. Aguarde os alunos entrarem!
                                        </div>
                                        <br>
                                        <button type="submit" class="btn btn-primary px-5 shadow-sm rounded-pill fw-bold">
                                            <i class="bi bi-play-fill"></i> Começar Jogo
                                        </button>
                                    </form>

                                @elseif($sala->aberta && !is_null($sala->started_at))
                                    <form action="{{ route('sala.terminar', $sala->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger px-5 shadow-sm rounded-pill fw-bold">
                                            <i class="bi bi-stop-fill"></i> Encerrar Jogo Manualmente
                                        </button>
                                    </form>

                                @else
                                    <div class="alert alert-warning mx-auto text-center shadow-sm" style="max-width: 400px; border-radius: 1rem;">
                                        <i class="bi bi-lock-fill"></i> Jogo encerrado. Esta sala não pode ser reaberta.
                                    </div>
                                    <a href="{{ route('sala.results', $sala->id) }}" class="btn btn-dark px-5 mt-2 shadow-sm rounded-pill fw-bold">
                                        <i class="bi bi-bar-chart-fill"></i> Ver Resultados
                                    </a>
                                @endif
                            </div>

                        </div>
                        <div class="card border-0 d-flex flex-column align-items-center py-5 px-4 mt-4" style="border-radius: 1rem; background: #ffffff; box-shadow: 0 4px 24px rgba(60, 72, 130, 0.10), 0 1.5px 4px rgba(60, 72, 130, 0.07);">
                            <h1 class="mb-4 fw-bold">{{ __('Students') }}</h1>

                            <livewire:lista-presenca :salaId="$sala->id" />
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="regras-show" tabindex="-1" aria-labelledby="regras-show" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h2 class="modal-title">{{ __('Rules') }}</h5>
          </div>
          <div class="modal-body">
            <p>Pontuação Máxima: {{ $sala->pontuacaoMaxima }}</p>
            <p>Tempo: {{ $sala->tempo }}</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
          </div>
        </div>
      </div>
    </div>

<script>

    document.addEventListener('DOMContentLoaded', () => {
        const el = {
            timerButton: document.getElementById('timerButton'),
        }

        let start = true;
        const updateButtonTimer = () => {
            if(start) {
                el.timerButton.innerHTML = '<i class="bi bi-stop-fill"></i> Parar';
                el.timerButton.classList.replace('btn-start', 'btn-stop');
                console.log('start');
            } else {
                el.timerButton.innerHTML = '<i class="bi bi-play-fill"></i> Começar';
                el.timerButton.classList.replace( 'btn-stop', 'btn-start');
                console.log('stop');
            }

            start = !start;
        }

        el.timerButton.addEventListener('click', updateButtonTimer);
    });
</script>

@endsection
