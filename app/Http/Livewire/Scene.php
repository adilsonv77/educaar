<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\DAO\PainelDAO;

class Scene extends Component
{

    public $texto;
    public $paineis;
    public $scene_id;

    public function mount($paineis, $scene_id)
    {
        $this->paineis = $paineis;
        $this->scene_id = $scene_id;
    }

    public function render()
    {
        return view('livewire.scene');
    }

    public function create(){
        $painelDAO = new PainelDAO();

        $painelCriado = $painelDAO->create([
            'panel' => '{"txt":"","link":"","arquivoMidia":"","midiaExtension":"","midiaType":"none"}',
            'scene_id' => $this->scene_id
        ]);

        $painelDAO->updateById($painelCriado->id,[
            'panel'=>'{"id":"' . $painelCriado->id . '","txt":"","link":"","arquivoMidia":"","midiaExtension":"","midiaType":"none"}',
        ]);

        // Atualiza a página sem recarregar
        $this->emit('painelCriado', $painelCriado->id);
    }

    public function update($id)
    {
        dd($id.' e '.$this->texto);
        $painelDAO = new PainelDAO();
        $painelDAO->updateById($id, ['txt' => ""]);
    }

}
