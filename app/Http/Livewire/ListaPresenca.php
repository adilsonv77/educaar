<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Sala;
use App\Models\Jogo;
use App\Models\Activity;
use App\Models\ArProgress;
use Illuminate\Support\Facades\DB;

class ListaPresenca extends Component
{
    public $salaId;

    public function render()
    {
        $sala = Sala::with('alunosPresentes')->find($this->salaId);
        $jogo = $sala ? Jogo::find($sala->jogo_id) : null;
        
        $atividades = collect();
        $alunos = collect();

        if ($jogo) {

            $atividades = Activity::where('content_id', $jogo->content_id)
                                  ->orderBy('position')
                                  ->get();

            $alunos = $sala->alunosPresentes;

            if ($alunos->isNotEmpty()) {

                $progressos = ArProgress::where('content_id', $jogo->content_id)
                                        ->whereIn('student_id', $alunos->pluck('id'))
                                        ->pluck('next_position', 'student_id');


                $sorts = DB::table('random_sorts')
                           ->where('content_id', $jogo->content_id)
                           ->whereIn('user_id', $alunos->pluck('id'))
                           ->pluck('sort', 'user_id');

                foreach ($alunos as $aluno) {
                    $posicaoAtual = $progressos[$aluno->id] ?? 1; 
                    $aluno->is_finalizado = $posicaoAtual > $atividades->count();

                    $aluno->sort = $sorts[$aluno->id] ?? null;
                    
                    if (!$aluno->is_finalizado) {
                        if (!empty($aluno->sort)) {

                            $ordemPosicoes = explode(',', $aluno->sort);
                            $indexAtual = $posicaoAtual - 1;
                            $posicaoAtividadeOndeAlunoEsta = $ordemPosicoes[$indexAtual] ?? 1;

                            $atividadeAtual = $atividades->firstWhere('position', $posicaoAtividadeOndeAlunoEsta);
                            
                        } else {

                            $atividadeAtual = $atividades->firstWhere('position', $posicaoAtual);
                        }

                        $aluno->atividade_id_atual = $atividadeAtual ? $atividadeAtual->id : null;
                    } else {
                        $aluno->atividade_id_atual = null;
                    }
                }
            }
        }

        return view('livewire.lista-presenca', [
            'alunos' => $alunos,
            'atividades' => $atividades
        ]);
    }
}