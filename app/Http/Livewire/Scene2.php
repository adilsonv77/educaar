<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\DAO\PainelDAO;

class Scene extends Component
{
    public $paineisRenderizados = [];
    public $scene_id;

    public function mount($paineis, $scene_id)
    {
        $this->scene_id = $scene_id;
        $this->paineisRenderizados = $paineis;
    }

    public function create()
    {
        $painelDAO = new PainelDAO();

        $novo = $painelDAO->create([
            'panel' => '{"txt":"","link":"","arquivoMidia":"","midiaExtension":"","midiaType":"none"}',
            'scene_id' => $this->scene_id
        ]);

        $painelDAO->updateById($novo->id,[
            'panel'=>'{"id":"'.$novo->id.'","txt":"","link":"","arquivoMidia":"","midiaExtension":"","midiaType":"none"}'
        ]);

        $novo->panel = json_decode('{"id":"'.$novo->id.'","txt":"","link":"","arquivoMidia":"","midiaExtension":"","midiaType":"none"}',true);

        $this->paineisRenderizados[] = $novo;

        $this->emit("painelCriado",$novo->id);
    }

}
